<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $request->session()->forget('url');

        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'completed_profiles' => User::where('profile_completed', true)->count(),
            'phone_verified' => User::where('phone_verified', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
