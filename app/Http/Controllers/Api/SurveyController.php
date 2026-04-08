<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\UserSurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    /**
     * Display a listing of active surveys.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $surveys = Survey::where('is_active', 1)
                ->with(['questions.options'])
                ->get();

            $surveys->each(function($survey) use ($user) {
                $survey->is_completed = UserSurveyResponse::where('user_id', $user->id)
                    ->where('survey_id', $survey->id)
                    ->exists();
            });

            return response()->json([
                'success' => true,
                'data' => $surveys,
                'message' => 'Surveys retrieved successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve surveys.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store user survey response.
     */
    public function storeResponse(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:survey_questions,id',
            'answers.*.option_id' => 'required|exists:survey_question_options,id',
        ]);

        $user = $request->user();
        $survey = Survey::findOrFail($request->survey_id);

        // Check if already completed
        $alreadyCompleted = UserSurveyResponse::where('user_id', $user->id)
            ->where('survey_id', $survey->id)
            ->exists();

        if ($alreadyCompleted) {
            return response()->json([
                'success' => false,
                'message' => 'You have already completed this survey.'
            ], 400);
        }

        try {
            \DB::beginTransaction();

            $response = UserSurveyResponse::create([
                'user_id' => $user->id,
                'survey_id' => $survey->id,
                'reward_coins' => $survey->total_reward_coins,
                'status' => 'completed',
            ]);

            // Store answers only if the survey has store_answers enabled
            if ($survey->store_answers) {
                foreach ($request->answers as $ans) {
                    $question = SurveyQuestion::find($ans['question_id']);
                    $isCorrect = false;

                    if ($question->correct_answer_id) {
                        $isCorrect = ($question->correct_answer_id == $ans['option_id']);
                    }

                    \App\Models\UserSurveyAnswer::create([
                        'response_id' => $response->id,
                        'question_id' => $ans['question_id'],
                        'option_id' => $ans['option_id'],
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            // Reward the user
            $user->balance += $survey->total_reward_coins;
            $user->save();

            // Log earning history
            \App\Models\EarningCoinHistory::create([
                'user_id' => $user->id,
                'coins' => $survey->total_reward_coins,
                'type' => 'survey',
                'description' => 'Reward for completing survey: ' . $survey->title,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Survey submitted successfully. ' . $survey->total_reward_coins . ' coins rewarded.'
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit survey.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}