<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_web_login_success()
{
    // Ambil pengguna yang sudah ada dari database
    $user = \App\Models\User::where('email', 'Alifwibu@gmail.com')->first();

    // Kirim permintaan login dengan kredensial yang benar
    $response = $this->post('/login', [
        'email' => 'Alifwibu@gmail.com',
        'password' => 'maxco3001',
    ]);

    // Pastikan diarahkan ke halaman utama
    $response->assertRedirect('/');

    // Pastikan pengguna terautentikasi
    $this->assertAuthenticatedAs($user);
}

public function test_web_login_failed_wrong_credentials()
{
    // Ambil pengguna yang sudah ada dari database
    $user = \App\Models\User::where('email', 'Alifwibu@gmail.com')->first();

    // Kirim permintaan login dengan kredensial yang salah
    $response = $this->post('/login', [
        'email' => 'Alifwibu@gmail.com',
        'password' => 'wrongpassword',
    ]);

    // Pastikan kembali ke halaman login dengan pesan kesalahan
    $response->assertSessionHas('loginError', 'Ada kesalahan saat login');

    // Pastikan pengguna tidak terautentikasi
    $this->assertGuest();
}
}
