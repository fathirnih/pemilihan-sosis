@extends('layouts.app')

@section('title', 'Login - Pemilihan OSIS')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="mx-auto w-full max-w-md">
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-100">
                    <img src="{{ asset('storage/logo-osis.png') }}" alt="Logo OSIS" class="h-full w-full object-contain p-2" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=&quot;text-slate-700 font-semibold&quot;>OS</span>';">
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Akses Siswa</p>
                    <h1 class="text-xl font-bold text-slate-900">Login Pemilihan OSIS</h1>
                    <p class="text-xs text-slate-500">Gunakan NIS/NIP dan token voting</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                        <div class="mb-2 flex items-center gap-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                            <span class="text-sm font-medium text-red-800">Login Gagal</span>
                        </div>
                        <ul class="space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (request()->filled('logout_reason'))
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.981-1.742 2.981H4.42c-1.53 0-2.492-1.647-1.743-2.98l5.58-9.92zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V7a1 1 0 00-1-1z"/>
                            </svg>
                            <span class="text-sm font-medium text-amber-800">{{ request('logout_reason') }}</span>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="nis" class="mb-2 block text-sm font-semibold text-slate-700">NIS/NIP</label>
                    <input
                        type="text"
                        id="nis"
                        name="nis"
                        value="{{ old('nis') }}"
                        required
                        placeholder="Contoh: 2024001"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-500 focus:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200"
                    />
                </div>

                <div>
                    <label for="token" class="mb-2 block text-sm font-semibold text-slate-700">Token Pemilihan</label>
                    <input
                        type="text"
                        id="token"
                        name="token"
                        value="{{ old('token') }}"
                        required
                        placeholder="Contoh: VOTE-ABC123XYZ"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-500 focus:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200"
                    />
                </div>

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 py-3 font-semibold text-white transition-colors hover:bg-slate-800"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 012 2v4M9 21H5a2 2 0 01-2-2v-4M21 9l-9 9m0 0l-3-3m3 3l3 3"></path>
                    </svg>
                    Masuk
                </button>
            </form>

            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                Pastikan token belum pernah dipakai. Jika login gagal, hubungi panitia untuk validasi token.
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-slate-500">Panitia Pemilihan OSIS {{ date('Y') }}</p>
    </div>
</div>
@endsection
