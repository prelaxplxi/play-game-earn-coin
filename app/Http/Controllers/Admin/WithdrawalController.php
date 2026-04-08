<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionHistory;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawal history.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            ## Read values
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column'] ?? 0; // Column index
            $columnName = $columnName_arr[$columnIndex]['data'] ?? 'id'; // Column name
            $columnSortOrder = $order_arr[0]['dir'] ?? 'desc'; // asc or desc
            
            $searchValue = '';
            if (is_array($search_arr) && isset($search_arr['value'])) {
                $searchValue = $search_arr['value'];
            } elseif (is_string($search_arr)) {
                $searchValue = $search_arr;
            }
            
            $user_id_filter = $request->get('user_id');

            // Total records
            $totalRecords = TransactionHistory::count();

            // Fetch records with search
            $query = TransactionHistory::with('user');

            if ($user_id_filter != '') {
                $query->where('user_id', $user_id_filter);
            }

            if ($searchValue != '') {
                $query->where(function($q) use ($searchValue) {
                    $q->where('withdraw_coins', 'like', '%' . $searchValue . '%')
                      ->orWhere('amount', 'like', '%' . $searchValue . '%')
                      ->orWhereHas('user', function($qu) use ($searchValue) {
                          $qu->where('name', 'like', '%' . $searchValue . '%')
                             ->orWhere('email', 'like', '%' . $searchValue . '%');
                      });
                });
            }

            $totalRecordswithFilter = $query->count();

            $records = $query->orderBy($columnName == 'user' ? 'user_id' : ($columnName == 'id' ? 'id' : $columnName), $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data = array();
            $i = $start;
            foreach ($records as $record) {
                $i++;
                $data[] = array(
                    "id" => $i,
                    "user" => $record->user ? "<strong>{$record->user->name}</strong> <br><small class='text-muted'>{$record->user->email}</small>" : "Deleted User",
                    "withdraw_coins" => $record->withdraw_coins,
                    "amount" => "Rs. " . number_format($record->amount, 2),
                    "created_at" => $record->created_at->format('d M Y, h:i A'),
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

        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.withdrawals.index', compact('users'));
    }
}
