<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();
        $totalRegistered = $user->referrals()->count();
        $rewardPoints = $user->reward_points;
        $rewardPerRegistration = SystemSetting::getVal('invite_reward_points', 50);

        return response()->json([
            'success' => true,
            'referral_code' => $user->referral_code,
            'referral_link' => url('/register?ref=' . $user->referral_code),
            'total_registered' => $totalRegistered,
            'reward_points' => $rewardPoints,
            'reward_per_registration' => $rewardPerRegistration,
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $history = $user->referrals()->with('referredUser:id,name,image,photos')->latest()->get();

        return response()->json([
            'success' => true,
            'history' => $history->map(function ($referral) {
                return [
                    'id' => $referral->id,
                    'user_id' => $referral->referred_user_id,
                    'name' => $referral->referredUser->name ?? 'Unknown',
                    'image' => $referral->referredUser->profile_photo ?? null,
                    'points_earned' => $referral->points_earned,
                    'registered_at' => $referral->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }
}
