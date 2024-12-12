<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Datapengirim;
use Tests\TestCase;

class DeletePengirimTest extends TestCase
{
    protected $user;
    protected $datapengirim;

    /**
     * Setup pengujian.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Ambil data pengguna admin
        $this->user = User::where('name', 'Mario Purba')->first(); // Ganti jika ingin menggunakan 'Budi Prabowo'

        // Pastikan user ditemukan
        if (!$this->user) {
            $this->fail("User Mario Purba atau Budi Prabowo tidak ditemukan di database.");
        }

        // Ambil data pengirim Danuja Gunawan yang akan dihapus
        $this->datapengirim = Datapengirim::where('nama_observant', 'Danuja Farid Gunawan')->first();

        // Pastikan data pengirim ditemukan
        if (!$this->datapengirim) {
            $this->fail("Data pengirim Danuja Farid Gunawan tidak ditemukan di tabel datapengirims.");
        }
    }

    /**
     * Pengujian untuk menghapus data pengirim
     *
     * @return void
     */
    public function testDeletePengirimSuccess()
    {
        // Menyimpan salinan data yang akan dihapus
        $originalData = $this->datapengirim->replicate();

        // Melakukan autentikasi dengan admin
        $this->actingAs($this->user);

        // Lakukan penghapusan data pengirim
        $response = $this->delete(route('datapengirim.destroy', $this->datapengirim->id));

        // Verifikasi bahwa data pengirim telah dihapus dari database
        $this->assertDatabaseMissing('datapengirims', [
            'id' => $this->datapengirim->id,
        ]);

        // Pastikan respon redirect ke halaman yang benar
        $response->assertRedirect(route('datapengirim.index'));

        // Mengembalikan data yang telah dihapus
        $this->datapengirim->fill($originalData->getAttributes())->save();
    }
}
