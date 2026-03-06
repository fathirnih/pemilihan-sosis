@extends('layouts.panitia')

@section('title', 'Hasil Pemilihan - Panitia')

@section('panitia.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Hasil Pemilihan OSIS</h1>
            <p class="mt-1 text-slate-600">Pantau hasil sementara secara real-time.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Rekap Suara Lengkap</h2>
                    <p class="text-sm text-slate-600">Detail per kandidat dengan visi & misi.</p>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <label class="text-xs text-slate-500">Periode</label>
                        <select name="periode_id" class="admin-select text-sm" onchange="this.form.submit()">
                            @foreach ($periodes as $p)
                                <option value="{{ $p->id }}" @selected((string) $p->id === (string) ($periodeId ?? $periode->id))>
                                    {{ $p->nama_periode }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <div class="text-xs text-slate-500">
                        Update terakhir: {{ now()->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-3">Total Suara</p>
                <p class="text-4xl font-bold text-slate-900">{{ $totalSuara }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-3">Periode</p>
                <p class="text-lg font-semibold text-slate-900">{{ $periode->nama_periode }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ ucfirst($periode->status) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-3">Total Kandidat</p>
                <p class="text-4xl font-bold text-slate-900">{{ $kandidats->count() }}</p>
            </div>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Daftar Kandidat</h2>
            
            @if ($kandidats->count() > 0)
                <div class="space-y-6">
                    @foreach ($kandidats->sortByDesc(fn($k) => $k->suara->count()) as $kandidat)
                        @php
                            $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                            $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                        @endphp
                        <div class="border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold text-sm">Nomor {{ $kandidat->nomor_urut }}</span>
                                    </div>
                                    <div class="text-sm text-slate-600">
                                        <p>Ketua: <span class="font-semibold text-slate-900">{{ $ketua ?? '-' }}</span></p>
                                        <p>Wakil: <span class="font-semibold text-slate-900">{{ $wakil ?? '-' }}</span></p>
                                    </div>
                                    <p class="text-slate-600 text-sm mt-2">Visi: {{ $kandidat->visi }}</p>
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
                                            <p class="text-sm text-slate-700">- {{ ucfirst($anggota->peran) }}: {{ $anggota->pemilih->nama ?? 'N/A' }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Info -->
                <div class="mt-8 pt-6 border-t border-slate-200 text-sm text-slate-600">
                    <p><strong>Catatan:</strong></p>
                    <p>- Hasil pemilihan diupdate secara real-time</p>
                    <p>- Halaman ini menampilkan jumlah suara untuk setiap kandidat</p>
                    <p>- Akses panitia hanya untuk melihat hasil (read-only)</p>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <p class="text-yellow-900">Belum ada data suara. Silahkan tunggu pemilihan dimulai.</p>
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
