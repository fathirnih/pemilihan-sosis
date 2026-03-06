@extends('layouts.admin')

@section('title', 'Suara - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Suara</h1>
                <p class="mt-1 text-slate-600">Kelola data suara pemilih.</p>
            </div>
            <a href="{{ route('admin.suara.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition-colors text-sm shadow-sm">
                Tambah Suara
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Pemilih</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">NISN/NIP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Kandidat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Waktu</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($suara as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item->pemilih?->nama }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->pemilih?->nisn }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $item->kandidat?->nomor_urut }} - {{ 
                                    $item->kandidat?->anggota?->firstWhere('peran', 'ketua')?->pemilih?->nama ?? 'Kandidat'
                                }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->created_at?->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.suara.edit', $item->id) }}" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors">Edit</a>
                                        <form action="{{ route('admin.suara.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus suara ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-600">Belum ada suara</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($suara->hasPages())
            <div class="mt-6">
                {{ $suara->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
