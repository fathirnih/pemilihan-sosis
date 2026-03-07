@extends('layouts.admin')

@section('title', 'Kandidat - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Kandidat</h1>
                <p class="admin-subtitle">Kelola kandidat per periode pemilihan.</p>
            </div>
            <a href="{{ route('admin.kandidat.create') }}" class="admin-btn admin-btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                Tambah Kandidat
            </a>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="admin-card admin-card-body mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Periode</label>
                    <select name="periode_id" class="admin-select" onchange="this.form.submit()">
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected((string) $p->id === (string) $periodeId)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead class="admin-thead">
                        <tr>
                            <th class="admin-th">No</th>
                            <th class="admin-th">Nomor Urut</th>
                            <th class="admin-th">Ketua</th>
                            <th class="admin-th">Wakil</th>
                            <th class="admin-th">Foto Pasangan</th>
                            <th class="admin-th">Foto Ketua</th>
                            <th class="admin-th">Foto Wakil</th>
                            <th class="admin-th">Visi</th>
                            <th class="admin-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($kandidats as $item)
                            @php
                                $ketua = $item->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                                $wakil = $item->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="admin-td text-slate-900">{{ $loop->iteration }}</td>
                                <td class="admin-td text-slate-900 font-medium">{{ $item->nomor_urut }}</td>
                                <td class="admin-td text-slate-700">{{ $ketua ?? '-' }}</td>
                                <td class="admin-td text-slate-700">{{ $wakil ?? '-' }}</td>
                                <td class="admin-td">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto kandidat" class="h-10 w-10 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="admin-td">
                                    @if ($item->foto_ketua)
                                        <img src="{{ asset('storage/' . $item->foto_ketua) }}" alt="Foto ketua" class="h-10 w-10 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="admin-td">
                                    @if ($item->foto_wakil)
                                        <img src="{{ asset('storage/' . $item->foto_wakil) }}" alt="Foto wakil" class="h-10 w-10 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="admin-td text-slate-600">{{ \Illuminate\Support\Str::limit($item->visi, 60) }}</td>
                                <td class="admin-td">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.kandidat.edit', $item->id) }}" class="admin-btn admin-btn-warning text-xs px-3 py-2">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.kandidat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kandidat ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger text-xs px-3 py-2">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4h8v2"></path>
                                                    <path d="M6 6l1 14h10l1-14"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-600">Belum ada kandidat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($kandidats->hasPages())
            <div class="mt-6">
                {{ $kandidats->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
