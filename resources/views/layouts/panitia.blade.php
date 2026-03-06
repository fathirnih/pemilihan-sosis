@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="flex flex-col lg:flex-row">
        <aside id="panitiaSidebar" class="sidebar-root w-full lg:w-64 bg-white border-b lg:border-b-0 lg:border-r border-slate-100 px-4 lg:px-6 py-6">
            <div class="flex items-center justify-between lg:block">
                <div class="sidebar-brand rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                            <img src="{{ asset('storage/logo-osis.png') }}" alt="Logo" class="h-full w-full object-contain p-2" onerror="this.style.display='none'; this.parentElement.classList.add('bg-slate-100'); this.parentElement.innerHTML='<span class=&quot;text-slate-700 font-semibold&quot;>OS</span>';">
                        </div>
                        <div class="sidebar-header-text">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Panitia</p>
                            <h1 class="text-lg font-bold text-slate-900">Pemilihan OSIS</h1>
                            <p class="text-xs text-slate-500">{{ Session::get('panitia_nama') }}</p>
                        </div>
                    </div>
                </div>
                <form action="{{ route('panitia.logout') }}" method="POST" class="lg:hidden mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Logout
                    </button>
                </form>
            </div>

            <nav class="mt-8 space-y-4">
                <div>
                    <p class="sidebar-group-title text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 mb-2">Menu Panitia</p>
                    <div class="space-y-2">
                        <a href="{{ route('panitia.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('panitia.dashboard') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('panitia.dashboard') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12l9-9 9 9"></path>
                                    <path d="M9 21V9h6v12"></path>
                                </svg>
                            </span>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                        <a href="{{ route('panitia.results') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('panitia.results') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('panitia.results') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M7 13l3-3 4 4 5-6"></path>
                                </svg>
                            </span>
                            <span class="sidebar-label">Hasil Suara</span>
                        </a>
                        <a href="{{ route('panitia.report') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('panitia.report') ? 'bg-slate-100 text-slate-900' : '' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-slate-900 {{ request()->routeIs('panitia.report') ? 'bg-white text-slate-900' : '' }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                                    <path d="M14 2v6h6"></path>
                                    <path d="M8 13h8"></path>
                                    <path d="M8 17h8"></path>
                                </svg>
                            </span>
                            <span class="sidebar-label">Report</span>
                        </a>
                    </div>
                </div>
            </nav>

            <form action="{{ route('panitia.logout') }}" method="POST" class="mt-6 hidden lg:block">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Logout
                </button>
            </form>
        </aside>

        <main class="flex-1">
            @yield('panitia.content')
        </main>
    </div>
</div>
@endsection
