<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $notifications = UserNotification::query()
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(20);

            $unreadCount = UserNotification::query()
                ->where('user_id', $user->id)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => 200,
                'unread_count' => $unreadCount,
                'notifications' => collect($notifications->items())->map(fn (UserNotification $n) => [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'is_read' => (bool) $n->is_read,
                    'read_at' => $n->read_at?->toDateTimeString(),
                    'created_at' => $n->created_at?->toDateTimeString(),
                ])->values(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function markAsRead(Request $request, int $id): JsonResponse
    {
        try {
            $notification = UserNotification::query()
                ->where('user_id', $request->user()->id)
                ->where('id', $id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => 200,
                'message' => 'Notification marked as read.',
                'notification' => [
                    'id' => $notification->id,
                    'is_read' => true,
                    'read_at' => $notification->read_at?->toDateTimeString(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $updated = UserNotification::query()
                ->where('user_id', $request->user()->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            return response()->json([
                'success' => 200,
                'message' => 'All notifications marked as read.',
                'updated_count' => $updated,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notifications as read',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function clearAll(Request $request): JsonResponse
    {
        try {
            $deleted = UserNotification::query()
                ->where('user_id', $request->user()->id)
                ->delete();

            return response()->json([
                'success' => 200,
                'message' => 'All notifications cleared.',
                'deleted_count' => $deleted,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear notifications',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
