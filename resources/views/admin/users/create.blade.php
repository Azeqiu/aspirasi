@extends('admin.layout')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')
@section('page-sub', 'Buat akun siswa baru')

@section('styles')
<style>
    .form-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; max-width: 560px; }
    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 18px; }
    .field { display: flex; flex-direction: column; gap: 7px; }
    .field-label { font-size: 13px; font-weight: 600; color: var(--text); }
    .field-input { padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; color: var(--text); outline: none; transition: border-color 0.2s; }
    .field-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16,185,129,0.14); }
    .field-error { font-size: 12px; color: #ef4444; }
    .form-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; gap: 10px; }
    .btn-save { padding: 10px 24px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; font-family: inherit; cursor: pointer; display:inline-flex; align-items:center; gap:8px; }
    .btn-save svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-cancel { padding: 10px 20px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; text-decoration: none; }
</style>
@endsection

@section('content')
<div class="form-card">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="form-body">
            <div class="field">
                <label class="field-label">Nama Lengkap</label>
                <input type="text" name="name" class="field-input" value="{{ old('name') }}" placeholder="Nama lengkap siswa">
                @error('name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Username</label>
                <input type="text" name="username" class="field-input" value="{{ old('username') }}" placeholder="Username untuk login">
                @error('username') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Email</label>
                <input type="email" name="email" class="field-input" value="{{ old('email') }}" placeholder="email@siswa.com">
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Password</label>
                <input type="password" name="password" class="field-input" placeholder="Minimal 6 karakter">
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="field-input" placeholder="Ulangi password">
            </div>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn-save">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Tambah Siswa
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection