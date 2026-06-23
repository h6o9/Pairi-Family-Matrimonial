<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriageBureauSubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'payment_status',
        'description',
        'status',
    ];
}
