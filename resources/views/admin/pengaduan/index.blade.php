@extends('admin.layout')

@section('title', 'Semua Pengaduan')
@section('page-title', 'Semua Pengaduan')
@section('page-sub', 'Kelola dan tindaklanjuti pengaduan siswa')

@section('styles')
<style>
    .filter-bar {
        background: white; border-radius: 12px; padding: 18px 22px;
        border: 1px solid var(--border); margin-bottom: 20px;
        display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 12px; font-weight: 600; color: var(--text-muted); }
    .filter-control {
        padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px;
        font-size: 13px; font-family: inherit; color: var(--text);
        background: white; outline: none; min-width: 150px; transition: border-color 0.2s;
    }
    .filter-control:focus { border-color: var(--primary); }
    .btn-filter { padding: 9px 18px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; font-family: inherit; cursor: pointer; display:inline-flex; align-items:center; gap:8px; }
    .btn-filter svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-reset { padding: 9px 14px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; text-decoration: none; }

    .table-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .table-header { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 700; color: var(--text); display:flex; align-items:center; gap:10px; }
    .table-header svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--primary-dark); }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); background: #f8fafc; border-bottom: 1px solid var(--border); }
    tbody td { padding: 13px 18px; font-size: 14px; color: var(--text); border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }

    .judul-cell { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; }
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .badge-pending        { background: #fef3c7; color: #92400e; }
    .badge-sedangdiproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai        { background: #d1fae5; color: #065f46; }
    .badge-ditolak        { background: #fee2e2; color: #991b1b; }

    .btn-detail { padding: 6px 12px; background: var(--primary); color: white; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; display:inline-flex; align-items:center; gap:6px; }
    .btn-hapus { padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; display:inline-flex; align-items:center; gap:6px; }
    .btn-detail svg, .btn-hapus svg { width: 14px; height: 14px; flex-shrink: 0; }

    .empty-state { text-align: center; padding: 52px 24px; }
    .empty-icon { width: 56px; height: 56px; margin: 0 auto 12px; color: #94a3b8; }
    .pagination-wrapper { padding: 16px 22px; border-top: 1px solid var(--border); }
</style>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.pengaduan.index') }}">
    <div class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Cari Judul</label>
            <input type="text" name="search" class="filter-control" placeholder="Cari..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-control">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Kategori</label>
            <select name="kategori" class="filter-control">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $item)
                    <option value="{{ $item }}" {{ request('kategori') == $item ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-filter">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16l-6 7v6l-4 1v-7L4 5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
            Filter
        </button>
        <a href="{{ route('admin.pengaduan.index') }}" class="btn-reset">Reset</a>
    </div>
</form>

<div class="table-card">
    <div class="table-header">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M8 6h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M8 10h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M8 14h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M9 3h6a2 2 0 0 1 2 2v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
        </svg>
        Daftar Pengaduan ({{ $pengaduan->total() }})
    </div>
    @if($pengaduan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Siswa</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengaduan as $index => $item)
                    @php $sc = strtolower(str_replace(' ', '', $item->status)); @endphp
                    <tr>
                        <td style="color:var(--text-muted);">{{ $pengaduan->firstItem() + $index }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td class="judul-cell" title="{{ $item->judul }}">{{ $item->judul }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td><span class="badge badge-{{ $sc }}">{{ $item->status }}</span></td>
                        <td style="color:var(--text-muted);">{{ $item->created_at->format('d M Y') }}</td>
                        <td style="display:flex; gap:6px; align-items:center;">
                            <a href="{{ route('admin.pengaduan.show', $item->id) }}" class="btn-detail">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M12 15a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                                Detail
                            </a>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.pengaduan.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-hapus js-open-delete" data-delete-form="delete-form-{{ $item->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 7h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M10 11v7M14 11v7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M6 7l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($pengaduan->hasPages())
            <div class="pagination-wrapper">{{ $pengaduan->appends(request()->query())->links() }}</div>
        @endif
    @else
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 8h16v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M4 8l8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 4h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <div style="color:var(--text-muted); font-size:14px;">Tidak ada pengaduan ditemukan.</div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const alertBox = document.getElementById('alertBox');
    if (alertBox) setTimeout(() => { alertBox.style.opacity='0'; alertBox.style.transition='opacity 0.3s'; setTimeout(()=>alertBox.remove(),300); }, 4000);
</script>
@endsection