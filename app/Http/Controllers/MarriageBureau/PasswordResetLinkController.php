<?php

namespace App\Http\Controllers\MarriageBureau;

use App\Http\Controllers\Controller;
use App\Models\MarriageBureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('marriage_bureau.auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = strtolower(trim($request->email));
        $bureau = MarriageBureau::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$bureau) {
            return back()->with(['message' => 'Email does not exist.', 'alert-type' => 'error']);
        }

        $token = Str::random(100);
        $bureau->update(['forget_password_token' => $token]);

        $resetUrl = route('marriage-bureau.password.reset', $token);

        try {
            Mail::send('emails.password-reset', [
                'subject' => 'Password Reset - Piyari Family',
                'heading' => 'Password Reset Request',
                'logoUrl' => url('assets/img/piyari_logo.png'),
                'userName' => $bureau->name,
                'resetUrl' => $resetUrl,
                'messageLine' => 'We received a request to reset your Marriage Bureau account password.',
            ], function ($mail) use ($bureau) {
                $mail->to($bureau->email)
                    ->subject('Password Reset - Piyari Family')
                    ->from(config('mail.from.address'), 'Piyari Family');
            });
        } catch (\Exception $e) {
            return back()->with(['message' => 'Could not send reset email. Please try again.', 'alert-type' => 'error']);
        }

        return back()->with(['message' => 'Password reset link has been sent to your email.', 'alert-type' => 'success']);
    }
}
