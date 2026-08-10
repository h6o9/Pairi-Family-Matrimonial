<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::whereIn('type', ['Free', 'VIP', 'VVIP'])
            ->orderByRaw("FIELD(type, 'Free', 'VIP', 'VVIP')")
            ->get();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return redirect()->route('admin.subscriptions.index')->with([
            'message' => 'New subscription plans cannot be created. Only Free, VIP and VVIP plans are allowed.',
            'alert-type' => 'error',
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.subscriptions.index')->with([
            'message' => 'New subscription plans cannot be created.',
            'alert-type' => 'error',
        ]);
    }

    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'duration_unit' => 'required|in:days,months',
        ]);

        // Keep Free plan price at 0
        if ($subscription->type === 'Free') {
            $data['price'] = 0;
        }

        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')->with([
            'message' => 'Subscription updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Subscription $subscription)
    {
        return redirect()->route('admin.subscriptions.index')->with([
            'message' => 'Subscription plans cannot be deleted.',
            'alert-type' => 'error',
        ]);
    }
}
