<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\LookupOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LookupController extends Controller
{
    public function countries(): JsonResponse
    {
        try {
            return response()->json([
                'success' => 200,
                'data' => Cache::remember('api_countries', 600, fn () => Country::query()
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get(['id', 'name', 'code', 'slug'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load countries.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function lookup(string $type): JsonResponse
    {
        $definition = config("profile_lookups.{$type}");

        if (! is_array($definition)) {
            return response()->json([
                'success' => false,
                'message' => 'Lookup type not found.',
            ], 404);
        }

        try {
            return response()->json([
                'success' => 200,
                'data' => Cache::remember('api_lookup_'.$type, 600, fn () => LookupOption::fromTable($definition['table'])
                    ->newQuery()
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get(['id', 'name'])),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load '.$definition['label'].'.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function profileOptions(): JsonResponse
    {
        try {
            $options = Cache::remember('api_profile_options', 600, function () {
                $options = [
                    'countries' => Country::query()
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->pluck('name')
                        ->values(),
                ];

                foreach (config('profile_lookups', []) as $type => $definition) {
                    if ($type === 'countries') {
                        continue;
                    }

                    $options[$definition['api_key']] = LookupOption::fromTable($definition['table'])
                        ->newQuery()
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->pluck('name')
                        ->values();
                }

                $options['employment_types'] = $options['employment_types']
                    ->map(fn ($name) => ['value' => Str::snake($name), 'label' => $name])
                    ->values();
                $options['monthly_income_ranges'] = $options['incomes'];
                $options['residential_statuses'] = $options['residence_statuses'];
                $options['genders'] = config('pairi_family.genders');
                $options['professions'] = config('pairi_family.professions');
                $options['filter_cities'] = config('pairi_family.filter_cities');
                $options['quick_filters'] = [
                    ['key' => 'near_me', 'label' => 'Near Me'],
                    ['key' => 'new_profiles', 'label' => 'New Profiles'],
                    ['key' => 'verified', 'label' => 'Verified'],
                ];

                return $options;
            });

            return response()->json([
                'success' => 200,
                'data' => $options,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile options.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
