@extends('layouts.panitia')

@section('title', 'Dashboard Panitia')

@section('panitia.content')
@php
    $palette = ['#4f46e5', '#0f172a', '#0d9488', '#f97316', '#e11d48', '#7c3aed'];
    $sortedKandidat = $kandidats->sortByDesc(fn ($kandidat) => $kandidat->suara->count())->values();
    $leading = $sortedKandidat->first();

    $chartPayload = $sortedKandidat->map(function ($kandidat, $index) use ($palette, $totalSuara) {
        $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama ?? '-';
        $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama ?? '-';
        $suara = $kandidat->suara->count();

        return [
            'label' => $ketua,
            'subLabel' => $wakil,
            'suara' => $suara,
            'persen' => $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0,
            'color' => $palette[$index % count($palette)],
        ];
    })->values();
@endphp

<div class="admin-page">
    <div class="admin-container max-w-7xl">
        <div class="mb-4 flex flex-wrap items-center justify-end gap-2">
            <button type="button" id="dashboard-refresh-toggle" class="admin-btn admin-btn-primary">Pause Refresh</button>
            <span id="dashboard-refresh-status" class="admin-badge admin-badge-success">Auto-refresh aktif (5 detik)</span>
            <span id="dashboard-last-updated" class="admin-badge admin-badge-muted">Update: {{ now()->format('d M Y H:i:s') }}</span>
        </div>

        <div id="dashboard-live" class="space-y-6 transition-opacity duration-200">
            <section class="admin-card overflow-hidden">
                <div class="admin-card-body">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-500">Panitia Monitoring</p>
                            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Dashboard Panitia</h1>
                            <p class="mt-1 text-slate-500">Pantau performa pemilihan secara langsung tanpa perlu pindah halaman.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="admin-badge admin-badge-muted">Periode: {{ $periode->nama_periode }}</span>
                            <span class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-700">
                                Grafik interaktif aktif
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="admin-metrics">
                <article class="admin-metric-card">
                    <p class="admin-metric-label">Total Suara Masuk</p>
                    <p class="admin-metric-value">{{ $totalSuara }}</p>
                    <p class="admin-metric-sub">Akumulasi seluruh kandidat pada periode aktif.</p>
                </article>

                <article class="admin-metric-card">
                    <p class="admin-metric-label">Jumlah Kandidat</p>
                    <p class="admin-metric-value">{{ $sortedKandidat->count() }}</p>
                    <p class="admin-metric-sub">Pasangan kandidat yang sedang dipantau panitia.</p>
                </article>

                <article class="admin-metric-card">
                    <p class="admin-metric-label">Pemimpin Sementara</p>
                    <p class="admin-metric-value text-2xl">
                        {{ $leading ? ($leading->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama ?? '-') : '-' }}
                    </p>
                    <p class="admin-metric-sub">
                        {{ $leading ? $leading->suara->count() . ' suara' : 'Belum ada perolehan suara.' }}
                    </p>
                </article>
            </section>

            <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <article class="admin-card">
                    <div class="admin-card-body">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Distribusi Suara</h2>
                                <p class="text-sm text-slate-500">Visual donut chart interaktif per kandidat.</p>
                            </div>
                            <span class="text-xs font-medium text-slate-500">Total {{ $totalSuara }}</span>
                        </div>

                        <div class="flex flex-col items-center gap-6 md:flex-row md:items-start">
                            <div class="relative h-64 w-full max-w-[260px] flex-shrink-0">
                                <canvas id="dashboardDonutChart" class="h-64 w-full"></canvas>
                            </div>

                            <div class="w-full space-y-3">
                                @forelse ($chartPayload as $item)
                                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2">
                                        <div class="flex min-w-0 items-center gap-2">
                                            <span class="h-3 w-3 flex-shrink-0 rounded-full" style="background: {{ $item['color'] }}"></span>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $item['label'] }}</p>
                                                <p class="truncate text-xs text-slate-500">{{ $item['subLabel'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-slate-900">{{ $item['suara'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $item['persen'] }}%</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">Belum ada data kandidat.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>

                <article class="admin-card">
                    <div class="admin-card-body">
                        <div class="mb-5">
                            <h2 class="text-lg font-semibold text-slate-900">Perbandingan Kandidat</h2>
                            <p class="text-sm text-slate-500">Bar chart interaktif untuk melihat jarak perolehan.</p>
                        </div>

                        <div class="h-72 w-full">
                            <canvas id="dashboardBarChart" class="h-72 w-full"></canvas>
                        </div>
                    </div>
                </article>
            </section>

            <section class="admin-card">
                <div class="admin-card-body">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Top Kandidat</h2>
                            <p class="text-sm text-slate-500">Tiga perolehan suara tertinggi saat ini.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        @forelse ($topKandidats as $index => $kandidat)
                            @php
                                $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                                $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                                $suara = $kandidat->suara->count();
                            @endphp
                            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Top {{ $index + 1 }}</p>
                                <p class="mt-3 text-base font-semibold text-slate-900">{{ $ketua ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $wakil ?? '-' }}</p>
                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <p class="text-2xl font-bold text-slate-900">{{ $suara }}</p>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">suara</p>
                                </div>
                            </article>
                        @empty
                            <p class="col-span-full text-sm text-slate-500">Belum ada data kandidat.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <script id="dashboard-chart-data" type="application/json">@json($chartPayload)</script>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(() => {
    const liveRegion = document.getElementById('dashboard-live');
    const toggleButton = document.getElementById('dashboard-refresh-toggle');
    const statusBadge = document.getElementById('dashboard-refresh-status');
    const updatedBadge = document.getElementById('dashboard-last-updated');

    if (!liveRegion) return;

    let isPaused = false;
    let isRefreshing = false;
    let doughnutChart = null;
    let barChart = null;

    const destroyCharts = () => {
        if (doughnutChart) {
            doughnutChart.destroy();
            doughnutChart = null;
        }
        if (barChart) {
            barChart.destroy();
            barChart = null;
        }
    };

    const initCharts = () => {
        destroyCharts();

        if (typeof Chart === 'undefined') return;

        const payloadNode = document.getElementById('dashboard-chart-data');
        if (!payloadNode) return;

        let payload = [];
        try {
            payload = JSON.parse(payloadNode.textContent || '[]');
        } catch (error) {
            console.error('Gagal parsing data chart dashboard:', error);
            return;
        }

        if (!Array.isArray(payload) || payload.length === 0) return;

        const labels = payload.map((item) => item.label);
        const votes = payload.map((item) => item.suara);
        const percents = payload.map((item) => item.persen);
        const colors = payload.map((item) => item.color);

        const donutCanvas = document.getElementById('dashboardDonutChart');
        if (donutCanvas) {
            doughnutChart = new Chart(donutCanvas, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: votes,
                        backgroundColor: colors,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 8,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '58%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    const value = context.parsed || 0;
                                    const percent = percents[context.dataIndex] || 0;
                                    return `${value} suara (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        const barCanvas = document.getElementById('dashboardBarChart');
        if (barCanvas) {
            barChart = new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Suara',
                        data: votes,
                        backgroundColor: colors,
                        borderRadius: 8,
                        borderSkipped: false,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    const percent = percents[context.dataIndex] || 0;
                                    return `${context.parsed.y} suara (${percent}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                            grid: {
                                color: '#e2e8f0',
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                            }
                        }
                    }
                }
            });
        }
    };

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

    const refreshDashboard = async () => {
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
            const nextRegion = doc.getElementById('dashboard-live');

            if (!nextRegion) return;

            destroyCharts();
            liveRegion.classList.add('opacity-60');
            liveRegion.innerHTML = nextRegion.innerHTML;
            liveRegion.classList.remove('opacity-60');

            initCharts();
            setUpdatedTime();
        } catch (error) {
            console.error('Gagal refresh dashboard panitia:', error);
        } finally {
            isRefreshing = false;
        }
    };

    toggleButton?.addEventListener('click', () => {
        isPaused = !isPaused;
        setRefreshState();
    });

    setRefreshState();
    initCharts();

    setInterval(() => {
        refreshDashboard();
    }, 5000);
})();
</script>
@endsection
