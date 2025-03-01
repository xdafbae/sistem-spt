<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spt extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tanggal_pengajuan',
        'dasar',
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'tujuan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'spt_user')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateNomorSurat()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $count = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count() + 1;

        return sprintf('SPT/%s/%s/%04d', $bulan, $tahun, $count);
    }
}
