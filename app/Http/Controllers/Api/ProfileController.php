<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function updateCountry(Request $request): JsonResponse
    {
        try {
            $request->validate(['country' => 'required|string|max:100']);

            $user = $request->user();
            $user->update(['country' => $request->country, 'profile_step' => max($user->profile_step, 1)]);

            return $this->success($user, 'Country saved.');
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update country.', $e);
        }
    }

    public function updateBasicInfo(Request $request): JsonResponse
    {
        try {
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
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update basic info.', $e);
        }
    }

    public function updateEducation(Request $request): JsonResponse
    {
        try {
           

            $user = $request->user();
            $user->update([
                'qualification' => $request->qualification,
                'field_of_study' => $request->field_of_study,
                'university' => $request->university,
                'graduation_year' => $request->graduation_year,
                'profile_step' => max($user->profile_step, 3),
            ]);

            return $this->success($user, 'Education saved.');
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update education.', $e);
        }
    }

    public function updateCareer(Request $request): JsonResponse
    {
        try {
            

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
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update career.', $e);
        }
    }

    public function updatePhysical(Request $request): JsonResponse
    {
        try {
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
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update physical details.', $e);
        }
    }

    public function updateFaith(Request $request): JsonResponse
    {
        try {
        

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
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update faith info.', $e);
        }
    }

  public function uploadPhotos(Request $request): JsonResponse
{
    try {
        $request->validate([
            'photos' => 'required',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'main_index' => 'nullable|integer|min:0',
        ]);

        $user = $request->user();
        
        // Directly get files
        $uploadedFiles = $request->file('photos');
        
        // Ensure it's an array
        if (!is_array($uploadedFiles)) {
            $uploadedFiles = [$uploadedFiles];
        }

        if (empty($uploadedFiles)) {
            return response()->json([
                'success' => false,
                'message' => 'No photos received. Send files as photos[] in form-data.',
            ], 422);
        }

        $photos = array_values($user->photos ?? []);
        if (count($photos) + count($uploadedFiles) > 10) {
            return response()->json([
                'success' => false,
                'message' => 'A user can have a maximum of 10 profile photos.',
            ], 422);
        }

        $mainIndex = $request->filled('main_index')
            ? (int) $request->input('main_index')
            : null;

        if ($mainIndex !== null && $mainIndex >= count($uploadedFiles)) {
            return response()->json([
                'success' => false,
                'message' => 'The selected main photo does not exist.',
            ], 422);
        }

        $hasMainPhoto = collect($photos)->contains(fn ($photo) => (bool) ($photo['is_main'] ?? false));
        if ($mainIndex !== null) {
            foreach ($photos as &$existingPhoto) {
                $existingPhoto['is_main'] = false;
            }
            unset($existingPhoto);
        }

        foreach ($uploadedFiles as $index => $photo) {
            $path = $photo->store('profiles/' . $user->id, 'public');
            $photos[] = [
                'path' => $path,
                'is_main' => $mainIndex !== null
                    ? $index === $mainIndex
                    : (!$hasMainPhoto && $index === 0),
            ];
        }

        $user->update([
            'photos' => $photos,
            'profile_step' => max($user->profile_step, 7),
        ]);

        return $this->success($user->fresh(), 'Photos uploaded.');
    } catch (ValidationException $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return $this->errorResponse('Failed to upload photos.', $e);
    }
}

    public function setMainPhoto(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'photo_index' => 'required|integer|min:0',
            ]);

            $user = $request->user();
            $photos = array_values($user->photos ?? []);
            $selectedIndex = (int) $data['photo_index'];

            if (!array_key_exists($selectedIndex, $photos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected photo does not exist.',
                ], 422);
            }

            foreach ($photos as $index => &$photo) {
                $photo['is_main'] = $index === $selectedIndex;
            }
            unset($photo);

            $user->update(['photos' => $photos]);

            return $this->success($user, 'Profile photo updated.');
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to set profile photo.', $e);
        }
    }

    private function collectUploadedPhotos(Request $request): array
    {
        $files = $request->file('photos');

        if (!$files) {
            return [];
        }

        return is_array($files) ? array_values(array_filter($files)) : [$files];
    }

    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $data = $request->validate([
                'city' => 'sometimes|nullable|string|max:100',
                'country' => 'sometimes|nullable|string|max:100',
                'height' => 'sometimes|nullable|string|max:50',
                'mother_tongue' => 'sometimes|nullable|string|max:100',
                'other_languages' => 'sometimes|nullable|array',
                'other_languages.*' => 'string|max:100',
                'marital_status' => 'sometimes|nullable|string|max:50',
                'community' => 'sometimes|nullable|string|max:100',
                'residential_status' => 'sometimes|nullable|string|max:100',
            ]);

            $user->update($data);

            return $this->success($user->fresh(), 'Profile updated.');
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update profile.', $e);
        }
    }

    public function completeProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->update(['profile_completed' => true, 'profile_step' => 8]);

            return $this->success($user, 'Congratulations! Your profile is ready.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to complete profile.', $e);
        }
    }

    private function success($user, string $message): JsonResponse
    {
        return response()->json([
            'success' => 200,
            'message' => $message,
            'user' => UserResource::toPayload($user->fresh()),
        ], 200);
    }

    private function errorResponse(string $message, \Exception $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
