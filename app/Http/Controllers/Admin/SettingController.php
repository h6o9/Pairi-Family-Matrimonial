<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invite_reward_points' => 'required|numeric|min:0',
            'redeem_vip_points' => 'required|numeric|min:0',
            'redeem_vvip_points' => 'required|numeric|min:0',
            'redeem_boost_points' => 'required|numeric|min:0',
            'redeem_boost_days' => 'required|integer|min:1',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::setVal($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
