<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MatchService
{
    public function oppositeGender(?string $gender): ?string
    {
        return match ($gender) {
            'male' => 'female',
            'female' => 'male',
            default => null,
        };
    }

    public function baseQuery(User $viewer): Builder
    {
        $opposite = $this->oppositeGender($viewer->gender);

        $excludedIds = $viewer->interactedUserIds();

        return User::query()
            ->where('id', '!=', $viewer->id)
            ->where('status', 'active')
            ->where('profile_completed', true)
            ->when($opposite, fn (Builder $q) => $q->where('gender', $opposite))
            ->when($excludedIds->isNotEmpty(), fn (Builder $q) => $q->whereNotIn('id', $excludedIds));
    }

    public function applyFilters(Builder $query, User $viewer, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['cities']) && is_array($filters['cities'])) {
            $query->where(function (Builder $q) use ($filters) {
                foreach ($filters['cities'] as $city) {
                    $q->orWhere('city', 'like', '%' . $city . '%');
                }
            });
        }

        if (!empty($filters['age_min'])) {
            $maxBirthday = now()->subYears((int) $filters['age_min'])->format('Y-m-d');
            $query->where('birthday', '<=', $maxBirthday);
        }

        if (!empty($filters['age_max'])) {
            $minBirthday = now()->subYears(((int) $filters['age_max']) + 1)->addDay()->format('Y-m-d');
            $query->where('birthday', '>=', $minBirthday);
        }

        if (!empty($filters['qualification']) && $filters['qualification'] !== 'Any') {
            $query->where('qualification', $filters['qualification']);
        }

        if (!empty($filters['profession']) && $filters['profession'] !== 'Any') {
            $query->where('job_title', 'like', '%' . $filters['profession'] . '%');
        }

        if (!empty($filters['religion']) && $filters['religion'] !== 'Any') {
            $query->where('religion', $filters['religion']);
        }

        if (!empty($filters['marital_status'])) {
            $query->where('marital_status', $filters['marital_status']);
        }

        if (!empty($filters['monthly_income'])) {
            $query->where('monthly_income', $filters['monthly_income']);
        }

        if (!empty($filters['near_me'])) {
            if ($viewer->latitude && $viewer->longitude) {
                $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->whereRaw(
                        '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?',
                        [$viewer->latitude, $viewer->longitude, $viewer->latitude, 50]
                    );
            } elseif ($viewer->city) {
                $query->where('city', $viewer->city);
            }
        }

        if (!empty($filters['new_profiles'])) {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        if (!empty($filters['verified'])) {
            $query->where('phone_verified', true);
        }

        return $query;
    }

    public function scoreProfile(User $viewer, User $candidate): int
    {
        $score = 0;

        $viewerInterests = collect($viewer->interests ?? [])->map(fn ($i) => strtolower(trim($i)))->filter();
        $candidateInterests = collect($candidate->interests ?? [])->map(fn ($i) => strtolower(trim($i)))->filter();

        if ($viewerInterests->isNotEmpty() && $candidateInterests->isNotEmpty()) {
            $common = $viewerInterests->intersect($candidateInterests)->count();
            $score += (int) round(($common / max($viewerInterests->count(), 1)) * 50);
            $score += min($common * 10, 30);
        }

        if ($viewer->city && $candidate->city && strcasecmp($viewer->city, $candidate->city) === 0) {
            $score += 10;
        }

        if ($viewer->religion && $candidate->religion && strcasecmp($viewer->religion, $candidate->religion) === 0) {
            $score += 10;
        }

        if ($viewer->age && $candidate->age) {
            $ageDiff = abs($viewer->age - $candidate->age);
            if ($ageDiff <= 3) {
                $score += 10;
            } elseif ($ageDiff <= 5) {
                $score += 5;
            }
        }

        if ($candidate->phone_verified) {
            $score += 5;
        }

        if ($candidate->created_at && $candidate->created_at->gte(now()->subDays(7))) {
            $score += 5;
        }

        return min($score, 100);
    }

    public function rankProfiles(User $viewer, Collection $candidates): Collection
    {
        return $candidates->map(function (User $candidate) use ($viewer) {
            $candidate->match_score = $this->scoreProfile($viewer, $candidate);

            return $candidate;
        })->sortByDesc('match_score')->values();
    }
}
