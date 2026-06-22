<?php

namespace App\Http\Controllers\MarriageBureau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $bureauId = Auth::guard('marriage_bureau')->id();
        $users = User::where('marriage_bureau_id', $bureauId)->latest()->paginate(15);
        return view('marriage_bureau.users.index', compact('users'));
    }

    public function create()
    {
        return view('marriage_bureau.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['marriage_bureau_id'] = Auth::guard('marriage_bureau')->id();
        
        User::create($validated);

        return redirect()->route('marriage-bureau.users.index')->with(['message' => 'User created successfully.', 'alert-type' => 'success']);
    }

    public function edit(User $user)
    {
        return view('marriage_bureau.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        if($request->filled('password')){
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('marriage-bureau.users.index')->with(['message' => 'User updated successfully.', 'alert-type' => 'success']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('marriage-bureau.users.index')->with(['message' => 'User deleted successfully.', 'alert-type' => 'success']);
    }
}
