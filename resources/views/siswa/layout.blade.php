<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Pengaduan Siswa</title>
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
        .brand-logo {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; color: var(--white);
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        .brand-name { font-size: 15px; font-weight: 700; color: var(--text); }
        .brand-sub { font-size: 11px; color: var(--text-muted); }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .nav-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-item:hover { background: var(--bg); color: var(--primary); }
        .nav-item.active { background: rgba(16,185,129,0.12); color: var(--primary-dark); }

        .navbar-right { display: flex; align-items: center; gap: 10px; }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 10px;
            background: var(--bg);
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); word-break: break-word; }
        .user-role { font-size: 11px; color: var(--text-muted); }
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
            font-weight: 700;
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
        .alert {
            border-radius: 10px; padding: 13px 18px;
            margin-bottom: 20px; font-size: 14px;
            display: flex; justify-content: space-between; align-items: center;
            border: 1px solid;
        }
        .alert strong { font-weight: 700; }
        .alert-success { background: #d1fae5; border-color: #a7f3d0; color: #065f46; }
        .alert-error { background: #fee2e2; border-color: #fecaca; color: #991b1b; }
        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            font: inherit;
            padding: 0;
            line-height: 1;
            opacity: 0.8;
        }
        .alert-close:hover { opacity: 1; }

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
            <a class="navbar-brand" href="{{ route('siswa.dashboard') }}">
                <div class="brand-logo">PS</div>
                <div style="min-width:0;">
                    <div class="brand-name">Pengaduan</div>
                    <div class="brand-sub">Portal Siswa</div>
                </div>
            </a>

            <nav class="nav-links" aria-label="Menu">
                <a href="{{ route('siswa.dashboard') }}" class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 4h7v7H4V4Zm9 0h7v11h-7V4ZM4 13h7v7H4v-7Zm9 4h7v3h-7v-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('siswa.pengaduan.create') }}" class="nav-item {{ request()->routeIs('siswa.pengaduan.create') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M8 3h7l4 4v13a1.5 1.5 0 0 1-1.5 1.5H8A3 3 0 0 1 5 19.5V6a3 3 0 0 1 3-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M15 3v5h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 11v6M9 14h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Buat Pengaduan
                </a>
                <a href="{{ route('siswa.pengaduan.index') }}" class="nav-item {{ request()->routeIs('siswa.pengaduan.index') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 8a8 8 0 1 0 8 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 6v4h-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Histori Pengaduan
                </a>
            </nav>
        </div>

        <div class="navbar-right">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="min-width:0;">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Siswa</div>
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
@yield('scripts')
</body>
</html>