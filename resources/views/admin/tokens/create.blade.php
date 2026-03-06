@extends('layouts.admin')

@section('title', 'Buat Token Baru - Admin')

@section('admin.content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold">Buat Token Pemilih Baru</h1>
            <p class="text-blue-100 mt-2">Tambah token pemilih untuk siswa baru</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('admin.tokens.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            ← Kembali ke Daftar Token
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.tokens.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Input -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-900 mb-2">
                        Nama Siswa <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama" 
                        id="nama" 
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                        required
                        autofocus
                    >
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIS Input -->
                <div>
                    <label for="nis" class="block text-sm font-semibold text-slate-900 mb-2">
                        NIS (Nomor Induk Siswa) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nis" 
                        id="nis" 
                        value="{{ old('nis') }}"
                        placeholder="Contoh: 2024001"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nis') border-red-500 @enderror"
                        required
                    >
                    @error('nis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas Input -->
                <div>
                    <label for="kelas_id" class="block text-sm font-semibold text-slate-900 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="kelas_id"
                        id="kelas_id"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kelas_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Pilih kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-900">
                        <strong>ℹ️ Token otomatis dibuat:</strong> Token unik akan dibuat secara otomatis dengan format VOTE-XXXXXXXXXXXXXXXX
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        ✓ Buat Token
                    </button>
                    <a 
                        href="{{ route('admin.tokens.index') }}" 
                        class="flex-1 px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold rounded-lg transition-colors text-center"
                    >
                        ❌ Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick Create Info -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg border border-slate-200 p-4">
                <p class="text-sm font-semibold text-slate-900 mb-2">📝 Nama Siswa</p>
                <p class="text-sm text-slate-600">Masukkan nama lengkap siswa sesuai data di sekolah</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4">
                <p class="text-sm font-semibold text-slate-900 mb-2">🔢 NIS</p>
                <p class="text-sm text-slate-600">Masukkan nomor induk siswa (NIS) unik</p>
            </div>
        </div>
    </div>
</div>
@endsection
