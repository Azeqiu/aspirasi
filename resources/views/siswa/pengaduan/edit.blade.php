@extends('siswa.layout')

@section('title', 'Edit Pengaduan')

@section('styles')
<style>
    .form-card { background: white; border-radius: 12px; border: 1px solid var(--border); max-width: 680px; overflow: hidden; }
    .form-header { padding: 20px 28px; border-bottom: 1px solid var(--border); }
    .form-title { font-size: 17px; font-weight: 700; color: var(--text); }
    .form-sub { font-size: 13px; color: var(--text-muted); margin-top: 3px; }
    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 20px; }
    .field { display: flex; flex-direction: column; gap: 7px; }
    .field-label { font-size: 13px; font-weight: 600; color: var(--text); }
    .field-input, .field-select, .field-textarea {
        padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px;
        font-size: 14px; font-family: inherit; color: var(--text); outline: none;
        transition: border-color 0.2s; background: white;
    }
    .field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .field-textarea { resize: vertical; min-height: 120px; }
    .field-error { font-size: 12px; color: #ef4444; }
    .foto-preview img { width: 120px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); margin-top: 8px; }
    .form-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; gap: 10px; }
    .btn-save { padding: 10px 24px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.2s; }
    .btn-save:hover { background: var(--primary-dark); }
    .btn-cancel { padding: 10px 20px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; text-decoration: none; }
    .warning-box { background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400e; margin-bottom: 20px; max-width: 680px; }
</style>
@endsection

@section('content')
@if($pengaduan->status !== 'Pending')
    <div style="text-align:center; padding:60px 24px;">
        <div style="font-size:52px; margin-bottom:16px;">🔒</div>
        <div style="font-size:17px; font-weight:700; margin-bottom:8px;">Pengaduan Tidak Dapat Diedit</div>
        <div style="font-size:14px; color:var(--text-muted); margin-bottom:24px;">Status saat ini: <strong>{{ $pengaduan->status }}</strong></div>
        <a href="{{ route('siswa.pengaduan.index') }}" style="padding:10px 24px; background:var(--primary); color:white; border-radius:8px; text-decoration:none; font-weight:600;">← Kembali</a>
    </div>
@else
<div class="warning-box">⚠️ Pengaduan hanya bisa diedit selama status masih <strong>Pending</strong>.</div>

<div class="form-card">
    <div class="form-header">
        <div class="form-title">✏️ Edit Pengaduan</div>
        <div class="form-sub">Perbarui detail pengaduanmu</div>
    </div>
    <form method="POST" action="{{ route('siswa.pengaduan.update', $pengaduan->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-body">
            <div class="field">
                <label class="field-label">Judul Pengaduan</label>
                <input type="text" name="judul" class="field-input" value="{{ old('judul', $pengaduan->judul) }}">
                @error('judul') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Kategori</label>
                <select name="kategori" class="field-select">
                    @foreach($kategori as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $pengaduan->kategori) === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
                @error('kategori') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Deskripsi</label>
                <textarea name="deskripsi" class="field-textarea">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                @error('deskripsi') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Foto Bukti <span style="font-weight:400; color:var(--text-muted);">(opsional)</span></label>
                @if($pengaduan->foto_bukti)
                    <div class="foto-preview">
                        <img src="{{ $pengaduan->foto_bukti_url }}" alt="Foto saat ini">
                        <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">Upload baru untuk mengganti.</div>
                    </div>
                @endif
                <input type="file" name="foto_bukti" class="field-input" accept="image/jpeg,image/png,image/jpg">
                @error('foto_bukti') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
            <a href="{{ route('siswa.pengaduan.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endif
@endsection