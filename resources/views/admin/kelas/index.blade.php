@extends('layouts.admin')

@section('title', 'Kelola Kelas - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Kelas</h1>
                <p class="admin-subtitle">Kelola data kelas untuk pemilih siswa.</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="admin-btn admin-btn-primary">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
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

        <section class="admin-metrics">
            <div class="admin-metric-card">
                <p class="admin-metric-label">Total Kelas</p>
                <h3 class="admin-metric-value">{{ \App\Models\Kelas::count() }}</h3>
                <p class="admin-metric-sub">Semua tingkat</p>
            </div>
            <div class="admin-metric-card">
                <p class="admin-metric-label">Total Siswa</p>
                <h3 class="admin-metric-value">{{ \App\Models\Pemilih::where('jenis', 'siswa')->count() }}</h3>
                <p class="admin-metric-sub">Terdaftar sebagai pemilih</p>
            </div>
            <div class="admin-metric-card">
                <p class="admin-metric-label">Kelas Berisi Siswa</p>
                <h3 class="admin-metric-value">{{ \App\Models\Kelas::has('siswa')->count() }}</h3>
                <p class="admin-metric-sub">Kelas aktif</p>
            </div>
        </section>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead class="admin-thead">
                        <tr>
                            <th class="admin-th">No</th>
                            <th class="admin-th">Nama Kelas</th>
                            <th class="admin-th">Tingkat</th>
                            <th class="admin-th">Jumlah Siswa</th>
                            <th class="admin-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($kelas as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="admin-td text-slate-900">{{ $loop->iteration }}</td>
                                <td class="admin-td text-slate-900 font-medium">{{ $item->nama_kelas }}</td>
                                <td class="admin-td text-slate-600">{{ $item->tingkat }}</td>
                                <td class="admin-td text-slate-600">{{ $item->siswa_count }}</td>
                                <td class="admin-td">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.kelas.edit', $item->id) }}" class="admin-btn admin-btn-warning text-xs px-3 py-2">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')" class="inline">
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
                                <td colspan="5" class="px-6 py-8 text-center text-slate-600">Belum ada data kelas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($kelas->hasPages())
            <div class="mt-6">
                {{ $kelas->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection
