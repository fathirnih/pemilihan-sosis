@extends('layouts.app')

@section('title', 'Login Staf')

@section('content')
<div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Portal Staf</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">Login</h2>
            <p class="mt-2 text-slate-600 text-sm">Gunakan email dan password yang terdaftar.</p>
        </div>

            @if (session('success'))
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    value="{{ old('email') }}"
                    placeholder="nama@pemilihan.local"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    placeholder="Masukkan password"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-slate-900 px-4 py-3 font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200"
            >
                Login
            </button>
        </form>

        <p class="mt-6 text-sm text-slate-600">
            <a href="{{ route('login') }}" class="font-medium text-slate-700 hover:text-slate-900">← Kembali ke login pemilih</a>
        </p>
    </div>
</div>
@endsection
