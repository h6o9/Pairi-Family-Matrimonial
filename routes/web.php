<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/{referralCode}', function (string $referralCode) {
    if (!preg_match('/^[A-Z0-9]{8}$/', $referralCode)) {
        abort(404);
    }

    $referrer = User::where('referral_code', $referralCode)->first();

    if (!$referrer) {
        abort(404);
    }

    return response()->json([
        'success' => 200,
        'referral_code' => $referralCode,
        'referral_link' => referral_link($referralCode),
        'referrer_name' => $referrer->name,
        'message' => 'Valid referral link. Use referral_code in register API.',
    ]);
})->where('referralCode', '[A-Z0-9]{8}');

Route::fallback(function () {
    if (request()->is('api') || request()->is('api/*')) {
        return response()->json([
            'success' => false,
            'message' => 'API endpoint not found.',
            'path' => request()->path(),
            'method' => request()->method(),
        ], 404);
    }

    abort(404);
});
