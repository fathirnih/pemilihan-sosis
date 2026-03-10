@extends('layouts.admin')

@section('title', 'Kelola Pemilih - Admin')

@section('admin.content')
 
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    .font-inter { font-family: 'Inter', sans-serif; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    
    @keyframes bounce-short {
        0%, 100% { transform: translate(-50%, 0); }
        50% { transform: translate(-50%, -5px); }
    }
    .animate-bounce-short { animation: bounce-short 3s ease-in-out infinite; }

     @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;  
    }

     .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .delay-500 { animation-delay: 500ms; }
</style>

<div class="p-4 lg:p-8 font-inter">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kelola Pemilih</h1>
            <p class="text-slate-500 mt-1">Manajemen database pemilih, pembuatan token, dan pemantauan status kehadiran.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pemilih.show-import') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition-all font-medium text-sm shadow-sm">
                <i class="w-4 h-4" data-lucide="upload"></i>
                Import CSV
            </a>
            <a href="{{ route('admin.pemilih.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all font-medium text-sm shadow-lg shadow-indigo-100">
                <i class="w-4 h-4" data-lucide="user-plus"></i>
                Tambah Pemilih
            </a>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
            <p class="text-emerald-800 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 mb-6">
            <ul class="text-rose-800 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pemilih</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\TokenPemilih::count() }}</h3>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                    <i class="w-6 h-6" data-lucide="users"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-xs text-slate-400">Total data keseluruhan</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Token Aktif</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\TokenPemilih::where('status', 'aktif')->count() }}</h3>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                    <i class="w-6 h-6" data-lucide="key"></i>
                </div>
            </div>
            <div class="mt-4">
                @php
                    $total = \App\Models\TokenPemilih::count();
                    $aktif = \App\Models\TokenPemilih::where('status', 'aktif')->count();
                    $persenAktif = $total > 0 ? round(($aktif / $total) * 100) : 0;
                @endphp
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-500 h-full" style="width: {{ $persenAktif }}%"></div>
                </div>
                <p class="text-xs text-slate-400 mt-2">{{ $persenAktif }}% token telah digenerate/aktif</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Suara Masuk</p>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\TokenPemilih::where('sudah_memilih', true)->count() }}</h3>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-600 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                    <i class="w-6 h-6" data-lucide="vote"></i>
                </div>
            </div>
            <div class="mt-4">
                @php
                    $masuk = \App\Models\TokenPemilih::where('sudah_memilih', true)->count();
                    $persenMasuk = $total > 0 ? round(($masuk / $total) * 100, 1) : 0;
                @endphp
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full" style="width: {{ $persenMasuk }}%"></div>
                </div>
                <p class="text-xs text-slate-400 mt-2">Partisipasi {{ $persenMasuk }}%</p>
            </div>
        </div>
    </section>

    <section class="bg-white border border-slate-200 rounded-xl p-4 mb-6 shadow-sm flex flex-col lg:flex-row items-center gap-4">
        <form method="GET" id="filter-form" class="flex flex-col lg:flex-row items-center gap-4 w-full m-0">
            <div class="relative w-full lg:w-96">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="w-4 h-4" data-lucide="search"></i>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" data-filter-input class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder:text-slate-400 transition-all" placeholder="Cari nama atau nomor induk..."/>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <select name="periode_id" data-filter-input class="block w-full lg:w-40 py-2 pl-3 pr-10 border border-slate-200 bg-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all appearance-none">
                    <option value="">Semua Periode</option>
                    @foreach ($periodeList as $p)
                        <option value="{{ $p->id }}" @selected(request('periode_id') == $p->id)>{{ $p->nama_periode }}</option>
                    @endforeach
                </select>

                <select name="tingkat" data-filter-input class="block w-full lg:w-32 py-2 pl-3 pr-10 border border-slate-200 bg-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all appearance-none">
                    <option value="">Tingkat</option>
                    <option value="1" @selected(request('tingkat') === '1')>Kelas 10</option>
                    <option value="2" @selected(request('tingkat') === '2')>Kelas 11</option>
                    <option value="3" @selected(request('tingkat') === '3')>Kelas 12</option>
                </select>

                <select name="kelas_id" data-filter-input class="block w-full lg:w-32 py-2 pl-3 pr-10 border border-slate-200 bg-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all appearance-none">
                    <option value="">Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected((string) $kelas->id === (string) request('kelas_id'))>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>

                

                <div class="h-8 w-px bg-slate-200 mx-1 hidden lg:block"></div>
                
                <a href="{{ route('admin.pemilih.index') }}" class="inline-flex items-center gap-2 px-3 py-2 text-slate-600 hover:text-indigo-600 text-sm font-medium transition-colors">
                    <i class="w-4 h-4" data-lucide="refresh-cw"></i> Reset
                </a>
            </div>
        </form>
    </section>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
       <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">
                    <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"/>
                </th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Pemilih</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Periode</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas / Jurusan</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Token Akses</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($pemilih as $row)
                @php 
                    $token = $row->tokens->first(); 
                    $inisial = strtoupper(substr($row->nama, 0, 2));
                    $colors = ['bg-indigo-100 text-indigo-600', 'bg-slate-100 text-slate-600', 'bg-emerald-100 text-emerald-600', 'bg-amber-100 text-amber-600'];
                    $colorIndex = $row->id % count($colors);
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="pemilih-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" value="{{ $row->id }}"/>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $colors[$colorIndex] }} flex items-center justify-center font-bold text-xs">
                                {{ $inisial }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $row->nama }}</p>
                                <p class="text-xs text-slate-500">{{ $row->nisn }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 border border-slate-200 text-xs font-medium text-slate-600 whitespace-nowrap">
                            <i class="w-3.5 h-3.5 text-slate-400" data-lucide="calendar"></i>
                            {{ $row->periodePemilihan?->nama_periode ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                        @php
                            $kelasLabel = '-';
                            if ($row->kelas) {
                                $kelasLabel = trim(
                                    ($row->kelas->tingkat ? $row->kelas->tingkat . ' ' : '') .
                                    ($row->kelas->nama_kelas ?? '')
                                );
                            }
                        @endphp
                        {{ $kelasLabel !== '' ? $kelasLabel : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($token)
                            <code class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-semibold text-slate-700 tracking-widest">{{ $token->token }}</code>
                        @else
                            <span class="text-xs italic text-slate-400">Belum di-generate</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if ($token)
                            @if ($token->sudah_memilih)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Digunakan
                                </span>
                            @elseif ($token->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></span> Nonaktif
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></span> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.pemilih.edit', $row->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                <i class="w-4 h-4" data-lucide="edit-2"></i>
                            </a>
                            
                            @if ($token)
                            <form action="{{ route('admin.pemilih.reset-token', $row->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Reset token ini?')">
                                @csrf
                                <button type="submit" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Reset Token">
                                    <i class="w-4 h-4" data-lucide="rotate-ccw"></i>
                                </button>
                            </form>
                            @else
                            <div class="w-8 h-8"></div> {{-- Spacer --}}
                            @endif

                            @if ($token)
                            <form action="{{ route('admin.pemilih.hapus-satu-token', $row->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Hapus token pemilih ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus Token">
                                    <i class="w-4 h-4" data-lucide="trash-2"></i>
                                </button>
                            </form>
                            @else
                            <div class="w-8 h-8"></div>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <p class="text-sm font-medium">Belum ada data pemilih.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

        {{ $pemilih->links('vendor.pagination.custom') }}
    </div>

    <div id="bulkActionBar" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 hidden items-center gap-2 bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-800 z-50 animate-bounce-short">
    <div class="flex items-center gap-2 pr-4 border-r border-slate-700">
        <span id="selectedCount" class="w-5 h-5 bg-indigo-500 rounded-full flex items-center justify-center text-[10px] font-bold">0</span>
        <span class="text-sm font-medium">Terpilih</span>
    </div>
    <div class="flex items-center gap-1 pl-2">
        
        {{-- Form Generate Terpilih --}}
        <form action="{{ route('admin.pemilih.generate-token') }}" method="POST" class="m-0" id="form-generate-terpilih">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-3 py-1.5 hover:bg-slate-800 rounded-lg transition-colors text-sm">
                <i class="w-4 h-4 text-indigo-400" data-lucide="key"></i> Generate
            </button>
        </form>

        {{-- Form Cetak Terpilih --}}
        <form action="{{ route('admin.pemilih.print-tokens') }}" method="GET" target="_blank" class="m-0" id="form-cetak-terpilih">
            <button type="submit" class="flex items-center gap-2 px-3 py-1.5 hover:bg-slate-800 rounded-lg transition-colors text-sm">
                <i class="w-4 h-4 text-slate-400" data-lucide="printer"></i> Cetak Terpilih
            </button>
        </form>

        {{-- Form Hapus Terpilih --}}
        <form action="{{ route('admin.pemilih.hapus-token-semua') }}" method="POST" class="m-0" id="form-hapus-terpilih" onsubmit="return confirm('Hapus token yang dipilih?')">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-3 py-1.5 hover:bg-red-900/50 text-red-400 rounded-lg transition-colors text-sm">
                <i class="w-4 h-4" data-lucide="trash-2"></i> Hapus Terpilih
            </button>
        </form>

    </div>
    <button type="button" id="closeBulkBar" class="ml-2 p-1 hover:text-slate-400 transition-colors">
        <i class="w-4 h-4" data-lucide="x"></i>
    </button>
</div>
</div>

 
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Inisialisasi Lucide Icon jika ada
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // 2. Logika Auto-Submit Filter
    const form = document.getElementById('filter-form');
    const inputs = form.querySelectorAll('[data-filter-input]');
    let timer;

    inputs.forEach((el) => {
        if (el.tagName === 'SELECT') {
            el.addEventListener('change', () => form.submit());
        } else {
            el.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => form.submit(), 500);
            });
        }
    });

    // 3. Logika Bulk Action Bar & Dynamic IDs (Cross-Pagination)
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.pemilih-checkbox');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');
    const closeBulkBar = document.getElementById('closeBulkBar');
    const filterForm = document.getElementById('filter-form');

    function updateBulkBar() {
        const checkedBoxes = document.querySelectorAll('.pemilih-checkbox:checked');
        const count = checkedBoxes.length;

        const forms = [
            document.getElementById('form-generate-terpilih'),
            document.getElementById('form-cetak-terpilih'),
            document.getElementById('form-hapus-terpilih')
        ];

        // Bersihkan input dinamis sebelumnya
        forms.forEach(form => {
            if(form) form.querySelectorAll('.dynamic-input').forEach(el => el.remove());
        });

        if (count > 0) {
            bulkActionBar.classList.remove('hidden');
            bulkActionBar.classList.add('flex');

            // Cek apakah Checkbox Master (Select All) yang dicentang
            const isSelectAll = selectAllCheckbox && selectAllCheckbox.checked;

            if (isSelectAll) {
                // Tampilkan teks "Semua" karena kita memilih lintas pagination
                selectedCount.textContent = "Semua";
                
                // Ambil semua nilai filter yang sedang aktif di atas (Periode, Kelas, dll)
                const filterData = new FormData(filterForm);

                forms.forEach(form => {
                    if(form) {
                        // Tambahkan flag penanda bahwa kita ingin memproses SEMUA halaman
                        form.insertAdjacentHTML('beforeend', '<input type="hidden" name="select_all_pages" value="1" class="dynamic-input">');
                        
                        // Copy parameter filter ke dalam form bulk action
                        for (let [key, value] of filterData.entries()) {
                            if (value) {
                                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="${key}" value="${value}" class="dynamic-input">`);
                            }
                        }
                    }
                });
            } else {
                // Jika hanya mencentang beberapa secara manual
                selectedCount.textContent = count;
                checkedBoxes.forEach(cb => {
                    forms.forEach(form => {
                        if(form) {
                            form.insertAdjacentHTML('beforeend', `<input type="hidden" name="ids[]" value="${cb.value}" class="dynamic-input">`);
                        }
                    });
                });
            }
        } else {
            bulkActionBar.classList.add('hidden');
            bulkActionBar.classList.remove('flex');
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkBar();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    if (closeBulkBar) {
        closeBulkBar.addEventListener('click', function() {
            itemCheckboxes.forEach(cb => cb.checked = false);
            updateBulkBar();
        });
    }
});
</script>
@endsection
