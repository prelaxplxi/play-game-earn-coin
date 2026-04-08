<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length");

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column'];
            $columnName = $columnName_arr[$columnIndex]['data'];
            $columnSortOrder = $order_arr[0]['dir'];

            $title_filter = $request->get('title');
            $status_filter = $request->get('status');

            $totalRecords = Survey::count();
            $query = Survey::query();

            if ($status_filter != '') {
                $query->where('is_active', $status_filter);
            }

            if ($title_filter != '') {
                $query->where('title', 'like', '%' . $title_filter . '%');
            }

            $searchValue = $search_arr['value'] ?? '';
            if ($searchValue != '') {
                $query->where('title', 'like', '%' . $searchValue . '%');
            }

            $totalRecordswithFilter = $query->count();

            $records = $query->orderBy($columnName == 'action' ? 'id' : $columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data = array();
            $i = $start;
            foreach ($records as $record) {
                $i++;
                $data[] = array(
                    "id" => $i,
                    "title" => '<strong>' . $record->title . '</strong>',
                    "total_reward_coins" => $record->total_reward_coins,
                    "status" => '<div class="form-check form-switch">' .
                                    '<input class="form-check-input status-toggle" type="checkbox" data-id="' . $record->id . '" ' . ($record->is_active == 1 ? 'checked' : '') . '>' .
                                '</div>',
                    "store_answers" => '<div class="form-check form-switch">' .
                                    '<input class="form-check-input store-answers-toggle" type="checkbox" data-id="' . $record->id . '" ' . ($record->store_answers == 1 ? 'checked' : '') . '>' .
                                '</div>',
                    "action" => $record->id,
                );
            }

            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordswithFilter,
                "data" => $data
            );
            return response()->json($response);
        }

        return view('admin.surveys.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.surveys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'total_reward_coins' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'store_answers' => 'nullable|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:1',
            'questions.*.options.*' => 'required|string',
        ]);

        $data = $request->only(['title', 'description', 'total_reward_coins']);
        $data['is_active'] = $request->is_active ? 1 : 0;
        $data['store_answers'] = $request->store_answers ? 1 : 0;

        $survey = Survey::create($data);

        foreach ($request->questions as $q) {
            $question = SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question' => $q['question'],
            ]);

            foreach ($q['options'] as $opt) {
                SurveyQuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt,
                ]);
            }
        }

        return redirect()->route('admin.surveys.index')->with('success', 'Survey created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        $survey->load('questions.options');
        return view('admin.surveys.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'total_reward_coins' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'store_answers' => 'nullable|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:1',
            'questions.*.options.*' => 'required|string',
        ]);

        $data = $request->only(['title', 'description', 'total_reward_coins']);
        $data['is_active'] = $request->is_active ? 1 : 0;
        $data['store_answers'] = $request->store_answers ? 1 : 0;

        $survey->update($data);

        // delete existing questions (cascade will remove options)
        $survey->questions()->delete();

        foreach ($request->questions as $q) {
            $question = SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question' => $q['question'],
            ]);

            foreach ($q['options'] as $opt) {
                SurveyQuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt,
                ]);
            }
        }

        return redirect()->route('admin.surveys.index')->with('success', 'Survey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Toggle the status of the survey.
     */
    public function toggleStatus(Request $request)
    {
        $survey = Survey::findOrFail($request->id);
        $survey->is_active = $survey->is_active == 1 ? 0 : 1;
        $survey->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
    /**
     * Toggle the store answers status of the survey.
     */
    public function toggleStoreAnswers(Request $request)
    {
        $survey = Survey::findOrFail($request->id);
        $survey->store_answers = $survey->store_answers == 1 ? 0 : 1;
        $survey->save();

        return response()->json(['success' => true, 'message' => 'Store answers status updated successfully.']);
    }
}
