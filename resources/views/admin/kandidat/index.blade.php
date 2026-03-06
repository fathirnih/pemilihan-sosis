@extends('layouts.admin')

@section('title', 'Kandidat - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kandidat</h1>
                <p class="mt-1 text-slate-600">Kelola kandidat per periode pemilihan.</p>
            </div>
            <a href="{{ route('admin.kandidat.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition-colors text-sm shadow-sm">
                Tambah Kandidat
            </a>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <p class="text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Periode</label>
                    <select name="periode_id" class="rounded-lg border border-slate-300 px-3 py-2" onchange="this.form.submit()">
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected((string) $p->id === (string) $periodeId)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nomor Urut</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Ketua</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Wakil</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Foto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Visi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($kandidats as $item)
                            @php
                                $ketua = $item->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama;
                                $wakil = $item->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item->nomor_urut }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $ketua ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $wakil ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto kandidat" class="h-10 w-10 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($item->visi, 60) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.kandidat.edit', $item->id) }}" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors">Edit</a>
                                        <form action="{{ route('admin.kandidat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kandidat ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-600">Belum ada kandidat</td>
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
