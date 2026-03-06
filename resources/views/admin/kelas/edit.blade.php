@extends('layouts.admin')

@section('title', 'Edit Kelas - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Kelas</h1>
            <p class="mt-1 text-slate-600">Perbarui data kelas.</p>
        </div>

        <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            <- Kembali ke daftar kelas
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_kelas" class="block text-sm font-semibold text-slate-900 mb-2">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_kelas"
                        id="nama_kelas"
                        value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                        placeholder="Contoh: 12 IPA 1"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('nama_kelas') border-red-500 @enderror"
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
                        value="{{ old('tingkat', $kelas->tingkat) }}"
                        min="1"
                        max="12"
                        placeholder="Contoh: 12"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('tingkat') border-red-500 @enderror"
                        required
                    >
                    @error('tingkat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Simpan
                    </button>
                    <a href="{{ route('admin.kelas.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
