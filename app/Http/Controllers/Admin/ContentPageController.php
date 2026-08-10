<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ContentPageController extends Controller
{
    public function edit(string $type)
    {
        $pageType = $this->pageType($type);
        $page = ContentPage::firstOrNew(
            ['type' => $pageType],
            ['title' => $this->defaultTitle($pageType), 'status' => 'active']
        );

        return view('admin.content_pages.edit', compact('page', 'type'));
    }

    public function update(Request $request, string $type)
    {
        $pageType = $this->pageType($type);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        ContentPage::updateOrCreate(['type' => $pageType], $data);
        Cache::forget('api_content_'.$pageType);

        return redirect()->route('admin.content.edit', ['type' => $type])->with([
            'message' => $this->defaultTitle($pageType).' updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    private function pageType(string $type): string
    {
        return match ($type) {
            'terms-conditions' => 'terms_conditions',
            'privacy-policy' => 'privacy_policy',
            default => abort(404),
        };
    }

    private function defaultTitle(string $type): string
    {
        return $type === 'terms_conditions' ? 'Terms & Conditions' : 'Privacy Policy';
    }
}
