<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileCardResource;
use App\Models\ProfileInterest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortlistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $viewer = $request->user();
            $tab = $request->get('tab', 'i_liked');

            if ($tab === 'liked_me') {
                $userIds = ProfileInterest::query()
                    ->where('to_user_id', $viewer->id)
                    ->where('action', 'interest')
                    ->pluck('from_user_id');

                $users = User::query()
                    ->whereIn('id', $userIds)
                    ->where('status', 'active')
                    ->latest()
                    ->get();
            } else {
                $userIds = ProfileInterest::query()
                    ->where('from_user_id', $viewer->id)
                    ->where('action', 'interest')
                    ->pluck('to_user_id');

                $users = User::query()
                    ->whereIn('id', $userIds)
                    ->where('status', 'active')
                    ->latest()
                    ->get();
            }

            return response()->json([
                'success' => true,
                'tab' => $tab,
                'total' => $users->count(),
                'profiles' => ProfileCardResource::collection($users),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch shortlists.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function sendInterest(Request $request, User $user): JsonResponse
    {
        try {
            $viewer = $request->user();

            if ($user->id === $viewer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot send interest to yourself.',
                ], 422);
            }

            $existing = ProfileInterest::query()
                ->where('from_user_id', $viewer->id)
                ->where('to_user_id', $user->id)
                ->first();

            if ($existing && $existing->action === 'interest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Already shortlisted.',
                    'shortlisted' => true,
                ], 200);
            }

            ProfileInterest::updateOrCreate(
                [
                    'from_user_id' => $viewer->id,
                    'to_user_id' => $user->id,
                ],
                ['action' => 'interest']
            );

            $mutual = ProfileInterest::query()
                ->where('from_user_id', $user->id)
                ->where('to_user_id', $viewer->id)
                ->where('action', 'interest')
                ->exists();

            return response()->json([
                'success' => true,
                'message' => 'Interest sent successfully. Profile shortlisted.',
                'shortlisted' => true,
                'mutual_match' => $mutual,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send interest.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function pass(Request $request, User $user): JsonResponse
    {
        try {
            $viewer = $request->user();

            if ($user->id === $viewer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action.',
                ], 422);
            }

            ProfileInterest::updateOrCreate(
                [
                    'from_user_id' => $viewer->id,
                    'to_user_id' => $user->id,
                ],
                ['action' => 'pass']
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile skipped.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to pass profile.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
