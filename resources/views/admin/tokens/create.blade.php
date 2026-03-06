@extends('layouts.admin')

@section('title', 'Tambah Pemilih - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Tambah Pemilih</h2>
            <p class="mt-1 text-slate-600">Isi data pemilih (siswa atau guru).</p>
        </div>

        <a href="{{ route('admin.pemilih.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            <- Kembali ke daftar pemilih
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.pemilih.store') }}" method="POST" class="space-y-6" id="pemilih-form">
                @csrf

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
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('nama') border-red-500 @enderror"
                        required
                        autofocus
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
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('nis') border-red-500 @enderror"
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
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('jenis') border-red-500 @enderror"
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
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('kelas_id') border-red-500 @enderror"
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

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Simpan
                    </button>
                    <a href="{{ route('admin.pemilih.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">
                        Batal
                    </a>
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
