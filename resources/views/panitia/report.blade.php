@extends('layouts.panitia')

@section('title', 'Report Panitia')

@section('panitia.content')
<div class="admin-page">
    <div class="admin-container max-w-7xl">
        <section class="admin-card mb-6">
            <div class="admin-card-body">
                <div class="admin-header mb-4">
                    <div>
                        <h1 class="admin-title">Laporan Pemilihan</h1>
                        <p class="admin-subtitle">Filter data suara lalu export CSV untuk kebutuhan dokumentasi panitia.</p>
                    </div>
                    <a href="{{ route('panitia.report.export', [
                        'periode_id' => $periodeId ?? $periode->id,
                        'jenis' => $jenis,
                        'tingkat' => $tingkat,
                        'kelas_id' => $kelasId,
                    ]) }}" class="admin-btn admin-btn-primary">
                        Export CSV
                    </a>
                </div>

                <form method="GET" class="admin-filter-panel mb-0">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5">
                        <div>
                            <label for="periode_id" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Periode</label>
                            <select id="periode_id" name="periode_id" class="admin-select w-full" onchange="this.form.submit()">
                                @foreach ($periodes as $p)
                                    <option value="{{ $p->id }}" @selected((string) $p->id === (string) ($periodeId ?? $periode->id))>
                                        {{ $p->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jenis" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis</label>
                            <select id="jenis" name="jenis" class="admin-select w-full" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="siswa" @selected($jenis === 'siswa')>Siswa</option>
                                <option value="guru" @selected($jenis === 'guru')>Guru</option>
                            </select>
                        </div>

                        <div>
                            <label for="tingkat" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tingkat</label>
                            <select id="tingkat" name="tingkat" class="admin-select w-full" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                @foreach ($tingkatList as $t)
                                    <option value="{{ $t }}" @selected((string) $t === (string) $tingkat)>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="kelas_id" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kelas</label>
                            <select id="kelas_id" name="kelas_id" class="admin-select w-full" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}" @selected((string) $k->id === (string) $kelasId)>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('panitia.report') }}" class="admin-btn admin-btn-soft w-full justify-center">Reset Filter</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="admin-metrics">
            <article class="admin-metric-card">
                <p class="admin-metric-label">Total Suara (Filtered)</p>
                <p class="admin-metric-value">{{ $totalSuara }}</p>
                <p class="admin-metric-sub">Jumlah suara setelah seluruh filter diterapkan.</p>
            </article>

            <article class="admin-metric-card">
                <p class="admin-metric-label">Periode</p>
                <p class="text-xl font-bold text-slate-900 mt-1">{{ $periode->nama_periode }}</p>
                <p class="admin-metric-sub">Status: {{ ucfirst($periode->status) }}</p>
            </article>

            <article class="admin-metric-card">
                <p class="admin-metric-label">Kandidat Terhitung</p>
                <p class="admin-metric-value">{{ $rekap->count() }}</p>
                <p class="admin-metric-sub">Jumlah kandidat pada periode laporan ini.</p>
            </article>
        </section>

        <section class="admin-card mb-6">
            <div class="admin-card-body">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-slate-900">Rekap Suara per Kandidat</h2>
                    <p class="text-sm text-slate-500">Ringkasan jumlah suara kandidat untuk periode dan filter aktif.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="admin-table min-w-[680px]">
                        <thead class="admin-thead">
                            <tr>
                                <th class="admin-th w-20">Nomor</th>
                                <th class="admin-th">Ketua</th>
                                <th class="admin-th">Wakil</th>
                                <th class="admin-th w-32 text-right">Suara</th>
                                <th class="admin-th w-44">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($rekap as $kandidat)
                                @php
                                    $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                                    $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                                    $suaraKandidat = $suara->where('kandidat_id', $kandidat->id)->count();
                                    $persen = $totalSuara > 0 ? round(($suaraKandidat / $totalSuara) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/70">
                                    <td class="admin-td">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">{{ $kandidat->nomor_urut }}</span>
                                    </td>
                                    <td class="admin-td font-semibold text-slate-900">{{ $ketua ?? '-' }}</td>
                                    <td class="admin-td text-slate-600">{{ $wakil ?? '-' }}</td>
                                    <td class="admin-td text-right">
                                        <p class="text-lg font-bold text-slate-900">{{ $suaraKandidat }}</p>
                                    </td>
                                    <td class="admin-td">
                                        <div class="space-y-2">
                                            <p class="text-xs font-semibold text-slate-700">{{ $persen }}%</p>
                                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                                <div class="h-full rounded-full bg-indigo-500" style="width: {{ $persen }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="admin-td text-center text-slate-500">Belum ada data rekap kandidat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card-body">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-slate-900">Detail Suara Masuk</h2>
                    <p class="text-sm text-slate-500">Daftar pemilih yang sudah memberikan suara berdasarkan filter aktif.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="admin-table min-w-[880px]">
                        <thead class="admin-thead">
                            <tr>
                                <th class="admin-th">Waktu</th>
                                <th class="admin-th">Nama Pemilih</th>
                                <th class="admin-th">NISN/NIP</th>
                                <th class="admin-th">Jenis</th>
                                <th class="admin-th">Kelas</th>
                                <th class="admin-th">Nomor Kandidat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($suara as $item)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="admin-td text-slate-600">{{ optional($item->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                    <td class="admin-td font-medium text-slate-900">{{ $item->pemilih?->nama ?? '-' }}</td>
                                    <td class="admin-td text-slate-600">{{ $item->pemilih?->nisn ?? '-' }}</td>
                                    <td class="admin-td text-slate-600">{{ ucfirst($item->pemilih?->jenis ?? '-') }}</td>
                                    <td class="admin-td text-slate-600">{{ $item->pemilih?->kelas?->nama_kelas ?? '-' }}</td>
                                    <td class="admin-td">
                                        <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                            {{ $item->kandidat?->nomor_urut ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="admin-td text-center text-slate-500">Belum ada data suara yang cocok dengan filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
