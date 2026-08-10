<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ContentController extends Controller
{
    public function faqs(): JsonResponse
    {
        $faqs = Cache::remember('api_faqs', 600, fn () => Faq::query()
            ->where('status', 'active')
            ->latest()
            ->get(['id', 'question', 'answer']));

        return response()->json(['success' => 200, 'data' => $faqs]);
    }

    public function termsConditions(): JsonResponse
    {
        return $this->page('terms_conditions');
    }

    public function privacyPolicy(): JsonResponse
    {
        return $this->page('privacy_policy');
    }

    private function page(string $type): JsonResponse
    {
        $page = Cache::remember('api_content_'.$type, 600, fn () => ContentPage::query()
            ->where('type', $type)
            ->where('status', 'active')
            ->first(['title', 'content', 'updated_at']));

        if (! $page) {
            return response()->json([
                'success' => false,
                'message' => 'Content is not available.',
            ], 404);
        }

        return response()->json(['success' => 200, 'data' => $page]);
    }
}
