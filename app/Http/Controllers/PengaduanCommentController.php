<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\PengaduanComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanCommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())->findOrFail($id);

        if ($pengaduan->status !== 'Pending') {
            return redirect()->route('siswa.pengaduan.show', $pengaduan->id)
                ->with('error', 'Komentar hanya bisa ditambahkan saat status masih Pending.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ], [
            'comment.required' => 'Komentar wajib diisi.',
            'comment.max'      => 'Komentar maksimal 1000 karakter.',
        ]);

        PengaduanComment::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id'      => Auth::id(),
            'comment'      => $validated['comment'],
        ]);

        return redirect()->route('siswa.pengaduan.show', $pengaduan->id)
            ->with('success', 'Komentar berhasil dikirim.');
    }
}
