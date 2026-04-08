<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Click;
use App\Models\TrackedEvent;
use App\Models\EarningCoinHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TrackController extends Controller
{
    /**
     * Record a click event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackClick(Request $request)
    {
        try {
            $input = $request->all();

            $clickId = $request->input('click_id');
            if (empty($clickId)) {
                $clickId = bin2hex(random_bytes(16));
            }

            $click = Click::updateOrCreate(
                ['click_id' => $clickId],
                [
                    'campaign_id' => $request->input('campaign_id'),
                    'source' => $request->input('source'),
                    'sub_source' => $request->input('sub_source'),
                    'device_id' => $request->input('device_id'),
                    'app_user_id' => $request->input('app_user_id'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'landing_url' => $request->input('landing_url'),
                    'referrer' => $request->input('referrer'),
                    'meta_json' => $request->input('meta', []),
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Click tracked successfully',
                'data' => $click
            ]);

        } catch (\Exception $e) {
            Log::error('TrackClick Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Record a tracked event (e.g. install, registration, purchase).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackEvent(Request $request)
    {
        try {
            $input = $request->all();

            $user = $request->user();

            $clickId = $request->input('click_id');
            $eventName = $request->input('event_name');

            if (empty($clickId) || empty($eventName)) {
                return response()->json(['status' => 'error', 'message' => 'click_id and event_name are required'], 422);
            }

            // $allowedEvents = ['initialevent', 'install', 'signup', 'login', 'kyccomplete', 'deposit', 'purchase', 'tutorial_complete', 'withdrawal', 'custom'];
            // $isKnownEvent = in_array($eventName, $allowedEvents, true);
            // if (!$isKnownEvent) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Unknown event_name',
            //     ], 400);
            // }

            // check if click exists in our db
            $click = Click::where('click_id', $clickId)->first();

            if (empty($click)) {
                return response()->json(['status' => 'error', 'message' => 'click_id not found.'], 422);
            }
            if ($request->event_time != '') {
                $eventTime = $request->input('event_time', Carbon::now()->toDateTimeString());
            } else {
                $eventTime = Carbon::now()->toDateTimeString();
            }

            $event = TrackedEvent::updateOrCreate(
                [
                    'click_id' => $clickId,
                    'event_name' => $eventName,
                    'transaction_id' => $request->input('transaction_id')
                ],
                [
                    'click_db_id' => $click ? $click->id : null,
                    'event_name' => $eventName,
                    'event_time' => $eventTime,
                    'device_id' => $request->input('device_id'),
                    'app_user_id' => $request->input('app_user_id'),
                    'transaction_id' => $request->input('transaction_id'),
                    'revenue' => $request->input('revenue'),
                    'currency' => $request->input('currency', 'USD'),
                    'meta_json' => $request->input('meta', []),
                    'raw_payload' => $input,
                ]
            );

            // Record earning history
            EarningCoinHistory::create([
                'user_id' => $user->id,
                'earning_coins' => $request->input('revenue'),
                'type' => $eventName,
                'description' => 'Earned coins from ' . $eventName,
            ]);

            // Update User balance
            $user->increment('balance', $request->input('revenue'));

            return response()->json([
                'status' => 'success',
                'message' => 'Event tracked successfully',
                'data' => $event
                // 'event_id' => $event->id,
                // 'event_name' => $eventName,
                // 'attributed' => $click ? $click->id : null,
                // 'resolved_click_id' => $clickId,
                // 'known_event' => $isKnownEvent,
                // 'event_time_utc' => $eventTime,
            ]);

        } catch (\Exception $e) {
            Log::error('TrackEvent Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    // public function postBackEvent(Request $request)
    // {
    //     try {
    //         $input = $request->all();

    //         $clickId   = $request->input('click_id');
    //         $eventName = $request->input('event_name');

    //         if (empty($clickId) || empty($eventName)) {
    //             return response()->json(['error' => 'click_id and event_name are required'], 400);
    //         }

    //         // check if click exists in our db
    //         $click = Click::where('click_id', $clickId)->first();

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Event tracked successfully',
    //             'id'      => $event->id
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('TrackEvent Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Internal server error'], 500);
    //     }
    // }
}
