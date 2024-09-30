<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index', [
            'title' => 'Register'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            'nama_perusahaan' => 'required|string|max:255',
        ]);

        if (!Perusahaan::where('nama_perusahaan', $request->nama_perusahaan)->exists()) {
            return redirect()->back()->withErrors(['nama_perusahaan' => 'Perusahaan ini belum terdaftar.'])->withInput();
        }


        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nama_perusahaan' => $request->nama_perusahaan,
            'role' => null,  
        ]);

        $user->save();

        return redirect()->route('login')->with('sukses', 'Berhasil daftar akun.');

    }
}
