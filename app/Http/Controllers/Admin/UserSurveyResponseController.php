<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSurveyResponse;
use App\Models\UserSurveyAnswer;

class UserSurveyResponseController extends Controller
{
    /**
     * Display a listing of the survey responses.
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

            $query = UserSurveyResponse::with(['user', 'survey']);

            // Filtering
            $user_filter = $request->get('user');
            $survey_filter = $request->get('survey');

            if ($user_filter != '') {
                $query->whereHas('user', function($q) use ($user_filter) {
                    $q->where('name', 'like', '%' . $user_filter . '%');
                });
            }

            if ($survey_filter != '') {
                $query->whereHas('survey', function($q) use ($survey_filter) {
                    $q->where('title', 'like', '%' . $survey_filter . '%');
                });
            }

            $searchValue = $search_arr['value'] ?? '';
            if ($searchValue != '') {
                $query->where(function($q) use ($searchValue) {
                    $q->whereHas('user', function($sq) use ($searchValue) {
                        $sq->where('name', 'like', '%' . $searchValue . '%');
                    })->orWhereHas('survey', function($sq) use ($searchValue) {
                        $sq->where('title', 'like', '%' . $searchValue . '%');
                    });
                });
            }

            $totalRecords = UserSurveyResponse::count();
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
                    "user" => $record->user->name ?? 'N/A',
                    "survey" => $record->survey->title ?? 'N/A',
                    "reward_coins" => $record->reward_coins,
                    "status" => ucfirst($record->status),
                    "created_at" => $record->created_at->format('Y-m-d H:i:s'),
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

        return view('admin.user-surveys.index');
    }

    /**
     * Display the specified survey response details.
     */
    public function show($id)
    {
        $response = UserSurveyResponse::with(['user', 'survey', 'answers.question', 'answers.option'])->findOrFail($id);
        return view('admin.user-surveys.show', compact('response'));
    }
}
