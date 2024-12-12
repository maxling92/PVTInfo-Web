<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SortPengirimTest extends TestCase
{
    protected $user;

    /**
     * Test sorting pengirim berdasarkan nama.
     *
     * @return void
     */
    public function testSortPengirimSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid
    
        // Pastikan user ditemukan sebelum melanjutkan
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");
    
        // Login sebagai user
        $this->actingAs($this->user);
    
        // Mengambil data melalui route dengan query parameter untuk sorting
        $response = $this->get(route('datapengirim.index', ['sort_by' => 'nama_observant', 'sort_order' => 'asc']));
    
        // Verifikasi bahwa status response adalah 200 (OK)
        $response->assertStatus(200);
    
        // Verifikasi urutan data
        $response->assertSeeInOrder(['Danuja Gunawan', 'Mario Purba', 'Muhammad Fathoni']);
    }
    
}


