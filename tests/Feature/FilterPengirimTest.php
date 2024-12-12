<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengirim;
use Tests\TestCase;

class FilterPengirimTest extends TestCase
{
    protected $user;

    public function testFilterByTglLahirSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid

        // Pastikan user ditemukan sebelum melanjutkan
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");

        // Login sebagai user
        $this->actingAs($this->user);

        // Tentukan tanggal range untuk filter
        $tglFrom = '1970-01-01';
        $tglTo = '1989-12-31';

        // Mengambil data melalui route dengan query parameter untuk filter tanggal lahir
        $response = $this->get(route('datapengirim.index', ['tgl_from' => $tglFrom, 'tgl_to' => $tglTo]));

        // Verifikasi bahwa status response adalah 200 (OK)
        $response->assertStatus(200);

        // Verifikasi bahwa data yang ditampilkan memiliki tgllahir dalam rentang yang diberikan
        $response->assertSeeInOrder(['Muhammad Fathoni', 'Danuja Gunawan']);  // Harus sesuai dengan data yang ada dalam rentang tanggal
    }

}