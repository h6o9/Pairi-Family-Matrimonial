<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('marriageBureau:id,name')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === 'yes');
        }

        if ($request->filled('phone_verified')) {
            $query->where('phone_verified', $request->phone_verified === 'yes');
        }

        if ($request->filled('profile')) {
            if ($request->profile === 'complete') {
                $query->where('profile_completed', true);
            } elseif ($request->profile === 'incomplete') {
                $query->where('profile_completed', false);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('creation_type')) {
            if ($request->creation_type === 'app') {
                $query->whereNull('marriage_bureau_id');
            } elseif ($request->creation_type === 'marriage_bureau') {
                $query->whereNotNull('marriage_bureau_id');
            }
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with(['message' => 'Deleted successfully.', 'alert-type' => 'success']);
    }

    public function verifyEmail(Request $request, User $user)
    {
        if ($user->is_verified) {
            return response()->json(['success' => false, 'message' => 'Email is already verified.']);
        }

        $user->update(['is_verified' => true, 'email_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully for ' . $user->name,
        ]);
    }

    public function verifyPhone(Request $request, User $user)
    {
        if ($user->phone_verified) {
            return response()->json(['success' => false, 'message' => 'Phone is already verified.']);
        }

        $user->update(['phone_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully for ' . $user->name,
        ]);
    }

    public function toggleStatus(Request $request, User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated.',
            'status' => $user->status,
        ]);
    }
    public function verifySubscription(Request $request, User $user)
    {
        $request->validate([
            'user_subscription_id' => 'required|exists:user_subscriptions,id',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subscription = \App\Models\UserSubscription::where('user_id', $user->id)
            ->where('id', $request->user_subscription_id)
            ->firstOrFail();

        if ($request->hasFile('payment_screenshot')) {
            $path = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
            $subscription->load('plan');
            $subscription->payment_screenshot = $path;
            $subscription->status = 'verified';
            $subscription->starts_at = now();
            $subscription->expires_at = $subscription->plan
                ? $subscription->plan->expiresAtFrom(now())
                : now()->addDays(30);
            $subscription->save();

            return response()->json([
                'success' => true,
                'message' => 'Subscription payment verified successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment screenshot is required.',
        ]);
    }
}
