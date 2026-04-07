@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan data pengaduan siswa')

@section('content')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card {
        background: white; border-radius: 12px;
        border: 1px solid var(--border); padding: 20px;
        display: flex; align-items: center; gap: 14px;
    }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon svg { width: 22px; height: 22px; }
    .stat-num { font-size: 26px; font-weight: 700; color: var(--text); line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

    @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(3,1fr); } }
    @media (max-width: 720px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 420px) { .stats-grid { grid-template-columns: 1fr; } }

    .grid-2 { display: grid; grid-template-columns: 1fr 320px; gap: 20px; }
    .card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .card-header { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 600; color: var(--text); display:flex; align-items:center; gap:10px; }
    .card-header svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--primary-dark); }

    .pengaduan-item { padding: 14px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
    .pengaduan-item:last-child { border-bottom: none; }
    .item-avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 13px; flex-shrink: 0; }
    .item-title { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; margin-bottom: 2px; }
    .item-meta { font-size: 11px; color: var(--text-muted); }

    .badge { display: inline-flex; align-items: center; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge-pending        { background: #fef3c7; color: #92400e; }
    .badge-sedangdiproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai        { background: #d1fae5; color: #065f46; }
    .badge-ditolak        { background: #fee2e2; color: #991b1b; }

    .kat-item { padding: 12px 22px; border-bottom: 1px solid var(--border); font-size: 14px; }
    .kat-item:last-child { border-bottom: none; }
    .kat-row { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; }
    .kat-name { font-weight: 600; color: var(--text); }
    .kat-value { font-size: 12px; font-weight: 700; color: var(--text-muted); white-space: nowrap; }
    .kat-bar { margin-top: 10px; height: 8px; border-radius: 999px; overflow: hidden; background: var(--border); }
    .kat-bar { background: color-mix(in srgb, var(--primary) 14%, white); }
    .kat-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--primary), var(--primary-dark)); }

    .empty-state { text-align: center; padding: 40px; color: var(--text-muted); font-size: 14px; }
</style>

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
        <div><div class="stat-num">{{ $totalPengaduan }}</div><div class="stat-label">Total Pengaduan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 3h10M7 21h10" stroke="#92400e" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 3v3.2c0 .6.3 1.1.8 1.4l2.7 1.6c.3.2.3.6 0 .8l-2.7 1.6c-.5.3-.8.8-.8 1.4V21" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 3v3.2c0 .6-.3 1.1-.8 1.4l-2.7 1.6c-.3.2-.3.6 0 .8l2.7 1.6c.5.3.8.8.8 1.4V21" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div><div class="stat-num">{{ $pending }}</div><div class="stat-label">Pending</div></div>
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
        <div><div class="stat-num">{{ $diproses }}</div><div class="stat-label">Diproses</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 6 9 17l-5-5" stroke="#065f46" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div><div class="stat-num">{{ $selesai }}</div><div class="stat-label">Selesai</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 7l10 10M17 7 7 17" stroke="#991b1b" stroke-width="1.9" stroke-linecap="round"/>
                <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="#991b1b" stroke-width="1.8"/>
            </svg>
        </div>
        <div><div class="stat-num">{{ $ditolak }}</div><div class="stat-label">Ditolak</div></div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M8 6h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 10h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 14h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M9 3h6a2 2 0 0 1 2 2v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
            Pengaduan Terbaru
        </div>
        @if($pengaduanTerbaru->count() > 0)
            @foreach($pengaduanTerbaru as $item)
                @php $sc = strtolower(str_replace(' ', '', $item->status)); @endphp
                <div class="pengaduan-item">
                    <div class="item-avatar">{{ strtoupper(substr($item->user->name ?? 'S', 0, 1)) }}</div>
                    <div style="flex:1; min-width:0;">
                        <div class="item-title">{{ $item->judul }}</div>
                        <div class="item-meta">{{ $item->user->name ?? '-' }} · {{ $item->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge badge-{{ $sc }}">{{ $item->status }}</span>
                </div>
            @endforeach
        @else
            <div class="empty-state">Belum ada pengaduan</div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 7h6l2 2h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
            Per Kategori
        </div>
        @if($perKategori->count() > 0)
            @php $perKategoriTotal = (int) $perKategori->sum('total'); @endphp
            @foreach($perKategori as $kat)
                @php
                    $pctRaw = $perKategoriTotal > 0
                        ? (((int) $kat->total / $perKategoriTotal) * 100)
                        : 0;
                    $pct = (int) round($pctRaw);
                    $pctBar = max(0, min(100, $pct));
                @endphp
                <div class="kat-item">
                    <div class="kat-row">
                        <span class="kat-name">{{ $kat->kategori }}</span>
                        <span class="kat-value">{{ (int) $kat->total }} ({{ $pct }}%)</span>
                    </div>
                    <div class="kat-bar" aria-hidden="true">
                        <div class="kat-bar-fill" style="width: {{ $pctBar }}%;"></div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">Belum ada data</div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    const alertBox = document.getElementById('alertBox');
    if (alertBox) setTimeout(() => { alertBox.style.opacity='0'; alertBox.style.transition='opacity 0.3s'; setTimeout(()=>alertBox.remove(),300); }, 4000);
</script>
@endsection