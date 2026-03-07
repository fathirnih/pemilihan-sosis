@extends('layouts.app')

@section('title', 'Pilih Kandidat')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="container-app">
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Akses Siswa</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">{{ $periode->nama_periode }}</h1>
                    <p class="mt-1 text-sm text-slate-600">Pilih pasangan kandidat satu kali untuk periode ini.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Keluar
                    </button>
                </form>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                    <span class="font-semibold text-emerald-700">Status:</span> Dibuka
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
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
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @foreach ($kandidats as $kandidat)
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-slate-300 hover:shadow-md">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nomor Urut</div>
                            <div class="mt-1 text-3xl font-bold text-slate-900">{{ $kandidat->nomor_urut }}</div>
                        </div>

                        <div class="p-6">
                            <div class="mb-5">
                                <h3 class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tim Kandidat</h3>
                                <div class="space-y-2">
                                    @foreach ($kandidat->anggota as $anggota)
                                        <div class="flex items-center gap-2 text-sm text-slate-700">
                                            <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                            <span>{{ $anggota->pemilih->nama ?? 'N/A' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Visi</h4>
                                <p class="text-sm leading-relaxed text-slate-700">{{ $kandidat->visi }}</p>
                            </div>

                            <div class="mb-6">
                                <h4 class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Misi</h4>
                                <p class="text-sm leading-relaxed text-slate-700">{{ Str::limit($kandidat->misi, 150) }}</p>
                            </div>

                            <form method="POST" action="{{ route('voting.store') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="kandidat_id" value="{{ $kandidat->id }}">
                                <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 font-semibold text-white transition-colors hover:bg-slate-800">
                                    Pilih Kandidat Ini
                                </button>
                            </form>
                        </div>
                    </div>
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
