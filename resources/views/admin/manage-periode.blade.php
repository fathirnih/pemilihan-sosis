@extends('layouts.admin')

@section('title', 'Manage Periode - Admin')

@section('admin.content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold">Kelola Periode Pemilihan</h1>
            <p class="text-blue-100 mt-2">Buka atau tutup periode pemilihan</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            ← Kembali ke Dashboard
        </a>

        <!-- Current Status -->
        @if ($periode)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Periode Aktif Saat Ini</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Nama</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $periode->nama_periode }}</p>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Status</p>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium font-semibold">{{ ucfirst($periode->status) }}</span>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Mulai</p>
                        <p class="text-slate-900">{{ $periode->mulai_pada->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-600 text-sm mb-1">Selesai</p>
                        <p class="text-slate-900">{{ $periode->selesai_pada->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <!-- Action -->
                <form action="{{ route('admin.toggle-periode', $periode->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengubah status periode?');">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        🔐 Tutup Periode Ini
                    </button>
                </form>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <p class="text-yellow-800">⚠️ Tidak ada periode yang sedang aktif</p>
            </div>
        @endif

        <!-- All Periods -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Semua Periode</h2>
            
            @if ($allPeriodes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">#</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Nama</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Mulai</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Selesai</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allPeriodes as $index => $p)
                                <tr class="border-b border-slate-200 hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-900 font-medium">{{ $p->nama_periode }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $p->mulai_pada->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $p->selesai_pada->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($p->status === 'aktif')
                                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                                        @elseif ($p->status === 'ditutup')
                                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Ditutup</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 rounded-full text-xs font-medium">{{ ucfirst($p->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($p->status !== 'aktif')
                                            <form action="{{ route('admin.toggle-periode', $p->id) }}" method="POST" class="inline"  onsubmit="return confirm('Yakin ingin mengaktifkan periode ini?');">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-700 font-medium">Aktifkan</button>
                                            </form>
                                        @else
                                            <span class="text-slate-500 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-600">Tidak ada periode pemilihan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-slate-600 text-center py-6">Tidak ada periode pemilihan</p>
            @endif
        </div>
    </div>
</div>
@endsection
