<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengaduanComment;
use Illuminate\Support\Str;

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

    public function getFotoBuktiUrlAttribute(): ?string
    {
        if (empty($this->foto_bukti)) {
            return null;
        }

        $value = trim(str_replace('\\', '/', (string) $this->foto_bukti));
        $value = ltrim($value, '/');

        $value = implode('/', array_map('rawurlencode', explode('/', $value)));

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        // If DB already stores a public path like "storage/pengaduan/xxx.jpg"
        if (Str::startsWith($value, 'storage/')) {
            return '/' . $value;
        }

        // If DB stores a disk-relative path like "pengaduan/xxx.jpg"
        if (Str::startsWith($value, 'pengaduan/')) {
            return '/storage/' . $value;
        }

        // Default: assume it's just a filename stored under storage/app/public/pengaduan
        return '/storage/pengaduan/' . $value;
    }
}