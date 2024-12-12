<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengirim;
use Tests\TestCase;

class SearchPengirimTest extends TestCase
{
    protected $user;

    public function testSearchPengirimSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid

        // Pastikan user ditemukan sebelum melanjutkan
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");

        // Login sebagai user
        $this->actingAs($this->user);

        // Tentukan nama yang ingin dicari
        $searchQuery = 'Danuja Gunawan';

        // Mengambil data melalui route dengan query parameter untuk pencarian
        $response = $this->get(route('datapengirim.index', ['search' => $searchQuery]));

        // Verifikasi bahwa status response adalah 200 (OK)
        $response->assertStatus(200);

        // Pastikan hasil pencarian mengandung nama yang dicari
        $response->assertSee($searchQuery);
    }
}