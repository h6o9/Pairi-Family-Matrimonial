<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarriageBureauSubscriptionPlan;
use Illuminate\Http\Request;

class MarriageBureauSubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = MarriageBureauSubscriptionPlan::latest()->get();

        return view('admin.marriage_bureau_subscription_plans.index', compact('plans'));
    }

    public function create()
    {
        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with([
            'message' => 'Only one MB subscription plan is allowed. Please edit the existing plan.',
            'alert-type' => 'error',
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with([
            'message' => 'Creating new MB subscription plans is disabled.',
            'alert-type' => 'error',
        ]);
    }

    public function edit(string $id)
    {
        $marriage_bureau_subscription = MarriageBureauSubscriptionPlan::findOrFail($id);

        return view('admin.marriage_bureau_subscription_plans.edit', compact('marriage_bureau_subscription'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'duration_unit' => 'required|in:days,months',
        ]);

        $plan = MarriageBureauSubscriptionPlan::findOrFail($id);
        $plan->update($validated);

        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with([
            'message' => 'Plan updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(string $id)
    {
        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with([
            'message' => 'MB subscription plan cannot be deleted.',
            'alert-type' => 'error',
        ]);
    }
}
