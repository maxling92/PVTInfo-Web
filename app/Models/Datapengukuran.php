<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datapengukuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'namadata',
        'tanggal',
        'lokasi',
        'jenistest',
        'ratarata',
        'hasil',
        'gagal',
        'nama_observant',
    
    ];

    public function datapengirim()
    {
        return $this->belongsTo(Datapengirim::class, 'nama_observant', 'nama_observant');
    }

    public function perusahaan()
    {
        return $this->hasOneThrough(Perusahaan::class, Datapengirim::class, 'nama_observant', 'nama_perusahaan', 'nama_observant', 'nama_perusahaan');
    }

    public function datahasils()
    {
        return $this->hasMany(Datahasil::class, 'namadata', 'namadata');
    }

}

