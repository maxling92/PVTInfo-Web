<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'email_resmi',
        'telepon',
    ];

    public $incrementing = false;
    protected $primaryKey = 'ID_perusahaan';

    public function users()
    {
        return $this->hasMany(User::class, 'nama_perusahaan', 'nama_perusahaan');
    }

    public function datapengirims()
    {
        return $this->hasMany(Datapengirim::class, 'nama_perusahaan', 'nama_perusahaan');
    }

    public function datapengukurans()
    {
        return $this->hasManyThrough(Datapengukuran::class, Datapengirim::class, 'nama_perusahaan', 'nama_observant', 'nama_perusahaan', 'nama_observant');
    }

    public function datahasils()
    {
        return $this->hasManyThrough(Datahasil::class, Datapengukuran::class, 'nama_perusahaan', 'namadata', 'nama_perusahaan', 'namadata');
    }
}

