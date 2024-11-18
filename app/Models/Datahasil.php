<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datahasil extends Model
{
    use HasFactory;

    protected $fillable = [
        'waktu_milidetik',
        'namadata'

    ];

    public function datapengukuran()
    {
        return $this->belongsTo(Datapengukuran::class, 'namadata', 'namadata');
    }

    public function perusahaan()
    {
        return $this->hasOneThrough(Perusahaan::class, Datapengukuran::class, 'namadata', 'nama_perusahaan', 'namadata', 'nama_perusahaan');
    }
}
