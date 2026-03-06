@extends('layouts.admin')

@section('title', 'Edit Panitia - Admin')

@section('admin.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit Panitia</h1>
            <p class="mt-1 text-slate-600">Perbarui data akun panitia.</p>
        </div>

        <a href="{{ route('admin.panitia.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 mb-6">
            <- Kembali ke daftar panitia
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.panitia.update', $panitia->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $panitia->nama) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    @error('nama')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $panitia->username) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    @error('username')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $panitia->email) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $panitia->jabatan) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    @error('jabatan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Password (Opsional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" placeholder="Kosongkan jika tidak diubah">
                    @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="aktif" value="1" class="h-4 w-4" {{ old('aktif', $panitia->aktif) ? 'checked' : '' }}>
                    <span class="text-sm text-slate-700">Aktif</span>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Simpan</button>
                    <a href="{{ route('admin.panitia.index') }}" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition-colors text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
