@extends('siswa.layout')

@section('title', 'Buat Pengaduan')

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
        transition: border-color 0.2s, box-shadow 0.2s; background: white;
    }
    .field-input:focus, .field-select:focus, .field-textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .field-textarea { resize: vertical; min-height: 120px; }
    .field-error { font-size: 12px; color: #ef4444; }
    .form-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; gap: 10px; }
    .btn-submit {
        padding: 10px 24px; background: var(--primary); color: white;
        border: none; border-radius: 8px; font-size: 14px; font-weight: 600;
        font-family: inherit; cursor: pointer; transition: all 0.2s;
    }
    .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .btn-cancel {
        padding: 10px 20px; background: white; color: var(--text-muted);
        border: 1px solid var(--border); border-radius: 8px; font-size: 14px;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-cancel:hover { background: #f8fafc; }
    .error-box { background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; color: #991b1b; }
    .file-area { border: 2px dashed var(--border); border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; position: relative; overflow: hidden; }
    .file-area:hover { border-color: var(--primary); background: rgba(37,99,235,0.02); }
    .file-area input[type="file"] { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    #preview-img { width: 100%; max-height: 220px; object-fit: contain; border-radius: 8px; border: 1px solid var(--border); margin-top: 10px; display: none; }</style>
@endsection

@section('content')
@if($errors->any())
    <div class="error-box" style="max-width:680px;">
        <strong>Mohon perbaiki kesalahan berikut:</strong>
        <ul style="margin-top:6px; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-card">
    <div class="form-header">
        <div class="form-title">📝 Buat Pengaduan Baru</div>
        <div class="form-sub">Isi form berikut untuk menyampaikan pengaduanmu</div>
    </div>
    <form action="{{ route('siswa.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-body">
            <div class="field">
                <label class="field-label">Kategori <span style="color:#ef4444;">*</span></label>
                <select name="kategori" class="field-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item }}" {{ old('kategori') == $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
                @error('kategori') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Judul Pengaduan <span style="color:#ef4444;">*</span></label>
                <input type="text" name="judul" class="field-input" value="{{ old('judul') }}" placeholder="Tulis judul singkat pengaduanmu">
                @error('judul') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Deskripsi <span style="color:#ef4444;">*</span></label>
                <textarea name="deskripsi" class="field-textarea" placeholder="Jelaskan pengaduanmu secara detail...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Foto Bukti <span style="color:var(--text-muted); font-weight:400;">(opsional)</span></label>
                <div class="file-area">
                    <div>📷 Klik untuk upload foto</div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">JPG, PNG — Maks 2MB</div>
                    <img id="preview-img" src="" alt="Preview">
                    <input type="file" id="foto_bukti" name="foto_bukti" accept="image/jpeg,image/png,image/jpg">
                </div>
                @error('foto_bukti') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn-submit">📤 Kirim Pengaduan</button>
            <a href="{{ route('siswa.dashboard') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('foto_bukti').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = ev => {
                const img = document.getElementById('preview-img');
                img.src = ev.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection