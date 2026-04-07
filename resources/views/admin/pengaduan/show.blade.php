@extends('admin.layout')

@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')
@section('page-sub', 'Lihat detail dan berikan tanggapan')

@section('topbar-action')
<div style="display:flex; gap:10px;">
    <a href="{{ route('admin.pengaduan.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px; background:white; border:1px solid var(--border); border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; color:var(--text-muted);">
        ← Kembali
    </a>
    <form id="delete-form-{{ $pengaduan->id }}" action="{{ route('admin.pengaduan.destroy', $pengaduan->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" class="js-open-delete" data-delete-form="delete-form-{{ $pengaduan->id }}" style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px; background:#ef4444; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
            🗑️ Hapus
        </button>
    </form>
</div>
@endsection

@section('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
    .card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 20px; }
    .card:last-child { margin-bottom: 0; }
    .card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 600; color: var(--text); }
    .card-body { padding: 22px; }
    .detail-row { display: flex; gap: 16px; margin-bottom: 14px; }
    .detail-row:last-child { margin-bottom: 0; }
    .detail-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); min-width: 100px; flex-shrink: 0; padding-top: 2px; }
    .detail-value { font-size: 14px; color: var(--text); flex: 1; }
    .deskripsi-text { font-size: 14px; color: var(--text); line-height: 1.75; white-space: pre-wrap; }
    .foto-bukti img { max-width: 100%; border-radius: 8px; border: 1px solid var(--border); }
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-pending        { background: #fef3c7; color: #92400e; }
    .badge-sedangdiproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai        { background: #d1fae5; color: #065f46; }
    .badge-ditolak        { background: #fee2e2; color: #991b1b; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 7px; }
    .form-control { width: 100%; padding: 10px 13px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; color: var(--text); outline: none; transition: border-color 0.2s; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
    textarea.form-control { resize: vertical; min-height: 110px; }
    .btn-update { width: 100%; padding: 12px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; font-family: inherit; cursor: pointer; }
    .tanggapan-box { background: #f8fafc; border-radius: 8px; padding: 14px 16px; font-size: 14px; color: var(--text); line-height: 1.6; border: 1px solid var(--border); }
    .locked-box { background: #f8fafc; border: 1px dashed var(--border); border-radius: 10px; padding: 24px; text-align: center; }
    .locked-box-icon { font-size: 32px; margin-bottom: 10px; }
    .locked-box-title { font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .locked-box-sub { font-size: 13px; color: var(--text-muted); }
    .comment-item { border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; background: white; }
    .comment-meta { display: flex; gap: 10px; flex-wrap: wrap; font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
    .comment-author { font-weight: 700; color: var(--text); }
    .comment-text { font-size: 14px; color: var(--text); line-height: 1.7; white-space: pre-wrap; }
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="detail-grid">
    <div>
        <div class="card">
            <div class="card-header">📄 Informasi Pengaduan</div>
            <div class="card-body">
                <div class="detail-row">
                    <div class="detail-label">Judul</div>
                    <div class="detail-value" style="font-weight:600; font-size:15px;">{{ $pengaduan->judul }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Dari Siswa</div>
                    <div class="detail-value">{{ $pengaduan->user->name ?? '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value">{{ $pengaduan->kategori }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @php $sc = strtolower(str_replace(' ', '', $pengaduan->status)); @endphp
                        <span class="badge badge-{{ $sc }}">{{ $pengaduan->status }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal</div>
                    <div class="detail-value">{{ $pengaduan->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">📝 Deskripsi</div>
            <div class="card-body">
                <p class="deskripsi-text">{{ $pengaduan->deskripsi }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">💬 Komentar Siswa</div>
            <div class="card-body">
                @forelse($pengaduan->comments as $comment)
                    <div class="comment-item" style="margin-bottom: 12px;">
                        <div class="comment-meta">
                            <span class="comment-author">{{ $comment->user->name ?? 'Siswa' }}</span>
                            <span>•</span>
                            <span>{{ $comment->created_at->format('d M Y, H:i') }}</span>
                            <span>•</span>
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-text">{{ $comment->comment }}</div>
                    </div>
                @empty
                    <div class="tanggapan-box">Belum ada komentar dari siswa.</div>
                @endforelse
            </div>
        </div>
        @if($pengaduan->foto_bukti)
        <div class="card">
            <div class="card-header">📷 Foto Bukti</div>
            <div class="card-body foto-bukti">
                <img src="{{ asset('storage/pengaduan/' . $pengaduan->foto_bukti) }}" alt="Foto Bukti">
            </div>
        </div>
        @endif
        @if($pengaduan->tanggapan)
        <div class="card">
            <div class="card-header">💬 Tanggapan Admin</div>
            <div class="card-body">
                <div class="tanggapan-box">{{ $pengaduan->tanggapan }}</div>
            </div>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">⚙️ Update Pengaduan</div>
        <div class="card-body">
            @if($canEdit)
                <form action="{{ route('admin.pengaduan.update', $pengaduan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#ef4444;">*</span></label>
                        <select name="status" class="form-control">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $pengaduan->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggapan <span style="color:var(--text-muted); font-weight:400;">(opsional)</span></label>
                        <textarea name="tanggapan" class="form-control" placeholder="Tulis tanggapan untuk siswa...">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                    </div>
                    <button type="submit" class="btn-update">💾 Simpan Perubahan</button>
                </form>
            @else
                <div class="locked-box">
                    <div class="locked-box-icon">🔒</div>
                    <div class="locked-box-title">Pengaduan Sudah Final</div>
                    <div class="locked-box-sub">Status <strong>{{ $pengaduan->status }}</strong> tidak dapat diubah lagi.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const alertBox = document.getElementById('alertBox');
    if (alertBox) setTimeout(() => { alertBox.style.opacity='0'; alertBox.style.transition='opacity 0.3s'; setTimeout(()=>alertBox.remove(),300); }, 4000);
</script>
@endsection