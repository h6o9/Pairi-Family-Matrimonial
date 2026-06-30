<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use App\Models\Subscription;
use App\Models\SystemSetting;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $totalRegistered = $user->referrals()->count();
            $rewardPoints = $user->reward_points;
            $rewardPerRegistration = (int) SystemSetting::getVal('invite_reward_points', 50);

            return response()->json([
                'success' => true,
                'referral_code' => $user->referral_code,
                'referral_link' => url('/register?ref=' . $user->referral_code),
                'total_registered' => $totalRegistered,
                'reward_points' => $rewardPoints,
                'reward_per_registration' => $rewardPerRegistration,
                'conversion_rate' => "1 Registration = {$rewardPerRegistration} pts",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch referral stats',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function history(Request $request): JsonResponse
    {
        try {
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
                        'status' => 'Registered',
                        'registered_at' => $referral->created_at->toDateTimeString(),
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch referral history',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function rewards(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $catalog = $this->rewardCatalog();

            return response()->json([
                'success' => true,
                'total_points' => $user->reward_points,
                'rewards' => collect($catalog)->map(function ($item) use ($user) {
                    return array_merge($item, [
                        'can_redeem' => $user->reward_points >= $item['points_cost'],
                    ]);
                })->values(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch rewards',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function redeem(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'reward_type' => 'required|string|in:vip_month,vvip_month,profile_boost',
            ]);

            $user = $request->user();
            $catalog = collect($this->rewardCatalog())->keyBy('type');
            $reward = $catalog->get($data['reward_type']);

            if (!$reward) {
                return response()->json(['success' => false, 'message' => 'Invalid reward type.'], 422);
            }

            if ($user->reward_points < $reward['points_cost']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough points to redeem this reward.',
                ], 422);
            }

            $user->decrement('reward_points', $reward['points_cost']);

            $meta = ['reward_label' => $reward['label']];

            if ($data['reward_type'] === 'profile_boost') {
                $days = (int) SystemSetting::getVal('redeem_boost_days', 7);
                $until = ($user->profile_boost_until && $user->profile_boost_until->isFuture())
                    ? $user->profile_boost_until->copy()->addDays($days)
                    : now()->addDays($days);
                $user->update(['profile_boost_until' => $until]);
                $meta['boost_until'] = $until->toIso8601String();
            } else {
                $planType = $data['reward_type'] === 'vip_month' ? 'VIP' : 'VVIP';
                $plan = Subscription::where('type', $planType)->where('status', 'active')->firstOrFail();

                UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_id' => $plan->id,
                    'status' => 'verified',
                    'starts_at' => now(),
                    'expires_at' => now()->addDays($plan->duration_days),
                ]);

                $meta['plan'] = $plan->name;
            }

            RewardRedemption::create([
                'user_id' => $user->id,
                'reward_type' => $data['reward_type'],
                'points_spent' => $reward['points_cost'],
                'meta' => $meta,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reward redeemed successfully.',
                'remaining_points' => $user->fresh()->reward_points,
                'meta' => $meta,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to redeem reward',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function rewardCatalog(): array
    {
        return [
            [
                'type' => 'vip_month',
                'label' => '1 Month Free VIP Plan',
                'points_cost' => (int) SystemSetting::getVal('redeem_vip_points', 500),
            ],
            [
                'type' => 'vvip_month',
                'label' => '1 Month Free VVIP Plan',
                'points_cost' => (int) SystemSetting::getVal('redeem_vvip_points', 1000),
            ],
            [
                'type' => 'profile_boost',
                'label' => 'Profile Boost — ' . (int) SystemSetting::getVal('redeem_boost_days', 7) . ' Days',
                'points_cost' => (int) SystemSetting::getVal('redeem_boost_points', 50),
            ],
        ];
    }
}
