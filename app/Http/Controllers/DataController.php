<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Datapengukuran;
use Illuminate\Support\Facades\Auth;
use App\Models\Datahasil;
use App\Models\Datapengirim;

class DataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $inputdata = Datapengirim::where('nama_perusahaan', $user->nama_perusahaan)->get();

        return view('Datapengirim.Inputdata', [
            "title" => "Input dan Analisis Data",
            "inputdata" => $inputdata
        ]);
    }

    public function showpengukuran($id)
    {
        $user = Auth::user();
        $pengirim = Datapengirim::where('datapengirim_id', $id)
                ->where('nama_perusahaan', $user->nama_perusahaan)
                ->firstOrFail();
        $data_pengukurans = Datapengukuran::where('nama_observant', $pengirim->nama_observant)
                          ->where('nama_perusahaan', $user->nama_perusahaan)
                          ->get();

        return view('singledata', [
            "title" => "Data Pengukuran",
            "pengirim" => $pengirim,
            "data_pengukurans" => $data_pengukurans
        ]);
    }

    public function showHasil($namadata)
    {
        $datapengukuran = Datapengukuran::where('namadata', $namadata)->firstOrFail();
        $datahasils = $datapengukuran->datahasils;

        return view('TampilanHasil', [
            "title" => "Hasil Pengukuran",
            "datapengukuran" => $datapengukuran,
            "datahasils" => $datahasils
        ]);
    }
    
}
