@extends('layouts.admin')

@section('title', 'Tambah Kandidat - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="admin-title">Tambah Kandidat</h1>
            <p class="admin-subtitle">Buat kandidat untuk periode tertentu.</p>
        </div>

        <a href="{{ route('admin.kandidat.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            ? Kembali ke daftar kandidat
        </a>

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.kandidat.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Periode</label>
                    <select name="periode_id" class="admin-select w-full" required>
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected(old('periode_id') == $p->id)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nomor Urut</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut') }}" min="1" class="admin-input" required>
                    @error('nomor_urut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Ketua</label>
                    <select name="ketua_id" class="admin-select w-full" required>
                        <option value="">Pilih ketua</option>
                        @foreach ($pemilih as $p)
                            <option value="{{ $p->id }}" @selected(old('ketua_id') == $p->id)>{{ $p->nama }} ({{ $p->nisn }})</option>
                        @endforeach
                    </select>
                    @error('ketua_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Wakil (Opsional)</label>
                    <select name="wakil_id" class="admin-select w-full">
                        <option value="">Tidak ada</option>
                        @foreach ($pemilih as $p)
                            <option value="{{ $p->id }}" @selected(old('wakil_id') == $p->id)>{{ $p->nama }} ({{ $p->nisn }})</option>
                        @endforeach
                    </select>
                    @error('wakil_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Visi</label>
                    <textarea name="visi" rows="3" class="admin-input" required>{{ old('visi') }}</textarea>
                    @error('visi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Misi</label>
                    <textarea name="misi" rows="4" class="admin-input" required>{{ old('misi') }}</textarea>
                    @error('misi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Foto Pasangan (Opsional)</label>
                        <input type="file" name="foto" accept="image/png,image/jpeg" class="admin-input">
                        <p class="text-xs text-slate-500 mt-2">Format: JPG/PNG. Maks 2MB.</p>
                        @error('foto')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Foto Ketua (Opsional)</label>
                        <input type="file" name="foto_ketua" accept="image/png,image/jpeg" class="admin-input">
                        @error('foto_ketua')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Foto Wakil (Opsional)</label>
                    <input type="file" name="foto_wakil" accept="image/png,image/jpeg" class="admin-input">
                    @error('foto_wakil')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                    <a href="{{ route('admin.kandidat.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
