@extends('layouts.admin')

@section('title', 'Periode Pemilihan - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Periode Pemilihan</h1>
                <p class="admin-subtitle">Atur jadwal, status, dan mode pemilihan.</p>
            </div>
            <a href="{{ route('admin.periode.create') }}" class="admin-btn admin-btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                Tambah Periode
            </a>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 mb-6">
                <p class="text-rose-800">{{ $errors->first() }}</p>
            </div>
        @endif

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead class="admin-thead">
                        <tr>
                            <th class="admin-th">No</th>
                            <th class="admin-th">Nama</th>
                            <th class="admin-th">Mulai</th>
                            <th class="admin-th">Selesai</th>
                            <th class="admin-th">Mode</th>
                            <th class="admin-th">Status</th>
                            <th class="admin-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($periodes as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="admin-td text-slate-900">{{ $loop->iteration }}</td>
                                <td class="admin-td text-slate-900 font-medium">{{ $item->nama_periode }}</td>
                                <td class="admin-td text-slate-600">{{ $item->mulai_pada->format('d M Y H:i') }}</td>
                                <td class="admin-td text-slate-600">{{ $item->selesai_pada->format('d M Y H:i') }}</td>
                                <td class="admin-td text-slate-600">{{ $item->mode_pasangan === 'ketua_wakil' ? 'Ketua & Wakil' : 'Ketua Saja' }}</td>
                                <td class="admin-td">
                                    @php
                                        $badge = $item->status === 'aktif'
                                            ? 'admin-badge-success'
                                            : ($item->status === 'ditutup' ? 'bg-rose-50 text-rose-700' : 'admin-badge-muted');
                                    @endphp
                                    <span class="admin-badge {{ $badge }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="admin-td">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.periode.edit', $item->id) }}" class="admin-btn admin-btn-warning text-xs px-3 py-2">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.toggle-periode', $item->id) }}" method="POST" onsubmit="return confirm('Ubah status periode ini?')" class="inline">
                                            @csrf
                                            <button type="submit" class="admin-btn admin-btn-soft text-xs px-3 py-2">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M5 12h14"></path>
                                                    <path d="M12 5v14"></path>
                                                </svg>
                                                {{ $item->status === 'aktif' ? 'Tutup' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.periode.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus periode ini?')" class="inline">
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
                                <td colspan="7" class="px-6 py-8 text-center text-slate-600">Belum ada periode pemilihan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($periodes->hasPages())
            <div class="mt-6">
                {{ $periodes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
