@extends('layouts.admin')

@section('title', 'Kelola Token - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Token</h2>
                <p class="mt-1 text-slate-600">Buat dan kelola token untuk siswa.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tokens.create') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-lg transition-colors inline-flex items-center gap-2">
                    Token Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <p class="text-slate-600 text-sm mb-1">Total Token</p>
                <p class="text-3xl font-bold text-slate-900">{{ \App\Models\TokenPemilih::count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <p class="text-slate-600 text-sm mb-1">Token Aktif</p>
                <p class="text-3xl font-bold text-emerald-600">{{ \App\Models\TokenPemilih::where('status', 'aktif')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <p class="text-slate-600 text-sm mb-1">Sudah Digunakan</p>
                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\TokenPemilih::where('sudah_memilih', true)->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">NIS</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Kelas</th>
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
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $token->kelas?->nama_kelas ?? $token->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <code class="bg-slate-100 px-3 py-1 rounded text-xs font-mono">{{ $token->token }}</code>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($token->status === 'aktif' && !$token->sudah_memilih)
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Aktif</span>
                                    @elseif ($token->sudah_memilih)
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Digunakan</span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Kadaluarsa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.tokens.edit', $token->id) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded transition-colors">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.tokens.print', $token->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded transition-colors">
                                            Cetak
                                        </a>
                                        <form action="{{ route('admin.tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Hapus token ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-600">
                                    <p class="text-lg">Belum ada token. <a href="{{ route('admin.tokens.create') }}" class="text-blue-600 hover:text-blue-700 font-medium">Buat token baru</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($tokens->hasPages())
            <div class="mt-6">
                {{ $tokens->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
