<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Pengaduan Siswa</title>
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
            --red: #ef4444;
        }
        html, body { height: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 420px;
            padding: 40px 36px;
        }
        .brand {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-icon {
            width: 52px; height: 52px;
            background: var(--primary);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            margin: 0 auto 14px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.5px;
        }
        .brand-sub {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .error-box {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 13px;
            color: #991b1b;
            margin-bottom: 20px;
        }
        .field { margin-bottom: 18px; }
        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 7px;
        }
        .field-input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.14);
        }
        .field-input.is-invalid { border-color: var(--red); }
        .field-error { font-size: 12px; color: var(--red); margin-top: 5px; }
        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 22px;
        }
        .remember-row input { accent-color: var(--primary); cursor: pointer; }
        .remember-row label { font-size: 13px; color: var(--text-muted); cursor: pointer; }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-icon">📋</div>
        <div class="brand-title">Pengaduan Siswa</div>
        <div class="brand-sub">Masuk ke akun kamu</div>
    </div>

    @php
        $alertMessage = session('error') ?: ($errors->any() ? $errors->first() : null);
    @endphp
    @if($alertMessage)
        <div class="error-box">{{ $alertMessage }}</div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="field">
            <label class="field-label">Username</label>
            <input type="text" name="username"
                   class="field-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                   placeholder="Masukkan username"
                   value="{{ old('username') }}"
                   autocomplete="username">
        </div>
        <div class="field">
            <label class="field-label">Password</label>
            <input type="password" name="password"
                   class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Masukkan password"
                   autocomplete="current-password">
        </div>
    
        <button type="submit" class="btn-submit">Masuk →</button>
    </form>
</div>
</body>
</html>