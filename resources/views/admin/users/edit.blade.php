@extends('admin.layout')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')
@section('page-sub', 'Perbarui akun siswa')

@section('styles')
<style>
    .form-card { background: white; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; max-width: 560px; }
    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 18px; }
    .field { display: flex; flex-direction: column; gap: 7px; }
    .field-label { font-size: 13px; font-weight: 600; color: var(--text); }
    .field-input { padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; color: var(--text); outline: none; transition: border-color 0.2s; }
    .field-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16,185,129,0.14); }
    .field-error { font-size: 12px; color: #ef4444; }
    .section-divider { padding: 14px 0 6px; border-top: 1px solid var(--border); font-size: 13px; font-weight: 700; color: var(--text-muted); display:flex; align-items:center; gap:8px; }
    .section-divider svg { width: 16px; height: 16px; flex-shrink: 0; }
    .form-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; gap: 10px; }
    .btn-save { padding: 10px 24px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; font-family: inherit; cursor: pointer; display:inline-flex; align-items:center; gap:8px; }
    .btn-save svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-cancel { padding: 10px 20px; background: white; color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; text-decoration: none; }
</style>
@endsection

@section('content')
<div class="form-card">
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="form-body">
            <div class="field">
                <label class="field-label">Nama Lengkap</label>
                <input type="text" name="name" class="field-input" value="{{ old('name', $user->name) }}">
                @error('name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Username</label>
                <input type="text" name="username" class="field-input" value="{{ old('username', $user->username) }}">
                @error('username') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Email</label>
                <input type="email" name="email" class="field-input" value="{{ old('email', $user->email) }}" placeholder="email@siswa.com">
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="section-divider">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M6 11h12v10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                Ganti Password (kosongkan jika tidak ingin mengubah)
            </div>
            <div class="field">
                <label class="field-label">Password Baru</label>
                <input type="password" name="password" class="field-input" placeholder="Minimal 6 karakter">
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="field-input" placeholder="Ulangi password baru">
            </div>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn-save">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 21h14a2 2 0 0 0 2-2V8l-3-3H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M8 21v-7h8v7" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M9 5v4h6V5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection