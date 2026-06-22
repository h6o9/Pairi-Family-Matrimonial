<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        try {
            $subscriptions = Subscription::where('status', 'active')->get();

            return response()->json([
                'success' => true,
                'subscriptions' => $subscriptions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscriptions',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
