<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.unique'   => 'Kategori sudah ada',
        ]);

        Kategori::create(['nama' => $request->nama]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama,' . $id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.unique'   => 'Kategori sudah ada',
        ]);

        $kategori->update(['nama' => $request->nama]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        $dipakai = Pengaduan::where('kategori', $kategori->nama)->count();
        if ($dipakai > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena digunakan oleh ' . $dipakai . ' pengaduan!');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}