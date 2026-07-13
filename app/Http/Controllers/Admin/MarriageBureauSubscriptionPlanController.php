<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarriageBureauSubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = \App\Models\MarriageBureauSubscriptionPlan::latest()->get();
        return view('admin.marriage_bureau_subscription_plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\App\Models\MarriageBureauSubscriptionPlan::exists()) {
            return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Only one subscription plan is allowed. Please edit the existing plan instead.', 'alert-type' => 'error']);
        }

        return view('admin.marriage_bureau_subscription_plans.create');
    }

    public function store(Request $request)
    {
        if (\App\Models\MarriageBureauSubscriptionPlan::exists()) {
            return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Only one subscription plan is allowed. Please edit the existing plan instead.', 'alert-type' => 'error']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:free,paid',
            'features' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['features'] = $this->parseFeatures($request->input('features'));

        \App\Models\MarriageBureauSubscriptionPlan::create($validated);
        
        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Plan created successfully.', 'alert-type' => 'success']);
    }

    public function edit(string $id)
    {
        $marriage_bureau_subscription = \App\Models\MarriageBureauSubscriptionPlan::findOrFail($id);
        return view('admin.marriage_bureau_subscription_plans.edit', compact('marriage_bureau_subscription'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:free,paid',
            'features' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['features'] = $this->parseFeatures($request->input('features'));

        $plan = \App\Models\MarriageBureauSubscriptionPlan::findOrFail($id);
        $plan->update($validated);

        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Plan updated successfully.', 'alert-type' => 'success']);
    }

    private function parseFeatures(?string $features): array
    {
        if (!$features) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $features))));
    }

    public function destroy(string $id)
    {
        $plan = \App\Models\MarriageBureauSubscriptionPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Deleted successfully.', 'alert-type' => 'success']);
    }
}
