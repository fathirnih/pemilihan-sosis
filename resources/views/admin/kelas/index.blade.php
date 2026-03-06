@extends('layouts.admin')

@section('title', 'Kelola Kelas - Admin')

@section('admin.content')
<div class="min-h-screen bg-slate-50">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8 px-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Kelola Kelas</h1>
                <p class="text-blue-100 mt-2">CRUD data kelas untuk dipakai pada input token</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition-colors">
                Tambah Kelas
            </a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            Kembali ke Dashboard
        </a>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-red-800">{{ $errors->first() }}</p>
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
                                        <a href="{{ route('admin.kelas.edit', $item->id) }}" class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
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
