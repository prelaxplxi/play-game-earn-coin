<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Game;
use App\Models\GameCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
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

            $name_filter = $request->get('name');
            $status_filter = $request->get('status');

            $totalRecords = Game::count();
            $query = Game::query();

            if ($status_filter != '') {
                $query->where('is_active', $status_filter);
            }

            if ($name_filter != '') {
                $query->where('name', 'like', '%' . $name_filter . '%');
            }

            $searchValue = $search_arr['value'] ?? '';
            if ($searchValue != '') {
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%')
                      ->orWhere('category', 'like', '%' . $searchValue . '%');
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
                    "thumbnail" => $record->thumbnail ? '<img src="'.asset('storage/'.$record->thumbnail).'" width="50" height="50" class="rounded shadow-sm">' : 'N/A',
                    "name" => '<strong>'.$record->name.'</strong>',
                    "category" => $record->gameCategory->name ?? 'N/A',
                    "coins" => $record->coins,
                    "status" => '<div class="form-check form-switch">' .
                                    '<input class="form-check-input status-toggle" type="checkbox" data-id="' . $record->id . '" ' . ($record->is_active == 1 ? 'checked' : '') . '>' .
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

        return view('admin.games.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = GameCategory::where('is_active', 1)->get();
        return view('admin.games.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:game_categories,id',
            'url' => 'required|url',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'coins' => 'required|integer|min:0',
            'time_duration' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['category'] = GameCategory::where('id', $request->category_id)->first()->name;
        $data['is_active'] = $request->is_active ?? 0;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('games', 'public');
        }

        Game::create($data);

        return redirect()->route('admin.games.index')->with('success', 'Game created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        $categories = GameCategory::where('is_active', 1)->get();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:game_categories,id',
            'url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'coins' => 'required|integer|min:0',
            'time_duration' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->is_active ?? 0;

        if ($request->hasFile('thumbnail')) {
            if ($game->thumbnail) {
                Storage::disk('public')->delete($game->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('games', 'public');
        }

        $game->update($data);

        return redirect()->route('admin.games.index')->with('success', 'Game updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        if ($game->thumbnail) {
            Storage::disk('public')->delete($game->thumbnail);
        }
        $game->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Toggle the status of the game.
     */
    public function toggleStatus(Request $request)
    {
        $game = Game::findOrFail($request->id);
        $game->is_active = $game->is_active == 1 ? 0 : 1;
        $game->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Category Methods consolidated from GameCategoryController
     */
    public function categoryIndex(Request $request)
    {
        if ($request->ajax()) {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length");

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
            $columnName = $columnName_arr[$columnIndex]['data'] ?? 'id';
            $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';

            $name_filter = $request->get('name');
            $status_filter = $request->get('status');

            $totalRecords = GameCategory::count();
            $query = GameCategory::query();

            if ($status_filter != '') {
                $query->where('is_active', $status_filter);
            }

            if ($name_filter != '') {
                $query->where('name', 'like', '%' . $name_filter . '%');
            }

            $searchValue = $search_arr['value'] ?? '';
            if ($searchValue != '') {
                $query->where('name', 'like', '%' . $searchValue . '%');
            }

            $totalRecordswithFilter = $query->count();

            $records = $query->orderBy($columnName == 'action' ? 'id' : $columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage == -1 ? $totalRecords : $rowperpage)
                ->get();

            $data = array();
            $i = 1;
            foreach ($records as $record) {
                $data[] = array(
                    "id" => $i,
                    "name" => $record->name,
                    "status" => '<div class="form-check form-switch">' .
                                    '<input class="form-check-input status-toggle" type="checkbox" data-id="' . $record->id . '" ' . ($record->is_active == 1 ? 'checked' : '') . '>' .
                                '</div>',
                    "action" => $record->id,
                );
                $i++;
            }

            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordswithFilter,
                "data" => $data
            );
            return response()->json($response);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $cat = GameCategory::create(['name' => $request->name, 'is_active' => 1]);
        return response()->json(['success' => true, 'data' => $cat]);
    }

    public function categoryDestroy($id)
    {
        $category = GameCategory::findOrFail($id);
        if ($category->games()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Category is in use.'], 422);
        }
        $category->delete();
        return response()->json(['success' => true]);
    }

    public function categoryUpdate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = GameCategory::findOrFail($id);
        $category->update(['name' => $request->name]);
        return response()->json(['success' => true, 'message' => 'Category updated.']);
    }

    public function categoryToggleStatus(Request $request)
    {
        $category = GameCategory::findOrFail($request->id);
        $category->is_active = $category->is_active == 1 ? 0 : 1;
        $category->save();
        return response()->json(['success' => true, 'message' => 'Category status updated.']);
    }
}
