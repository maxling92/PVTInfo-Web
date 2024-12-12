<?php

use Tests\TestCase;
use App\Models\Datapengirim;
use App\Models\User;

class EditPengirimTest extends TestCase
{
    protected $datapengirim;
    protected $user;

    public function setUp(): void
{
    parent::setUp();

    // Mengambil data admin (Mario Purba atau Budi Prabowo) yang ada di database
    $this->user = User::where('name', 'Mario Purba')->first(); // Bisa diganti dengan 'Budi Prabowo'

    // Pastikan user ditemukan
    if (!$this->user) {
        $this->fail("User Mario Purba atau Budi Prabowo tidak ditemukan di database.");
    }

    // Ambil data pengirim Danuja Gunawan yang sudah ada
    $this->datapengirim = Datapengirim::where('nama_observant', 'Danuja Gunawan')->first();

    // Pastikan data pengirim ditemukan
    if (!$this->datapengirim) {
        $this->fail("Data pengirim Danuja Gunawan tidak ditemukan di database.");
    }
}

public function testEditPengirimSuccess()
{
    // Melakukan autentikasi dengan admin (Mario Purba atau Budi Prabowo)
    $this->actingAs($this->user); 

    // Lakukan pengeditan data pengirim
    $response = $this->put(route('datapengirim.update', $this->datapengirim->id), [
        'nama_observant' => 'Danuja Farid Gunawan ',
        'tgllahir' => '1982-08-21',
    ]);

    // Verifikasi bahwa data pengirim telah berubah
    $this->assertDatabaseHas('datapengirims', [
        'nama_observant' => 'Danuja Farid Gunawan ',
        'tgllahir' => '1982-08-21',
    ]);
}

}
