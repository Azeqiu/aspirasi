@extends('siswa.layout')

@section('title', 'Dashboard')

@section('content')
<style>
    .welcome { margin-bottom: 28px; }
    .welcome-title { font-size: 22px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .welcome-sub { font-size: 14px; color: var(--text-muted); }

    .stats-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card {
        background: var(--white); border-radius: 12px;
        border: 1px solid var(--border); padding: 20px;
        display: flex; align-items: center; gap: 14px;
    }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon svg { width: 22px; height: 22px; }
    .stat-num { font-size: 24px; font-weight: 700; color: var(--text); line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 720px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 420px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .card-title { font-size: 15px; font-weight: 600; color: var(--text); }

    .pengaduan-item {
        padding: 16px 22px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 14px;
    }
    .pengaduan-item:last-child { border-bottom: none; }
    .item-icon {
        width: 38px; height: 38px; border-radius: 8px;
        background: rgba(37,99,235,0.08);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }
    .item-title { font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 320px; }
    .item-meta { font-size: 12px; color: var(--text-muted); }
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge-pending        { background: #fef3c7; color: #92400e; }
    .badge-sedangdiproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai        { background: #d1fae5; color: #065f46; }
    .badge-ditolak        { background: #fee2e2; color: #991b1b; }

    .btn-new {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; background: var(--primary); color: white;
        border-radius: 8px; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-new:hover { background: var(--primary-dark); }

    .empty-state { text-align: center; padding: 48px 24px; }
    .empty-state div:first-child { font-size: 48px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; color: var(--text-muted); margin-bottom: 16px; }
</style>

<div class="welcome">
    <div class="welcome-title">Halo, {{ auth()->user()->name }}! 👋</div>
    <div class="welcome-sub">Berikut ringkasan pengaduanmu</div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M8 6h8" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 10h8" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 14h5" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M9 3h6a2 2 0 0 1 2 2v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1V5a2 2 0 0 1 2-2Z" stroke="#1e40af" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <div class="stat-num">{{ $totalPengaduan }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 3h10M7 21h10" stroke="#92400e" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 3v3.2c0 .6.3 1.1.8 1.4l2.7 1.6c.3.2.3.6 0 .8l-2.7 1.6c-.5.3-.8.8-.8 1.4V21" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 3v3.2c0 .6-.3 1.1-.8 1.4l-2.7 1.6c-.3.2-.3.6 0 .8l2.7 1.6c.5.3.8.8.8 1.4V21" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <div class="stat-num">{{ $pending }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 12a8 8 0 0 1-14.9 3" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M4 12a8 8 0 0 1 14.9-3" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M18 4v5h-5" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 20v-5h5" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <div class="stat-num">{{ $diproses }}</div>
            <div class="stat-label">Diproses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 6 9 17l-5-5" stroke="#065f46" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <div class="stat-num">{{ $selesai }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 7l10 10M17 7 7 17" stroke="#991b1b" stroke-width="1.9" stroke-linecap="round"/>
                <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="#991b1b" stroke-width="1.8"/>
            </svg>
        </div>
        <div>
            <div class="stat-num">{{ $ditolak }}</div>
            <div class="stat-label">Ditolak</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">📋 Pengaduan Terbaru</div>
        <a href="{{ route('siswa.pengaduan.create') }}" class="btn-new">➕ Buat Pengaduan</a>
    </div>

    @if($pengaduanTerbaru->count() > 0)
        @foreach($pengaduanTerbaru as $item)
            @php $sc = strtolower(str_replace(' ', '', $item->status)); @endphp
            <div class="pengaduan-item">
                <div class="item-icon">📝</div>
                <div style="flex:1; min-width:0;">
                    <div class="item-title">{{ $item->judul }}</div>
                    <div class="item-meta">{{ $item->kategori }} · {{ $item->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge badge-{{ $sc }}">{{ $item->status }}</span>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <div>📭</div>
            <p>Belum ada pengaduan. Mulai buat pengaduanmu!</p>
            <a href="{{ route('siswa.pengaduan.create') }}" class="btn-new">➕ Buat Pengaduan</a>
        </div>
    @endif
</div>
@endsection