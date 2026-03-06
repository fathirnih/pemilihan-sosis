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

        <!-- Candidates Results -->
        @if ($kandidats->count() > 0)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Hasil Sementara Kandidat</h3>
                <div class="space-y-4">
                    @foreach ($kandidats as $kandidat)
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-slate-900">Nomor Urut {{ $kandidat->nomor_urut }}</p>
                                    <p class="text-sm text-slate-600">{{ $kandidat->visi }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-600">{{ $kandidat->suara->count() }}</p>
                                    <p class="text-xs text-slate-500">suara</p>
                                </div>
                            </div>
                            <div class="bg-slate-100 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalSuara > 0 ? ($kandidat->suara->count() / $totalSuara * 100) : 0 }}%"></div>
                            </div>
                            <p class="text-xs text-slate-600 mt-2">{{ $totalSuara > 0 ? round($kandidat->suara->count() / $totalSuara * 100, 1) : 0 }}%</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
