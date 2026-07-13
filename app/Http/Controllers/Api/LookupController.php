<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class LookupController extends Controller
{
    public function countries(): JsonResponse
    {
        try {
            return response()->json([
                'success' => 200,
                'data' => config('pairi_family.countries'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load countries.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function profileOptions(): JsonResponse
    {
        try {
            return response()->json([
                'success' => 200,
                'data' => [
                    'countries' => config('pairi_family.countries'),
                    'qualifications' => config('pairi_family.qualifications'),
                    'employment_types' => config('pairi_family.employment_types'),
                    'monthly_income_ranges' => config('pairi_family.monthly_income_ranges'),
                    'body_types' => config('pairi_family.body_types'),
                    'complexions' => config('pairi_family.complexions'),
                    'religions' => config('pairi_family.religions'),
                    'marital_statuses' => config('pairi_family.marital_statuses'),
                    'residential_statuses' => config('pairi_family.residential_statuses'),
                    'mother_tongues' => config('pairi_family.mother_tongues'),
                    'languages' => config('pairi_family.languages'),
                    'genders' => config('pairi_family.genders'),
                    'professions' => config('pairi_family.professions'),
                    'filter_cities' => config('pairi_family.filter_cities'),
                    'quick_filters' => [
                        ['key' => 'near_me', 'label' => 'Near Me'],
                        ['key' => 'new_profiles', 'label' => 'New Profiles'],
                        ['key' => 'verified', 'label' => 'Verified'],
                    ],
                ],
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
