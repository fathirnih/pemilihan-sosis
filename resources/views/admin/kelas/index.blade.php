@extends('layouts.admin')

@section('title', 'Kelola Kelas - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kelas</h1>
                <p class="mt-1 text-slate-600">Kelola data kelas untuk pemilih siswa.</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition-colors text-sm shadow-sm">
                Tambah Kelas
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nama Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Tingkat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Jumlah Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($kelas as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item->nama_kelas }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->tingkat }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->siswa_count }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.kelas.edit', $item->id) }}" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-600">Belum ada data kelas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($kelas->hasPages())
            <div class="mt-6">
                {{ $kelas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
