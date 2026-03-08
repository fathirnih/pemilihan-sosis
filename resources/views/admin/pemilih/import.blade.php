@extends('layouts.admin')

@section('title', 'Import Pemilih - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Import Pemilih</h2>
            <p class="mt-1 text-slate-600">Impor data pemilih dari file Excel atau CSV</p>
        </div>

        <a href="{{ route('admin.pemilih.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            ← Kembali ke daftar pemilih
        </a>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>

            @if (session('errors', []) && count(session('errors', [])) > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-yellow-800 font-semibold mb-2">Peringatan:</p>
                    <ul class="text-yellow-700 text-sm space-y-1">
                        @foreach (session('errors', []) as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.pemilih.import-data') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="periode_pemilihan_id" class="block text-sm font-semibold text-slate-900 mb-2">
                        Periode Pemilihan <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="periode_pemilihan_id"
                        id="periode_pemilihan_id"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('periode_pemilihan_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Pilih periode</option>
                        @foreach ($periodeList as $periode)
                            <option value="{{ $periode->id }}" {{ old('periode_pemilihan_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode_pemilihan_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="file" class="block text-sm font-semibold text-slate-900 mb-2">
                        File (Excel .xlsx atau CSV) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="file"
                        name="file"
                        id="file"
                        accept=".xlsx,.csv"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('file') border-red-500 @enderror"
                        required
                    >
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-slate-500 text-xs mt-2">Format file yang didukung: .xlsx, .csv</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-900 text-sm font-semibold mb-2">Format File:</p>
                    <p class="text-blue-800 text-sm mb-3">File harus memiliki kolom berikut (header di baris pertama):</p>
                    <ul class="text-blue-800 text-sm space-y-1 ml-4">
                        <li>• <strong>NISN</strong> - Nomor identitas (wajib)</li>
                        <li>• <strong>Nama</strong> - Nama lengkap (wajib)</li>
                        <li>• <strong>Tingkat</strong> - Tingkat kelas (1, 2, 3 untuk siswa, kosong untuk guru)</li>
                        <li>• <strong>Nama Kelas</strong> - Nama kelas (misal:  IPA 1,  RPL 2)</li>
                    </ul>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-amber-900 text-sm font-semibold mb-2">Catatan:</p>
                    <ul class="text-amber-800 text-sm space-y-1 ml-4">
                        <li>• Jenis pemilih otomatis ditentukan (siswa jika ada tingkat, guru jika kosong)</li>
                        <li>• Duplikat NISN dalam periode yang sama akan di-skip</li>
                        <li>• Nama kelas harus sesuai dengan data kelas yang sudah ada</li>
                    </ul>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Import
                    </button>
                    <a href="{{ route('admin.pemilih.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
