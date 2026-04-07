<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;
use App\Notifications\PengaduanUpdatedForStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPengaduan  = Pengaduan::count();
        $pending         = Pengaduan::where('status', 'Pending')->count();
        $diproses        = Pengaduan::where('status', 'Sedang Diproses')->count();
        $selesai         = Pengaduan::where('status', 'Selesai')->count();
        $ditolak         = Pengaduan::where('status', 'Ditolak')->count();
        $totalSiswa      = User::where('role', 'siswa')->count();
        $pengaduanTerbaru = Pengaduan::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        $perKategori     = Pengaduan::selectRaw('kategori, count(*) as total')->groupBy('kategori')->get();

        return view('admin.dashboard', compact(
            'totalPengaduan', 'pending', 'diproses', 'selesai',
            'ditolak', 'totalSiswa', 'pengaduanTerbaru', 'perKategori'
        ));
    }

    public function index(Request $request)
    {
        $query = Pengaduan::with('user');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengaduan = $query->orderBy('created_at', 'desc')->paginate(10);
        $statuses  = Pengaduan::STATUS;
        $kategoris = Pengaduan::KATEGORI;

        return view('admin.pengaduan.index', compact('pengaduan', 'statuses', 'kategoris'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['user', 'comments.user'])->findOrFail($id);
        $statuses  = Pengaduan::STATUS;
        $canEdit   = !in_array($pengaduan->status, ['Selesai', 'Ditolak']);
        return view('admin.pengaduan.show', compact('pengaduan', 'statuses', 'canEdit'));
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::with('user')->findOrFail($id);

        $originalStatus = $pengaduan->status;
        $originalTanggapan = $pengaduan->tanggapan;

        if (in_array($pengaduan->status, ['Selesai', 'Ditolak'])) {
            return redirect()->route('admin.pengaduan.show', $id)
                ->with('error', 'Pengaduan tidak dapat diubah karena sudah final.');
        }

        $request->validate([
            'status'    => 'required|in:Pending,Sedang Diproses,Selesai,Ditolak',
            'tanggapan' => 'nullable|string',
        ]);

        $pengaduan->update([
            'status'    => $request->status,
            'tanggapan' => $request->tanggapan,
        ]);

        $statusChanged = $originalStatus !== $pengaduan->status;
        $tanggapanChanged = $originalTanggapan !== $pengaduan->tanggapan;

        try {
            if (($statusChanged || $tanggapanChanged) && !empty($pengaduan->user?->email)) {
                $pengaduan->user->notify(new PengaduanUpdatedForStudent($pengaduan, $statusChanged, $tanggapanChanged));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('admin.pengaduan.show', $id)
            ->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if ($pengaduan->foto_bukti) {
            Storage::disk('public')->delete('pengaduan/' . $pengaduan->foto_bukti);
        }

        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus!');
    }
}