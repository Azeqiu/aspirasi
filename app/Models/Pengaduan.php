<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengaduanComment;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'user_id', 'judul', 'kategori',
        'deskripsi', 'foto_bukti', 'status', 'tanggapan'
    ];

    const STATUS = [
        'Pending'         => 'Pending',
        'Sedang Diproses' => 'Sedang Diproses',
        'Selesai'         => 'Selesai',
        'Ditolak'         => 'Ditolak',
    ];

    const KATEGORI = [
        'Kelas',
        'Lab Praktek',
        'Sarana dan Prasarana',
        'Guru',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PengaduanComment::class, 'pengaduan_id')->latest();
    }
}