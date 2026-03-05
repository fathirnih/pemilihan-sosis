@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
                    <p class="text-blue-100">Selamat datang, {{ Session::get('admin_nama') }}</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 px-6 py-2 rounded-lg font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Tokens -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Total Token</p>
                        <p class="text-3xl font-bold text-slate-900">{{ $totalTokens }}</p>
                    </div>
                    <div class="text-4xl text-blue-600 opacity-20">📋</div>
                </div>
            </div>

            <!-- Active Tokens -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Token Aktif</p>
                        <p class="text-3xl font-bold text-green-600">{{ $tokensAktif }}</p>
                    </div>
                    <div class="text-4xl text-green-600 opacity-20">✓</div>
                </div>
            </div>

            <!-- Sudah Memilih -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Sudah Memilih</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $sudahMemilih }}</p>
                    </div>
                    <div class="text-4xl text-blue-600 opacity-20">🗳️</div>
                </div>
            </div>

            <!-- Total Suara -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 text-sm font-medium mb-2">Total Suara</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $totalSuara }}</p>
                    </div>
                    <div class="text-4xl text-purple-600 opacity-20">📊</div>
                </div>
            </div>
        </div>

        <!-- Active Period -->
        @if ($periode)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Periode Aktif</h2>
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
                <p class="text-yellow-800">⚠️ Tidak ada periode pemilihan yang aktif</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.tokens.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-center">
                📄 Generate Token
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-center">
                🏫 Kelas
            </a>
            <a href="{{ route('admin.manage-periode') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-center">
                🔧 Manage Periode
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-600 hover:bg-slate-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-center">
                🔄 Refresh
            </a>
        </div>

        <!-- Candidates Results -->
        @if ($kandidats->count() > 0)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Hasil Sementara Kandidat</h2>
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
