@extends('layouts.panitia')

@section('title', 'Hasil Pemilihan - Panitia')

@section('panitia.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Belum Ada Periode Aktif</h1>
            <p class="text-slate-600 mb-6">Silahkan tunggu admin mengaktifkan periode pemilihan.</p>
            <form action="{{ route('panitia.logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
