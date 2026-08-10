<?php

namespace App\Models;

use App\Services\SubscriptionAccessService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MarriageBureauSubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'duration_unit',
        'type',
        'payment_status',
        'features',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    public function durationLabel(): string
    {
        $value = (int) ($this->duration_days ?? 0);
        $unit = strtolower((string) ($this->duration_unit ?? 'days'));

        if ($unit === 'months') {
            return $value . ' ' . ($value === 1 ? 'Month' : 'Months');
        }

        return $value . ' ' . ($value === 1 ? 'Day' : 'Days');
    }

    public function expiresAtFrom(?Carbon $from = null): Carbon
    {
        $from = $from ?? now();

        return SubscriptionAccessService::addDuration(
            $from,
            (int) ($this->duration_days ?? 30),
            (string) ($this->duration_unit ?? 'days')
        );
    }
}
