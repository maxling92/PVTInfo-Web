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

        // Simpan user baru ke database
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nama_perusahaan' => $request->nama_perusahaan,
            'role' => null,  
        ]);
        $user->save();

        // Generate kode verifikasi 5 angka
        $verificationCode = random_int(10000, 99999);

        // Simpan kode ke session untuk verifikasi nanti
        Session::put('verification_code', $verificationCode);

        // Kirimkan email dengan kode verifikasi
        Mail::to($request->email)->send(new VerificationMail($verificationCode));

        // Redirect ke halaman verifikasi kode
        return redirect()->route('verify.show');
    }

    public function verify(Request $request)
    {
        // Ambil kode verifikasi dari session
        $verificationCode = Session::get('verification_code');

        // Cek apakah kode yang diinput user sesuai
        if ($request->code == $verificationCode) {
            // Redirect ke halaman login setelah verifikasi sukses
            return redirect()->route('login')->with('sukses', 'Verifikasi berhasil. Silakan login.');
        } else {
            // Jika kode salah, kembalikan ke halaman verifikasi dengan pesan error
            return back()->withErrors(['code' => 'Kode verifikasi salah.']);
        }
    }
}
