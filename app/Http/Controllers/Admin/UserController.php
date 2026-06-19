<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === 'yes');
        }

        if ($request->filled('phone_verified')) {
            $query->where('phone_verified', $request->phone_verified === 'yes');
        }

        if ($request->filled('profile')) {
            if ($request->profile === 'complete') {
                $query->where('profile_completed', true);
            } elseif ($request->profile === 'incomplete') {
                $query->where('profile_completed', false);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function verifyEmail(Request $request, User $user)
    {
        if ($user->is_verified) {
            return response()->json(['success' => false, 'message' => 'Email is already verified.']);
        }

        $user->update(['is_verified' => true, 'email_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully for ' . $user->name,
        ]);
    }

    public function verifyPhone(Request $request, User $user)
    {
        if ($user->phone_verified) {
            return response()->json(['success' => false, 'message' => 'Phone is already verified.']);
        }

        $user->update(['phone_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully for ' . $user->name,
        ]);
    }

    public function toggleStatus(Request $request, User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated.',
            'status' => $user->status,
        ]);
    }
}
