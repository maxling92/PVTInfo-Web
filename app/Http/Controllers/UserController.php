<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
        $currentUser = Auth::user();
    
        if ($currentUser->role === 'owner') {
            // Owner melihat semua user
            $users = User::all();
        } elseif ($currentUser->role === 'admin') {
            // Admin hanya melihat user di perusahaan yang sama
            $users = User::where('nama_perusahaan', $currentUser->nama_perusahaan)->get();
        } else {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    
        return view('Management', compact('users'));
    }

public function assignRole(Request $request, $userId)
{
    // Validasi request untuk memastikan role yang di-assign adalah normal_user atau admin
    $validated = $request->validate([
        'role' => 'required|in:normal_user,admin',  // Role harus salah satu dari normal_user atau admin
    ]);

    // Mengambil user yang akan diubah
    $user = User::findOrFail($userId);
    $currentUser = Auth::user();  // User yang sedang login

    // Log permintaan perubahan role (debugging)
    Log::info('Attempting to change role for user: ' . $user->email . ' by: ' . $currentUser->email . ' to role: ' . $validated['role']);

    // Cegah perubahan role owner
    if ($user->role === 'owner') {
        return back()->withErrors(['error' => 'You cannot change the role of an owner.']);
    }

    // Jika user yang login adalah owner, dia bisa mengubah role siapa saja
    if ($currentUser->role === 'owner') {
        $user->role = $validated['role'];
        $user->save();

        // Log perubahan role berhasil
        Log::info('Role successfully changed for user: ' . $user->email . ' to role: ' . $user->role . ' by owner: ' . $currentUser->email);
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    // Jika user yang login adalah admin, dia hanya bisa mengubah role user di perusahaan yang sama
    if ($currentUser->role === 'admin') {
        if ($currentUser->company_id === $user->company_id) {
            $user->role = $validated['role'];
            $user->save();

            // Log perubahan role berhasil oleh admin
            Log::info('Role successfully changed for user: ' . $user->email . ' to role: ' . $user->role . ' by admin: ' . $currentUser->email);
            return redirect()->back()->with('success', 'Role assigned successfully.');
        } else {
            // Admin tidak memiliki hak untuk mengubah user dari perusahaan lain
            return back()->withErrors(['error' => 'You are only allowed to change roles for users within your own company.']);
        }
    }

    // Jika user yang login bukan owner atau admin
    return back()->withErrors(['error' => 'You do not have permission to assign roles.']);
}




    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('profile.show')->with('sukses', 'User sudah dihapus.');
    }
}
