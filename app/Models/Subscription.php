<?php

namespace App\Models;

use App\Services\SubscriptionAccessService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    public function displayFeatures(): array
    {
        $features = $this->features ?? [];

        return $features['display'] ?? [];
    }

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
