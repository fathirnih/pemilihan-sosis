@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="flex flex-col lg:flex-row">
        <div id="sidebarBackdrop" class="fixed inset-0 z-20 hidden bg-slate-900/30 lg:hidden"></div>
        <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-30 w-72 -translate-x-full bg-white border-r border-slate-100 px-4 lg:px-6 py-6 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:w-64 lg:border-b-0 lg:border-r lg:shadow-none">
            <div class="flex items-center justify-between lg:block">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                            <img src="{{ asset('storage/logo-osis.png') }}" alt="Logo" class="h-full w-full object-contain p-2" onerror="this.style.display='none'; this.parentElement.classList.add('bg-slate-100'); this.parentElement.innerHTML='<span class=&quot;text-slate-700 font-semibold&quot;>OS</span>';">
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Admin Panel</p>
                            <h1 class="text-lg font-bold text-slate-900">Pemilihan OSIS</h1>
                            <p class="text-xs text-slate-500">Administrator</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 lg:mt-3">
                    <button type="button" id="sidebarClose" class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="lg:hidden">
                    @csrf
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                        Logout
                    </button>
                </form>
            </div>

            <nav class="mt-8 space-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 mb-2">Menu Utama</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12l9-9 9 9"></path>
                                    <path d="M9 21V9h6v12"></path>
                                </svg>
                            </span>
                            <span>Halaman Utama</span>
                        </a>
                        <a href="{{ route('admin.pemilih.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.pemilih.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.pemilih.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </span>
                            <span>Data Pemilih</span>
                        </a>
                        <a href="{{ route('admin.kandidat.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.kandidat.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.kandidat.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3v18"></path>
                                    <path d="M6 8h12"></path>
                                    <path d="M6 16h12"></path>
                                </svg>
                            </span>
                            <span>Kandidat</span>
                        </a>
                        <a href="{{ route('admin.suara.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.suara.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.suara.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M7 13l3-3 4 4 5-6"></path>
                                </svg>
                            </span>
                            <span>Rekap Suara</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 mb-2">Manajemen</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.kelas.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.kelas.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.kelas.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                    <path d="M7 8h10"></path>
                                    <path d="M7 12h10"></path>
                                    <path d="M7 16h6"></path>
                                </svg>
                            </span>
                            <span>Data Kelas</span>
                        </a>
                        <a href="{{ route('admin.periode.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.periode.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.periode.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                    <path d="M16 2v4"></path>
                                    <path d="M8 2v4"></path>
                                    <path d="M3 10h18"></path>
                                </svg>
                            </span>
                            <span>Periode Pemilihan</span>
                        </a>
                        <a href="{{ route('admin.admins.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.admins.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.admins.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 12c2.5 0 4.5-2 4.5-4.5S14.5 3 12 3 7.5 5 7.5 7.5 9.5 12 12 12z"></path>
                                    <path d="M4 21a8 8 0 0 1 16 0"></path>
                                </svg>
                            </span>
                            <span>Akun Admin</span>
                        </a>
                        <a href="{{ route('admin.panitia.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.panitia.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('admin.panitia.*') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12h6"></path>
                                    <path d="M12 9v6"></path>
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                </svg>
                            </span>
                            <span>Akun Panitia</span>
                        </a>
                    </div>
                </div>
            </nav>

            <form action="{{ route('admin.logout') }}" method="POST" class="mt-6 hidden lg:block">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Logout
                </button>
            </form>
        </aside>

        <main class="flex-1">
            <div class="sticky top-0 z-10 bg-slate-50/90 backdrop-blur border-b border-slate-200 lg:hidden">
                <div class="px-4 py-3 flex items-center justify-between">
                    <button type="button" id="sidebarToggle" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18"></path>
                            <path d="M3 12h18"></path>
                            <path d="M3 18h18"></path>
                        </svg>
                        Menu
                    </button>
                </div>
            </div>
            @yield('admin.content')
        </main>
    </div>
</div>
<script>
    (function () {
        const sidebar = document.getElementById('adminSidebar');
        const openBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (!sidebar) return;

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            backdrop?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.matchMedia('(min-width: 1024px)').matches) {
                sidebar.classList.remove('-translate-x-full');
                backdrop?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    })();
</script>
@endsection
