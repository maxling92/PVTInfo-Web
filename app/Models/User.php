<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nama_perusahaan',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function datapengirim()
    {
        return $this->hasMany(Datapengirim::class, 'nama_perusahaan', 'nama_perusahaan');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'nama_perusahaan', 'nama_perusahaan');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function companyAdmins()
    {
        return self::where('nama_perusahaan', $this->nama_perusahaan)
                    ->where('role', 'admin')
                    ->count();
    }

    public static function canAddAdmin($companyId)
    {
        return self::where('nama_perusahaan', $companyId)
                   ->where('role', 'admin')
                   ->count() < 2;
    }
}

