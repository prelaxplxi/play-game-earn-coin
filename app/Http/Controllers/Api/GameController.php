<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use App\Models\EarningCoinHistory;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display a listing of the games.
     */
    public function index()
    {
        $games = Game::where('is_active', 1)->get()->map(function($game) {
            $game->thumbnail = $game->thumbnail ? asset('storage/' . $game->thumbnail) : null;
            return $game;
        });

        return response()->json([
            'status' => 'success',
            'data' => $games
        ]);
    }

    /**
     * Store earned coins for the authenticated user and a specific game.
     */
    public function storeEarnCoin(Request $request)
    {
        $request->validate([
            'earn_coin' => 'required',
            'earn_type' => 'required',
        ]);

        $user = $request->user();
        $earnCoin = $request->earn_coin;

        // Record earning history
        EarningCoinHistory::create([
            'user_id' => $user->id,
            'earning_coins' => $earnCoin,
            'type' => $request->earn_type,
            'description' => 'Earned coins from game play',
        ]);

        // Update User balance
        $user->increment('balance', $earnCoin);

        // Referral Reward Logic
        if ($user->referred_by_id) {
            $earningCount = EarningCoinHistory::where('user_id', $user->id)
                ->where('type', $request->earn_type)
                ->count();

            if ($earningCount <= 3) {
                $referrer = User::find($user->referred_by_id);
                if ($referrer) {
                    $referralReward = floor($earnCoin * 0.5); // 50% reward
                    if ($referralReward > 0) {
                        $referrer->increment('balance', $referralReward);
                        
                        EarningCoinHistory::create([
                            'user_id' => $referrer->id,
                            'earning_coins' => $referralReward,
                            'type' => 'referral',
                            'description' => "50% referral reward from {$user->name}'s offer #{$earningCount}",
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Coins earned successfully',
            'earned_coins' => $earnCoin,
            'new_balance' => $user->balance
        ]);
    }

    /**
     * Handle withdrawal request.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'withdraw_coins' => 'required|integer|min:10',
            'payment_type' => 'required|string',
            'payment_details' => 'nullable|string',
        ]);

        $user = $request->user();
        $withdrawCoins = $request->withdraw_coins;
        $paymentType = $request->payment_type;
        $paymentDetails = $request->payment_details;

        if ($user->balance < $withdrawCoins) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient coin balance.'
            ], 400);
        }

        // 10 coins = 1 Rs
        $amount = $withdrawCoins / 10;

        return DB::transaction(function () use ($user, $withdrawCoins, $amount, $paymentType, $paymentDetails) {
            // Deduct from User balance
            $user->decrement('balance', $withdrawCoins);

            // Increment redeemed_amount
            $user->increment('redeemed_amount', $amount);

            // Record transaction history
            $transaction = TransactionHistory::create([
                'user_id' => $user->id,
                'withdraw_coins' => $withdrawCoins,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'payment_details' => $paymentDetails,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Withdrawal request processed successfully.',
                'data' => [
                    'withdraw_coins' => $withdrawCoins,
                    'amount' => $amount,
                    'new_balance' => $user->balance,
                    'transaction' => $transaction
                ]
            ]);
        });
    }

    /**
     * Get withdrawal history for the authenticated user.
     */
    public function withdrawHistory(Request $request)
    {
        $history = TransactionHistory::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $history
        ]);
    }

    /**
     * Get coin earning history for the authenticated user.
     */
    public function earningHistory(Request $request)
    {
        $history = EarningCoinHistory::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $history
        ]);
    }
    /**
     * Get games grouped by category.
     */
    public function categoryWiseList()
    {
        $categories = \App\Models\GameCategory::where('is_active', 1)
            ->with(['games' => function($query) {
                $query->where('is_active', 1);
            }])
            ->get()
            ->map(function($category) {
                $category->games = $category->games->map(function($game) {
                    $game->thumbnail = $game->thumbnail ? asset('storage/' . $game->thumbnail) : null;
                    return $game;
                });
                return $category;
            });

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}