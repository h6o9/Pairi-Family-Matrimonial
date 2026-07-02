<?php

namespace App\Http\Controllers\MarriageBureau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $bureau = Auth::guard('marriage_bureau')->user();

        return view('marriage_bureau.profile.edit', compact('bureau'));
    }

    public function update(Request $request)
    {
        $bureau = Auth::guard('marriage_bureau')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:marriage_bureaus,email,' . $bureau->id,
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $bureau->image = file_upload(file: $request->file('image'), path: 'uploads/marriage-bureau/', oldFile: $bureau->image);
        }

        $bureau->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with(['message' => 'Profile updated successfully.', 'alert-type' => 'success']);
    }

    public function updatePassword(Request $request)
    {
        $bureau = Auth::guard('marriage_bureau')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $bureau->password)) {
            return redirect()->back()->with(['message' => 'Current password does not match.', 'alert-type' => 'error']);
        }

        $bureau->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with(['message' => 'Password updated successfully.', 'alert-type' => 'success']);
    }
}
