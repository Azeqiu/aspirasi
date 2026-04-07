<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;
use App\Notifications\PengaduanCreatedForAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class PengaduanController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalPengaduan   = Pengaduan::where('user_id', $user->id)->count();
        $pending          = Pengaduan::where('user_id', $user->id)->where('status', 'Pending')->count();
        $diproses         = Pengaduan::where('user_id', $user->id)->where('status', 'Sedang Diproses')->count();
        $selesai          = Pengaduan::where('user_id', $user->id)->where('status', 'Selesai')->count();
        $ditolak          = Pengaduan::where('user_id', $user->id)->where('status', 'Ditolak')->count();
        $pengaduanTerbaru = Pengaduan::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(5)->get();

        return view('siswa.dashboard', compact(
            'totalPengaduan', 'pending', 'diproses', 'selesai', 'ditolak', 'pengaduanTerbaru'
        ));
    }

    public function create()
    {
        $kategori = \App\Models\Kategori::orderBy('nama')->pluck('nama');
        return view('siswa.pengaduan.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|exists:kategoris,nama', // ✅ FIXED: dinamis dari database
            'deskripsi'  => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required'     => 'Judul pengaduan wajib diisi',
            'kategori.required'  => 'Kategori wajib dipilih',
            'kategori.exists'    => 'Kategori yang dipilih tidak valid',
            'deskripsi.required' => 'Deskripsi pengaduan wajib diisi',
            'foto_bukti.image'   => 'File harus berupa gambar',
            'foto_bukti.max'     => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'user_id'   => Auth::id(),
            'judul'     => $request->judul,
            'kategori'  => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'status'    => 'Pending',
        ];

        if ($request->hasFile('foto_bukti')) {
            $file     = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pengaduan', $filename, 'public');
            $data['foto_bukti'] = $filename;
        }

        $pengaduan = Pengaduan::create($data);

        try {
            $admins = User::where('role', 'admin')
                ->whereNotNull('email')
                ->get();

            if ($admins->isNotEmpty()) {
                $pengaduan->loadMissing('user');
                Notification::send($admins, new PengaduanCreatedForAdmin($pengaduan));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('siswa.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function index()
    {
        $user      = Auth::user();
        $pengaduan = Pengaduan::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        $pending   = Pengaduan::where('user_id', $user->id)->where('status', 'Pending')->count();
        $diproses  = Pengaduan::where('user_id', $user->id)->where('status', 'Sedang Diproses')->count();
        $selesai   = Pengaduan::where('user_id', $user->id)->where('status', 'Selesai')->count();
        $ditolak   = Pengaduan::where('user_id', $user->id)->where('status', 'Ditolak')->count();

        return view('siswa.pengaduan.index', compact(
            'pengaduan', 'pending', 'diproses', 'selesai', 'ditolak'
        ));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['comments.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('siswa.pengaduan.show', compact('pengaduan'));
    }

    public function edit($id)
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())->findOrFail($id);
        $kategori  = \App\Models\Kategori::orderBy('nama')->pluck('nama'); // ✅ konsisten pakai model
        return view('siswa.pengaduan.edit', compact('pengaduan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())->findOrFail($id);

        if ($pengaduan->status !== 'Pending') {
            return redirect()->route('siswa.pengaduan.index')
                ->with('error', 'Pengaduan tidak dapat diedit karena sudah diproses.');
        }

        $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|exists:kategoris,nama', // ✅ FIXED: pakai exists konsisten
            'deskripsi'  => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required'     => 'Judul pengaduan wajib diisi',
            'kategori.required'  => 'Kategori wajib dipilih',
            'kategori.exists'    => 'Kategori yang dipilih tidak valid',
            'deskripsi.required' => 'Deskripsi pengaduan wajib diisi',
            'foto_bukti.image'   => 'File harus berupa gambar',
            'foto_bukti.max'     => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'judul'     => $request->judul,
            'kategori'  => $request->kategori,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('foto_bukti')) {
            if ($pengaduan->foto_bukti) {
                Storage::disk('public')->delete('pengaduan/' . $pengaduan->foto_bukti);
            }
            $file     = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pengaduan', $filename, 'public');
            $data['foto_bukti'] = $filename;
        }

        $pengaduan->update($data);

        return redirect()->route('siswa.pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())->findOrFail($id);

        if ($pengaduan->status !== 'Pending') {
            return redirect()->route('siswa.pengaduan.index')
                ->with('error', 'Pengaduan tidak dapat dihapus karena sudah diproses.');
        }

        if ($pengaduan->foto_bukti) {
            Storage::disk('public')->delete('pengaduan/' . $pengaduan->foto_bukti);
        }

        $pengaduan->delete();

        return redirect()->route('siswa.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus!');
    }
}