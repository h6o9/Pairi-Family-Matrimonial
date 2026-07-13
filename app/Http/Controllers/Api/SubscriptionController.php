<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $plans = Subscription::where('status', 'active')->orderBy('price')->get();

            return response()->json([
                'success' => 200,
                'plans' => $plans->map(fn ($plan) => $this->formatPlan($plan)),
                'comparison' => $this->comparisonMatrix($plans),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscriptions',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function myPlan(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $subscription = UserSubscription::with('plan')
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if (!$subscription) {
                $freePlan = Subscription::where('type', 'Free')->where('status', 'active')->first();

                return response()->json([
                    'success' => 200,
                    'has_subscription' => false,
                    'plan' => $freePlan ? $this->formatPlan($freePlan) : null,
                    'status' => 'free',
                    'is_active' => true,
                    'message' => 'You are on the Free plan.',
                ], 200);
            }

            return response()->json([
                'success' => 200,
                'has_subscription' => true,
                'subscription' => [
                    'id' => $subscription->id,
                    'status' => $subscription->status,
                    'is_active' => $subscription->isActive(),
                    'payment_method' => $subscription->payment_method,
                    'starts_at' => $subscription->starts_at?->toIso8601String(),
                    'expires_at' => $subscription->expires_at?->toIso8601String(),
                    'next_billing' => $subscription->expires_at?->format('d M Y'),
                    'cancelled_at' => $subscription->cancelled_at?->toIso8601String(),
                    'plan' => $subscription->plan ? $this->formatPlan($subscription->plan) : null,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscription',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function subscribe(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'subscription_id' => 'required|exists:subscriptions,id',
                'payment_method' => 'nullable|string|in:easypaisa,jazzcash,bank,card,google_pay,apple_pay',
            ]);

            $user = $request->user();
            $plan = Subscription::where('id', $data['subscription_id'])->where('status', 'active')->firstOrFail();

            $pending = UserSubscription::where('user_id', $user->id)
                ->whereIn('status', ['paid', 'pending'])
                ->exists();

            if ($pending) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending subscription awaiting verification.',
                ], 422);
            }

            $active = UserSubscription::where('user_id', $user->id)
                ->whereIn('status', ['verified', 'free'])
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->whereNull('cancelled_at')
                ->exists();

            if ($active && (float) $plan->price > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active subscription.',
                ], 422);
            }

            $isFree = (float) $plan->price <= 0;
            $status = $isFree ? 'free' : 'paid';

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'subscription_id' => $plan->id,
                'status' => $status,
                'payment_method' => $data['payment_method'] ?? null,
                'starts_at' => $isFree ? now() : null,
                'expires_at' => $isFree ? now()->addDays($plan->duration_days) : null,
            ]);

            return response()->json([
                'success' => 200,
                'message' => $isFree
                    ? 'Free plan activated successfully.'
                    : 'Subscription request submitted. Please upload payment screenshot.',
                'subscription' => [
                    'id' => $subscription->id,
                    'status' => $subscription->status,
                    'plan' => $this->formatPlan($plan),
                    'requires_payment_upload' => !$isFree,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function uploadPayment(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'user_subscription_id' => 'required|exists:user_subscriptions,id',
                'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            ]);

            $user = $request->user();
            $subscription = UserSubscription::where('user_id', $user->id)
                ->where('id', $data['user_subscription_id'])
                ->where('status', 'paid')
                ->firstOrFail();

            $path = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
            $subscription->update(['payment_screenshot' => $path]);

            return response()->json([
                'success' => 200,
                'message' => 'Payment screenshot uploaded. Please wait for admin verification.',
                'payment_screenshot' => media_url($path),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Pending subscription not found.'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload payment',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function cancel(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $subscription = UserSubscription::with('plan')
                ->where('user_id', $user->id)
                ->whereIn('status', ['verified', 'free', 'paid'])
                ->whereNull('cancelled_at')
                ->latest()
                ->first();

            if (!$subscription || !$subscription->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription to cancel.',
                ], 422);
            }

            $subscription->update(['cancelled_at' => now()]);

            return response()->json([
                'success' => 200,
                'message' => 'Subscription cancelled successfully.',
                'cancelled_at' => $subscription->cancelled_at->toIso8601String(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function formatPlan(Subscription $plan): array
    {
        $features = $plan->features ?? [];

        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'price' => (float) $plan->price,
            'price_label' => 'PKR ' . number_format($plan->price, 0),
            'duration_days' => $plan->duration_days,
            'type' => $plan->type,
            'badge' => $plan->badge,
            'features' => $features,
            'status' => $plan->status,
        ];
    }

    private function comparisonMatrix($plans): array
    {
        $rows = [
            ['key' => 'basic_search', 'label' => 'Basic Search'],
            ['key' => 'chat_limit', 'label' => 'Chats'],
            ['key' => 'boosts_per_month', 'label' => 'Profile Boosts'],
            ['key' => 'super_likes_per_day', 'label' => 'Super Likes'],
            ['key' => 'see_who_liked', 'label' => 'See Who Liked You'],
            ['key' => 'verified_badge', 'label' => 'Plan Badge'],
        ];

        $matrix = [];
        foreach ($rows as $row) {
            $entry = ['feature' => $row['label'], 'plans' => []];
            foreach ($plans as $plan) {
                $value = $plan->features[$row['key']] ?? null;
                $entry['plans'][$plan->type] = $this->formatFeatureValue($row['key'], $value);
            }
            $matrix[] = $entry;
        }

        return $matrix;
    }

    private function formatFeatureValue(string $key, $value): string
    {
        if ($key === 'chat_limit') {
            return $value === null ? 'Unlimited' : ($value . '/day');
        }
        if ($key === 'boosts_per_month') {
            return $value === null ? 'Unlimited' : ((int) $value . '/month');
        }
        if ($key === 'super_likes_per_day') {
            return $value === null ? 'Unlimited' : ((int) $value . '/day');
        }
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return $value ? (string) $value : 'No';
    }
}
