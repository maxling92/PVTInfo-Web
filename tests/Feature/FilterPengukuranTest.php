<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengukuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterPengukuranTest extends TestCase
{
    protected $user;

    /**
     * Test filter results by 'jenistest' (VISUAL).
     *
     * @return void
     */
    public function testFilterResultsSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");
        $this->actingAs($this->user);

        // Tentukan jenis tes yang ingin difilter (VISUAL)
        $jenisTesFilter = 1 ;

        // Request filter dengan jenis tes 'VISUAL'
        $response = $this->get(route('datapengukuran.index', [
            'nama_observant' => 'Mario Purba',  // Nama observant yang valid
            'jenistest' => $jenisTesFilter,  // Filter berdasarkan jenis tes
        ]));

        // Verifikasi apakah statusnya 200 (berhasil)
        $response->assertStatus(200);

        // Ambil data yang sesuai dengan jenis tes 'VISUAL'
        $filteredData = Datapengukuran::where('nama_observant', 'Mario Purba')
            ->where('jenistest', $jenisTesFilter) // Filter berdasarkan jenis tes
            ->get();

        // Pastikan data yang diterima hanya yang memiliki jenis tes 'VISUAL'
        $filteredDataArray = $filteredData->pluck('jenistest')->toArray();
        $responseDataArray = $response->viewData('datapengukurans')->pluck('jenistest')->toArray();

        // Verifikasi apakah hasil data yang ditampilkan sesuai dengan filter 'VISUAL'
        $this->assertEquals($filteredDataArray, $responseDataArray, "Data yang ditampilkan tidak sesuai dengan filter jenis tes 'VISUAL'.");
    }
}
