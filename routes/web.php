<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

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
