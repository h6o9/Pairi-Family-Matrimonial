<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'phone_otp',
        'reset_password_token',
        'reset_otp',
        'forget_password_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_otp_expires_at' => 'datetime',
        'phone_otp_expires_at' => 'datetime',
        'reset_token_expires_at' => 'datetime',
        'otp_resend_available_at' => 'datetime',
        'phone_otp_resend_available_at' => 'datetime',
        'terms_accepted_at' => 'datetime',
        'birthday' => 'date',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'physical_disability' => 'boolean',
        'profile_completed' => 'boolean',
        'reset_code_verified' => 'boolean',
        'other_languages' => 'array',
        'interests' => 'array',
        'photos' => 'array',
    ];

    public function getAgeAttribute(): ?int
    {
        return $this->birthday ? $this->birthday->age : null;
    }

    public function getProfilePhotoAttribute(): ?string
    {
        $photos = $this->photos ?? [];

        if (empty($photos)) {
            return $this->image ? asset($this->image) : null;
        }

        $main = collect($photos)->firstWhere('is_main', true) ?? $photos[0];

        return isset($main['path']) ? asset('storage/' . $main['path']) : null;
    }
}
