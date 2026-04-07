@extends('admin.layout')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-sub', 'Kelola akun siswa')

@section('topbar-action')
    <a href="{{ route('admin.users.create') }}"
       style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px; background:var(--primary); color:white; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
        <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Tambah Siswa
    </a>
@endsection

@section('styles')
<style>
    .filter-bar { background: white; border-radius: 12px; padding: 18px 22px; border: 1px solid var(--border); margin-bottom: 20px; display: flex; gap: 12px; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 12px; font-weight: 600; color: var(--text-muted); }
    .filter-control { padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-family: inherit; color: var(--text); outline: none; min-width: 220px; }
    .filter-control:focus { border-color: var(--primary); }
    .btn-filter { padding: 9px 18px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; }
    .btn-reset { padding: 9px 14px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; text-decoration: none; }
    .table-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .table-header { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 700; color: var(--text); display:flex; align-items:center; gap:10px; }
    .table-header svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--primary-dark); }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 12px 18px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); background: #f8fafc; border-bottom: 1px solid var(--border); }
    tbody td { padding: 14px 18px; font-size: 14px; color: var(--text); border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 13px; flex-shrink: 0; }
    .user-name { font-weight: 600; font-size: 14px; }
    .user-username { font-size: 12px; color: var(--text-muted); }
    .badge-siswa { display: inline-block; padding: 4px 10px; background: rgba(79,70,229,0.1); color: var(--primary); border-radius: 20px; font-size: 11px; font-weight: 600; }
    .btn-edit { padding: 6px 14px; background: #f59e0b; color: white; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; display:inline-flex; align-items:center; gap:6px; }
    .btn-hapus { padding: 6px 14px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; display:inline-flex; align-items:center; gap:6px; }
    .btn-edit svg, .btn-hapus svg { width: 14px; height: 14px; flex-shrink: 0; }
    .empty-state { text-align: center; padding: 52px 24px; }
    .empty-icon { width: 56px; height: 56px; margin: 0 auto 12px; color: #94a3b8; }
    .pagination-wrapper { padding: 16px 22px; border-top: 1px solid var(--border); }
</style>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.users.index') }}">
    <div class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Cari Nama / Username</label>
            <input type="text" name="search" class="filter-control" placeholder="Cari siswa..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn-filter">🔍 Cari</button>
        <a href="{{ route('admin.users.index') }}" class="btn-reset">Reset</a>
    </div>
</form>

<div class="table-card">
    <div class="table-header">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M16 21a6 6 0 0 0-12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M10 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8"/>
            <path d="M17 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
            <path d="M22 21a5 5 0 0 0-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Daftar Siswa ({{ $users->total() }})
    </div>
    @if($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Siswa</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-username">{{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-siswa">Siswa</span></td>
                        <td style="color:var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit">
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0 0-3L16.5 4a2.12 2.12 0 0 0-3 0L3 14.5V20Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        <path d="M12.5 5.5 18.5 11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                    Edit
                                </a>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-hapus js-open-delete" data-delete-form="delete-form-{{ $user->id }}">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M4 7h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            <path d="M10 11v7M14 11v7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            <path d="M6 7l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                            <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($users->hasPages())
            <div class="pagination-wrapper">{{ $users->appends(request()->query())->links() }}</div>
        @endif
    @else
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M16 21a6 6 0 0 0-12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M10 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8"/>
                <path d="M17 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
                <path d="M22 21a5 5 0 0 0-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <div style="color:var(--text-muted); font-size:14px;">Belum ada siswa terdaftar.</div>
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