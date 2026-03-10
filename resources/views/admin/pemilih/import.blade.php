@extends('layouts.admin')

@section('title', 'Import Pemilih - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-form-container">
        <div class="mb-6">
            <h1 class="admin-title">Import Pemilih</h1>
            <p class="admin-subtitle">Impor data pemilih dari file Excel atau CSV.</p>
        </div>

        <a href="{{ route('admin.pemilih.index') }}" class="admin-back-link">
            &larr; Kembali ke daftar pemilih
        </a>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>

            @if (session('import_errors', []) && count(session('import_errors', [])) > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-yellow-800 font-semibold mb-2">Peringatan:</p>
                    <ul class="text-yellow-700 text-sm space-y-1">
                        @foreach (session('import_errors', []) as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.pemilih.import-data') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="periode_pemilihan_id" class="block text-sm font-semibold text-slate-900 mb-2">
                        Periode Pemilihan <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="periode_pemilihan_id"
                        id="periode_pemilihan_id"
                        class="admin-select w-full @error('periode_pemilihan_id') border-red-500 @enderror"
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
                        class="admin-input @error('file') border-red-500 @enderror"
                        required
                    >
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-slate-500 text-xs mt-2">Format file yang didukung: .xlsx, .csv</p>
                </div>

                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <p class="mb-2 text-sm font-semibold text-blue-900">Format File:</p>
                    <p class="mb-3 text-sm text-blue-800">File harus memiliki kolom header di baris pertama:</p>
                    <ul class="ml-4 space-y-1 text-sm text-blue-800">
                        <li>- <strong>NISN</strong> (wajib)</li>
                        <li>- <strong>Nama</strong> (wajib)</li>
                        <li>- <strong>Tingkat</strong> (1, 2, 3 untuk siswa, kosong untuk guru)</li>
                        <li>- <strong>Nama Kelas</strong> (contoh: 12 IPA 1, 11 RPL 2)</li>
                    </ul>
                </div>

                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <p class="mb-2 text-sm font-semibold text-amber-900">Catatan:</p>
                    <ul class="ml-4 space-y-1 text-sm text-amber-800">
                        <li>- Jenis pemilih otomatis ditentukan (siswa jika ada tingkat, guru jika kosong).</li>
                        <li>- Duplikat NISN di periode yang sama akan dilewati.</li>
                        <li>- Nama kelas dan tingkat harus sesuai data kelas yang sudah ada.</li>
                    </ul>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="flex-1 admin-btn admin-btn-primary justify-center">Import</button>
                    <a href="{{ route('admin.pemilih.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
