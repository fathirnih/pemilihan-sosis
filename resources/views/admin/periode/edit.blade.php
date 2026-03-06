@extends('layouts.admin')

@section('title', 'Edit Periode - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Periode</h1>
            <p class="mt-1 text-slate-600">Perbarui data periode pemilihan.</p>
        </div>

        <a href="{{ route('admin.periode.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            ← Kembali ke daftar periode
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.periode.update', $periode->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nama Periode</label>
                    <input type="text" name="nama_periode" value="{{ old('nama_periode', $periode->nama_periode) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    @error('nama_periode')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Mulai</label>
                        <input type="datetime-local" name="mulai_pada" value="{{ old('mulai_pada', $periode->mulai_pada->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        @error('mulai_pada')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Selesai</label>
                        <input type="datetime-local" name="selesai_pada" value="{{ old('selesai_pada', $periode->selesai_pada->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        @error('selesai_pada')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
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
                        <select name="mode_pasangan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            <option value="ketua_wakil" @selected(old('mode_pasangan', $periode->mode_pasangan) === 'ketua_wakil')>Ketua & Wakil</option>
                            <option value="ketua_saja" @selected(old('mode_pasangan', $periode->mode_pasangan) === 'ketua_saja')>Ketua Saja</option>
                        </select>
                        @error('mode_pasangan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Simpan</button>
                    <a href="{{ route('admin.periode.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
