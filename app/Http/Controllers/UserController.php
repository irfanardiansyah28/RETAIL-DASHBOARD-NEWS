<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('users.index', compact('users', 'search'));
    }

    public function liveSearch(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            })
            ->latest()
            ->limit(20)
            ->get();

        return response()->json($users);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        logActivity(
            'Create User',
            'User',
            'User created: '.$user->name.' with role '.$user->role,
            null,
            $user->only(['id', 'name', 'email', 'role'])
        );

        return redirect('/users')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);

        $oldUser = $user->only(['id', 'name', 'email', 'role']);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        $newUser = $user->fresh()->only(['id', 'name', 'email', 'role']);

        logActivity(
            'Update User',
            'User',
            'User updated: '.$user->name,
            $oldUser,
            $newUser
        );

        return redirect('/users')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $oldUser = $user->only(['id', 'name', 'email', 'role']);

        $userName = $user->name;

        $user->delete();

        logActivity(
            'Delete User',
            'User',
            'User deleted: '.$userName,
            $oldUser,
            null
        );

        createRiskFlag(
            'User Deleted',
            'High',
            'User',
            $oldUser['id'],
            'User account deleted',
            'Deleted user: '.$oldUser['name'].' with role '.$oldUser['role']
        );

        return redirect('/users')
            ->with('success', 'User deleted successfully');
    }
}