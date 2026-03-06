@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Ringkasan</h2>
            <p class="mt-1 text-slate-600">Pantau aktivitas pemilihan dan status periode.</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Total Token</p>
                        <p class="text-3xl font-bold text-slate-900">{{ $totalTokens }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-sm font-semibold">TK</div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Token Aktif</p>
                        <p class="text-3xl font-bold text-emerald-600">{{ $tokensAktif }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-semibold">AK</div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Sudah Memilih</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $sudahMemilih }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-semibold">SM</div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Total Suara</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $totalSuara }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-semibold">TS</div>
                </div>
            </div>
        </div>

        <!-- Active Period -->
        @if ($periode)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Periode Aktif</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Nama Periode</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $periode->nama_periode }}</p>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Status</p>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">{{ ucfirst($periode->status) }}</span>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Mulai Pada</p>
                        <p class="text-slate-900">{{ $periode->mulai_pada->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Selesai Pada</p>
                        <p class="text-slate-900">{{ $periode->selesai_pada->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <p class="text-yellow-800">Tidak ada periode pemilihan yang aktif.</p>
            </div>
        @endif

        <!-- Hasil Sementara -->
        @if ($kandidats->count() > 0)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Hasil Suara Sementara</h3>
                        <p class="mt-1 text-sm text-slate-600">Update real-time berdasarkan suara yang masuk.</p>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-slate-600">
                        <span class="px-3 py-1 rounded-full bg-slate-100">Total suara: <strong class="text-slate-900">{{ $totalSuara }}</strong></span>
                        <span class="px-3 py-1 rounded-full bg-slate-100">Sudah memilih: <strong class="text-slate-900">{{ $sudahMemilih }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($topKandidats as $index => $kandidat)
                    @php
                        $suaraCount = $kandidat->suara->count();
                        $percent = $totalSuara > 0 ? round(($suaraCount / $totalSuara) * 100, 1) : 0;
                        $ketua = $kandidat->anggota->firstWhere('peran', 'ketua');
                        $wakil = $kandidat->anggota->firstWhere('peran', 'wakil');
                        $fotoUrl = $kandidat->foto ? asset('storage/' . $kandidat->foto) : null;
                        $rankStyles = [
                            'ring-amber-300 bg-amber-50 text-amber-700',
                            'ring-slate-300 bg-slate-100 text-slate-700',
                            'ring-orange-300 bg-orange-50 text-orange-700',
                        ];
                        $badgeText = 'Peringkat ' . ($index + 1);
                    @endphp
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full {{ $rankStyles[$index] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $badgeText }}
                            </span>
                            <span class="text-sm font-semibold text-slate-700">No {{ $kandidat->nomor_urut }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                @if ($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="Foto kandidat {{ $kandidat->nomor_urut }}" class="h-16 w-16 rounded-full object-cover ring-4 ring-slate-100">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-sm font-semibold text-slate-600 ring-4 ring-slate-100">
                                        NO {{ $kandidat->nomor_urut }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-base font-semibold text-slate-900">{{ $ketua?->pemilih?->nama ?? 'Ketua belum diisi' }}</p>
                                <p class="text-sm text-slate-500">{{ $wakil?->pemilih?->nama ?? 'Wakil belum diisi' }}</p>
                                <p class="mt-2 text-xs text-slate-500 line-clamp-2">{{ $kandidat->visi }}</p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <div class="flex items-center justify-between text-sm text-slate-600 mb-2">
                                <span>Total suara</span>
                                <span class="font-semibold text-slate-900">{{ $suaraCount }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-2 rounded-full bg-blue-600" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">{{ $percent }}% dari total suara</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($otherKandidats->count() > 0)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h4 class="text-base font-semibold text-slate-900 mb-4">Kandidat Lainnya</h4>
                    <div class="space-y-3">
                        @foreach ($otherKandidats as $kandidat)
                            @php
                                $suaraCount = $kandidat->suara->count();
                                $percent = $totalSuara > 0 ? round(($suaraCount / $totalSuara) * 100, 1) : 0;
                                $ketua = $kandidat->anggota->firstWhere('peran', 'ketua');
                                $wakil = $kandidat->anggota->firstWhere('peran', 'wakil');
                            @endphp
                            <div class="border border-slate-200 rounded-lg p-4">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">No {{ $kandidat->nomor_urut }} - {{ $ketua?->pemilih?->nama ?? 'Ketua belum diisi' }}</p>
                                        <p class="text-sm text-slate-500">{{ $wakil?->pemilih?->nama ?? 'Wakil belum diisi' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-slate-900">{{ $suaraCount }} suara</p>
                                        <p class="text-xs text-slate-500">{{ $percent }}%</p>
                                    </div>
                                </div>
                                <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-2 rounded-full bg-slate-800" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
