@extends('layouts.admin')

@section('title', 'Kelola Pemilih - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Pemilih</h2>
                <p class="mt-1 text-slate-600">Kelola data pemilih dan token pemilihan.</p>
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
                <p class="text-3xl font-bold text-slate-900">{{ \App\Models\TokenPemilih::where('status', 'aktif')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <p class="text-slate-600 text-sm mb-1">Sudah Digunakan</p>
                <p class="text-3xl font-bold text-slate-900">{{ \App\Models\TokenPemilih::where('sudah_memilih', true)->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-4">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <a href="{{ route('admin.pemilih.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3.5 py-2 rounded-lg transition-colors text-sm shadow-sm">
                    Pemilih Baru
                </a>
                <a href="{{ route('admin.pemilih.show-import') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-3.5 py-2 rounded-lg transition-colors text-sm shadow-sm">
                    Import Pemilih
                </a>
                <form action="{{ route('admin.pemilih.generate-token') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3.5 py-2 rounded-lg transition-colors text-sm shadow-sm">
                        Generate Token Semua
                    </button>
                </form>
                <form action="{{ route('admin.pemilih.hapus-token-semua') }}" method="POST" onsubmit="return confirm('Hapus semua token dan semua suara di periode ini?')">
                    @csrf
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-3.5 py-2 rounded-lg transition-colors text-sm shadow-sm">
                        Hapus Semua Token
                    </button>
                </form>
                <form action="{{ route('admin.pemilih.print-tokens') }}" method="GET" target="_blank" class="inline">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="periode_id" value="{{ request('periode_id') }}">
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                    <input type="hidden" name="tingkat" value="{{ request('tingkat') }}">
                    <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                    <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-semibold px-3.5 py-2 rounded-lg transition-colors text-sm shadow-sm">
                        Cetak Token (Filter)
                    </button>
                </form>
            </div>

            <form method="GET" id="filter-form" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Periode</label>
                    <select name="periode_id" class="w-full rounded-lg border border-slate-300 px-3 py-2" data-filter-input>
                        <option value="">Semua Periode</option>
                        @foreach ($periodeList as $p)
                            <option value="{{ $p->id }}" @selected(request('periode_id') == $p->id)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cari</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama atau NISN/NIP" class="w-full rounded-lg border border-slate-300 px-3 py-2" data-filter-input>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis</label>
                    <select name="jenis" class="w-full rounded-lg border border-slate-300 px-3 py-2" data-filter-input>
                        <option value="">Semua</option>
                        <option value="siswa" @selected(request('jenis') === 'siswa')>Siswa</option>
                        <option value="guru" @selected(request('jenis') === 'guru')>Guru</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tingkat</label>
                    <select name="tingkat" class="w-full rounded-lg border border-slate-300 px-3 py-2" data-filter-input>
                        <option value="">Semua</option>
                        <option value="1" @selected(request('tingkat') === '1')>1</option>
                        <option value="2" @selected(request('tingkat') === '2')>2</option>
                        <option value="3" @selected(request('tingkat') === '3')>3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
                    <select name="kelas_id" class="w-full rounded-lg border border-slate-300 px-3 py-2" data-filter-input>
                        <option value="">Semua</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected((string) $kelas->id === (string) request('kelas_id'))>{{ $kelas->nama_kelas }} ({{ $kelas->tingkat }})</option>
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">NISN/NIP</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nama Pemilih</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Periode</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Tingkat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Token</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($pemilih as $index => $row)
                            @php
                                $token = $row->tokens->first();
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $row->nisn }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $row->nama }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $row->periodePemilihan?->nama_periode ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $row->kelas?->tingkat ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $row->kelas?->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($token)
                                        <code class="bg-slate-100 px-3 py-1 rounded text-xs font-mono">{{ $token->token }}</code>
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($token)
                                        @if ($token->status === 'aktif' && !$token->sudah_memilih)
                                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">Aktif</span>
                                        @elseif ($token->sudah_memilih)
                                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">Digunakan</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">Kadaluarsa</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.pemilih.edit', $row->id) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded transition-colors">
                                            Edit
                                        </a>
                                        @if ($token)
                                            <form action="{{ route('admin.pemilih.reset-token', $row->id) }}" method="POST" onsubmit="return confirm('Reset token dan hapus suara pemilih ini?')" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-medium rounded transition-colors">
                                                    Reset
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.pemilih.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus pemilih ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-600">
                                    <p class="text-lg">Belum ada data pemilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($pemilih->hasPages())
            <div class="mt-6">
                {{ $pemilih->links() }}
            </div>
        @endif
    </div>
</div>
<script>
    (function () {
        const form = document.getElementById('filter-form');
        const inputs = form.querySelectorAll('[data-filter-input]');
        let timer;

        function submitWithDebounce() {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 400);
        }

        inputs.forEach((el) => {
            el.addEventListener('input', submitWithDebounce);
            el.addEventListener('change', () => form.submit());
        });
    })();
</script>
@endsection
