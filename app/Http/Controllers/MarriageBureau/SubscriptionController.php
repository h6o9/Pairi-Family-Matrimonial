<?php

namespace App\Http\Controllers\MarriageBureau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MarriageBureauSubscriptionPlan;
use App\Models\MarriageBureauSubscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        $bureauId = Auth::guard('marriage_bureau')->id();
        $activeSub = MarriageBureauSubscription::where('marriage_bureau_id', $bureauId)->where('status', 'verified')->first();
        
        $plans = MarriageBureauSubscriptionPlan::where('status', 'active')->get();

        return view('marriage_bureau.subscription.index', compact('plans', 'activeSub'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:marriage_bureau_subscription_plans,id',
            'payment_method' => 'required|string',
        ]);

        $plan = MarriageBureauSubscriptionPlan::findOrFail($request->plan_id);
        $bureauId = Auth::guard('marriage_bureau')->id();

        // Check if there's already a pending or active sub
        $existing = MarriageBureauSubscription::where('marriage_bureau_id', $bureauId)
            ->whereIn('status', ['paid', 'verified'])
            ->first();

        if ($existing && $existing->status === 'verified') {
            return back()->with(['message' => 'You already have an active subscription.', 'alert-type' => 'error']);
        }

        if ($existing && $existing->status === 'paid') {
            return back()->with(['message' => 'You already have a pending subscription waiting for admin verification.', 'alert-type' => 'error']);
        }

        $status = $plan->price > 0 ? 'paid' : 'verified';

        MarriageBureauSubscription::create([
            'marriage_bureau_id' => $bureauId,
            'marriage_bureau_subscription_plan_id' => $plan->id,
            'status' => $status,
            'payment_screenshot' => null, // Will be updated by Admin
        ]);

        if ($status === 'paid') {
            return redirect()->route('marriage-bureau.dashboard')->with(['message' => 'Your subscription request has been submitted. Please wait for admin verification.', 'alert-type' => 'success']);
        }

        return redirect()->route('marriage-bureau.dashboard')->with(['message' => 'Free subscription activated successfully.', 'alert-type' => 'success']);
    }
}
