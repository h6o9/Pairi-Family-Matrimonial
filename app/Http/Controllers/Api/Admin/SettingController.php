<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'success' => 200,
                'settings' => SystemSetting::all()->pluck('value', 'key'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'settings' => 'required|array',
            ]);

            foreach ($data['settings'] as $key => $value) {
                SystemSetting::setVal($key, $value);
            }

            return response()->json([
                'success' => 200,
                'message' => 'Settings updated successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
