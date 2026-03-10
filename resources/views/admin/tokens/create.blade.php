@extends('layouts.admin')

@section('title', 'Tambah Pemilih - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-form-container">
        <div class="mb-6">
            <h1 class="admin-title">Tambah Pemilih</h1>
            <p class="admin-subtitle">Isi data pemilih (siswa atau guru).</p>
        </div>

        <a href="{{ route('admin.pemilih.index') }}" class="admin-back-link">
            &larr; Kembali ke daftar pemilih
        </a>

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.pemilih.store') }}" method="POST" class="space-y-6" id="pemilih-form">
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
                        autofocus
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
                    <label for="nama" class="block text-sm font-semibold text-slate-900 mb-2">
                        Nama Pemilih <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Budi Santoso"
                        class="admin-input @error('nama') border-red-500 @enderror"
                        required
                    >
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nis" class="block text-sm font-semibold text-slate-900 mb-2">
                        NISN/NIP <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nis"
                        id="nis"
                        value="{{ old('nis') }}"
                        placeholder="Contoh: 2024001 / NIP000001"
                        class="admin-input @error('nis') border-red-500 @enderror"
                        required
                    >
                    @error('nis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis" class="block text-sm font-semibold text-slate-900 mb-2">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="jenis"
                        id="jenis"
                        class="admin-select w-full @error('jenis') border-red-500 @enderror"
                        required
                    >
                        <option value="siswa" {{ old('jenis', 'siswa') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ old('jenis') === 'guru' ? 'selected' : '' }}>Guru</option>
                    </select>
                    @error('jenis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-semibold text-slate-900 mb-2">
                        Kelas <span class="text-red-500" id="kelas-required">*</span>
                    </label>
                    <select
                        name="kelas_id"
                        id="kelas_id"
                        class="admin-select w-full @error('kelas_id') border-red-500 @enderror"
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

                <div class="admin-form-actions">
                    <button type="submit" class="flex-1 admin-btn admin-btn-primary justify-center">Simpan</button>
                    <a href="{{ route('admin.pemilih.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const jenisSelect = document.getElementById('jenis');
    const kelasSelect = document.getElementById('kelas_id');
    const kelasRequired = document.getElementById('kelas-required');

    function toggleKelas() {
        const isSiswa = jenisSelect.value === 'siswa';
        kelasSelect.disabled = !isSiswa;
        kelasSelect.required = isSiswa;
        kelasRequired.style.display = isSiswa ? 'inline' : 'none';
        if (!isSiswa) {
            kelasSelect.value = '';
        }
    }

    jenisSelect.addEventListener('change', toggleKelas);
    toggleKelas();
</script>
@endsection
