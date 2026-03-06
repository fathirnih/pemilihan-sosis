@extends('layouts.admin')

@section('title', 'Tambah Kelas - Admin')

@section('admin.content')
<div class="min-h-screen bg-slate-50">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold">Tambah Kelas</h1>
            <p class="text-blue-100 mt-2">Buat data kelas baru</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            Kembali ke Daftar Kelas
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
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
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_kelas') border-red-500 @enderror"
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
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tingkat') border-red-500 @enderror"
                        required
                    >
                    @error('tingkat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        Simpan Kelas
                    </button>
                    <a href="{{ route('admin.kelas.index') }}" class="flex-1 px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold rounded-lg transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
