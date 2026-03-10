@extends('layouts.admin')

@section('title', 'Suara - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Suara</h1>
                <p class="admin-subtitle">Kelola data suara pemilih.</p>
            </div>
            <a href="{{ route('admin.suara.create') }}" class="admin-btn admin-btn-primary">
                Tambah Suara
            </a>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 mb-6">
                <p class="text-rose-800">{{ $errors->first() }}</p>
            </div>
        @endif

        @php
            $totalSuaraPeriode = $periodeId
                ? \App\Models\Suara::where('periode_id', $periodeId)->count()
                : \App\Models\Suara::count();
            $totalTokenPeriode = $periodeId
                ? \App\Models\TokenPemilih::where('periode_id', $periodeId)->count()
                : \App\Models\TokenPemilih::count();
            $sudahMemilihPeriode = $periodeId
                ? \App\Models\TokenPemilih::where('periode_id', $periodeId)->where('sudah_memilih', true)->count()
                : \App\Models\TokenPemilih::where('sudah_memilih', true)->count();
            $partisipasi = $totalTokenPeriode > 0 ? round(($sudahMemilihPeriode / $totalTokenPeriode) * 100, 1) : 0;
        @endphp

        <section class="admin-metrics">
            <div class="admin-metric-card">
                <p class="admin-metric-label">Total Suara</p>
                <h3 class="admin-metric-value">{{ $totalSuaraPeriode }}</h3>
                <p class="admin-metric-sub">{{ $periodeId ? 'Periode dipilih' : 'Semua periode' }}</p>
            </div>
            <div class="admin-metric-card">
                <p class="admin-metric-label">Sudah Memilih</p>
                <h3 class="admin-metric-value">{{ $sudahMemilihPeriode }}</h3>
                <p class="admin-metric-sub">{{ $periodeId ? 'Dari token aktif periode' : 'Semua periode' }}</p>
            </div>
            <div class="admin-metric-card">
                <p class="admin-metric-label">Partisipasi</p>
                <h3 class="admin-metric-value">{{ $partisipasi }}%</h3>
                <p class="admin-metric-sub">{{ $totalTokenPeriode }} total token</p>
            </div>
        </section>

        <div class="admin-filter-panel">
            <form method="GET" id="filter-form" class="flex flex-wrap items-end gap-3">
                {{-- Search --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Pemilih</label>
                    <div class="flex gap-2">
                        <input type="text" name="search" id="filter-search" value="{{ $search }}" placeholder="Nama / NISN..." class="admin-input flex-1" />
                        <button type="submit" class="admin-btn admin-btn-primary whitespace-nowrap">Cari</button>
                    </div>
                </div>

                {{-- Periode --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Periode</label>
                    <select name="periode_id" class="admin-select" onchange="this.form.submit()">
                        <option value="" @selected(!$periodeId)>Semua Periode</option>
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected((string) $p->id === (string) $periodeId)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tingkat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tingkat</label>
                    <select name="tingkat" class="admin-select" onchange="document.querySelector('[name=kelas_id]').value=''; this.form.submit()">
                        <option value="" @selected(!$tingkat)>Semua Tingkat</option>
                        <option value="none" @selected($tingkat === 'none')>Tanpa Tingkat (Guru)</option>
                        @foreach ($tingkats as $t)
                            <option value="{{ $t }}" @selected((string) $t === (string) $tingkat)>Tingkat {{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
                    <select name="kelas_id" class="admin-select" onchange="this.form.submit()">
                        <option value="" @selected(!$kelasId)>Semua Kelas</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id }}" @selected((string) $k->id === (string) $kelasId)>{{ $k->tingkat }} - {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kandidat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kandidat</label>
                    <select name="kandidat_id" class="admin-select" onchange="this.form.submit()">
                        <option value="" @selected(!$kandidatId)>Semua Kandidat</option>
                        @foreach ($kandidats as $k)
                            <option value="{{ $k->id }}" @selected((string) $k->id === (string) $kandidatId)>
                                {{ $k->nomor_urut }} - {{ $k->anggota?->firstWhere('peran','ketua')?->pemilih?->nama ?? 'Kandidat' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset --}}
                <div>
                    <a href="{{ route('admin.suara.index') }}" class="admin-btn admin-btn-soft">Reset</a>
                </div>
            </form>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead class="admin-thead">
                        <tr>
                            <th class="admin-th">No</th>
                            <th class="admin-th">Pemilih</th>
                            <th class="admin-th">NISN/NIP</th>
                            <th class="admin-th">Kelas</th>
                            <th class="admin-th">Kandidat</th>
                            @if (!$periodeId)
                                <th class="admin-th">Periode</th>
                            @endif
                            <th class="admin-th">Waktu</th>
                            <th class="admin-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($suara as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="admin-td text-slate-900">{{ $loop->iteration }}</td>
                                <td class="admin-td text-slate-900 font-medium">{{ $item->pemilih?->nama }}</td>
                                <td class="admin-td text-slate-600">{{ $item->pemilih?->nisn }}</td>
                                <td class="admin-td text-slate-600">{{ $item->pemilih?->kelas ? $item->pemilih->kelas->tingkat . ' - ' . $item->pemilih->kelas->nama_kelas : '-' }}</td>
                                <td class="admin-td text-slate-700">{{ $item->kandidat?->nomor_urut }} - {{
                                    $item->kandidat?->anggota?->firstWhere('peran', 'ketua')?->pemilih?->nama ?? 'Kandidat'
                                }}</td>
                                @if (!$periodeId)
                                    <td class="admin-td text-slate-600">{{ $item->periode?->nama_periode }}</td>
                                @endif
                                <td class="admin-td text-slate-600">{{ $item->created_at?->format('d M Y H:i') }}</td>
                                <td class="admin-td">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.suara.edit', $item->id) }}" class="admin-btn admin-btn-warning text-xs px-3 py-2">Edit</a>
                                        <form action="{{ route('admin.suara.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus suara ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger text-xs px-3 py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $periodeId ? 7 : 8 }}" class="px-6 py-8 text-center text-slate-600">Belum ada suara</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($suara->hasPages())
            <div class="mt-6">
                {{ $suara->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection
