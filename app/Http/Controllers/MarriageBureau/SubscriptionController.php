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

        $activeSub = MarriageBureauSubscription::with('plan')
            ->where('marriage_bureau_id', $bureauId)
            ->where('status', 'verified')
            ->latest()
            ->first();

        $pendingSub = MarriageBureauSubscription::with('plan')
            ->where('marriage_bureau_id', $bureauId)
            ->where('status', 'paid')
            ->latest()
            ->first();

        $plans = MarriageBureauSubscriptionPlan::where('status', 'active')->get();

        return view('marriage_bureau.subscription.index', compact('plans', 'activeSub', 'pendingSub'));
    }

    public function store(Request $request)
    {
        $plan = MarriageBureauSubscriptionPlan::where('status', 'active')->find($request->plan_id);

        if (!$plan) {
            return back()->with(['message' => 'Selected plan is not available.', 'alert-type' => 'error']);
        }

        $request->validate([
            'plan_id' => 'required|exists:marriage_bureau_subscription_plans,id',
        ]);

        $bureauId = Auth::guard('marriage_bureau')->id();

        $currentVerified = MarriageBureauSubscription::where('marriage_bureau_id', $bureauId)
            ->where('status', 'verified')
            ->latest()
            ->first();

        if ($currentVerified && (int) $currentVerified->marriage_bureau_subscription_plan_id === (int) $plan->id) {
            return back()->with(['message' => 'This is already your active plan.', 'alert-type' => 'error']);
        }

        // Switching plans: retire the previous active subscription so the
        // new one becomes the single source of truth for access checks.
        if ($currentVerified) {
            $currentVerified->update(['status' => 'replaced']);
        }

        MarriageBureauSubscription::create([
            'marriage_bureau_id' => $bureauId,
            'marriage_bureau_subscription_plan_id' => $plan->id,
            'status' => 'verified',
            'payment_screenshot' => null,
        ]);

        return redirect()->route('marriage-bureau.subscription.index')->with(['message' => 'Plan updated successfully.', 'alert-type' => 'success']);
    }

    public function uploadScreenshot(Request $request)
    {
        $request->validate([
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bureauId = Auth::guard('marriage_bureau')->id();
        $sub = MarriageBureauSubscription::where('marriage_bureau_id', $bureauId)->latest()->first();

        if (!$sub || $sub->status !== 'paid') {
            return back()->with(['message' => 'No pending subscription found.', 'alert-type' => 'error']);
        }

        if ($request->hasFile('payment_screenshot')) {
            $image = $request->file('payment_screenshot');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads/screenshots'), $imageName);
            $sub->payment_screenshot = 'uploads/screenshots/'.$imageName;
            $sub->save();

            return back()->with(['message' => 'Screenshot uploaded successfully. Please wait for admin verification.', 'alert-type' => 'success']);
        }

        return back()->with(['message' => 'Failed to upload screenshot.', 'alert-type' => 'error']);
    }
}
