<?php

namespace App\Http\Resources;

use App\Models\PhotoAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $accessGranted = $viewer
            && ($viewer->id === $this->id
                || PhotoAccessRequest::hasApprovedAccess((int) $viewer->id, (int) $this->id));
        $profilePhotoVisible = (bool) ($this->profile_photo_visible ?? true) || $accessGranted;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'city' => $this->city,
            'country' => $this->country,
            'location' => trim(implode(', ', array_filter([$this->city, $this->country]))),
            'profession' => $this->job_title,
            'qualification' => $this->qualification,
            'religion' => $this->religion,
            'marital_status' => $this->marital_status,
            'profile_photo' => $profilePhotoVisible ? $this->profile_photo : null,
            'profile_photo_visible' => $profilePhotoVisible,
            'is_verified' => (bool) $this->is_verified,
            'phone_verified' => (bool) $this->phone_verified,
            'is_new' => $this->created_at?->gte(now()->subDays(config('pairi_family.new_profile_days', 3))) ?? false,
            'match_score' => (int) ($this->match_score ?? 0),
            'interests' => $this->interests ?? [],
        ];
    }
}
