<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaduanComment extends Model
{
    protected $table = 'pengaduan_comments';

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'comment',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
