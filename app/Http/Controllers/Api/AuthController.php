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
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {

        try {
			$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',  // ✅ یہ چیک کرے گا
            'phone' => 'required|string|max:20',
              'password' => 'required|string|min:8',
            'referral_code' => 'nullable|string|exists:users,referral_code',
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

            if ($request->filled('referral_code')) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if ($referrer) {
                    $points = \App\Models\SystemSetting::getVal('invite_reward_points', 50);
                    
                    \App\Models\Referral::create([
                        'referrer_id' => $referrer->id,
                        'referred_user_id' => $user->id,
                        'points_earned' => $points,
                    ]);

                    $referrer->increment('reward_points', $points);
                }
            }

            $this->sendOtpEmail($user->email, $otp, 'Email Verification - Pairi Family');

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully. We sent a 4-digit code to your email.',
                'user' => UserResource::toPayload($user),
                'resend_after_seconds' => config('pairi_family.otp_resend_seconds', 45),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function verifyEmailOtp(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string|size:4',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user->is_verified) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email already verified.',
                    'user' => UserResource::toPayload($user),
                    'token' => $user->createToken('auth')->plainTextToken,
                ], 200);
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
                'user' => UserResource::toPayload($user->fresh()),
                'token' => $user->createToken('auth')->plainTextToken,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify email OTP.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function resendEmailOtp(Request $request): JsonResponse
    {
        try {
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
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
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
                'user' => UserResource::toPayload($user),
                'token' => $user->createToken('auth')->plainTextToken,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

  public function forgotPassword(Request $request): JsonResponse
{
    try {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        
        // Generate 6-digit OTP
        $otp = $this->ForgetgenerateOtp(6);
        $resendSeconds = config('pairi_family.otp_resend_seconds', 45);

        // Update user with OTP
        $user->otp = $otp;
        $user->reset_token_expires_at = now()->addMinutes(10);
        $user->reset_code_verified = false;
        $user->otp_resend_available_at = now()->addSeconds($resendSeconds);
        $user->save();

        // Send OTP email
        $this->sendOtpEmail($user->email, $otp, 'Password Reset - Pairi Family');

        return response()->json([
            'success' => true,
            'message' => 'Reset code sent to your email.',
            'resend_after_seconds' => $resendSeconds,
        ], 200);
        
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage(), 
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Forgot password request failed.',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

/**
 * Generate OTP with specified length
 */
private function ForgetgenerateOtp($length = 6): string
{
    // Generate random 6-digit number
    return str_pad((string) random_int(0, 999999), $length, '0', STR_PAD_LEFT);
}

 public function verifyResetOtp(Request $request): JsonResponse
{
    try {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if OTP matches and not expired
        if (!$user->reset_otp || $user->otp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP code.',
            ], 400);
        }

        if ($user->reset_token_expires_at && $user->reset_token_expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }

        // Mark as verified
        $user->reset_code_verified = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully. You can now reset your password.',
        ], 200);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage(), 
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'OTP verification failed.',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}


    public function setNewPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user->reset_code_verified || ($user->reset_token_expires_at && $user->reset_token_expires_at->isPast())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your reset code first.',
                ], 400);
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_otp' => null,
                'reset_token_expires_at' => null,
                'reset_code_verified' => false,
                'reset_password_token' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Set new password failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string|size:4',
                'password' =>'required', 
				]);

            $user = User::where('email', $request->email)->first();

            if ($user->reset_otp !== $request->otp || ($user->reset_token_expires_at && $user->reset_token_expires_at->isPast())) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired code.'], 400);
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_otp' => null,
                'reset_token_expires_at' => null,
                'reset_code_verified' => false,
                'reset_password_token' => null,
            ]);

            return response()->json(['success' => true, 'message' => 'Password reset successfully.'], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function changePassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 400);
            }

            $user->update(['password' => Hash::make($request->password)]);

            return response()->json(['success' => true, 'message' => 'Password changed successfully.'], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password change failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function sendPhoneOtp(Request $request): JsonResponse
    {
        try {
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

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Send phone OTP failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function resendPhoneOtp(Request $request): JsonResponse
    {
        try {
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

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resend phone OTP failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function verifyPhoneOtp(Request $request): JsonResponse
    {
        try {
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
                'user' => UserResource::toPayload($user->fresh()),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verify phone OTP failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function profile(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'user' => UserResource::toPayload($request->user()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fetch profile failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['success' => true, 'message' => 'Logged out successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
            // Log error silently
        }
    }
}
