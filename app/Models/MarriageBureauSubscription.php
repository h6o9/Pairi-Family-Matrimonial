<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriageBureauSubscription extends Model
{
    protected $fillable = [
        'marriage_bureau_id',
        'marriage_bureau_subscription_plan_id',
        'status',
        'payment_screenshot',
    ];

    public function marriageBureau()
    {
        return $this->belongsTo(MarriageBureau::class);
    }

    public function plan()
    {
        return $this->belongsTo(MarriageBureauSubscriptionPlan::class, 'marriage_bureau_subscription_plan_id');
    }
}
