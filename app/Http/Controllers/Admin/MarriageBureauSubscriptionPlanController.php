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
        $plans = \App\Models\MarriageBureauSubscriptionPlan::latest()->paginate(15);
        return view('admin.marriage_bureau_subscription_plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marriage_bureau_subscription_plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:free,paid',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

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
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $plan = \App\Models\MarriageBureauSubscriptionPlan::findOrFail($id);
        $plan->update($validated);

        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Plan updated successfully.', 'alert-type' => 'success']);
    }

    public function destroy(string $id)
    {
        $plan = \App\Models\MarriageBureauSubscriptionPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.marriage-bureau-subscriptions.index')->with(['message' => 'Plan deleted successfully.', 'alert-type' => 'success']);
    }
}
