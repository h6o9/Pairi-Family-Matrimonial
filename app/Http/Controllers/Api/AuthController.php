<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'terms_accepted' => 'required|accepted',
        ]);

        $otp = $this->generateOtp(4);
        $resendAt = now()->addSeconds(config('pairi_family.otp_resend_seconds', 45));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(10),
            'otp_resend_available_at' => $resendAt,
            'terms_accepted_at' => now(),
            'status' => 'active',
        ]);

        $this->sendOtpEmail($user->email, $otp, 'Email Verification - Pairi Family');

        return response()->json([
            'success' => true,
            'message' => 'Account created. We sent a 4-digit code to your email.',
            'user' => UserResource::make($user),
            'resend_after_seconds' => config('pairi_family.otp_resend_seconds', 45),
        ], 201);
    }

    public function verifyEmailOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->is_verified) {
            return response()->json([
                'success' => true,
                'message' => 'Email already verified.',
                'user' => UserResource::make($user),
                'token' => $user->createToken('auth')->plainTextToken,
            ]);
        }

        if ($user->otp !== $request->otp || ($user->email_otp_expires_at && $user->email_otp_expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
        }

        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
            'otp' => null,
            'email_otp_expires_at' => null,
            'otp_resend_available_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
            'user' => UserResource::make($user->fresh()),
            'token' => $user->createToken('auth')->plainTextToken,
        ]);
    }

    public function resendEmailOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->is_verified) {
            return response()->json(['success' => false, 'message' => 'Email is already verified.'], 400);
        }

        if ($user->otp_resend_available_at && $user->otp_resend_available_at->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before requesting a new code.',
                'resend_after_seconds' => now()->diffInSeconds($user->otp_resend_available_at),
            ], 429);
        }

        $otp = $this->generateOtp(4);
        $resendSeconds = config('pairi_family.otp_resend_seconds', 45);

        $user->update([
            'otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(10),
            'otp_resend_available_at' => now()->addSeconds($resendSeconds),
        ]);

        $this->sendOtpEmail($user->email, $otp, 'Email Verification - Pairi Family');

        return response()->json([
            'success' => true,
            'message' => 'OTP resent to your email.',
            'resend_after_seconds' => $resendSeconds,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Your account is inactive.'], 403);
        }

        if (!$user->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first.',
                'requires_verification' => true,
                'email' => $user->email,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'user' => UserResource::make($user),
            'token' => $user->createToken('auth')->plainTextToken,
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $otp = $this->generateOtp(4);
        $resendSeconds = config('pairi_family.otp_resend_seconds', 45);

        $user->update([
            'reset_otp' => $otp,
            'reset_token_expires_at' => now()->addMinutes(10),
            'reset_code_verified' => false,
            'otp_resend_available_at' => now()->addSeconds($resendSeconds),
        ]);

        $this->sendOtpEmail($user->email, $otp, 'Password Reset - Pairi Family');

        return response()->json([
            'success' => true,
            'message' => 'Reset code sent to your email.',
            'resend_after_seconds' => $resendSeconds,
        ]);
    }

    public function verifyResetOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->reset_otp !== $request->otp || ($user->reset_token_expires_at && $user->reset_token_expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired code.'], 400);
        }

        $user->update(['reset_code_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Code verified! You can now set a new password.',
        ]);
    }

    public function setNewPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->reset_code_verified || ($user->reset_token_expires_at && $user->reset_token_expires_at->isPast())) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your reset code first.',
            ], 400);
        }

        $user->update([
            'password' => $request->password,
            'reset_otp' => null,
            'reset_token_expires_at' => null,
            'reset_code_verified' => false,
            'reset_password_token' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:4',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->reset_otp !== $request->otp || ($user->reset_token_expires_at && $user->reset_token_expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired code.'], 400);
        }

        $user->update([
            'password' => $request->password,
            'reset_otp' => null,
            'reset_token_expires_at' => null,
            'reset_code_verified' => false,
            'reset_password_token' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Password reset successfully.']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 400);
        }

        $user->update(['password' => $request->password]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    }

    public function sendPhoneOtp(Request $request): JsonResponse
    {
        $request->validate(['phone' => 'required|string|max:20']);

        $user = $request->user();
        $resendSeconds = config('pairi_family.otp_resend_seconds', 45);

        if ($user->phone_otp_resend_available_at && $user->phone_otp_resend_available_at->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before requesting a new code.',
                'resend_after_seconds' => now()->diffInSeconds($user->phone_otp_resend_available_at),
            ], 429);
        }

        $otp = $this->generateOtp(6);

        $user->update([
            'phone' => $request->phone,
            'phone_otp' => $otp,
            'phone_otp_expires_at' => now()->addMinutes(10),
            'phone_otp_resend_available_at' => now()->addSeconds($resendSeconds),
            'phone_verified' => false,
        ]);

        $response = [
            'success' => true,
            'message' => 'Verification code sent to your phone.',
            'resend_after_seconds' => $resendSeconds,
        ];

        if (config('app.debug')) {
            $response['debug_otp'] = $otp;
        }

        return response()->json($response);
    }

    public function resendPhoneOtp(Request $request): JsonResponse
    {
        $user = $request->user();
        $resendSeconds = config('pairi_family.otp_resend_seconds', 45);

        if ($user->phone_verified) {
            return response()->json(['success' => false, 'message' => 'Phone is already verified.'], 400);
        }

        if ($user->phone_otp_resend_available_at && $user->phone_otp_resend_available_at->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before requesting a new code.',
                'resend_after_seconds' => now()->diffInSeconds($user->phone_otp_resend_available_at),
            ], 429);
        }

        $otp = $this->generateOtp(6);

        $user->update([
            'phone_otp' => $otp,
            'phone_otp_expires_at' => now()->addMinutes(10),
            'phone_otp_resend_available_at' => now()->addSeconds($resendSeconds),
        ]);

        $response = [
            'success' => true,
            'message' => 'Verification code resent.',
            'resend_after_seconds' => $resendSeconds,
        ];

        if (config('app.debug')) {
            $response['debug_otp'] = $otp;
        }

        return response()->json($response);
    }

    public function verifyPhoneOtp(Request $request): JsonResponse
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $user = $request->user();

        if ($user->phone_otp !== $request->otp || ($user->phone_otp_expires_at && $user->phone_otp_expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired code.'], 400);
        }

        $user->update([
            'phone_verified' => true,
            'phone_otp' => null,
            'phone_otp_expires_at' => null,
            'phone_otp_resend_available_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Number verified! Your profile has been verified successfully.',
            'user' => UserResource::make($user->fresh()),
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => UserResource::make($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    }

    private function generateOtp(int $length): string
    {
        $max = (10 ** $length) - 1;

        return str_pad((string) random_int(0, $max), $length, '0', STR_PAD_LEFT);
    }

    private function sendOtpEmail(string $email, string $otp, string $subject): void
    {
        try {
            Mail::raw("Your Pairi Family verification code is: {$otp}\n\nThis code expires in 10 minutes.", function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });
        } catch (\Exception $e) {
            logError('OTP email failed', $e);
        }
    }
}
