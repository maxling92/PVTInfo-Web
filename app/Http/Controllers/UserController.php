<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|string|in:admin,normal_user',
            'nama_perusahaan' => 'required|string|max:255',
        ]);

        $role = $request->input('role', 'normal_user');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'nama_perusahaan' => $request->nama_perusahaan,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show()
    {
        $title = 'Profile';
        return view('Profile', compact('title'));
    }

    public function edit(User $user)
    {
        return view('EditUser', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.show')->with('sukses', 'User sudah diupdate.');
    }

    public function showRoleManagement()
{
    // Fetch all users except those with 'owner' role, including users with NULL roles
    $users = User::with('perusahaan')
                 ->where(function ($query) {
                     $query->where('role', '!=', 'owner')
                           ->orWhereNull('role');  // Include users with null roles
                 })
                 ->get();

    return view('Management', compact('users'));
}

public function assignRole(Request $request, $userId)
{
    // Validate the request and ensure the role is passed
    $validated = $request->validate([
        'role' => 'required|in:normal_user,admin',  // Role must be one of these values
    ]);

    // Fetch the user to be updated
    $user = User::findOrFail($userId);

    // Debugging: Log the role change request
    Log::info('Attempting to change role for user: ' . $user->email . ' to role: ' . $validated['role']);

    // Prevent changing the role of an owner
    if ($user->role === 'owner') {
        return back()->withErrors(['error' => 'You cannot change the role of an owner.']);
    }

    // Assign the new role and save
    $user->role = $validated['role'];
    $user->save();

    // Debugging: Confirm role change in logs
    Log::info('Role successfully changed for user: ' . $user->email . ' to role: ' . $user->role);

    return redirect()->back()->with('success', 'Role assigned successfully.');
}



    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('profile.show')->with('sukses', 'User sudah dihapus.');
    }
}
