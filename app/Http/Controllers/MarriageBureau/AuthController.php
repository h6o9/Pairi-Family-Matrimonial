<?php

namespace App\Http\Controllers\MarriageBureau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\MarriageBureau;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('marriage_bureau.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('marriage_bureau')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('marriage-bureau.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('marriage_bureau.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:marriage_bureaus',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $bureau = MarriageBureau::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('marriage_bureau')->login($bureau);

        return redirect()->route('marriage-bureau.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('marriage_bureau')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('marriage-bureau.login');
    }
}
