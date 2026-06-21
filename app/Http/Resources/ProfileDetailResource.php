<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $photos = collect($this->photos ?? [])->map(fn ($photo) => [
            'url' => asset('storage/' . ($photo['path'] ?? '')),
            'is_main' => (bool) ($photo['is_main'] ?? false),
        ])->values()->all();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'city' => $this->city,
            'country' => $this->country,
            'location' => trim(implode(', ', array_filter([$this->city, $this->country]))),
            'profile_photo' => $this->profile_photo,
            'photos' => $photos,
            'is_verified' => (bool) $this->is_verified,
            'phone_verified' => (bool) $this->phone_verified,
            'bio' => $this->bio,
            'qualification' => $this->qualification,
            'profession' => $this->job_title,
            'job_title' => $this->job_title,
            'company' => $this->company,
            'religion' => $this->religion,
            'community' => $this->community,
            'sect' => $this->sect,
            'marital_status' => $this->marital_status,
            'height' => $this->height,
            'weight' => $this->weight,
            'body_type' => $this->body_type,
            'complexion' => $this->complexion,
            'mother_tongue' => $this->mother_tongue,
            'other_languages' => $this->other_languages ?? [],
            'interests' => $this->interests ?? [],
            'monthly_income' => $this->monthly_income,
            'residential_status' => $this->residential_status,
            'employment_type' => $this->employment_type,
            'field_of_study' => $this->field_of_study,
            'university' => $this->university,
            'graduation_year' => $this->graduation_year,
            'physical_disability' => (bool) $this->physical_disability,
            'gender' => $this->gender,
            'match_score' => (int) ($this->match_score ?? 0),
            'is_new' => $this->created_at?->gte(now()->subDays(30)) ?? false,
            'interest_sent' => (bool) ($this->interest_sent ?? false),
        ];
    }
}
