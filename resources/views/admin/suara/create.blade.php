@extends('layouts.admin')

@section('title', 'Tambah Suara - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Tambah Suara</h1>
            <p class="mt-1 text-slate-600">Input manual suara pemilih.</p>
        </div>

        <a href="{{ route('admin.suara.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            ? Kembali ke daftar suara
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.suara.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Periode</label>
                    <select name="periode_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected(old('periode_id') == $p->id)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Pemilih</label>
                    <select name="pemilih_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        <option value="">Pilih pemilih</option>
                        @foreach ($pemilih as $p)
                            <option value="{{ $p->id }}" @selected(old('pemilih_id') == $p->id)>{{ $p->nama }} ({{ $p->nisn }})</option>
                        @endforeach
                    </select>
                    @error('pemilih_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Kandidat</label>
                    <select name="kandidat_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        <option value="">Pilih kandidat</option>
                        @foreach ($kandidats as $k)
                            <option value="{{ $k->id }}" @selected(old('kandidat_id') == $k->id)>{{ $k->nomor_urut }} - {{ $k->anggota?->firstWhere('peran','ketua')?->pemilih?->nama ?? 'Kandidat' }}</option>
                        @endforeach
                    </select>
                    @error('kandidat_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Simpan</button>
                    <a href="{{ route('admin.suara.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
