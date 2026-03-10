@extends('layouts.panitia')

@section('title', 'Hasil Pemilihan - Panitia')

@section('panitia.content')
@php
    $palette = ['#4f46e5', '#0f172a', '#0d9488', '#f97316', '#e11d48', '#7c3aed'];
    $sortedKandidat = $kandidats->sortByDesc(fn ($kandidat) => $kandidat->suara->count())->values();
@endphp

<div class="admin-page">
    <div class="admin-container max-w-7xl">
        <div class="mb-4 flex flex-wrap items-center justify-end gap-2">
            <button type="button" id="results-refresh-toggle" class="admin-btn admin-btn-primary">Pause Refresh</button>
            <span id="results-refresh-status" class="admin-badge admin-badge-success">Auto-refresh aktif (5 detik)</span>
            <span id="results-last-updated" class="admin-badge admin-badge-muted">Update: {{ now()->format('d M Y H:i:s') }}</span>
        </div>

        <div id="results-live" class="space-y-6 transition-opacity duration-200">
            <section class="admin-card">
                <div class="admin-card-body">
                    <div class="admin-header mb-0">
                        <div>
                            <h1 class="admin-title">Hasil Pemilihan</h1>
                            <p class="admin-subtitle">Pantau perolehan suara kandidat secara real-time dan terstruktur.</p>
                        </div>

                        <form method="GET" class="flex flex-wrap items-center gap-3">
                            <label for="periode_id" class="text-sm font-medium text-slate-600">Periode</label>
                            <select id="periode_id" name="periode_id" class="admin-select" onchange="this.form.submit()">
                                @foreach ($periodes as $p)
                                    <option value="{{ $p->id }}" @selected((string) $p->id === (string) ($periodeId ?? $periode->id))>
                                        {{ $p->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="admin-badge admin-badge-muted">Realtime tanpa reload halaman</span>
                        </form>
                    </div>
                </div>
            </section>

            <section class="admin-metrics">
                <article class="admin-metric-card">
                    <p class="admin-metric-label">Total Suara</p>
                    <p class="admin-metric-value">{{ $totalSuara }}</p>
                    <p class="admin-metric-sub">Akumulasi suara dari periode terpilih.</p>
                </article>

                <article class="admin-metric-card">
                    <p class="admin-metric-label">Periode Aktif Ditampilkan</p>
                    <p class="text-xl font-bold text-slate-900 mt-1">{{ $periode->nama_periode }}</p>
                    <p class="admin-metric-sub">Status: {{ ucfirst($periode->status) }}</p>
                </article>

                <article class="admin-metric-card">
                    <p class="admin-metric-label">Jumlah Kandidat</p>
                    <p class="admin-metric-value">{{ $sortedKandidat->count() }}</p>
                    <p class="admin-metric-sub">Kandidat yang ikut dalam periode ini.</p>
                </article>
            </section>

            <section class="admin-card">
                <div class="admin-card-body">
                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Peringkat Kandidat</h2>
                            <p class="text-sm text-slate-500">Urutan kandidat berdasarkan suara tertinggi.</p>
                        </div>
                        <p class="text-xs text-slate-500">Data tersinkron otomatis setiap 5 detik.</p>
                    </div>

                    @if ($sortedKandidat->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="admin-table min-w-[760px]">
                                <thead class="admin-thead">
                                    <tr>
                                        <th class="admin-th w-20">Rank</th>
                                        <th class="admin-th">Pasangan Kandidat</th>
                                        <th class="admin-th">Visi dan Misi</th>
                                        <th class="admin-th w-32 text-right">Suara</th>
                                        <th class="admin-th w-44">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($sortedKandidat as $index => $kandidat)
                                        @php
                                            $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                                            $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                                            $suara = $kandidat->suara->count();
                                            $percent = $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0;
                                            $color = $palette[$index % count($palette)];
                                        @endphp
                                        <tr class="hover:bg-slate-50/70">
                                            <td class="admin-td">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">{{ $index + 1 }}</span>
                                            </td>
                                            <td class="admin-td">
                                                <div class="space-y-1">
                                                    <p class="text-sm font-semibold text-slate-900">{{ $ketua ?? '-' }}</p>
                                                    <p class="text-xs text-slate-500">Wakil: {{ $wakil ?? '-' }}</p>
                                                    <p class="text-xs text-slate-400">Nomor urut {{ $kandidat->nomor_urut }}</p>
                                                </div>
                                            </td>
                                            <td class="admin-td">
                                                <div class="space-y-1 text-xs text-slate-600">
                                                    <p><span class="font-medium text-slate-700">Visi:</span> {{ $kandidat->visi }}</p>
                                                    <p><span class="font-medium text-slate-700">Misi:</span> {{ $kandidat->misi }}</p>
                                                </div>
                                            </td>
                                            <td class="admin-td text-right">
                                                <p class="text-xl font-bold text-slate-900">{{ $suara }}</p>
                                                <p class="text-xs text-slate-500">suara</p>
                                            </td>
                                            <td class="admin-td">
                                                <div class="space-y-2">
                                                    <p class="text-xs font-semibold text-slate-700">{{ $percent }}%</p>
                                                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                                        <div class="h-full rounded-full" style="width: {{ $percent }}%; background: {{ $color }}"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                            Belum ada data suara untuk periode ini.
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>

<script>
(() => {
    const liveRegion = document.getElementById('results-live');
    const toggleButton = document.getElementById('results-refresh-toggle');
    const statusBadge = document.getElementById('results-refresh-status');
    const updatedBadge = document.getElementById('results-last-updated');

    if (!liveRegion) return;

    let isPaused = false;
    let isRefreshing = false;

    const setRefreshState = () => {
        if (toggleButton) {
            toggleButton.textContent = isPaused ? 'Lanjutkan Refresh' : 'Pause Refresh';
            toggleButton.className = isPaused ? 'admin-btn admin-btn-soft' : 'admin-btn admin-btn-primary';
        }

        if (statusBadge) {
            statusBadge.textContent = isPaused ? 'Auto-refresh dijeda' : 'Auto-refresh aktif (5 detik)';
            statusBadge.className = isPaused ? 'admin-badge admin-badge-muted' : 'admin-badge admin-badge-success';
        }
    };

    const setUpdatedTime = () => {
        if (!updatedBadge) return;

        const now = new Date();
        updatedBadge.textContent = `Update: ${now.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        })}`;
    };

    const refreshResults = async () => {
        if (isPaused || isRefreshing || document.hidden) return;

        isRefreshing = true;
        try {
            const response = await fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache',
                },
            });

            if (!response.ok) return;

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const nextRegion = doc.getElementById('results-live');

            if (!nextRegion) return;

            liveRegion.classList.add('opacity-60');
            liveRegion.innerHTML = nextRegion.innerHTML;
            liveRegion.classList.remove('opacity-60');

            setUpdatedTime();
        } catch (error) {
            console.error('Gagal refresh hasil panitia:', error);
        } finally {
            isRefreshing = false;
        }
    };

    toggleButton?.addEventListener('click', () => {
        isPaused = !isPaused;
        setRefreshState();
    });

    setRefreshState();

    setInterval(() => {
        refreshResults();
    }, 5000);
})();
</script>
@endsection
