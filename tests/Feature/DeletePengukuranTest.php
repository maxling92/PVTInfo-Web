<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengukuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePengukuranTest extends TestCase
{
    protected $user;
    protected $datapengukuran;

    /**
     * Test deleting a datapengukuran entry.
     *
     * @return void
     */
    public function testDeleteResultsSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");
        $this->actingAs($this->user);

        // Ambil datapengukuran yang sudah ada
        $this->datapengukuran = Datapengukuran::where('namadata', 'Test123')->first(); // Pastikan ada data yang memiliki nama "Test123"
        $this->assertNotNull($this->datapengukuran, "Datapengukuran 'Test123' tidak ditemukan!");

        // Melakukan delete pada datapengukuran
        $response = $this->delete(route('datapengukuran.destroy', $this->datapengukuran->id));

        // Verifikasi bahwa statusnya 302 (redirect after delete)
        $response->assertStatus(302);

        // Pastikan datapengukuran telah dihapus
        $this->assertNull(Datapengukuran::find($this->datapengukuran->id), "Datapengukuran masih ada di database.");
    }
}
