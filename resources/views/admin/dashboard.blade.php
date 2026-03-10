@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin.content')
<div class="admin-page space-y-8">
    <div class="admin-container space-y-8">
    
    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h1 class="admin-title">Ringkasan Utama</h1>
            <p class="admin-subtitle">Pantau progres pemungutan suara secara real-time</p>
        </div>
        <button onclick="window.location.reload()" class="admin-btn admin-btn-primary active:scale-95">
            <span class="material-symbols-rounded text-xl">refresh</span>
            <span>Refresh Data</span>
        </button>
    </div>

    {{-- Status Periode --}}
    <section class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm overflow-hidden relative">
        @if ($periode)
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="size-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                    <span class="material-symbols-rounded text-3xl">event_available</span>
                </div>
                <div>
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Periode Aktif</h3>
                    <p class="text-xl font-bold text-slate-900">{{ $periode->nama_periode }}</p>
                    <p class="text-xs text-slate-500 font-medium flex items-center gap-2 mt-1">
                        <span class="material-symbols-rounded text-sm">calendar_month</span>
                        {{ $periode->mulai_pada->format('d M Y') }} - {{ $periode->selesai_pada->format('d M Y') }} 
                        <span class="text-slate-300">|</span>
                        <span class="material-symbols-rounded text-sm">schedule</span>
                        {{ $periode->mulai_pada->format('H:i') }} - {{ $periode->selesai_pada->format('H:i') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center">
                <span class="px-5 py-2 rounded-2xl bg-emerald-50 text-emerald-600 text-xs font-black uppercase tracking-widest flex items-center gap-2 border border-emerald-100">
                    <span class="size-2 bg-emerald-500 rounded-full animate-ping"></span>
                    {{ $periode->status }}
                </span>
            </div>
        </div>
        @else
        <div class="flex items-center gap-4 text-orange-600 bg-orange-50 p-4 rounded-2xl border border-orange-100">
            <span class="material-symbols-rounded text-3xl">warning</span>
            <p class="font-bold text-sm">Tidak ada periode pemilihan yang aktif saat ini.</p>
        </div>
        @endif
    </section>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Token', 'value' => number_format($totalTokens), 'icon' => 'toll', 'color' => 'slate', 'sub' => 'Terdaftar'],
                ['label' => 'Token Aktif', 'value' => number_format($tokensAktif), 'icon' => 'verified', 'color' => 'emerald', 'sub' => 'Siap Pakai'],
                ['label' => 'Sudah Memilih', 'value' => number_format($sudahMemilih), 'icon' => 'how_to_reg', 'color' => 'indigo', 'sub' => ($totalTokens > 0 ? round(($sudahMemilih / $totalTokens) * 100, 1) : 0) . '% Partisipasi'],
                ['label' => 'Total Suara', 'value' => number_format($totalSuara), 'icon' => 'equalizer', 'color' => 'purple', 'sub' => 'Suara Masuk'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative group transition-all hover:-translate-y-1">
            <div class="flex justify-between items-start mb-4">
                <div class="size-12 rounded-2xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 flex items-center justify-center transition-colors group-hover:bg-{{ $stat['color'] }}-600 group-hover:text-white">
                    <span class="material-symbols-rounded text-2xl">{{ $stat['icon'] }}</span>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider italic">{{ $stat['sub'] }}</span>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $stat['label'] }}</p>
            <h4 class="text-3xl font-black text-slate-900 mt-1">{{ $stat['value'] }}</h4>
        </div>
        @endforeach
    </div>

    @if ($kandidats->count() > 0)
    {{-- Hasil Suara --}}
    <div class="space-y-6 pt-4">
        <div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Hasil Sementara</h3>
            <p class="text-sm text-slate-500 font-medium">Urutan perolehan suara tertinggi saat ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($topKandidats as $index => $kandidat)
                @php
                    $suaraCount = $kandidat->suara->count();
                    $percent = $totalSuara > 0 ? round(($suaraCount / $totalSuara) * 100, 1) : 0;
                    $ketua = $kandidat->anggota->firstWhere('peran', 'ketua');
                    $wakil = $kandidat->anggota->firstWhere('peran', 'wakil');
                    
                    $styles = [
                        0 => ['border' => 'border-amber-400', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'icon' => 'workspace_premium', 'bar' => 'bg-amber-400'],
                        1 => ['border' => 'border-slate-200', 'bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'icon' => 'military_tech', 'bar' => 'bg-slate-400'],
                        2 => ['border' => 'border-orange-200', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'icon' => 'looks_3', 'bar' => 'bg-orange-400']
                    ];
                    $current = $styles[$index] ?? $styles[1];
                @endphp

                <div class="bg-white rounded-[2.5rem] border {{ $current['border'] }} p-6 shadow-xl relative overflow-hidden flex flex-col items-center text-center transition-transform hover:scale-[1.02]">
                    <div class="absolute top-0 right-0 p-6">
                        <span class="text-5xl font-black text-slate-100">#{{ $kandidat->nomor_urut }}</span>
                    </div>

                    {{-- Rank Badge --}}
                    <div class="mb-6 flex flex-col items-center">
                        <div class="size-12 rounded-full {{ $current['bg'] }} {{ $current['text'] }} flex items-center justify-center mb-2 shadow-sm">
                            <span class="material-symbols-rounded text-2xl">{{ $current['icon'] }}</span>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] {{ $current['text'] }}">Peringkat {{ $index + 1 }}</span>
                    </div>

                    {{-- Photo --}}
                   <div class="flex gap-4">
                        <div class="size-20 rounded-full overflow-hidden border-2 border-white shadow">
                            <img src="{{ $kandidat->foto_ketua ? asset('storage/' . $kandidat->foto_ketua) : asset('images/default-user.png') }}" class="size-full object-cover">
                        </div>
                        
                        <div class="size-20 rounded-full overflow-hidden border-2 border-white shadow">
                            <img src="{{ $kandidat->foto_wakil ? asset('storage/' . $kandidat->foto_wakil) : asset('images/default-user.png') }}" class="size-full object-cover">
                        </div>
                    </div>

                    <h4 class="text-lg font-black text-slate-900 leading-tight mb-1 truncate w-full px-4">
                        {{ $ketua?->pemilih?->nama ?? 'Nama Ketua' }}
                    </h4>
                    <p class="text-xs font-bold text-slate-400 mb-4 italic">w/ {{ $wakil?->pemilih?->nama ?? 'Nama Wakil' }}</p>

                    <div class="w-full mt-auto pt-6 border-t border-slate-50">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-2xl font-black text-slate-900">{{ number_format($suaraCount) }} <small class="text-[10px] uppercase text-slate-400">Suara</small></span>
                            <span class="text-sm font-black {{ $current['text'] }}">{{ $percent }}%</span>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $current['bar'] }} rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    </div>
</div>
@endsection
