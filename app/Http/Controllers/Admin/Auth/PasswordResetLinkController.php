<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function custom_forget_password(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => __('Email is required'),
        ]);

        $email = strtolower(trim($request->email));
        $admin = Admin::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$admin) {
            return redirect()->back()->with(['message' => __('Email does not exist'), 'alert-type' => 'error']);
        }

        $token = Str::random(100);
        $admin->forget_password_token = $token;
        $admin->save();

        $resetUrl = route('admin.password.reset', $token);

        try {
            Mail::send('emails.password-reset', [
                'subject' => 'Password Reset - Piyari Family',
                'heading' => 'Password Reset Request',
                'logoUrl' => url('assets/img/piyari_logo.png'),
                'userName' => $admin->name,
                'resetUrl' => $resetUrl,
                'messageLine' => 'We received a request to reset your admin account password.',
            ], function ($mail) use ($admin) {
                $mail->to($admin->email)
                    ->subject('Password Reset - Piyari Family')
                    ->from(config('mail.from.address'), 'Piyari Family');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'Could not send reset email. Please try again.', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('A password reset link has been send to your mail'), 'alert-type' => 'success']);
    }
}
