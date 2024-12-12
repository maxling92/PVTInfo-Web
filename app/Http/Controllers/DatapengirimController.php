<?php

namespace App\Http\Controllers;

use App\Models\Datapengirim; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DatapengirimController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'owner') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses untuk melihat data pengirim.');
        }

        $searchQuery = $request->query('search');
        $sortBy = $request->query('sort_by');
        $sortOrder = $request->query('sort_order', 'asc');
        $ageFrom = $request->query('age_from'); 
        $ageTo = $request->query('age_to');      

        $query = Datapengirim::query(); 

        if ($searchQuery) {
            $query->where('nama_observant', 'like', '%' . $searchQuery . '%');
        }

        if ($sortBy) {
            if (in_array($sortBy, ['nama_observant', 'jabatan', 'tgllahir'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        if ($ageFrom !== null && $ageTo !== null) {
            $query->whereRaw("FLOOR(DATEDIFF(?, tgllahir) / 365.25) BETWEEN ? AND ?", [
                Carbon::now()->format('Y-m-d'), $ageFrom, $ageTo
            ]);
        }

        $user = Auth::user(); 
        $query->where('nama_perusahaan', $user->nama_perusahaan);

        $datapengirims = $query->with('datapengukurans')->paginate(20); 

        $action = route('datapengirim.index'); 

        return view('DataPengirim.Inputdata', [ 
            'inputdata' => $datapengirims, 
            'title' => 'Input Data',
            'action' => $action,
            'searchQuery' => $searchQuery,
            'searchAction' => $action,
            'sortOptions' => [
                ['value' => 'nama_observant', 'label' => 'Nama'],
                ['value' => 'tgllahir', 'label' => 'Tanggal Lahir']
            ],
        ]);
    }

    public function groupAnalysis(Request $request)
{
    Log::info('Analisis Kelompok Aktif');
    $user = Auth::user();
    $query = Datapengirim::where('nama_perusahaan', $user->nama_perusahaan);


    if ($request->has('tgllahir') && $request->tgllahir !== '') {
        $query->where('tgllahir', $this->formattedDate($request->query('tgllahir')));
    }

    // Apply sorting based on user input
    if ($request->has('sort_by') && $request->has('sort_direction')) {
        $query->orderBy($request->query('sort_by'), $request->query('sort_direction'));
    }

    $datapengirims = $query->with('datapengukurans')->get();

    $fatigueCategories = [
        'safe' => 0,
        'mild' => 0,
        'moderate' => 0,
        'heavy' => 0,
    ];

    // Calculate the average 'ratarata' for each 'datapengirim'
    $avg_pengirims = $datapengirims->map(function ($pengirim) use (&$fatigueCategories) {
        $totalRataRata = $pengirim->datapengukurans->sum('ratarata');
        $countDatapengukurans = $pengirim->datapengukurans->count();
        $avg_pengirim = $countDatapengukurans ? $totalRataRata / $countDatapengukurans : 0;

        $avg_pengirim = round($avg_pengirim, 2);

        if ($avg_pengirim <= 300) {
            $fatigueCategories['safe']++;
        } elseif ($avg_pengirim > 300 && $avg_pengirim <= 450) {
            $fatigueCategories['mild']++;
        } elseif ($avg_pengirim > 450 && $avg_pengirim <= 600) {
            $fatigueCategories['moderate']++;
        } elseif ($avg_pengirim > 600) {
            $fatigueCategories['heavy']++;
        }
        return [
            'nama_observant' => $pengirim->nama_observant,
            'avg_pengirim' => $avg_pengirim,
            'ratarata_values' => $pengirim->datapengukurans->pluck('ratarata')->toArray(), // Collect 'ratarata' values
        ];
    });

    $mostFrequentCategory = array_search(max($fatigueCategories), $fatigueCategories);

    // Prepare data for the chart
    $xValues = $avg_pengirims->pluck('nama_observant')->toArray();
    $yValues = $avg_pengirims->pluck('ratarata_values')->toArray(); // Array of arrays for the y-axis

    return view('KelompokSupir', [
        'datapengirims' => $datapengirims,
        'avg_pengirims' => $avg_pengirims,
        'filters' => [
            'tgllahir' => $request->query('tgllahir'),
        ],
        'xValues' => $xValues,
        'yValues' => $yValues,
        'fatigueCategories' => $fatigueCategories, 
        'mostFrequentCategory' => $mostFrequentCategory, 
    ]);
}




private function formattedDate($date)
    {
        try {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
            Log::info("Formatted Date: $formattedDate");
            return $formattedDate;
        } catch (\Exception $e) {
            Log::error("Error formatting date: {$e->getMessage()}");
            return null; 
        }
    }



    public function create()
    {
        return view('datapengirim.create', ['title' => 'Tambah Data Pengirim']); 
    }

    public function store(Request $request)
    {

        Log::info('Request data:', $request->all());

        $request->validate([
            'nama_observant' => 'required|string|max:255',
            'tgllahir' => 'required|date_format:d-m-Y',
        ]);

        Log::info('Validation passed');

        $date = \DateTime::createFromFormat('d-m-Y', $request->tgllahir);
        $formattedDate = $date->format('Y-m-d');

        $datapengirim = new Datapengirim(); 
        $datapengirim->nama_observant = $request->nama_observant;
        $datapengirim->tgllahir = $formattedDate;
        $datapengirim->nama_perusahaan = Auth::user()->nama_perusahaan;
        $datapengirim->save();

        return redirect()->route('datapengirim.index')->with('sukses', 'Datapengirim baru berhasil dibuat.'); 
    }

    public function edit($id)
    {
        $datapengirim = Datapengirim::findOrFail($id); 
        return view('datapengirim.EditPengirim', compact('datapengirim')); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_observant' => 'required|string|max:255',
            'tgllahir' => 'required|date_format:Y-m-d',
        ]);

        $datapengirim = Datapengirim::findOrFail($id);

        $datapengirim->nama_observant = $request->input('nama_observant');
        $datapengirim->tgllahir = $request->input('tgllahir');
        $datapengirim->save();

        return redirect()->route('datapengirim.index')->with('sukses', 'Data berhasil diupdate.');

    }


    public function destroy($id)
    {
        $datapengirim = Datapengirim::findOrFail($id); 
        $datapengirim->delete();

        return redirect()->route('datapengirim.index')->with('sukses', 'Datapengirim terhapus.'); 
    }
}
