<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContestController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Top users by balance (Leaderboard)
        $topUsers = User::where('is_active', 1)
            // ->where('is_admin', 0) // Assuming there's an is_admin or we just want normal users
            ->orderBy('balance', 'desc')
            ->limit(20)
            ->get(['id', 'name', 'email', 'balance']);

        // Ongoing Contests
        $ongoingContests = Contest::where('is_active', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get()
            ->map(function ($contest) use ($topUsers) {
                $contest->users_list = $topUsers;
                return $contest;
            });

        // Closed Contests
        $closedContests = Contest::where(function($query) use ($now) {
                $query->where('is_active', 0)
                      ->orWhere('end_date', '<', $now);
            })
            ->get()
            ->map(function ($contest) use ($topUsers) {
                $contest->users_list = $topUsers;
                return $contest;
            });

        return response()->json([
            'status' => true,
            'message' => 'Contests fetched successfully',
            'ongoing_contest' => $ongoingContests,
            'closed_contest' => $closedContests
        ]);
    }
}
