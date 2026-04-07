<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --bg: #f1f5f9;
            --white: #ffffff;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }
        .navbar-left { display: flex; align-items: center; gap: 16px; min-width: 0; }
        .navbar-brand { display: flex; align-items: center; gap: 10px; min-width: 0; text-decoration: none; }
        .brand-icon {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; color: var(--white);
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        .brand-name { font-size: 15px; font-weight: 800; color: var(--text); }
        .brand-sub { font-size: 11px; color: var(--text-muted); }

        .nav-links { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .nav-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-item:hover { background: var(--bg); color: var(--primary); }
        .nav-item.active { background: rgba(16,185,129,0.12); color: var(--primary-dark); }

        .navbar-right { display: flex; align-items: center; gap: 10px; }
        .admin-card {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 10px;
            background: var(--bg);
        }
        .admin-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 13px; flex-shrink: 0;
        }
        .admin-name { font-size: 13px; font-weight: 700; color: var(--text); word-break: break-word; }
        .admin-role { font-size: 11px; color: var(--text-muted); }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            background: rgba(239,68,68,0.08);
            color: #dc2626;
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .logout-btn svg { width: 16px; height: 16px; flex-shrink: 0; }
        .logout-btn:hover { background: rgba(239,68,68,0.15); }

        .main {
            min-height: 100vh;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 18px 32px;
        }
        .topbar {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .page-title { font-size: 20px; font-weight: 700; color: var(--text); }
        .page-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
        .alert {
            border-radius: 10px; padding: 13px 18px;
            margin-bottom: 20px; font-size: 14px;
            display: flex; justify-content: space-between; align-items: center;
            border: 1px solid;
        }
        .alert strong { font-weight: 700; }
        .alert-success { background: #d1fae5; border-color: #a7f3d0; color: #065f46; }
        .alert-error { background: #fee2e2; border-color: #fecaca; color: #991b1b; }
        .alert-close { background: none; border: none; cursor: pointer; color: inherit; font: inherit; padding: 0; line-height: 1; opacity: 0.8; }
        .alert-close:hover { opacity: 1; }

        .modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.55);
            display: none; align-items: center; justify-content: center;
            padding: 18px; z-index: 2000;
        }
        .modal-backdrop.is-open { display: flex; }
        .modal-card {
            width: 100%; max-width: 420px;
            background: white; border: 1px solid var(--border);
            border-radius: 14px; box-shadow: 0 20px 55px rgba(15,23,42,0.25); overflow: hidden;
        }
        .modal-head { padding: 16px 18px; display: flex; gap: 12px; align-items: flex-start; }
        .modal-icon { width: 42px; height: 42px; border-radius: 12px; background: #fee2e2; color: #991b1b; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .modal-icon svg { width: 22px; height: 22px; }
        .modal-title { font-size: 15px; font-weight: 800; color: var(--text); margin: 1px 0 4px; }
        .modal-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; }
        .modal-actions { padding: 12px 18px 16px; display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border); }
        .btn-modal { appearance: none; border: 1px solid var(--border); background: white; color: var(--text); border-radius: 10px; padding: 10px 14px; font-weight: 800; font-size: 13px; cursor: pointer; font-family: inherit; }
        .btn-modal:hover { background: #f8fafc; }
        .btn-modal-danger { border-color: #ef4444; background: #ef4444; color: white; }
        .btn-modal-danger:hover { background: #dc2626; border-color: #dc2626; }

        @media (max-width: 720px) {
            .navbar-inner { align-items: flex-start; flex-direction: column; }
            .navbar-right { width: 100%; justify-content: space-between; }
            .nav-links { width: 100%; }
        }
    </style>
    @yield('styles')
</head>
<body>
<header class="navbar">
    <div class="navbar-inner">
        <div class="navbar-left">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <div class="brand-icon">AP</div>
                <div style="min-width:0;">
                    <div class="brand-name">Admin Panel</div>
                    <div class="brand-sub">Manajemen Pengaduan</div>
                </div>
            </a>

            <nav class="nav-links" aria-label="Menu">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 4h7v7H4V4Zm9 0h7v11h-7V4ZM4 13h7v7H4v-7Zm9 4h7v3h-7v-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.pengaduan.index') }}" class="nav-item {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M8 6h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 10h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 14h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M9 3h6a2 2 0 0 1 2 2v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                    Semua Pengaduan
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M16 21a6 6 0 0 0-12 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M10 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M17 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M22 21a5 5 0 0 0-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Manajemen User
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 6h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M4 10h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M4 14h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M4 18h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M17 13l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kategori
                </a>
            </nav>
        </div>

        <div class="navbar-right">
            <div class="admin-card">
                <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="min-width:0;">
                    <div class="admin-name">{{ auth()->user()->name }}</div>
                    <div class="admin-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M10 7V6a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M6 9l-3 3 3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

<main class="main">
    <div class="topbar">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="page-sub">@yield('page-sub', '')</div>
        </div>
        @yield('topbar-action')
    </div>

    @if(session('success'))
        <div class="alert alert-success" id="alertBox">
            <span><strong>Berhasil:</strong> {{ session('success') }}</span>
            <button class="alert-close" type="button" onclick="document.getElementById('alertBox').remove()">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" id="alertBoxErr">
            <span><strong>Gagal:</strong> {{ session('error') }}</span>
            <button class="alert-close" type="button" onclick="document.getElementById('alertBoxErr').remove()">✕</button>
        </div>
    @endif

    @yield('content')
</main>

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
                <div id="confirmDeleteDesc" class="modal-desc">Yakin ingin menghapus ini? Tindakan ini tidak bisa dibatalkan.</div>
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

    setTimeout(() => {
        document.getElementById('alertBox')?.remove();
        document.getElementById('alertBoxErr')?.remove();
    }, 4000);
</script>

@yield('scripts')
</body>
</html>