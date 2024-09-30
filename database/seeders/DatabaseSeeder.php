<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Datapengirim;
use App\Models\Datapengukuran;
use App\Models\Datahasil;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Datapengirim::factory(10)->create()->each(function ($datapengirim) {
            $datapengukurans = collect();

            for ($i = 0; $i < rand(3, 5); $i++) {
                $datapengukuran = Datapengukuran::factory()
                    ->withNamaDataAndObserver($datapengirim->nama_observant)
                    ->make();

                $datahasils = collect();
                $totalWaktuMilidetik = 0;

                for ($j = 1; $j <= 20; $j++) {
                    $waktu_milidetik = rand(230, 1000);
                    $totalWaktuMilidetik += $waktu_milidetik;

                    $datahasils->push(new Datahasil([
                        'nomor' => $j,
                        'waktu_milidetik' => $waktu_milidetik,
                    ]));
                }

                $ratarata = $totalWaktuMilidetik / 20;

                $hasil = match (true) {
                    $ratarata < 300 => 'kondisi supir aman',
                    $ratarata >= 301 && $ratarata <= 450 => 'supir lelah ringan',
                    $ratarata >= 451 && $ratarata <= 600 => 'supir lelah medium',
                    $ratarata > 600 => 'supir lelah berat',
                };

                $datapengukuran->ratarata = $ratarata;
                $datapengukuran->hasil = $hasil;

                $datapengukurans->push($datapengukuran);

                $datapengukuran->save();
                $datapengukuran->datahasils()->saveMany($datahasils);
            }

            $datapengirim->datapengukurans()->saveMany($datapengukurans);
        });
    }
}
