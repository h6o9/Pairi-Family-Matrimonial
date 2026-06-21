<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'settings' => SystemSetting::all()->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($data['settings'] as $key => $value) {
            SystemSetting::setVal($key, $value);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);
    }
}
