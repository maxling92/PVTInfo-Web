<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Jika user tidak memiliki role
    if (is_null($user->role)) {
        return view('home', [
            'title' => 'Home',
            'noRoleMessage' => 'Anda belum memiliki role. Silakan hubungi admin perusahaan Anda untuk mendapatkan akses.'
        ]);
    }

    // Jika user memiliki role
    return view('home', ['title' => 'Home']);
}
}
