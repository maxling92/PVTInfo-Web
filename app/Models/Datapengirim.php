<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datapengirim extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_observant',
        'tgllahir',
        'nama_perusahaan'

    ];

    public function datapengukurans()
    {
        return $this->hasMany(Datapengukuran::class, 'nama_observant', 'nama_observant');
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'nama_perusahaan', 'nama_perusahaan');
    } 
}