@extends('layouts.admin')

@section('title', 'Generate Token - Admin')

@section('admin.content')
<div class="min-h-screen bg-slate-50">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold">Generate Token</h1>
            <p class="text-blue-100 mt-2">Buat token baru untuk pemilih</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800"><strong>Error:</strong> {{ $errors->first() }}</p>
                </div>
            @endif

            @if (!$periode)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">Tidak ada periode pemilihan yang aktif. Silahkan buat periode terlebih dahulu.</p>
                </div>
            @else
                <form action="{{ route('admin.generate-token') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-900"><strong>Periode Aktif:</strong> {{ $periode->nama_periode }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Token</label>
                        <input
                            type="number"
                            name="jumlah"
                            required
                            min="1"
                            max="100"
                            value="{{ old('jumlah', 10) }}"
                            class="@apply w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                            placeholder="Masukkan jumlah token (1-100)"
                        >
                        <p class="text-xs text-slate-600 mt-1">Masukkan jumlah token yang ingin di-generate (maksimal 100)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Tipe Pemilih</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="tipe_pemilih" value="siswa" {{ old('tipe_pemilih', 'siswa') === 'siswa' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                <span class="ml-3 text-slate-700">Siswa</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tipe_pemilih" value="guru" {{ old('tipe_pemilih') === 'guru' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                                <span class="ml-3 text-slate-700">Guru</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="kelas_id" class="block text-sm font-semibold text-slate-700 mb-2">Kelas (untuk siswa)</label>
                        <select
                            name="kelas_id"
                            id="kelas_id"
                            class="@apply w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all @error('kelas_id') border-red-500 @enderror"
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

                    <button
                        type="submit"
                        class="@apply w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors"
                    >
                        Generate Tokens
                    </button>
                </form>
            @endif
        </div>

        <div class="mt-6 text-slate-600 text-sm space-y-2">
            <p><strong>Info:</strong></p>
            <p>- Token yang di-generate akan memiliki status "aktif"</p>
            <p>- Setiap pemilih akan mendapatkan satu token unik</p>
            <p>- Token dapat digunakan hanya satu kali untuk memilih</p>
        </div>
    </div>
</div>

<script>
    const tipeInputs = document.querySelectorAll('input[name="tipe_pemilih"]');
    const kelasSelect = document.getElementById('kelas_id');

    function syncKelasState() {
        const selected = document.querySelector('input[name="tipe_pemilih"]:checked');
        const isSiswa = selected && selected.value === 'siswa';
        kelasSelect.disabled = !isSiswa;
        kelasSelect.required = isSiswa;
        if (!isSiswa) {
            kelasSelect.value = '';
        }
    }

    tipeInputs.forEach((input) => input.addEventListener('change', syncKelasState));
    syncKelasState();
</script>
@endsection
