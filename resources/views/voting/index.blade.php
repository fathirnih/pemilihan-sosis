@extends('layouts.app')

@section('title', 'Pilih Kandidat')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="container-app">
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Akses Siswa</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">{{ $periode->nama_periode }}</h1>
                    <p class="mt-1 text-sm text-slate-600">Pilih satu pasangan kandidat. Pilihan tidak dapat diubah.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Keluar
                    </button>
                </form>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                    <span class="font-semibold text-emerald-700">Status:</span> Dibuka
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700 md:col-span-2">
                    <span class="font-semibold text-slate-900">Berakhir:</span> {{ $periode->selesai_pada->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        @if ($kandidats->isEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto mb-4 h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mb-2 text-lg font-semibold text-slate-900">Tidak Ada Kandidat</h3>
                <p class="text-slate-600">Kandidat belum tersedia untuk pemilihan ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                @foreach ($kandidats as $kandidat)
                    @php
                        $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                        $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                        $fotoPasanganUrl = $kandidat->foto ? asset('storage/' . $kandidat->foto) : null;
                        $fotoKetuaUrl = $kandidat->foto_ketua ? asset('storage/' . $kandidat->foto_ketua) : $fotoPasanganUrl;
                        $fotoWakilUrl = $kandidat->foto_wakil ? asset('storage/' . $kandidat->foto_wakil) : $fotoPasanganUrl;
                    @endphp
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-slate-300 hover:shadow-md">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Pasangan Kandidat</p>
                                    <h2 class="mt-1 text-lg font-bold text-slate-900 sm:text-xl">Nomor Urut {{ $kandidat->nomor_urut }}</h2>
                                </div>
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-white text-xl font-bold text-slate-900 shadow-sm">{{ $kandidat->nomor_urut }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 border-b border-slate-200 bg-slate-50/40 p-5 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Ketua</p>
                                @if ($fotoKetuaUrl)
                                    <img src="{{ $fotoKetuaUrl }}" alt="Foto ketua {{ $ketua ?? 'kandidat' }}" class="h-40 w-full rounded-lg border border-slate-200 object-cover">
                                @else
                                    <div class="flex h-40 w-full items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-8 10a4 4 0 00-4 4h16a4 4 0 00-4-4H8z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Wakil</p>
                                @if ($fotoWakilUrl)
                                    <img src="{{ $fotoWakilUrl }}" alt="Foto wakil {{ $wakil ?? 'kandidat' }}" class="h-40 w-full rounded-lg border border-slate-200 object-cover">
                                @else
                                    <div class="flex h-40 w-full items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-8 10a4 4 0 00-4 4h16a4 4 0 00-4-4H8z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="p-5 sm:p-6">
                            <div class="mb-4 flex items-start justify-between gap-4">
                                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Siap Dipilih</span>
                                @if ($fotoPasanganUrl)
                                    <a href="{{ $fotoPasanganUrl }}" target="_blank" class="text-xs font-semibold text-slate-500 hover:text-slate-800">Lihat foto pasangan</a>
                                @endif
                            </div>

                            <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Ketua</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $ketua ?? '-' }}</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Wakil</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $wakil ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h3 class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Visi</h3>
                                <p class="text-sm leading-relaxed text-slate-700">{{ $kandidat->visi }}</p>
                            </div>

                            <div class="mb-5">
                                <h3 class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Misi</h3>
                                <p class="text-sm leading-relaxed text-slate-700">{{ Str::limit($kandidat->misi, 170) }}</p>
                            </div>

                            <form method="POST" action="{{ route('voting.store') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="kandidat_id" value="{{ $kandidat->id }}">
                                <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 font-semibold text-white transition-colors hover:bg-slate-800">
                                    Pilih Kandidat Ini
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
<script>
    (() => {
        const statusUrl = @json(route('voting.status'));
        const loginUrl = @json(route('login'));
        let redirected = false;

        const checkVotingStatus = async () => {
            if (redirected) return;

            try {
                const response = await fetch(statusUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (response.status === 401) {
                    let message = 'Periode pemilihan sudah ditutup. Silakan login kembali.';
                    try {
                        const payload = await response.json();
                        if (payload && payload.message) {
                            message = payload.message;
                        }
                    } catch (error) {
                        // Keep default message when response body is not JSON.
                    }

                    redirected = true;
                    const separator = loginUrl.includes('?') ? '&' : '?';
                    window.location.href = `${loginUrl}${separator}logout_reason=${encodeURIComponent(message)}`;
                }
            } catch (error) {
                // Ignore temporary network issues and retry on next interval.
            }
        };

        checkVotingStatus();
        setInterval(checkVotingStatus, 5000);
    })();
</script>
@endsection
