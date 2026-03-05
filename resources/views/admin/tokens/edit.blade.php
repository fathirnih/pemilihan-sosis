@extends('layouts.app')

@section('title', 'Edit Token - Admin')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold">Edit Token Pemilih</h1>
            <p class="text-blue-100 mt-2">Perbarui data token pemilih</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('admin.tokens.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            ← Kembali ke Daftar Token
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.tokens.update', $token->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Token Info (Read-only) -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <p class="text-sm text-slate-600 mb-2">🔐 Kode Token (tidak dapat diubah)</p>
                    <div class="flex items-center gap-2">
                        <code class="bg-white px-4 py-2 rounded border border-slate-300 text-sm font-mono flex-1">
                            {{ $token->token }}
                        </code>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $token->token }}')" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 rounded transition-colors text-sm">
                            📋 Salin
                        </button>
                    </div>
                </div>

                <!-- Nama Input -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-900 mb-2">
                        Nama Siswa <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama" 
                        id="nama" 
                        value="{{ old('nama', $token->nama_pemilih) }}"
                        placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                        required
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
                        value="{{ old('nis', $token->nis_pemilih) }}"
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
                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $token->kelas_id ?? $token->siswa?->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-slate-600 mb-2">📊 Status Token</p>
                    <div class="flex gap-4">
                        <div>
                            <p class="text-xs text-slate-600">Status</p>
                            <p class="font-semibold text-blue-900">{{ ucfirst($token->status) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600">Sudah Memilih</p>
                            <p class="font-semibold {{ $token->sudah_memilih ? 'text-green-600' : 'text-slate-600' }}">
                                {{ $token->sudah_memilih ? '✓ Ya' : '✗ Belum' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600">Dibuat</p>
                            <p class="font-semibold text-slate-900">{{ $token->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        💾 Simpan Perubahan
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

        <!-- Help Text -->
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
            <p class="text-sm text-amber-900">
                <strong>ℹ️ Catatan:</strong> Anda hanya dapat mengubah nama dan NIS. Kode token tidak dapat diubah untuk keamanan.
            </p>
        </div>
    </div>
</div>
@endsection
