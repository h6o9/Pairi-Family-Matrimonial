<?php

namespace App\Http\Controllers\MarriageBureau;

use App\Http\Controllers\Controller;
use App\Models\MarriageBureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller
{
    public function create(string $token)
    {
        $bureau = MarriageBureau::where('forget_password_token', $token)->first();

        if (!$bureau) {
            return view('marriage_bureau.auth.expired-token');
        }

        return view('marriage_bureau.auth.reset-password', compact('bureau', 'token'));
    }

    public function store(Request $request, string $token)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $bureau = MarriageBureau::where('forget_password_token', $token)
            ->where('email', $request->email)
            ->first();

        if (!$bureau) {
            return view('marriage_bureau.auth.expired-token');
        }

        $bureau->update([
            'password' => Hash::make($request->password),
            'forget_password_token' => null,
        ]);

        return redirect()->route('marriage-bureau.login')
            ->with(['message' => 'Password reset successfully. Please login.', 'alert-type' => 'success']);
    }
}
