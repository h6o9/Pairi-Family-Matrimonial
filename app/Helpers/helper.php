<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

if (!function_exists('isRoute')) {
    function isRoute(string|array $route, string|null $returnValue = null)
    {
        if (is_array($route)) {
            foreach ($route as $value) {
                if (Route::is($value)) {
                    return is_null($returnValue) ? true : $returnValue;
                }
            }

            return false;
        }

        if (Route::is($route)) {
            return is_null($returnValue) ? true : $returnValue;
        }

        return false;
    }
}

if (!function_exists('logError')) {
    function logError(string $context, \Exception $exception): void
    {
        logger()->error("{$context}: " . $exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}

if (!function_exists('app_base_url')) {
    function app_base_url(): string
    {
        return rtrim((string) config('pairi_family.base_url', config('app.url')), '/');
    }
}

if (!function_exists('referral_link')) {
    function referral_link(?string $referralCode): ?string
    {
        if (empty($referralCode)) {
            return null;
        }

        return app_base_url() . '/' . $referralCode;
    }
}

if (!function_exists('media_url')) {
    function media_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalized, 'uploads/store/')) {
            return app_base_url() . '/' . $normalized;
        }

        if (str_starts_with($normalized, 'uploads/')) {
            return app_base_url() . '/' . $normalized;
        }

        return app_base_url() . '/uploads/store/' . $normalized;
    }
}

if (!function_exists('file_upload')) {
    function file_upload(UploadedFile $file, string $path = 'uploads/custom-images/', string|null $oldFile = null): ?string
    {
        $blockedExtensions = ['php', 'phtml', 'phar', 'sh', 'exe', 'js', 'html', 'htaccess'];

        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, $blockedExtensions)) {
            Log::warning("Blocked upload. Ext: {$ext}");
            return null;
        }

        if (!File::isDirectory(public_path($path))) {
            File::makeDirectory(public_path($path), 0755, true);
        }

        $fileName = 'img-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $ext;
        $fullPath = $path . $fileName;
        $file->move(public_path($path), $fileName);

        if ($oldFile && File::exists(public_path($oldFile))) {
            File::delete(public_path($oldFile));
        }

        return $fullPath;
    }
}
