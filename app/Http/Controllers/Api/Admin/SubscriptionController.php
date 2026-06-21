<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'subscriptions' => Subscription::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'type' => 'required|string',
            'badge' => 'nullable|string',
            'features' => 'nullable|array',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $subscription = Subscription::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'subscription' => $subscription,
        ]);
    }

    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);
        return response()->json([
            'success' => true,
            'subscription' => $subscription,
        ]);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'duration_days' => 'sometimes|integer|min:1',
            'type' => 'sometimes|string',
            'badge' => 'nullable|string',
            'features' => 'nullable|array',
            'status' => 'sometimes|string|in:active,inactive',
        ]);

        $subscription->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'subscription' => $subscription,
        ]);
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted successfully',
        ]);
    }
}
