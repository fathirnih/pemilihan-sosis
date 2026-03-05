@extends('layouts.app')

@section('title', 'Hasil Pemilihan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="container-app">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-1">Hasil Pemilihan</h1>
                    <p class="text-slate-600">{{ $periode->nama_periode }}</p>
                </div>
                <a href="{{ route('logout') }}" class="text-slate-600 hover:text-slate-900 font-medium text-sm">
                    Keluar
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-600 mb-1">Total Suara</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $totalSuara }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-600 mb-1">Kandidat</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $kandidats->count() }}</p>
                </div>
            </div>
        </div>

        @if ($kandidats->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum Ada Hasil</h3>
                <p class="text-slate-600">Hasil akan muncul ketika pemilihan dimulai</p>
            </div>
        @else
            <!-- Results List -->
            <div class="space-y-4">
                @php
                    $maxSuara = $kandidats->map(fn($k) => $k->suara()->count())->max();
                @endphp

                @foreach ($kandidats->sortByDesc(fn($k) => $k->suara()->count()) as $index => $kandidat)
                    @php
                        $suaraKandidat = $kandidat->suara()->count();
                        $persentase = $totalSuara > 0 ? ($suaraKandidat / $totalSuara) * 100 : 0;
                    @endphp

                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-lg border border-blue-200">
                                        <span class="text-xl font-bold text-blue-600">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-600">NOMOR URUT</p>
                                        <p class="text-2xl font-bold text-slate-900">{{ $kandidat->nomor_urut }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-slate-900">{{ $suaraKandidat }}</p>
                                    <p class="text-sm text-slate-600">{{ number_format($persentase, 1) }}%</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500"
                                        style="width: {{ $maxSuara > 0 ? ($suaraKandidat / $maxSuara) * 100 : 0 }}%"
                                    ></div>
                                </div>
                            </div>

                            <!-- Team Info -->
                            <div class="text-sm text-slate-600">
                                <p class="font-medium mb-1">Tim Kandidat:</p>
                                <div class="space-y-1">
                                    @foreach ($kandidat->anggota as $anggota)
                                        <p class="text-slate-700">{{ $anggota->siswa->nama ?? 'N/A' }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Refresh Info -->
            <div class="mt-8 text-center text-sm text-slate-600">
                <p>Data hasil diperbarui secara otomatis setiap beberapa detik</p>
            </div>
        @endif
    </div>
</div>

<script>
    // Auto refresh setiap 5 detik
    setTimeout(() => {
        location.reload();
    }, 5000);
</script>
@endsection
