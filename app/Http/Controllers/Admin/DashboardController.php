<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use App\Models\Contest;
use App\Models\TransactionHistory;
use App\Models\EarningCoinHistory;
use App\Models\Survey;
use App\Models\UserSurveyResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGames = Game::count();
        $todayWithdrawAmount = TransactionHistory::whereDate('created_at', Carbon::today())->sum('amount');
        $todayEarnCoins = EarningCoinHistory::whereDate('created_at', Carbon::today())->sum('earning_coins');
        $totalContests = Contest::count();
        $activeContests = Contest::where('is_active', 1)->count();
        $totalSurveys = Survey::count();
        $totalResponses = UserSurveyResponse::count();
        $todayResponses = UserSurveyResponse::whereDate('created_at', Carbon::today())->count();

        return view('admin.dashboard', compact('totalUsers', 'totalGames', 'todayWithdrawAmount', 'todayEarnCoins', 'totalContests', 'activeContests', 'totalSurveys', 'totalResponses', 'todayResponses'));
    }

    public function surveyData()
    {
        $surveys = Survey::withCount('responses')->get();
        return response()->json(['data' => $surveys]);
    }
}
