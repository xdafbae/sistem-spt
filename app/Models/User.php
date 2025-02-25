<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama', // Nama pengguna
        'email', // Email pengguna
        'password', // Password pengguna
        'no_hp', // Nomor handphone pengguna
        'nip', // NIP pengguna
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Method untuk menentukan field login berdasarkan role.
     */
    public function getLoginField()
    {
        if ($this->hasRole('karyawan')) { // Pastikan role-nya adalah 'karyawan'
            return 'nip'; // Karyawan login menggunakan NIP
        }
        return 'email'; // Role lain login menggunakan email
    }
}
