<?php

namespace App\Services;

use App\Models\Datapengirim;
use App\Models\Datapengukuran;
use App\Models\Datahasil;
use Illuminate\Support\Facades\DB;

class DataService
{
    public function processDataFile($file)
    {
        // Read the file content
        $data = file_get_contents($file->getPathname());

        // Convert JSON to array (assuming the file is JSON)
        $dataArray = json_decode($data, true);

        // Process each entry in the data array
        foreach ($dataArray as $entry) {
            $this->processEntry($entry);
        }
    }

    protected function processEntry($entry)
    {
        DB::transaction(function () use ($entry) {
            // Save Datapengguna
            $pengguna = Datapengirim::updateOrCreate(
                ['id' => $entry['pengguna']['id']],
                $entry['pengguna']
            );

            // Save Datapengukuran
            $ukuran = Datapengukuran::updateOrCreate(
                ['id' => $entry['ukuran']['id']],
                $entry['ukuran']
            );

            
        });
    }
}
