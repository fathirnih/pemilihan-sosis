@extends('layouts.panitia')

@section('title', 'Report Panitia')

@section('panitia.content')
<div class="px-4 py-8 lg:px-8">
    <div class="max-w-6xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Export Report</h1>
            <p class="text-slate-600 mb-6">Download laporan hasil pemilihan dalam format Excel (CSV).</p>
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <label class="text-sm text-slate-600">Periode</label>
                <select name="periode_id" class="admin-select" onchange="this.form.submit()">
                    @foreach ($periodes as $p)
                        <option value="{{ $p->id }}" @selected((string) $p->id === (string) ($periodeId ?? $periode->id))>
                            {{ $p->nama_periode }}
                        </option>
                    @endforeach
                </select>
                <label class="text-sm text-slate-600">Jenis</label>
                <select name="jenis" class="admin-select" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="siswa" @selected($jenis === 'siswa')>Siswa</option>
                    <option value="guru" @selected($jenis === 'guru')>Guru</option>
                </select>
                <label class="text-sm text-slate-600">Tingkat</label>
                <select name="tingkat" class="admin-select" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach ($tingkatList as $t)
                        <option value="{{ $t }}" @selected((string) $t === (string) $tingkat)>{{ $t }}</option>
                    @endforeach
                </select>
                <label class="text-sm text-slate-600">Kelas</label>
                <select name="kelas_id" class="admin-select" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" @selected((string) $k->id === (string) $kelasId)>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
                <a href="{{ route('panitia.report.export', [
                    'periode_id' => $periodeId ?? $periode->id,
                    'jenis' => $jenis,
                    'tingkat' => $tingkat,
                    'kelas_id' => $kelasId,
                ]) }}" class="admin-btn admin-btn-primary">
                    Export Excel (CSV)
                </a>
            </form>
        </div>
    </div>
</div>

@endsection
