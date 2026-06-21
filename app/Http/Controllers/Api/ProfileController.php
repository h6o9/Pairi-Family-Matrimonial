<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateCountry(Request $request): JsonResponse
    {
        $request->validate(['country' => 'required|string|max:100']);

        $user = $request->user();
        $user->update(['country' => $request->country, 'profile_step' => max($user->profile_step, 1)]);

        return $this->success($user, 'Country saved.');
    }

    public function updateBasicInfo(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birthday' => 'required|date|before:today',
        ]);

        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'profile_step' => max($user->profile_step, 2),
        ]);

        return $this->success($user, 'Basic info saved.');
    }

    public function updateEducation(Request $request): JsonResponse
    {
        $request->validate([
            'qualification' => 'required|string|max:100',
            'field_of_study' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|string|max:10',
        ]);

        $user = $request->user();
        $user->update([
            'qualification' => $request->qualification,
            'field_of_study' => $request->field_of_study,
            'university' => $request->university,
            'graduation_year' => $request->graduation_year,
            'profile_step' => max($user->profile_step, 3),
        ]);

        return $this->success($user, 'Education saved.');
    }

    public function updateCareer(Request $request): JsonResponse
    {
        $request->validate([
            'employment_type' => 'required|in:employed,self_employed,business',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|string|max:100',
            'residential_status' => 'nullable|string|max:100',
        ]);

        $user = $request->user();
        $user->update([
            'employment_type' => $request->employment_type,
            'job_title' => $request->job_title,
            'company' => $request->company,
            'monthly_income' => $request->monthly_income,
            'residential_status' => $request->residential_status,
            'profile_step' => max($user->profile_step, 4),
        ]);

        return $this->success($user, 'Career info saved.');
    }

    public function updatePhysical(Request $request): JsonResponse
    {
        $request->validate([
            'height' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'body_type' => 'nullable|in:slim,athletic,average,heavy',
            'complexion' => 'nullable|in:fair,wheatish,dusky,dark',
            'physical_disability' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $user->update([
            'height' => $request->height,
            'weight' => $request->weight,
            'body_type' => $request->body_type,
            'complexion' => $request->complexion,
            'physical_disability' => $request->boolean('physical_disability'),
            'profile_step' => max($user->profile_step, 5),
        ]);

        return $this->success($user, 'Physical details saved.');
    }

    public function updateFaith(Request $request): JsonResponse
    {
        $request->validate([
            'religion' => 'nullable|string|max:100',
            'community' => 'nullable|string|max:100',
            'sect' => 'nullable|string|max:100',
            'mother_tongue' => 'nullable|string|max:100',
            'other_languages' => 'nullable|array',
        ]);

        $user = $request->user();
        $user->update([
            'religion' => $request->religion,
            'community' => $request->community,
            'sect' => $request->sect,
            'mother_tongue' => $request->mother_tongue,
            'other_languages' => $request->other_languages,
            'profile_step' => max($user->profile_step, 6),
        ]);

        return $this->success($user, 'Faith & community info saved.');
    }

    public function uploadPhotos(Request $request): JsonResponse
    {
        $minPhotos = config('pairi_family.min_profile_photos', 3);

        $request->validate([
            'photos' => "required|array|min:{$minPhotos}|max:6",
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'main_index' => 'nullable|integer|min:0',
        ]);

        $user = $request->user();
        $photos = [];

        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store('profiles/' . $user->id, 'public');
            $photos[] = [
                'path' => $path,
                'is_main' => $index === (int) $request->input('main_index', 0),
            ];
        }

        $user->update([
            'photos' => $photos,
            'profile_step' => max($user->profile_step, 7),
        ]);

        return $this->success($user, 'Photos uploaded.');
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'bio' => 'nullable|string|max:200',
            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'height' => 'nullable|string|max:50',
            'mother_tongue' => 'nullable|string|max:100',
            'other_languages' => 'nullable|array',
            'marital_status' => 'nullable|string|max:50',
            'community' => 'nullable|string|max:100',
            'residential_status' => 'nullable|string|max:100',
            'interests' => 'nullable|array',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user = $request->user();
        $data = $request->only([
            'name', 'birthday', 'gender', 'bio', 'email', 'phone',
            'city', 'country', 'height', 'mother_tongue', 'marital_status',
            'community', 'residential_status',
        ]);

        if ($request->has('other_languages')) {
            $data['other_languages'] = $request->other_languages;
        }
        if ($request->has('interests')) {
            $data['interests'] = $request->interests;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles/' . $user->id, 'public');
            $photos = $user->photos ?? [];
            array_unshift($photos, ['path' => $path, 'is_main' => true]);
            $data['photos'] = $photos;
        }

        $user->update(array_filter($data, fn ($v) => $v !== null));

        return $this->success($user->fresh(), 'Profile updated.');
    }

    public function completeProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['profile_completed' => true, 'profile_step' => 8]);

        return $this->success($user, 'Congratulations! Your profile is ready.');
    }

    private function success($user, string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'user' => UserResource::toPayload($user->fresh()),
        ]);
    }
}
