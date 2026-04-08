<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Contest::query();

            // Filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('is_active', $request->status);
            }

            if ($request->has('name') && $request->name !== '') {
                $query->where('contest_name', 'like', '%' . $request->name . '%');
            }

            return datatables()->of($query)
                ->addIndexColumn()
                ->editColumn('contest_name', function($row){
                    return '<strong>'.$row->contest_name.'</strong>';
                })
                ->editColumn('status', function($row){
                    return '<div class="form-check form-switch">' .
                                '<input class="form-check-input status-toggle" type="checkbox" data-id="' . $row->id . '" ' . ($row->is_active == 1 ? 'checked' : '') . '>' .
                            '</div>';
                })
                ->addColumn('action', function($row){
                    return $row->id; // Handled by JS render in view
                })
                ->editColumn('start_date', function($row){
                    return $row->start_date->format('Y-m-d H:i');
                })
                ->editColumn('end_date', function($row){
                    return $row->end_date->format('Y-m-d H:i');
                })
                ->rawColumns(['contest_name', 'status', 'action'])
                ->make(true);
        }

        return view('admin.contests.index');
    }

    public function create()
    {
        return view('admin.contests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'contest_name' => 'required|string|max:255',
            'rules' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Contest::create($data);

        return redirect()->route('admin.contests.index')->with('success', 'Contest created successfully.');
    }

    public function edit(Contest $contest)
    {
        return view('admin.contests.edit', compact('contest'));
    }

    public function update(Request $request, Contest $contest)
    {
        $request->validate([
            'contest_name' => 'required|string|max:255',
            'rules' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $contest->update($data);

        return redirect()->route('admin.contests.index')->with('success', 'Contest updated successfully.');
    }

    public function destroy(Contest $contest)
    {
        $contest->delete();
        return response()->json(['success' => true, 'message' => 'Contest deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $contest = Contest::findOrFail($request->id);
        $contest->is_active = $contest->is_active == 1 ? 0 : 1;
        $contest->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
