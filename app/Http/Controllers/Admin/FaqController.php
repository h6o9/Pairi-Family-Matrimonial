<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::query()->latest()->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));
        Cache::forget('api_faqs');

        return redirect()->route('admin.faqs.index')->with([
            'message' => 'FAQ created successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));
        Cache::forget('api_faqs');

        return redirect()->route('admin.faqs.index')->with([
            'message' => 'FAQ updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        Cache::forget('api_faqs');

        return redirect()->route('admin.faqs.index')->with([
            'message' => 'FAQ deleted successfully.',
            'alert-type' => 'success',
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
