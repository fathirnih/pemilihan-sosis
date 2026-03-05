@extends('layouts.app')

@section('title', 'Kelola Token - Admin')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Kelola Token Pemilih</h1>
                    <p class="text-blue-100 mt-2">Buat dan kelola token untuk siswa</p>
                </div>
                <a href="{{ route('admin.tokens.create') }}" class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                    ➕ Token Baru
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            ← Kembali ke Dashboard
        </a>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800">✅ {{ session('success') }}</p>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-slate-600 text-sm mb-1">Total Token</p>
                <p class="text-3xl font-bold text-slate-900">{{ \App\Models\TokenPemilih::count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-slate-600 text-sm mb-1">Token Aktif</p>
                <p class="text-3xl font-bold text-green-600">{{ \App\Models\TokenPemilih::where('status', 'aktif')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-slate-600 text-sm mb-1">Sudah Digunakan</p>
                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\TokenPemilih::where('sudah_memilih', true)->count() }}</p>
            </div>
        </div>

        <!-- Tokens Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">NIS</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Token</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($tokens as $index => $token)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $token->nama_pemilih }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $token->nis_pemilih }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <code class="bg-slate-100 px-3 py-1 rounded text-xs font-mono">{{ $token->token }}</code>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($token->status === 'aktif' && !$token->sudah_memilih)
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Aktif</span>
                                    @elseif ($token->sudah_memilih)
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">✓ Digunakan</span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Kadaluarsa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a 
                                            href="{{ route('admin.tokens.edit', $token->id) }}" 
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded transition-colors"
                                        >
                                            ✏️ Edit
                                        </a>
                                        <a 
                                            href="{{ route('admin.tokens.print', $token->id) }}" 
                                            target="_blank"
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors"
                                        >
                                            🖨️ Cetak
                                        </a>
                                        <form action="{{ route('admin.tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Hapus token ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors"
                                            >
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-600">
                                    <p class="text-lg">Belum ada token. <a href="{{ route('admin.tokens.create') }}" class="text-blue-600 hover:text-blue-700 font-medium">Buat token baru</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($tokens->hasPages())
            <div class="mt-6">
                {{ $tokens->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
