<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they is not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $path = $request->path();

        if (str_contains($path, 'marriage-bureau') || $request->is('marriage-bureau/*', '*/marriage-bureau/*')) {
            return route('marriage-bureau.login');
        }

        if (str_contains($path, 'staff/') || $request->is('staff/*', '*/staff/*')) {
            return route('staff.login');
        }

        if (str_contains($path, 'admin/') || $request->is('admin/*', '*/admin/*')) {
            return route('admin.login');
        }

        if ($request->route()) {
            $routeName = $request->route()->getName() ?? '';

            if (str_starts_with($routeName, 'marriage-bureau.')) {
                return route('marriage-bureau.login');
            }

            if (str_starts_with($routeName, 'staff.')) {
                return route('staff.login');
            }

            if (str_starts_with($routeName, 'admin.')) {
                return route('admin.login');
            }
        }

        if (\Illuminate\Support\Facades\Route::has('login')) {
            return route('login');
        }

        return url('/');
    }
}
