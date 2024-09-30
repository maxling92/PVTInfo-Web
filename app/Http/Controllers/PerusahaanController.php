<?php

// app/Http/Controllers/PerusahaanController.php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaans = Perusahaan::all();
        return view('DaftarPerusahaan', compact('perusahaans'));
    }

    public function create()
{
    return view('TambahPerusahaan');
}


public function store(Request $request)
{
    $validated = $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'email_resmi'=>'required|email|max:30',
        'telepon' => 'required|string|max:15',
    ]);

    Perusahaan::create($validated);

    return redirect()->route('perusahaan.index')->with('success', 'Perusahaan added successfully');
}


}

