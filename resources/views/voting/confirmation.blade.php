@extends('layouts.app')

@section('title', 'Konfirmasi Pilihan')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm sm:p-8">
            <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50">
                <svg class="h-8 w-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
            </div>

            <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Konfirmasi Siswa</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Suara Anda Tercatat</h1>
            <p class="mt-2 text-sm text-slate-600">Terima kasih telah berpartisipasi dalam pemilihan OSIS.</p>

            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5 text-left">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pilihan Anda</p>
                <p class="mt-2 text-lg font-bold text-slate-900">Nomor Urut {{ $suara->kandidat->nomor_urut }}</p>
            </div>

            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4 text-left text-sm text-blue-800">
                Suara bersifat rahasia dan tidak dapat diubah.
            </div>

            <div class="mt-6 space-y-3">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 font-semibold text-white transition-colors hover:bg-slate-800">
                        Selesai
                    </button>
                </form>
                <a href="{{ route('results.index') }}" class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 font-semibold text-slate-700 transition-colors hover:bg-slate-100">
                    Lihat Hasil Sementara
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
