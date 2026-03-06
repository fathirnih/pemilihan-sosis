@extends('layouts.panitia')

@section('title', 'Dashboard Panitia')

@section('panitia.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Dashboard Panitia</h1>
                    <p class="mt-1 text-slate-600">Ringkasan hasil pemilihan yang sedang berjalan.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600">
                    Auto-refresh 5 detik
                </div>
            </div>
        </div>

        @php
            $palette = ['#0f172a', '#1d4ed8', '#9333ea', '#f97316', '#0d9488', '#e11d48'];
            $sortedKandidat = $kandidats->sortByDesc(fn($k) => $k->suara->count())->values();
            $circumference = 2 * pi() * 54;
            $offset = 0;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Pie Chart Suara</h2>
                        <p class="text-sm text-slate-600">Distribusi suara semua kandidat.</p>
                    </div>
                    <span class="text-xs text-slate-500">Total: {{ $totalSuara }}</span>
                </div>
                <div class="flex flex-col md:flex-row gap-6 items-center">
                    <div class="relative h-40 w-40">
                        <svg viewBox="0 0 140 140" class="h-40 w-40">
                            <circle cx="70" cy="70" r="54" fill="none" stroke="#e2e8f0" stroke-width="18"></circle>
                            @foreach ($sortedKandidat as $index => $kandidat)
                                @php
                                    $suara = $kandidat->suara->count();
                                    $percent = $totalSuara > 0 ? ($suara / $totalSuara) * 100 : 0;
                                    $dash = ($percent / 100) * $circumference;
                                    $color = $palette[$index % count($palette)];
                                @endphp
                                <circle
                                    cx="70"
                                    cy="70"
                                    r="54"
                                    fill="none"
                                    stroke="{{ $color }}"
                                    stroke-width="18"
                                    stroke-dasharray="{{ $dash }} {{ $circumference - $dash }}"
                                    stroke-dashoffset="-{{ $offset }}"
                                    stroke-linecap="round"
                                    transform="rotate(-90 70 70)"
                                ></circle>
                                @php $offset += $dash; @endphp
                            @endforeach
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-center">
                            <div>
                                <p class="text-xs text-slate-500">Total Suara</p>
                                <p class="text-lg font-bold text-slate-900">{{ $totalSuara }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 space-y-3 w-full">
                        @forelse ($sortedKandidat as $index => $kandidat)
                            @php
                                $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                                $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                                $suara = $kandidat->suara->count();
                                $percent = $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0;
                                $color = $palette[$index % count($palette)];
                            @endphp
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-3 w-3 rounded-full" style="background: {{ $color }}"></span>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $ketua ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ $wakil ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">{{ $suara }}</p>
                                    <p class="text-xs text-slate-500">{{ $percent }}%</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600">Belum ada data kandidat.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Bar Chart Suara</h2>
                        <p class="text-sm text-slate-600">Perbandingan suara semua kandidat.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse ($sortedKandidat as $index => $kandidat)
                        @php
                            $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                            $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                            $suara = $kandidat->suara->count();
                            $percent = $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0;
                            $color = $palette[$index % count($palette)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm text-slate-700 mb-2">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $ketua ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $wakil ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-900">{{ $suara }}</p>
                                    <p class="text-xs text-slate-500">{{ $percent }}%</p>
                                </div>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: {{ $percent }}%; background: {{ $color }}"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-600">Belum ada data kandidat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @forelse ($topKandidats as $index => $kandidat)
                @php
                    $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                    $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                    $suara = $kandidat->suara->count();
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <p class="text-xs font-semibold text-slate-500 mb-2">Top {{ $index + 1 }}</p>
                    <p class="text-base font-semibold text-slate-900">{{ $ketua ?? '-' }}</p>
                    <p class="text-xs text-slate-500">{{ $wakil ?? '-' }}</p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-lg font-bold text-slate-900">{{ $suara }}</span>
                        <span class="text-xs text-slate-500">suara</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-slate-600 text-sm">Belum ada data kandidat.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        location.reload();
    }, 5000);
</script>
@endsection
