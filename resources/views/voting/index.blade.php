@extends('layouts.app')

@section('title', 'Pilih Kandidat')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="container-app">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-1">{{ $periode->nama_periode }}</h1>
                    <p class="text-slate-600">Pilih pasangan kandidat pilihan Anda</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-600 hover:text-slate-900 font-medium text-sm">
                        Keluar
                    </button>
                </form>
            </div>

            <!-- Timeline Info -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span class="text-slate-700">Status: <strong class="text-green-600">Dibuka</strong></span>
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H5.75A4.25 4.25 0 1 0 10 18.5h.5m0-17v11m0 0l3-3m-3 3l-3-3"/>
                        </svg>
                        <span class="text-slate-600">Berakhir: <strong>{{ $periode->selesai_pada->format('d M Y, H:i') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        @if ($kandidats->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Tidak Ada Kandidat</h3>
                <p class="text-slate-600">Kandidat belum tersedia untuk pemilihan ini</p>
            </div>
        @else
            <!-- Candidates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($kandidats as $kandidat)
                    <div class="group">
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all h-full flex flex-col">
                            <!-- Candidate Card Header -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-slate-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-slate-600">NOMOR URUT</div>
                                        <div class="text-3xl font-bold text-blue-600 mt-1">{{ $kandidat->nomor_urut }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="p-6 flex-1 flex flex-col">
                                <!-- Team Members -->
                                <div class="mb-6">
                                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Tim Kandidat</h3>
                                    <div class="space-y-2">
                                        @foreach ($kandidat->anggota as $anggota)
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                <span class="text-sm text-slate-700">{{ $anggota->pemilih->nama ?? 'N/A' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Visi -->
                                <div class="mb-4">
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Visi</h4>
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $kandidat->visi }}</p>
                                </div>

                                <!-- Misi -->
                                <div class="mb-6 flex-1">
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Misi</h4>
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ Str::limit($kandidat->misi, 150) }}</p>
                                </div>

                                <!-- Vote Button -->
                                <form method="POST" action="{{ route('voting.store') }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="kandidat_id" value="{{ $kandidat->id }}">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors">
                                        Pilih Kandidat Ini
                                    </button>
                                </form>
                            </div>
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
