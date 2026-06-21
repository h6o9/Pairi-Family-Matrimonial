<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('status', 'active')->get();

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
        ]);
    }
}
