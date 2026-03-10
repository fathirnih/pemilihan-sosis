@extends('layouts.admin')

@section('title', 'Edit Suara - Admin')

@section('admin.content')
<div class="admin-page">
    <div class="admin-form-container">
        <div class="mb-6">
            <h1 class="admin-title">Edit Suara</h1>
            <p class="admin-subtitle">Perbarui data suara pemilih.</p>
        </div>

        <a href="{{ route('admin.suara.index') }}" class="admin-back-link">
            &larr; Kembali ke daftar suara
        </a>

        <div class="admin-card admin-card-body">
            <form action="{{ route('admin.suara.update', $suara->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Periode</label>
                    <select name="periode_id" class="admin-select w-full" required onchange="window.location='{{ route('admin.suara.edit', $suara->id) }}?periode_id=' + this.value">
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" @selected(old('periode_id', $periodeId ?? $suara->periode_id) == $p->id)>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Pemilih</label>
                    <select name="pemilih_id" class="admin-select w-full" required>
                        <option value="">Pilih pemilih</option>
                        @foreach ($pemilih as $p)
                            <option value="{{ $p->id }}" @selected(old('pemilih_id', $suara->pemilih_id) == $p->id)>{{ $p->nama }} ({{ $p->nisn }})</option>
                        @endforeach
                    </select>
                    @error('pemilih_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Kandidat</label>
                    <select name="kandidat_id" class="admin-select w-full" required>
                        <option value="">Pilih kandidat</option>
                        @foreach ($kandidats as $k)
                            <option value="{{ $k->id }}" @selected(old('kandidat_id', $suara->kandidat_id) == $k->id)>{{ $k->nomor_urut }} - {{ $k->anggota?->firstWhere('peran','ketua')?->pemilih?->nama ?? 'Kandidat' }}</option>
                        @endforeach
                    </select>
                    @error('kandidat_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="flex-1 admin-btn admin-btn-primary justify-center">Simpan</button>
                    <a href="{{ route('admin.suara.index') }}" class="flex-1 admin-btn admin-btn-soft justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
