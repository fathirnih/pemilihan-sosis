@extends('layouts.admin')

@section('title', 'Edit Periode - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-form-container">
        <div class="mb-6">
            <h1 class="admin-title">Edit Periode</h1>
            <p class="admin-subtitle">Perbarui data periode pemilihan.</p>
        </div>

        <a href="{{ route('admin.periode.index') }}" class="admin-back-link">
            &larr; Kembali ke daftar periode
        </a>

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.periode.update', $periode->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nama Periode</label>
                    <input type="text" name="nama_periode" value="{{ old('nama_periode', $periode->nama_periode) }}" class="admin-input" required>
                    @error('nama_periode')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Mulai</label>
                        <input type="datetime-local" name="mulai_pada" value="{{ old('mulai_pada', $periode->mulai_pada->format('Y-m-d\\TH:i')) }}" class="admin-input" required>
                        @error('mulai_pada')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Selesai</label>
                        <input type="datetime-local" name="selesai_pada" value="{{ old('selesai_pada', $periode->selesai_pada->format('Y-m-d\\TH:i')) }}" class="admin-input" required>
                        @error('selesai_pada')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Status</label>
                        <select name="status" class="admin-select w-full">
                            <option value="draf" @selected(old('status', $periode->status) === 'draf')>Draf</option>
                            <option value="aktif" @selected(old('status', $periode->status) === 'aktif')>Aktif</option>
                            <option value="ditutup" @selected(old('status', $periode->status) === 'ditutup')>Ditutup</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Mode Pasangan</label>
                        <select name="mode_pasangan" class="admin-select w-full">
                            <option value="ketua_wakil" @selected(old('mode_pasangan', $periode->mode_pasangan) === 'ketua_wakil')>Ketua & Wakil</option>
                            <option value="ketua_saja" @selected(old('mode_pasangan', $periode->mode_pasangan) === 'ketua_saja')>Ketua Saja</option>
                        </select>
                        @error('mode_pasangan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="flex-1 admin-btn admin-btn-primary justify-center">Simpan</button>
                    <a href="{{ route('admin.periode.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
