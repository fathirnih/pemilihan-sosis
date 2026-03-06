@extends('layouts.admin')

@section('title', 'Periode Pemilihan - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Periode Pemilihan</h1>
                <p class="mt-1 text-slate-600">Atur jadwal, status, dan mode pemilihan.</p>
            </div>
            <a href="{{ route('admin.periode.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition-colors text-sm shadow-sm">
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

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nama</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Mulai</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Selesai</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Mode</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($periodes as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item->nama_periode }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->mulai_pada->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->selesai_pada->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->mode_pasangan === 'ketua_wakil' ? 'Ketua & Wakil' : 'Ketua Saja' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $badge = $item->status === 'aktif'
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : ($item->status === 'ditutup' ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-700');
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.periode.edit', $item->id) }}" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors">Edit</a>
                                        <form action="{{ route('admin.toggle-periode', $item->id) }}" method="POST" onsubmit="return confirm('Ubah status periode ini?')" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-medium rounded transition-colors">
                                                {{ $item->status === 'aktif' ? 'Tutup' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.periode.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus periode ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">Hapus</button>
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
