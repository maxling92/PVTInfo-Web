<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengukuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortPengukuranTest extends TestCase
{
    protected $user;
    protected $datapengukuran;

    /**
     * Test sorting datapengukuran by 'namadata' column.
     *
     * @return void
     */
    public function testSortResultsByNamadataSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");
        $this->actingAs($this->user);

        // Mendapatkan datapengukuran yang sudah ada (misalnya dengan nama 'Test123')
        $this->datapengukuran = Datapengukuran::first(); // Mengambil datapengukuran pertama yang ada
        $this->assertNotNull($this->datapengukuran, "Tidak ada datapengukuran yang ditemukan!");
        
        // Mendapatkan nama_observant terkait
        $namaObservant = $this->datapengukuran->datapengirim->nama_observant;  // Ini memastikan $datapengukuran terkait dengan datapengirim

        // Request dengan parameter sort by 'namadata'
        $response = $this->get(route('datapengukuran.index', [
            'nama_observant' => $namaObservant, // Menyertakan nama_observant pada route
            'sort_by' => 'namadata', // Kolom yang ingin disortir
            'sort_order' => 'asc', // Urutan ascending
        ]));

        // Verifikasi apakah statusnya 200 (berhasil)
        $response->assertStatus(200);

        // Ambil data untuk verifikasi urutan setelah disortir
        $sortedData = Datapengukuran::whereHas('datapengirim', function ($query) use ($namaObservant) {
            $query->where('nama_observant', $namaObservant);
        })->orderBy('namadata', 'asc')
          ->get();

        // Pastikan data yang diterima sesuai dengan yang diurutkan
        $sortedDataArray = $sortedData->pluck('namadata')->toArray();
        $responseDataArray = $response->viewData('datapengukurans')->pluck('namadata')->toArray();

        $this->assertEquals($sortedDataArray, $responseDataArray, "Data tidak diurutkan dengan benar berdasarkan kolom 'namadata'.");
    }
}
