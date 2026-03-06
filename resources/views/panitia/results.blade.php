@extends('layouts.app')

@section('title', 'Hasil Pemilihan - Panitia')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Hasil Pemilihan OSIS</h1>
                    <p class="text-purple-100">Selamat datang, {{ Session::get('panitia_nama') }} (Panitia)</p>
                </div>
                <form action="{{ route('panitia.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 px-6 py-2 rounded-lg font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Auto Refresh Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-900 text-sm">🔄 Halaman ini akan auto-refresh setiap 5 detik untuk menampilkan hasil terbaru</p>
        </div>

        <!-- Statistics -->
        @if ($totalSuara > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <p class="text-slate-600 text-sm font-medium mb-2">Total Suara Masuk</p>
                    <p class="text-4xl font-bold text-slate-900">{{ $totalSuara }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <p class="text-slate-600 text-sm font-medium mb-2">Periode Pemilihan</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $periode->nama_periode }}</p>
                </div>
            </div>
        @endif

        <!-- Periode Info -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Informasi Periode</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-slate-600 text-sm mb-1">Nama Periode</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $periode->nama_periode }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm mb-1">Status</p>
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">{{ ucfirst($periode->status) }}</span>
                </div>
                <div>
                    <p class="text-slate-600 text-sm mb-1">Total Suara</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $totalSuara }}</p>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Hasil Perolehan Suara - <span class="text-sm text-slate-600">{{ now()->format('d M Y H:i') }}</span></h2>
            
            @if ($kandidats->count() > 0)
                <div class="space-y-6">
                    @foreach ($kandidats->sortByDesc(fn($k) => $k->suara->count()) as $kandidat)
                        <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold text-sm">Nomor {{ $kandidat->nomor_urut }}</span>
                                    </div>
                                    <p class="font-semibold text-slate-900 text-lg mb-2">Visi: {{ $kandidat->visi }}</p>
                                    <p class="text-slate-600 text-sm">Misi: {{ $kandidat->misi }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-4xl font-bold text-blue-600">{{ $kandidat->suara->count() }}</p>
                                    <p class="text-sm text-slate-600">suara</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="bg-slate-200 rounded-full h-3 overflow-hidden">
                                    <div 
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 transition-all duration-500"
                                        style="width: {{ $totalSuara > 0 ? ($kandidat->suara->count() / $totalSuara * 100) : 0 }}%"
                                    ></div>
                                </div>
                            </div>

                            <!-- Percentage -->
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $totalSuara > 0 ? round($kandidat->suara->count() / $totalSuara * 100, 1) : 0 }}% 
                                <span class="text-slate-500 font-normal">(dari {{ $totalSuara }} suara)</span>
                            </p>

                            <!-- Anggota -->
                            @if ($kandidat->anggota->count() > 0)
                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <p class="text-xs text-slate-600 font-medium mb-2">Tim:</p>
                                    <div class="space-y-1">
                                        @foreach ($kandidat->anggota as $anggota)
                                            <p class="text-sm text-slate-700">• {{ ucfirst($anggota->peran) }}: {{ $anggota->pemilih->nama ?? 'N/A' }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Info -->
                <div class="mt-8 pt-6 border-t border-slate-200 text-sm text-slate-600">
                    <p><strong>📝 Catatan:</strong></p>
                    <p>• Hasil pemilihan diupdate secara real-time</p>
                    <p>• Halaman ini menampilkan jumlah suara untuk setiap kandidat</p>
                    <p>• Akses panitia hanya untuk melihat hasil (read-only)</p>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <p class="text-yellow-900">⚠️ Belum ada data suara. Silahkan tunggu pemilihan dimulai.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-refresh every 5 seconds
    setTimeout(() => {
        location.reload();
    }, 5000);
</script>
@endsection
