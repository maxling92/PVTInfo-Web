<?php

namespace App\Http\Controllers;

use App\Models\Datapengukuran;
use App\Models\Datapengirim;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 

class DatapengukuranController extends Controller
{
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


    public function index($nama_observant, Request $request)
    {
        $sortBy = $request->query('sort_by');
        $searchQuery = $request->query('search');
        $sortOrder = $request->query('sort_order', 'asc');
        $filterTanggalFrom = $request->query('tanggal_from');
        $filterTanggalTo = $request->query('tanggal_to');
        $filterJenistest = $request->query('jenistest');

        $pengirim = Datapengirim::where('nama_observant', $nama_observant)->firstOrFail();
        
        $user = Auth::user(); 

        $query = Datapengukuran::with('datahasils')->where('nama_observant', $pengirim->nama_observant);

        if ($searchQuery) {
            $query->where('lokasi', 'like', '%' . $searchQuery . '%');
        }

        if ($sortBy) {
            if (in_array($sortBy, ['namadata', 'tanggal', 'waktu', 'lokasi'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        if ($filterTanggalFrom && $filterTanggalTo) {
            $query->whereBetween('tanggal', [$this->formattedDate($filterTanggalFrom), $this->formattedDate($filterTanggalTo)]);
        } elseif ($filterTanggalFrom) {
            $query->whereDate('tanggal', '>=', $this->formattedDate($filterTanggalFrom));
        } elseif ($filterTanggalTo) {
            $query->whereDate('tanggal', '<=', $this->formattedDate($filterTanggalTo));
        }


        if ($filterJenistest) {
            $query->where('jenistest', $filterJenistest);
        }

        $datapengukurans = $query->get();

        // Fetch distinct filter values
        $jenisTests = Datapengukuran::where('nama_observant', $pengirim->nama_observant)
            ->distinct()
            ->pluck('jenistest');
        $tanggalTests = Datapengukuran::where('nama_observant', $pengirim->nama_observant)
            ->distinct()
            ->pluck('tanggal');

        $action = route('datapengukuran.index', ['nama_observant' => $pengirim->nama_observant]);

        $sortOptions = [
            ['value' => 'nama_data', 'label' => 'Nama Data'],
            ['value' => 'tanggal', 'label' => 'Tanggal Pengukuran'],
            ['value' => 'waktu', 'label' => 'Waktu Pengukuran'],
        ];

        return view('singledata', [
            'title' => 'Data Pengukuran',
            'datapengukurans' => $datapengukurans,
            'pengirim' => $pengirim,
            'searchQuery' => $searchQuery,
            'searchAction' => $action,
            'sortOptions' => $sortOptions,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'filterTanggalFrom' => $filterTanggalFrom,
            'filterTanggalTo' => $filterTanggalTo,
            'filterJenistest' => $filterJenistest,
            'jenisTests' => $jenisTests,
            'tanggalTests' => $tanggalTests,
        ]);
    }


    public function analyze($nama_observant, Request $request)
{
    $pengirim = Datapengirim::where('nama_observant', $nama_observant)->firstOrFail();

    $query = Datapengukuran::where('nama_observant', $nama_observant);

    // Apply filters
    if ($request->has('jenistest') && $request->jenistest !== '') {
        $query->where('jenistest', $request->query('jenistest'));
    }

    if ($request->has('lokasi') && $request->lokasi !== '') {
        $query->where('lokasi', 'like', '%' . $request->query('lokasi') . '%');
    }

    if ($request->has('tanggal') && $request->tanggal !== '') {
        $query->where('tanggal', $this->formattedDate($request->query('tanggal')));
    }

    // Apply sorting if provided
    if ($request->has('sort_by') && $request->has('sort_direction')) {
        $sortBy = $request->query('sort_by');
        $sortDirection = $request->query('sort_direction');
        $query->orderBy($sortBy, $sortDirection);
    } else {
        // Default sorting
        $query->orderBy('tanggal', 'asc');
    }

    $datapengukurans = $query->get();

    $totalRatarata = $datapengukurans->sum('ratarata');
    $averageRatarata = $datapengukurans->count() > 0 ? $totalRatarata / $datapengukurans->count() : 0;

    $xValues = $datapengukurans->pluck('namadata')->toArray();
    $yValues = $datapengukurans->pluck('ratarata')->toArray();

    $upwardTrend = 0;
    $downwardTrend = 0;
    $previousRatarata = null;

    foreach ($datapengukurans as $datapengukuran) {
        if ($previousRatarata !== null) {
            if ($datapengukuran->ratarata > $previousRatarata) {
                $upwardTrend++;
            } elseif ($datapengukuran->ratarata < $previousRatarata) {
                $downwardTrend++;
            }
        }
        $previousRatarata = $datapengukuran->ratarata;
    }

    $trend = 'No significant trend detected';
    if ($upwardTrend > $downwardTrend) {
        $trend = 'Kondisi supir relatif memburuk';
    } elseif ($downwardTrend > $upwardTrend) {
        $trend = 'Kondisi supir relatif membaik';
    }

    $ratarataCategories = [
        'safe' => 0,
        'mild' => 0,
        'moderate' => 0,
        'heavy' => 0,
    ];

    foreach ($datapengukurans as $datapengukuran) {
        if ($datapengukuran->ratarata <= 300) {
            $ratarataCategories['safe']++;
        } elseif ($datapengukuran->ratarata >= 301 && $datapengukuran->ratarata <= 450) {
            $ratarataCategories['mild']++;
        } elseif ($datapengukuran->ratarata >= 451 && $datapengukuran->ratarata <= 600) {
            $ratarataCategories['moderate']++;
        } elseif ($datapengukuran->ratarata > 600) {
            $ratarataCategories['heavy']++;
        }
    }

    // Determine the most frequent fatigue category
    $mostFrequentCategory = array_search(max($ratarataCategories), $ratarataCategories);

    $fatigueMessage = 'Unknown fatigue level';
    switch ($mostFrequentCategory) {
        case 'safe':
            $fatigueMessage = 'Supir relatif aman';
            break;
        case 'mild':
            $fatigueMessage = 'Supir sering mengalami kelelahan ringan';
            break;
        case 'moderate':
            $fatigueMessage = 'Supir sering mengalami kelelahan sedang';
            break;
        case 'heavy':
            $fatigueMessage = 'Supir sering mengalami kelelahan berat';
            break;
    }

    return view('analisakelompok', [
        'pengirim' => $pengirim,
        'datapengukurans' => $datapengukurans,
        'filters' => [
            'jenistest' => $request->query('jenistest'),
            'lokasi' => $request->query('lokasi'),
            'tanggal' => $request->query('tanggal'),
            'waktu' => $request->query('waktu'),
        ],
        'xValues' => $xValues,
        'yValues' => $yValues,
        'averageRatarata' => $averageRatarata,
        'trend' => $trend,
        'fatigueMessage' => $fatigueMessage,
    ]);
    }

    public function destroy($id)
    {
        $datapengukuran = Datapengukuran::findOrFail($id); 
        $datapengukuran->delete(); 

        // Redirect atau response JSON untuk AJAX
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }



}
