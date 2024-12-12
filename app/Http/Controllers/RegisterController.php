<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

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
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]{8,}$/',
            'nama_perusahaan' => 'required|string|max:255',
        ]);

        // Cek apakah perusahaan terdaftar
        if (!Perusahaan::where('nama_perusahaan', $request->nama_perusahaan)->exists()) {
            return redirect()->back()->withErrors(['nama_perusahaan' => 'Perusahaan ini belum terdaftar.'])->withInput();
        }

        // Generate kode verifikasi 5 angka
        $verificationCode = random_int(10000, 99999);
        $request->session()->put('user_data', [
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'nama_perusahaan' => $request->nama_perusahaan,
        'role' => null,
        'verification_code'=>$verificationCode
    ]);

        Mail::to($request->email)->send(new VerificationMail($verificationCode));
        
        // Redirect ke halaman verifikasi kode
        return redirect()->route('verify.show');
    }

    public function showVerifyForm()
    {
        return view('Verify'); // Pastikan Anda memiliki view bernama Verify.blade.php
    }
    

    public function verify(Request $request)
{
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $verificationCode = $userData['verification_code'] ?? null;
      
        // Ambil data user dari session
        $userData = Session::get('user_data');
        $verificationCode = $userData['verification_code'] ?? null;

        // Cek apakah kode yang diinput user sesuai
        if ($request->code == $verificationCode) {
            // Simpan user baru ke database
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'nama_perusahaan' => $userData['nama_perusahaan'],
                'role' => $userData['role'],
            ]);

        Session::forget('user_data');

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('sukses', 'Verifikasi berhasil. Silakan login.');
    } else {
        // Jika kode salah, kembalikan ke halaman verifikasi dengan pesan error
        return back()->withErrors(['code' => 'Kode verifikasi salah.']);
    }
}

}

