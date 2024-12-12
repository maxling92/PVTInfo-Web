<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_web_regis_success()
{

    $response = $this->post('/register', [
        'name' => 'Aldo',
        'email' => 'Aldo@example.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'nama_perusahaan' => 'Indofood',
    ]);

    // Pastikan diarahkan ke halaman verifikasi
    $response->assertRedirect(route('verify.show'));

    // Pastikan data sesi tersedia
    $this->assertTrue(session()->has('user_data'));
    $userData = session('user_data');

    // Step 3: Kirim permintaan verifikasi dengan kode yang benar
    $response = $this->post('/verify', [
        'code' => $userData['verification_code'], // Ambil kode dari sesi
    ]);

    // Pastikan diarahkan ke halaman login
    $response->assertRedirect(route('login'));

    // Step 4: Pastikan data pengguna tersimpan di database
    $this->assertDatabaseHas('users', [
        'email' => 'Aldo@example.com',
    ]);
}

/** @test */
public function test_web_regis_failed_wrong_verification_code()
{
    // Step 1: Kirim permintaan registrasi
    $response = $this->post('/register', [
        'name' => 'Aldo',
        'email' => 'Aldo@example.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'nama_perusahaan' => 'Indofood',
    ]);

    // Pastikan diarahkan ke halaman verifikasi
    $response->assertRedirect(route('verify.show'));

    // Step 2: Simulasi kode verifikasi yang salah
    $response = $this->post('/verify', [
        'code' => '12345', // Kode yang salah
    ]);

    // Pastikan kembali ke halaman verifikasi dengan pesan kesalahan
    $response->assertSessionHasErrors(['code' => 'Kode verifikasi salah.']);

    // Pastikan data tidak ada di database
    $this->assertDatabaseMissing('users', [
        'email' => 'Aldo@example.com',
    ]);
}


}
