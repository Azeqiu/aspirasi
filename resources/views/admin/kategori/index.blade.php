@extends('admin.layout')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')
@section('page-sub', 'Kelola kategori pengaduan')

@section('styles')
<style>
    .layout { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
    .card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 600; color: var(--text); }

    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); background: #f8fafc; border-bottom: 1px solid var(--border); }
    tbody td { padding: 14px 18px; font-size: 14px; color: var(--text); border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }

    .btn-edit { padding: 6px 14px; background: #f59e0b; color: white; border-radius: 6px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; font-family: inherit; }
    .btn-hapus { padding: 6px 14px; background: #ef4444; color: white; border-radius: 6px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; font-family: inherit; }
    .btn-save { width: 100%; padding: 11px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; font-family: inherit; cursor: pointer; margin-top: 4px; }
    .btn-cancel { width: 100%; padding: 10px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; cursor: pointer; margin-top: 8px; }

    .form-body { padding: 22px; }
    .field { margin-bottom: 14px; }
    .field-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 7px; }
    .field-input { width: 100%; padding: 10px 13px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; color: var(--text); outline: none; transition: border-color 0.2s; }
    .field-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .field-error { font-size: 12px; color: #ef4444; margin-top: 5px; }

    .badge-count { display: inline-block; padding: 2px 8px; background: rgba(37,99,235,0.1); color: var(--primary); border-radius: 20px; font-size: 11px; font-weight: 600; }

    .edit-row { display: none; background: #f8faff; }
    .edit-row.active { display: table-row; }
    .edit-inline { display: flex; gap: 8px; align-items: center; padding: 10px 18px; }
    .edit-inline input { flex: 1; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; font-family: inherit; outline: none; }
    .edit-inline input:focus { border-color: var(--primary); }
</style>
@endsection

@section('content')
<div class="layout">

    {{-- Kiri: Daftar Kategori --}}
    <div class="card">
        <div class="card-header">🗂️ Daftar Kategori ({{ $kategoris->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Dipakai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategoris as $index => $item)
                    @php $dipakai = \App\Models\Pengaduan::where('kategori', $item->nama)->count(); @endphp
                    <tr id="row-{{ $item->id }}">
                        <td style="color:var(--text-muted);">{{ $index + 1 }}</td>
                        <td style="font-weight:500;">{{ $item->nama }}</td>
                        <td><span class="badge-count">{{ $dipakai }} pengaduan</span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn-edit" onclick="showEdit({{ $item->id }}, '{{ $item->nama }}')">✏️ Edit</button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-hapus js-open-delete" data-delete-form="delete-form-{{ $item->id }}">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr class="edit-row" id="edit-{{ $item->id }}">
                        <td colspan="4">
                            <form action="{{ route('admin.kategori.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="edit-inline">
                                    <input type="text" name="nama" value="{{ $item->nama }}" id="input-{{ $item->id }}">
                                    <button type="submit" class="btn-edit">💾 Simpan</button>
                                    <button type="button" class="btn-hapus" onclick="hideEdit({{ $item->id }})">✕</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Kanan: Form Tambah --}}
    <div class="card">
        <div class="card-header">➕ Tambah Kategori</div>
        <div class="form-body">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label class="field-label">Nama Kategori</label>
                    <input type="text" name="nama" class="field-input"
                           placeholder="contoh: Toilet, Kantin..."
                           value="{{ old('nama') }}">
                    @error('nama') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn-save">➕ Tambah Kategori</button>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function showEdit(id, nama) {
        document.querySelectorAll('.edit-row').forEach(el => el.classList.remove('active'));
        document.getElementById('edit-' + id).classList.add('active');
        document.getElementById('input-' + id).focus();
    }
    function hideEdit(id) {
        document.getElementById('edit-' + id).classList.remove('active');
    }
</script>
@endsection