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
