<?php 

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengukuran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchPengukuranTest extends TestCase
{
    protected $user;

    /**
     * Test search results by keyword 'Wed' on multiple columns (namadata, lokasi, tanggal).
     *
     * @return void
     */
    public function testSearchResultsSuccess()
    {
        // Login sebagai admin
        $this->user = User::where('name', 'Mario Purba')->first();  // Pastikan user admin yang valid
        $this->assertNotNull($this->user, "User admin 'Mario Purba' tidak ditemukan!");
        $this->actingAs($this->user);

        // Tentukan kata kunci pencarian
        $searchKeyword = 'Utara';

        // Request pencarian
        $response = $this->get(route('datapengukuran.index', [
            'nama_observant' => 'Mario Purba', // Nama observant yang valid
            'search' => $searchKeyword, // Kata kunci pencarian
        ]));

        // Verifikasi apakah statusnya 200 (berhasil)
        $response->assertStatus(200);

        // Ambil data yang sesuai dengan kata kunci 'Wed' pada kolom 'namadata', 'lokasi', dan 'tanggal'
        $filteredData = Datapengukuran::where('nama_observant', 'Mario Purba')
            ->where(function ($query) use ($searchKeyword) {
                $query->where('namadata', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('lokasi', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('tanggal', 'like', '%' . $searchKeyword . '%');
            })
            ->get();

        // Pastikan data yang diterima hanya yang mengandung 'Wed' pada kolom-kolom yang relevan
        $filteredDataArray = $filteredData->pluck('namadata')->merge($filteredData->pluck('lokasi'))->merge($filteredData->pluck('tanggal'))->toArray();
        $responseDataArray = $response->viewData('datapengukurans')->pluck('namadata')->merge($response->viewData('datapengukurans')->pluck('lokasi'))->merge($response->viewData('datapengukurans')->pluck('tanggal'))->toArray();

        // Verifikasi apakah hasil data yang ditampilkan sesuai dengan data yang terfilter
        $this->assertEquals($filteredDataArray, $responseDataArray, "Data yang ditampilkan tidak sesuai dengan kata kunci pencarian.");
    }
}
