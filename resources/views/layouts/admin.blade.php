@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="flex flex-col lg:flex-row">
        <aside class="w-full lg:w-64 bg-white border-b lg:border-b-0 lg:border-r border-slate-200 px-4 lg:px-6 py-6">
            <div class="flex items-center justify-between lg:block">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Admin</p>
                    <h1 class="mt-2 text-2xl font-bold text-slate-900">Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-600">{{ Session::get('admin_nama') }}</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="lg:hidden">
                    @csrf
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                        Logout
                    </button>
                </form>
            </div>

            <nav class="mt-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900' : '' }}">
                    Ringkasan
                </a>
                <a href="{{ route('admin.tokens.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ request()->routeIs('admin.tokens.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                    Token
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ request()->routeIs('admin.kelas.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                    Kelas
                </a>
                <a href="{{ route('admin.manage-periode') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ request()->routeIs('admin.manage-periode') ? 'bg-slate-100 text-slate-900' : '' }}">
                    Periode
                </a>
                <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                    Refresh
                </a>
            </nav>

            <form action="{{ route('admin.logout') }}" method="POST" class="mt-6 hidden lg:block">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                    Logout
                </button>
            </form>
        </aside>

        <main class="flex-1">
            @yield('admin.content')
        </main>
    </div>
</div>
@endsection
