@extends('layouts.app')

@section('title', 'Konfirmasi Pilihan')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4">
    <div class="w-full max-w-md">
        <!-- Success Message -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 text-center">
            <!-- Icon -->
            <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-green-50 rounded-full border border-green-200">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mb-2">Suara Anda Tercatat</h1>
            <p class="text-slate-600 mb-6">Terima kasih telah berpartisipasi dalam pemilihan OSIS</p>

            <!-- Candidate Info -->
            <div class="bg-slate-50 rounded-lg p-6 mb-8 text-left">
                <p class="text-sm text-slate-600 mb-2">Anda memilih:</p>
                <p class="text-lg font-bold text-slate-900">Nomor Urut {{ $suara->kandidat->nomor_urut }}</p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-3a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        Suara Anda bersifat rahasia dan tidak dapat diubah
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors">
                        Selesai
                    </button>
                </form>
                <a href="{{ route('results.index') }}" class="w-full block bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium py-3 rounded-lg transition-colors">
                    Lihat Hasil Sementara
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
