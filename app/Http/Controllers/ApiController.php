<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datapengirim;
use App\Models\Datapengukuran;
use App\Models\Datahasil;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function receiveData(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->json()->all();

            foreach ($data as $hasil) {
                // Check if the user exists in Datapengirim
                $pengirim = Datapengirim::firstOrCreate(
                    ['nama_observant' => $hasil['namaObservant']],
                    [
                        'jabatan' => $hasil['jabatan'],
                        'tgllahir' => $hasil['tglLahir'],
                        'nama_perusahaan' => $hasil['namaPerusahaan']
                    ]
                );

                // Create Datapengukuran
                $pengukuran = Datapengukuran::create([
                    'namadata' => $hasil['ID'], // Assuming 'ID' is unique for each measurement
                    'tanggal' => $hasil['tanggal'],
                    'lokasi' => $hasil['lokasi'],
                    'jenistest' => $hasil['jenistest'],
                    'ratarata' => $hasil['rataRata'],
                    'hasil' => $this->getHasilText($hasil['rataRata']),
                    'gagal' => $hasil['gagal'],
                    'nama_observant' => $hasil['namaObservant']
                ]);

                // Create Datahasil entries
                foreach ($hasil['hasilData'] as $index => $value) {
                    Datahasil::create([
                        'nomor' => $index + 1,
                        'waktu_milidetik' => $value,
                        'namadata' => $hasil['ID']
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data received and saved successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    private function getHasilText($rataRata)
    {
        if ($rataRata < 240) return 'Normal';
        if ($rataRata < 480) return 'Ringan';
        if ($rataRata < 540) return 'Sedang';
        return 'Berat';
    }
}