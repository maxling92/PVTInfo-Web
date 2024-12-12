<?php

namespace App\Http\Controllers;

use App\Models\Datahasil;
use App\Models\Datapengirim;
use Illuminate\Http\Request;
use App\Models\Datapengukuran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function receiveData(Request $request)
    
{
    DB::beginTransaction();

    try {
        // Dapatkan data JSON dari request
        $data = $request->json()->all();
        Log::info('Received data:', $data);

        $pengirim = Datapengirim::where('nama_observant', $data['namaobservant'])->first();

        // Jika tidak ditemukan, kembalikan error
        if (!$pengirim) {
            return response()->json([
                'message' => 'Error: nama_observant tidak ditemukan di database.',
            ], 400);
        }

        // Create the Datapengukuran entry
        $pengukuran = Datapengukuran::create([
            'namadata' => $data['namadata'],
            'tanggal' => $data['tanggal'],
            'lokasi' => $data['lokasi'],
            'jenistest' => $data['jenistest'],
            'ratarata' => $data['rata_rata'],
            'hasil' => $this->getHasilText($data['rata_rata']),
            'gagal' => $data['gagal'],
            'nama_observant' => $data['namaobservant']
        ]);

        // Loop over each entry in the 'jeda' array and create a Datahasil entry
        foreach ($data['jeda'] as $index => $value) {
            Datahasil::create([
                'nomor' => $index + 1,
                'waktu_milidetik' => $value,
                'namadata' => $data['namadata'] // Foreign Key reference to Datapengukuran
            ]);
        }

        DB::commit();
        return response()->json(['message' => 'Data received and saved successfully'], 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['message' => 'Error saving data: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
}

// Fungsi tambahan untuk mendapatkan hasil teks dari ratarata
private function getHasilText($rataRata)
{
    if ($rataRata < 240) return 'Normal';
    if ($rataRata < 480) return 'Ringan';
    if ($rataRata < 540) return 'Sedang';
    return 'Berat';
}

}