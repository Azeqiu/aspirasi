@extends('siswa.layout')

@section('title', 'Histori Pengaduan')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-title { font-size: 20px; font-weight: 700; color: var(--text); }
    .page-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

    .title-wrap { display: flex; align-items: center; gap: 10px; }
    .title-icon { width: 22px; height: 22px; flex-shrink: 0; color: var(--primary-dark); }
    .btn-icon { width: 16px; height: 16px; flex-shrink: 0; }

    .btn-new {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 18px; background: var(--primary); color: white;
        border-radius: 8px; font-size: 14px; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-new:hover { background: var(--primary-dark); }

    .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
    .stat-chip {
        background: white; border-radius: 10px; padding: 14px 16px;
        border: 1px solid var(--border); display: flex; align-items: center; gap: 10px;
    }
    .stat-dot { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-dot svg { width: 18px; height: 18px; }
    .stat-info span { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); }
    .stat-info strong { font-size: 20px; font-weight: 700; color: var(--text); }

    .list-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .list-header { padding: 14px 22px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 600; color: var(--text); }

    .pengaduan-item {
        padding: 16px 22px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 14px; transition: background 0.15s;
    }
    .pengaduan-item:last-child { border-bottom: none; }
    .pengaduan-item:hover { background: #f8fafc; }
    .item-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(16,185,129,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--primary-dark); }
    .item-icon svg { width: 20px; height: 20px; }
    .item-body { flex: 1; min-width: 0; }
    .item-title { font-size: 14px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px; }
    .item-meta { font-size: 12px; color: var(--text-muted); display: flex; gap: 10px; flex-wrap: wrap; }
    .meta { display: inline-flex; align-items: center; gap: 6px; }
    .meta svg { width: 14px; height: 14px; flex-shrink: 0; }

    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; flex-shrink: 0; }
    .badge-pending        { background: #fef3c7; color: #92400e; }
    .badge-sedangdiproses { background: #dbeafe; color: #1e40af; }
    .badge-selesai        { background: #d1fae5; color: #065f46; }
    .badge-ditolak        { background: #fee2e2; color: #991b1b; }

    .item-actions { display: flex; gap: 6px; align-items: center; flex-shrink: 0; }
    .btn-detail { padding: 5px 12px; background: var(--primary); color: white; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .btn-edit { padding: 5px 12px; background: #f59e0b; color: white; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .btn-hapus { padding: 5px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 6px; }
    .item-actions svg { width: 14px; height: 14px; }

    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state .empty-icon { width: 56px; height: 56px; margin: 0 auto 14px; color: #94a3b8; }
    .empty-state p { font-size: 14px; color: var(--text-muted); margin-bottom: 18px; }

    .pagination-wrapper { padding: 14px 22px; border-top: 1px solid var(--border); }

    /* Confirm modal (in-page) */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(15, 23, 42, 0.55);
        display: none; align-items: center; justify-content: center;
        padding: 18px;
        z-index: 2000;
    }
    .modal-backdrop.is-open { display: flex; }
    .modal-card {
        width: 100%; max-width: 420px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 20px 55px rgba(15, 23, 42, 0.25);
        overflow: hidden;
    }
    .modal-head { padding: 16px 18px; display: flex; gap: 12px; align-items: flex-start; }
    .modal-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: #fee2e2; color: #991b1b;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .modal-icon svg { width: 22px; height: 22px; }
    .modal-title { font-size: 15px; font-weight: 800; color: var(--text); margin: 1px 0 4px; }
    .modal-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; }
    .modal-actions { padding: 12px 18px 16px; display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border); }
    .btn-modal {
        appearance: none;
        border: 1px solid var(--border);
        background: white;
        color: var(--text);
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        font-family: inherit;
    }
    .btn-modal:hover { background: #f8fafc; }
    .btn-modal-danger { border-color: #ef4444; background: #ef4444; color: white; }
    .btn-modal-danger:hover { background: #dc2626; border-color: #dc2626; }
    .btn-modal:focus { outline: none; box-shadow: 0 0 0 3px rgba(16,185,129,0.25); }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">
            <span class="title-wrap">
                <svg class="title-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M8 6h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M8 10h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M8 14h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M9 3h6a2 2 0 0 1 2 2v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                Histori Pengaduan
            </span>
        </div>
        <div class="page-sub">Semua pengaduan yang pernah kamu kirimkan</div>
    </div>
    <a href="{{ route('siswa.pengaduan.create') }}" class="btn-new">
        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Buat Pengaduan
    </a>
</div>

<div class="stats-grid">
    <div class="stat-chip">
        <div class="stat-dot" style="background:#fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 3h10M7 21h10" stroke="#92400e" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M8 3v3.2c0 .6.3 1.1.8 1.4l2.7 1.6c.3.2.3.6 0 .8l-2.7 1.6c-.5.3-.8.8-.8 1.4V21" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 3v3.2c0 .6-.3 1.1-.8 1.4l-2.7 1.6c-.3.2-.3.6 0 .8l2.7 1.6c.5.3.8.8.8 1.4V21" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-info"><span>Pending</span><strong>{{ $pending }}</strong></div>
    </div>
    <div class="stat-chip">
        <div class="stat-dot" style="background:#dbeafe;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 12a8 8 0 0 1-14.9 3" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M4 12a8 8 0 0 1 14.9-3" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M18 4v5h-5" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 20v-5h5" stroke="#1e40af" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-info"><span>Diproses</span><strong>{{ $diproses }}</strong></div>
    </div>
    <div class="stat-chip">
        <div class="stat-dot" style="background:#d1fae5;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 6 9 17l-5-5" stroke="#065f46" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-info"><span>Selesai</span><strong>{{ $selesai }}</strong></div>
    </div>
    <div class="stat-chip">
        <div class="stat-dot" style="background:#fee2e2;">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 7l10 10M17 7 7 17" stroke="#991b1b" stroke-width="1.9" stroke-linecap="round"/>
                <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="#991b1b" stroke-width="1.8"/>
            </svg>
        </div>
        <div class="stat-info"><span>Ditolak</span><strong>{{ $ditolak }}</strong></div>
    </div>
</div>

<div class="list-card">
    <div class="list-header">Pengaduan Kamu ({{ $pengaduan->total() }})</div>
    @if($pengaduan->count() > 0)
        @foreach($pengaduan as $item)
            @php
                $sc = strtolower(str_replace(' ', '', $item->status));
                $canEdit = $item->status === 'Pending';
            @endphp
            <div class="pengaduan-item">
                <div class="item-icon" aria-hidden="true">
                    @switch($item->kategori)
                        @case('Kelas')
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M4 10h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M6 10V7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M5 10v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M8 14h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break
                        @case('Lab Praktek')
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M10 2v6l-5 9a3 3 0 0 0 2.6 4.5h8.8A3 3 0 0 0 19 17l-5-9V2" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M8 12h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break
                        @case('Sarana dan Prasarana')
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M3 20h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M6 20V9l6-4 6 4v11" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M10 20v-6h4v6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            </svg>
                            @break
                        @case('Guru')
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M4 20a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break
                        @default
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h7l4 4v13a1.5 1.5 0 0 1-1.5 1.5H8A3 3 0 0 1 5 19.5V6a3 3 0 0 1 3-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M15 3v5h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                    @endswitch
                </div>
                <div class="item-body">
                    <div class="item-title">{{ $item->judul }}</div>
                    <div class="item-meta">
                        <span class="meta">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 7h6l2 2h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            </svg>
                            {{ $item->kategori }}
                        </span>
                        <span class="meta">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 3v3M17 3v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M4 7h16v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M7 11h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            {{ $item->created_at->format('d M Y') }}
                        </span>
                        <span class="meta">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <span class="badge badge-{{ $sc }}">{{ $item->status }}</span>
                <div class="item-actions">
                    <a href="{{ route('siswa.pengaduan.show', $item->id) }}" class="btn-detail">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M12 15a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                        Detail
                    </a>
                    @if($canEdit)
                        <a href="{{ route('siswa.pengaduan.edit', $item->id) }}" class="btn-edit">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0 0-3L16.5 4a2.12 2.12 0 0 0-3 0L3 14.5V20Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M12.5 5.5 18.5 11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            Edit
                        </a>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('siswa.pengaduan.destroy', $item->id) }}" method="POST">
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
                    @endif
                </div>
            </div>
        @endforeach
        @if($pengaduan->hasPages())
            <div class="pagination-wrapper">{{ $pengaduan->links() }}</div>
        @endif
    @else
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 8h16v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M4 8l8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 4h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <p>Kamu belum pernah membuat pengaduan.</p>
            <a href="{{ route('siswa.pengaduan.create') }}" class="btn-new">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M8 3h7l4 4v13a1.5 1.5 0 0 1-1.5 1.5H8A3 3 0 0 1 5 19.5V6a3 3 0 0 1 3-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M15 3v5h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 11v6M9 14h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Buat Pengaduan
            </a>
        </div>
    @endif
</div>

<div id="confirmDeleteModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="confirmDeleteTitle" aria-describedby="confirmDeleteDesc">
        <div class="modal-head">
            <div class="modal-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 9v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 17h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    <path d="M10.3 4.6 3.4 17a2 2 0 0 0 1.7 3h13.8a2 2 0 0 0 1.7-3L13.7 4.6a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div id="confirmDeleteTitle" class="modal-title">Konfirmasi Hapus</div>
                <div id="confirmDeleteDesc" class="modal-desc">Yakin ingin menghapus pengaduan ini? Tindakan ini tidak bisa dibatalkan.</div>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-modal" data-modal-cancel>Batal</button>
            <button type="button" class="btn-modal btn-modal-danger" data-modal-confirm>Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('confirmDeleteModal');
        const btnCancel = modal?.querySelector('[data-modal-cancel]');
        const btnConfirm = modal?.querySelector('[data-modal-confirm]');
        let targetForm = null;
        let lastFocused = null;

        function openModal(form) {
            if (!modal) return;
            targetForm = form;
            lastFocused = document.activeElement;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            btnCancel?.focus();
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            targetForm = null;
            if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
        }

        document.addEventListener('click', function (e) {
            const trigger = e.target.closest?.('.js-open-delete');
            if (!trigger) return;
            e.preventDefault();
            const formId = trigger.getAttribute('data-delete-form');
            const form = formId ? document.getElementById(formId) : null;
            if (!form) return;
            openModal(form);
        });

        btnCancel?.addEventListener('click', function () { closeModal(); });
        btnConfirm?.addEventListener('click', function () {
            if (targetForm) targetForm.submit();
        });

        modal?.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (!modal || !modal.classList.contains('is-open')) return;
            if (e.key === 'Escape') closeModal();
        });
    })();
</script>
@endsection