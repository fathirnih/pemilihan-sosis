@extends('layouts.panitia')

@section('title', 'Panitia - Periode Belum Aktif')

@section('panitia.content')
<div class="admin-page">
    <div class="admin-container max-w-3xl">
        <section class="admin-card overflow-hidden">
            <div class="admin-card-body text-center py-12">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-slate-900">Belum Ada Periode Aktif</h1>
                <p class="mt-2 text-sm text-slate-500">Silakan tunggu admin mengaktifkan periode pemilihan sebelum monitoring dimulai.</p>

                <form action="{{ route('panitia.logout') }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-primary">
                        Logout Panitia
                    </button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
