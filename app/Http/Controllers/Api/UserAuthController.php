<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->is_active == 0) {
            return response()->json(['message' => 'please contact our support team.'], 403);
        }

        $deviceName = $request->device_name ?? 'default';

        return response()->json([
            'token' => $user->createToken($deviceName)->plainTextToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function registerNormal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'referral_code' => 'nullable|string|exists:users,refer_code',
        ]);

        $referredById = $this->getReferrerId($request->referral_code);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_no' => $request->phone_no,
            'referred_by_id' => $referredById,
        ]);

        if ($referredById) {
            User::where('id', $referredById)->increment('referrals_count');
        }

        $deviceName = $request->device_name ?? 'default';

        return response()->json([
            'token' => $user->createToken($deviceName)->plainTextToken,
            'user' => $user
        ], 201);
    }

    public function registerGmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'photo_url' => 'nullable|string',
            'gmail_account_id' => 'required|string',
            'referral_code' => 'nullable|string|exists:users,refer_code',
        ]);

        $user = User::where('gmail_account_id', $request->gmail_account_id)->first();

        if (!$user) {
            // Check if user exists with the same email
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Link the gmail_account_id to the existing user
                $user->update([
                    'gmail_account_id' => $request->gmail_account_id,
                ]);
            } else {
                $referredById = $this->getReferrerId($request->referral_code);

                // Create new user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'photo_url' => $request->photo_url,
                    'gmail_account_id' => $request->gmail_account_id,
                    'is_active' => 1,
                    'referred_by_id' => $referredById,
                ]);

                if ($referredById) {
                    User::where('id', $referredById)->increment('referrals_count');
                }
            }
        }

        // Fresh instance to ensure attributes like is_active are correctly loaded if modified/created
        $user->refresh();

        if ($user->is_active == 0) {
            return response()->json(['message' => 'please contact our support team.'], 403);
        }

        $deviceName = $request->device_name ?? 'default';

        return response()->json([
            'token' => $user->createToken($deviceName)->plainTextToken,
            'user' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'The provided old password does not match your current password.'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    private function getReferrerId($code)
    {
        if (empty($code)) return null;
        $referrer = User::where('refer_code', $code)->first();
        return $referrer ? $referrer->id : null;
    }
}
