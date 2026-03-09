@extends('layouts.app')

@section('content')
 <div class="min-h-screen bg-slate-50">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <div id="page-loader" class="fixed inset-0 z-[9999] bg-slate-50/80 backdrop-blur-sm flex flex-col items-center justify-center transition-opacity duration-500">
    <div class="relative flex items-center justify-center">
        <div class="w-12 h-12 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin"></div>
        <div class="absolute text-indigo-600">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
    </div>
    <p class="mt-4 text-xs font-semibold tracking-widest text-indigo-600 uppercase animate-pulse">Memuat...</p>
</div>

    <div class="flex flex-col lg:flex-row">

        {{-- Backdrop --}}
        <div id="sidebarBackdrop" class="fixed inset-0 z-20 hidden bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>

        {{-- ══════════════════════════════════════
             SIDEBAR
        ══════════════════════════════════════ --}}
        <aside id="adminSidebar"
            class="fixed inset-y-0 left-0 z-30 w-72 -translate-x-full bg-white border-r border-slate-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:w-64 flex flex-col"
            style="box-shadow: 1px 0 0 0 #f1f5f9, 4px 0 24px rgba(0,0,0,0.04)">

            {{-- ── Brand ── --}}
            <div class="px-5 pt-5 pb-4 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm">
                            <img src="{{ asset('storage/logo-osis.png') }}" alt="Logo"
                                class="h-full w-full object-contain p-1.5"
                                onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:13px;font-weight:700;color:#fff\'>OS</span>'">
                        </div>
                        <div>
                            <p class="text-[9.5px] font-bold uppercase tracking-[0.25em] text-slate-400 leading-none mb-0.5">Admin Panel</p>
                            <h1 class="text-sm font-bold text-slate-900 leading-tight">Pemilihan OSIS</h1>
                        </div>
                    </div>
                    {{-- Mobile close --}}
                    <button type="button" id="sidebarClose"
                        class="lg:hidden h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Admin info strip --}}
                <div class="mt-3 flex items-center gap-2.5 px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="h-7 w-7 rounded-lg bg-slate-200 flex items-center justify-center flex-shrink-0">
                        <svg class="h-3.5 w-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 12c2.5 0 4.5-2 4.5-4.5S14.5 3 12 3 7.5 5 7.5 7.5 9.5 12 12 12z"/>
                            <path d="M4 21a8 8 0 0 1 16 0"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-700 truncate">Administrator</p>
                        <p class="text-[10px] text-slate-400 leading-none mt-0.5">Akses penuh</p>
                    </div>
                    <div class="ml-auto flex-shrink-0">
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100 px-1.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Navigation ── --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5">

                {{-- Menu Utama --}}
                <div>
                    <p class="text-[9.5px] font-bold uppercase tracking-[0.2em] text-slate-400 px-2 mb-1.5">Menu Utama</p>
                    <div class="space-y-0.5">

                        @php
                            $mainNav = [
                                [
                                    'route'  => 'admin.dashboard',
                                    'match'  => 'admin.dashboard',
                                    'label'  => 'Halaman Utama',
                                    'icon'   => '<path d="M3 12l9-9 9 9"/><path d="M9 21V9h6v12"/>',
                                ],
                                [
                                    'route'  => 'admin.pemilih.index',
                                    'match'  => 'admin.pemilih.*',
                                    'label'  => 'Data Pemilih',
                                    'icon'   => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                                ],
                                [
                                    'route'  => 'admin.kandidat.index',
                                    'match'  => 'admin.kandidat.*',
                                    'label'  => 'Kandidat',
                                    'icon'   => '<path d="M12 3v18"/><path d="M6 8h12"/><path d="M6 16h12"/>',
                                ],
                                [
                                    'route'  => 'admin.suara.index',
                                    'match'  => 'admin.suara.*',
                                    'label'  => 'Rekap Suara',
                                    'icon'   => '<path d="M3 3v18h18"/><path d="M7 13l3-3 4 4 5-6"/>',
                                ],
                            ];
                        @endphp

                        @foreach ($mainNav as $item)
                            @php $active = request()->routeIs($item['match']); @endphp
                            <a href="{{ route($item['route']) }}"
                                class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150
                                    {{ $active
                                        ? 'bg-slate-900 text-white shadow-sm'
                                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">

                                {{-- Active left indicator --}}
                                @if($active)
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-white/40 rounded-full -ml-0.5"></span>
                                @endif

                                <span class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                                    {{ $active
                                        ? 'bg-white/15 text-white'
                                        : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-slate-900 group-hover:shadow-sm' }}">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $item['icon'] !!}
                                    </svg>
                                </span>

                                <span class="truncate">{{ $item['label'] }}</span>

                                @if($active)
                                <svg class="ml-auto h-3.5 w-3.5 opacity-60 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M9 18l6-6-6-6"/>
                                </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Manajemen --}}
                <div>
                    <p class="text-[9.5px] font-bold uppercase tracking-[0.2em] text-slate-400 px-2 mb-1.5">Manajemen</p>
                    <div class="space-y-0.5">

                        @php
                            $mgmtNav = [
                                [
                                    'route'  => 'admin.kelas.index',
                                    'match'  => 'admin.kelas.*',
                                    'label'  => 'Data Kelas',
                                    'icon'   => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h6"/>',
                                ],
                                [
                                    'route'  => 'admin.periode.index',
                                    'match'  => 'admin.periode.*',
                                    'label'  => 'Periode Pemilihan',
                                    'icon'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>',
                                ],
                                [
                                    'route'  => 'admin.admins.index',
                                    'match'  => 'admin.admins.*',
                                    'label'  => 'Akun Admin',
                                    'icon'   => '<path d="M12 12c2.5 0 4.5-2 4.5-4.5S14.5 3 12 3 7.5 5 7.5 7.5 9.5 12 12 12z"/><path d="M4 21a8 8 0 0 1 16 0"/>',
                                ],
                                [
                                    'route'  => 'admin.panitia.index',
                                    'match'  => 'admin.panitia.*',
                                    'label'  => 'Akun Panitia',
                                    'icon'   => '<path d="M9 12h6"/><path d="M12 9v6"/><rect x="3" y="3" width="18" height="18" rx="2"/>',
                                ],
                            ];
                        @endphp

                        @foreach ($mgmtNav as $item)
                            @php $active = request()->routeIs($item['match']); @endphp
                            <a href="{{ route($item['route']) }}"
                                class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150
                                    {{ $active
                                        ? 'bg-slate-900 text-white shadow-sm'
                                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">

                                @if($active)
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-white/40 rounded-full -ml-0.5"></span>
                                @endif

                                <span class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                                    {{ $active
                                        ? 'bg-white/15 text-white'
                                        : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-slate-900 group-hover:shadow-sm' }}">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $item['icon'] !!}
                                    </svg>
                                </span>

                                <span class="truncate">{{ $item['label'] }}</span>

                                @if($active)
                                <svg class="ml-auto h-3.5 w-3.5 opacity-60 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M9 18l6-6-6-6"/>
                                </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

            </nav>

            {{-- ── Footer / Logout ── --}}
            <div class="px-3 py-4 border-t border-slate-100">
                {{-- Mobile logout --}}
                <form action="{{ route('admin.logout') }}" method="POST" class="lg:hidden mb-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Logout
                    </button>
                </form>

                {{-- Desktop logout --}}
                <form action="{{ route('admin.logout') }}" method="POST" class="hidden lg:block">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors group">
                        <span class="h-7 w-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 group-hover:border-slate-300 transition-colors">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                        </span>
                        <span>Keluar</span>
                    </button>
                </form>

                <p class="text-center text-[10px] text-slate-300 mt-3">Pemilihan OSIS &copy; {{ date('Y') }}</p>
            </div>

        </aside>

        {{-- ══════════════════════════════════════
             MAIN CONTENT
        ══════════════════════════════════════ --}}
        <main class="flex-1 min-w-0">

            {{-- Mobile topbar --}}
            <div class="sticky top-0 z-10 bg-white/90 backdrop-blur-sm border-b border-slate-200 lg:hidden">
                <div class="px-4 py-3 flex items-center justify-between gap-3">
                    <button type="button" id="sidebarToggle"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M3 12h18M3 18h18"/>
                        </svg>
                        Menu
                    </button>
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-slate-900 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/logo-osis.png') }}" alt="Logo" class="h-full w-full object-contain p-1"
                                onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:10px;font-weight:700;color:#fff\'>OS</span>'">
                        </div>
                        <span class="text-sm font-bold text-slate-900">Pemilihan OSIS</span>
                    </div>
                </div>
            </div>

            @yield('admin.content')
        </main>

    </div>
</div>

<script>
(function () {
    const sidebar  = document.getElementById('adminSidebar');
    const openBtn  = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (!sidebar) return;

    const open = () => {
        sidebar.classList.remove('-translate-x-full');
        backdrop?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };
    const close = () => {
        sidebar.classList.add('-translate-x-full');
        backdrop?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    openBtn?.addEventListener('click', open);
    closeBtn?.addEventListener('click', close);
    backdrop?.addEventListener('click', close);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            backdrop?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
})();
</script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Logika Preloader
    window.addEventListener('load', () => {
        const loader = document.getElementById('page-loader');
        if (loader) {
            // Fade out
            loader.classList.add('opacity-0');
            // Hilangkan dari DOM setelah transisi selesai
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    });

    // Opsional: Tampilkan loader lagi saat link menu di-klik
    document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"])').forEach(link => {
        link.addEventListener('click', function(e) {
            // Jangan trigger jika punya class specific atau link download
            if(!this.classList.contains('no-loader')) {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.style.display = 'flex';
                    setTimeout(() => loader.classList.remove('opacity-0'), 10);
                }
            }
        });
    });

    // Tangani jika user menekan tombol "Back" di browser
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.classList.add('opacity-0');
                setTimeout(() => loader.style.display = 'none', 500);
            }
        }
    });
</script>
@endsection