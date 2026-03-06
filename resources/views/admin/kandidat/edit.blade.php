@extends('layouts.admin')

@section('title', 'Edit Kandidat - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Kandidat</h1>
            <p class="mt-1 text-slate-600">Perbarui data kandidat.</p>
        </div>

        <a href="{{ route('admin.kandidat.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            ? Kembali ke daftar kandidat
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.kandidat.update', $kandidat->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Periode</label>
                    <select name="periode_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected(old('periode_id', $kandidat->periode_id) == $p->id)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nomor Urut</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $kandidat->nomor_urut) }}" min="1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    @error('nomor_urut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Ketua</label>
                    <select name="ketua_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                        <option value="">Pilih ketua</option>
                        @foreach ($pemilih as $p)
                            <option value="{{ $p->id }}" @selected(old('ketua_id', $ketuaId) == $p->id)>{{ $p->nama }} ({{ $p->nisn }})</option>
                        @endforeach
                    </select>
                    @error('ketua_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Wakil (Opsional)</label>
                    <select name="wakil_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                        <option value="">Tidak ada</option>
                        @foreach ($pemilih as $p)
                            <option value="{{ $p->id }}" @selected(old('wakil_id', $wakilId) == $p->id)>{{ $p->nama }} ({{ $p->nisn }})</option>
                        @endforeach
                    </select>
                    @error('wakil_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Visi</label>
                    <textarea name="visi" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>{{ old('visi', $kandidat->visi) }}</textarea>
                    @error('visi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Misi</label>
                    <textarea name="misi" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>{{ old('misi', $kandidat->misi) }}</textarea>
                    @error('misi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Foto</label>
                    <input type="file" name="foto" accept="image/png,image/jpeg" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                    @if ($kandidat->foto)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $kandidat->foto) }}" alt="Foto kandidat" class="h-20 w-20 rounded-lg object-cover border border-slate-200">
                        </div>
                    @endif
                    <p class="text-xs text-slate-500 mt-2">Upload baru untuk mengganti foto. Maks 2MB.</p>
                    @error('foto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Simpan</button>
                    <a href="{{ route('admin.kandidat.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
