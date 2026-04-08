<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            ## Read value
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc

            // Fetch records
            $name_filter = $request->get('name');
            $status_filter = $request->get('status');
            
            // Total records
            $totalRecords = User::count();
            
            $query = User::query();

            // Apply filters if needed
            if ($status_filter != '') {
                $query->where('is_active', $status_filter);
            }

            if ($name_filter != '') {
                $query->where('name', 'like', '%' . $name_filter . '%');
            }

            // Filtering by search term (manual or default)
            $searchValue = '';
            if (is_array($search_arr) && isset($search_arr['value'])) {
                $searchValue = $search_arr['value'];
            } elseif (is_string($search_arr)) {
                $searchValue = $search_arr;
            }

            if ($searchValue != '') {
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%')
                      ->orWhere('email', 'like', '%' . $searchValue . '%')
                      ->orWhere('phone_no', 'like', '%' . $searchValue . '%');
                });
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
                    "name" => '<strong>'.$record->name.'</strong>',
                    "email" => $record->email,
                    "phone_no" => $record->phone_no ?? 'N/A',
                    "created_at" => $record->created_at->format('d M Y, h:i A'),
                    "balance" => $record->balance,
                    "redeemed_amount" => $record->redeemed_amount ?? 0,
                    "status" => '<div class="form-check form-switch">' .
                                    '<input class="form-check-input status-toggle" type="checkbox" data-id="' . $record->id . '" ' . ($record->is_active == 1 ? 'checked' : '') . '>' .
                                '</div>',
                    "action" => $record->id, // Used for the JS rendering in index.blade.php
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
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone_no' => 'nullable|string|max:20',
            'balance' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_no' => $request->phone_no,
            'balance' => $request->balance ?? 0.00,
            'is_active' => $request->is_active ?? 0,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone_no' => 'nullable|string|max:20',
            'balance' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'balance' => $request->balance ?? 0.00,
            'is_active' => $request->is_active ?? 0,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle the status of the user.
     */
    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->is_active = $user->is_active == 1 ? 0 : 1;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
