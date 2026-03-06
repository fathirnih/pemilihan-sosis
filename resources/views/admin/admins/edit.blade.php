@extends('layouts.admin')

@section('title', 'Edit Admin - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="admin-title">Edit Admin</h1>
            <p class="admin-subtitle">Perbarui data akun admin.</p>
        </div>

        <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            <- Kembali ke daftar admin
        </a>

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}" class="admin-input" required>
                    @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $admin->username) }}" class="admin-input" required>
                    @error('username')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="admin-input" required>
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Password (Opsional)</label>
                    <input type="password" name="password" class="admin-input" placeholder="Kosongkan jika tidak diubah">
                    @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="aktif" value="1" class="h-4 w-4" {{ old('aktif', $admin->aktif) ? 'checked' : '' }}>
                    <span class="text-sm text-slate-700">Aktif</span>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 admin-btn admin-btn-primary justify-center">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <path d="M17 21v-8H7v8"></path>
                            <path d="M7 3v5h8"></path>
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.admins.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
