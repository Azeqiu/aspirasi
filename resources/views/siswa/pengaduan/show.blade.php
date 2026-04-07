@extends('siswa.layout')

@section('title', 'Detail Pengaduan')

@section('styles')
<style>
    .back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-muted); text-decoration: none; margin-bottom: 20px; transition: color 0.2s; }
    .back-btn:hover { color: var(--primary); }
    .detail-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; max-width: 700px; }
    .detail-header { padding: 22px 28px; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
    .detail-title { font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .detail-meta { font-size: 13px; color: var(--text-muted); display: flex; gap: 14px; flex-wrap: wrap; }
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .badge-pending        { background: #fef3c7; color: #92400e; }
    .badge-sedangdiproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai        { background: #d1fae5; color: #065f46; }
    .badge-ditolak        { background: #fee2e2; color: #991b1b; }
    .detail-body { padding: 28px; display: flex; flex-direction: column; gap: 22px; }
    .section-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); margin-bottom: 8px; }
    .section-text { font-size: 14px; color: var(--text); line-height: 1.75; }
    .divider { height: 1px; background: var(--border); }
    .foto-img { width: 100%; max-width: 400px; border-radius: 10px; border: 1px solid var(--border); object-fit: cover; }
    .tanggapan-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px 20px; }
    .tanggapan-label { font-size: 12px; font-weight: 700; color: #065f46; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; }
    .tanggapan-text { font-size: 14px; color: #064e3b; line-height: 1.7; }
    .no-tanggapan { background: #f8fafc; border: 1px dashed var(--border); border-radius: 10px; padding: 16px 20px; font-size: 13px; color: var(--text-muted); font-style: italic; }
    .detail-footer { padding: 18px 28px; border-top: 1px solid var(--border); display: flex; gap: 10px; }
    .btn-edit { padding: 9px 20px; background: #f59e0b; color: white; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; }
    .btn-back { padding: 9px 20px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; text-decoration: none; }

    .comment-item { border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; background: white; }
    .comment-meta { display: flex; gap: 10px; flex-wrap: wrap; font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
    .comment-author { font-weight: 700; color: var(--text); }
    .comment-text { font-size: 14px; color: var(--text); line-height: 1.7; white-space: pre-wrap; }
    .comment-form textarea { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px; font-size: 14px; font-family: inherit; outline: none; resize: vertical; min-height: 110px; }
    .comment-form textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16,185,129,0.12); }
    .comment-actions { display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 10px; }
    .btn-send { padding: 10px 16px; border-radius: 10px; border: none; cursor: pointer; font-size: 13px; font-weight: 700; color: white; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
    .field-error { margin-top: 8px; font-size: 13px; color: #991b1b; }
</style>
@endsection

@section('content')
<a href="{{ route('siswa.pengaduan.index') }}" class="back-btn">← Kembali ke Histori</a>

@php
    $sc = strtolower(str_replace(' ', '', $pengaduan->status));
    $canEdit = $pengaduan->status === 'Pending';
    $canComment = $pengaduan->status === 'Pending';
@endphp

<div class="detail-card">
    <div class="detail-header">
        <div>
            <div class="detail-title">{{ $pengaduan->judul }}</div>
            <div class="detail-meta">
                <span>📁 {{ $pengaduan->kategori }}</span>
                <span>📅 {{ $pengaduan->created_at->format('d M Y, H:i') }}</span>
                <span>🕐 {{ $pengaduan->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <span class="badge badge-{{ $sc }}">{{ $pengaduan->status }}</span>
    </div>
    <div class="detail-body">
        <div>
            <div class="section-label">Deskripsi Pengaduan</div>
            <div class="section-text">{{ $pengaduan->deskripsi }}</div>
        </div>
        @if($pengaduan->foto_bukti)
            <div class="divider"></div>
            <div>
                <div class="section-label">Foto Bukti</div>
                <img class="foto-img" src="{{ $pengaduan->foto_bukti_url }}" alt="Foto Bukti">
            </div>
        @endif
        <div class="divider"></div>
        <div>
            <div class="section-label">Tanggapan Admin</div>
            @if($pengaduan->tanggapan)
                <div class="tanggapan-box">
                    <div class="tanggapan-label">💬 Tanggapan</div>
                    <div class="tanggapan-text">{{ $pengaduan->tanggapan }}</div>
                </div>
            @else
                <div class="no-tanggapan">Belum ada tanggapan dari admin.</div>
            @endif
        </div>

        <div class="divider"></div>

        <div>
            <div class="section-label">Komentar Siswa</div>

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
                <div class="no-tanggapan">Belum ada komentar.</div>
            @endforelse

            @if($canComment)
                <form class="comment-form" action="{{ route('siswa.pengaduan.comments.store', $pengaduan->id) }}" method="POST" style="margin-top: 14px;">
                    @csrf
                    <textarea name="comment" placeholder="Tulis komentar untuk admin...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="comment-actions">
                        <button type="submit" class="btn-send">Kirim Komentar</button>
                    </div>
                </form>
            @else
                <div class="no-tanggapan" style="margin-top: 14px;">Komentar hanya bisa ditambahkan saat status masih Pending.</div>
            @endif
        </div>
    </div>
    <div class="detail-footer">
        @if($canEdit)
            <a href="{{ route('siswa.pengaduan.edit', $pengaduan->id) }}" class="btn-edit">✏️ Edit Pengaduan</a>
        @endif
        <a href="{{ route('siswa.pengaduan.index') }}" class="btn-back">← Kembali</a>
    </div>
</div>
@endsection