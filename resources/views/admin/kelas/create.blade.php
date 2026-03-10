@extends('layouts.admin')

@section('title', 'Tambah Kelas - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-form-container">
        <div class="mb-6">
            <h1 class="admin-title">Tambah Kelas</h1>
            <p class="admin-subtitle">Buat data kelas baru.</p>
        </div>

        <a href="{{ route('admin.kelas.index') }}" class="admin-back-link">
            &larr; Kembali ke daftar kelas
        </a>

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="nama_kelas" class="block text-sm font-semibold text-slate-900 mb-2">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_kelas"
                        id="nama_kelas"
                        value="{{ old('nama_kelas') }}"
                        placeholder="Contoh: 12 IPA 1"
                        class="admin-input @error('nama_kelas') border-red-500 @enderror"
                        required
                        autofocus
                    >
                    @error('nama_kelas')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tingkat" class="block text-sm font-semibold text-slate-900 mb-2">
                        Tingkat <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="tingkat"
                        id="tingkat"
                        value="{{ old('tingkat') }}"
                        min="1"
                        max="12"
                        placeholder="Contoh: 12"
                        class="admin-input @error('tingkat') border-red-500 @enderror"
                        required
                    >
                    @error('tingkat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="flex-1 admin-btn admin-btn-primary justify-center">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <path d="M17 21v-8H7v8"></path>
                            <path d="M7 3v5h8"></path>
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.kelas.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
