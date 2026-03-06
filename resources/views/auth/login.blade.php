@extends('layouts.app')

@section('title', 'Login - Pemilihan OSIS')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
    <!-- Header -->
    <div class="mb-12 text-center">
        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-white rounded-xl shadow-sm border border-slate-200">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 20H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2h-3m0 0H9m0 0a2 2 0 002 2h2a2 2 0 002-2m0-5V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v5"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Pemilihan OSIS</h1>
        <p class="text-slate-600">Masukkan NIS/NIP dan token anda</p>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                            <span class="text-sm font-medium text-red-800">Login Gagal</span>
                        </div>
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (request()->filled('logout_reason'))
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.981-1.742 2.981H4.42c-1.53 0-2.492-1.647-1.743-2.98l5.58-9.92zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V7a1 1 0 00-1-1z"/>
                            </svg>
                            <span class="text-sm font-medium text-amber-800">{{ request('logout_reason') }}</span>
                        </div>
                    </div>
                @endif

                

                

                <!-- NIS Input -->
                <div>
                    <label for="nis" class="block text-sm font-medium text-slate-700 mb-2">
                        🆔 NIS/NIP
                    </label>
                    <input
                        type="text"
                        id="nis"
                        name="nis"
                        value="{{ old('nis') }}"
                        required
                        placeholder="Conto: 2024001"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white text-slate-900 placeholder-slate-500 transition-all"
                    />
                </div>

                <!-- Token Input -->
                <div>
                    <label for="token" class="block text-sm font-medium text-slate-700 mb-2">
                        🎫 Token Pemilihan
                    </label>
                    <input
                        type="text"
                        id="token"
                        name="token"
                        value="{{ old('token') }}"
                        required
                        placeholder="Conto: VOTE-Kq8T6pxcyyIC"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white text-slate-900 placeholder-slate-500 transition-all"
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3L8 20H4v-4l9-8z"></path>
                    </svg>
                    Masuk
                </button>
            </form>

            <!-- Info Box -->
            <div class="mt-8 pt-8 border-t border-slate-200">
                <p class="text-sm text-slate-600 text-center">
                    🔓 Gunakan NIS/NIP dan token untuk login
                </p>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-sm text-slate-600">
            <p>Pemilihan dipimpin oleh Panitia OSIS {{ date('Y') }}</p>
        </div>
    </div>
</div>
@endsection
