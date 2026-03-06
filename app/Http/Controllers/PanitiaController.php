<?php

namespace App\Http\Controllers;

use App\Models\PeriodePemilihan;
use App\Models\Suara;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PanitiaController extends Controller
{
    /**
     * Panitia dashboard (summary)
     */
    public function dashboard()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            return view('panitia.no-periode');
        }

        $kandidats = $periode->kandidat()->with(['anggota.pemilih', 'suara'])->get();
        $totalSuara = $periode->suara()->count();
        $topKandidats = $kandidats->sortByDesc(fn ($k) => $k->suara->count())->take(3);

        return view('panitia.dashboard', compact('periode', 'kandidats', 'totalSuara', 'topKandidats'));
    }

    /**
     * Panitia view results (read-only)
     */
    public function viewResults(Request $request)
    {
        $periodes = PeriodePemilihan::orderBy('mulai_pada', 'desc')->get();
        if ($periodes->isEmpty()) {
            return view('panitia.no-periode');
        }

        $periodeId = $request->get('periode_id');
        $periode = $periodeId
            ? $periodes->firstWhere('id', (int) $periodeId)
            : $periodes->firstWhere('status', 'aktif');

        if (!$periode) {
            $periode = $periodes->first();
        }

        $kandidats = $periode->kandidat()->with(['anggota.pemilih', 'suara'])->get();
        $totalSuara = $periode->suara()->count();

        return view('panitia.results', compact('periode', 'periodes', 'periodeId', 'kandidats', 'totalSuara'));
    }

    /**
     * Panitia report (rekap suara + daftar pemilih yang sudah memilih)
     */
    public function report(Request $request)
    {
        $periodes = PeriodePemilihan::orderBy('mulai_pada', 'desc')->get();
        if ($periodes->isEmpty()) {
            return view('panitia.no-periode');
        }

        $periodeId = $request->get('periode_id');
        $jenis = $request->get('jenis');
        $tingkat = $request->get('tingkat');
        $kelasId = $request->get('kelas_id');
        $periode = $periodeId
            ? $periodes->firstWhere('id', (int) $periodeId)
            : $periodes->firstWhere('status', 'aktif');

        if (!$periode) {
            $periode = $periodes->first();
        }

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tingkatList = Kelas::select('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');

        $suara = Suara::with(['pemilih.kelas', 'kandidat.anggota.pemilih'])
            ->where('periode_id', $periode->id)
            ->when($jenis, function ($query) use ($jenis) {
                $query->whereHas('pemilih', fn ($q) => $q->where('jenis', $jenis));
            })
            ->when($tingkat, function ($query) use ($tingkat) {
                $query->whereHas('pemilih.kelas', fn ($q) => $q->where('tingkat', $tingkat));
            })
            ->when($kelasId, function ($query) use ($kelasId) {
                $query->whereHas('pemilih', fn ($q) => $q->where('kelas_id', $kelasId));
            })
            ->orderBy('created_at')
            ->get();

        $rekap = $periode->kandidat()->with(['anggota.pemilih', 'suara'])->get()
            ->sortByDesc(fn ($k) => $k->suara->count())
            ->values();

        $totalSuara = $suara->count();

        return view('panitia.report', compact(
            'periode',
            'periodes',
            'periodeId',
            'suara',
            'rekap',
            'totalSuara',
            'kelasList',
            'tingkatList',
            'jenis',
            'tingkat',
            'kelasId'
        ));
    }

    /**
     * Export report to CSV (Excel)
     */
    public function exportReportExcel(Request $request)
    {
        $periodes = PeriodePemilihan::orderBy('mulai_pada', 'desc')->get();
        if ($periodes->isEmpty()) {
            return redirect()->route('panitia.report')->withErrors(['periode' => 'Belum ada periode pemilihan.']);
        }

        $periodeId = $request->get('periode_id');
        $jenis = $request->get('jenis');
        $tingkat = $request->get('tingkat');
        $kelasId = $request->get('kelas_id');
        $periode = $periodeId
            ? $periodes->firstWhere('id', (int) $periodeId)
            : $periodes->firstWhere('status', 'aktif');

        if (!$periode) {
            $periode = $periodes->first();
        }

        $suara = Suara::with(['pemilih.kelas', 'kandidat.anggota.pemilih'])
            ->where('periode_id', $periode->id)
            ->when($jenis, function ($query) use ($jenis) {
                $query->whereHas('pemilih', fn ($q) => $q->where('jenis', $jenis));
            })
            ->when($tingkat, function ($query) use ($tingkat) {
                $query->whereHas('pemilih.kelas', fn ($q) => $q->where('tingkat', $tingkat));
            })
            ->when($kelasId, function ($query) use ($kelasId) {
                $query->whereHas('pemilih', fn ($q) => $q->where('kelas_id', $kelasId));
            })
            ->orderBy('created_at')
            ->get();

        $filename = 'report_pemilihan_' . $periode->id . '.csv';

        return response()->streamDownload(function () use ($suara, $periode) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Periode',
                'NISN/NIP',
                'Nama',
                'Jenis',
                'Tingkat',
                'Kelas',
                'Nomor Kandidat',
                'Waktu Memilih',
            ]);

            foreach ($suara as $item) {
                $pemilih = $item->pemilih;
                $kelas = $pemilih?->kelas;
                fputcsv($handle, [
                    $periode->nama_periode,
                    $pemilih?->nisn ?? '-',
                    $pemilih?->nama ?? '-',
                    $pemilih?->jenis ?? '-',
                    $kelas?->tingkat ?? '-',
                    $kelas?->nama_kelas ?? '-',
                    $item->kandidat?->nomor_urut ?? '-',
                    optional($item->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Panitia logout
     */
    public function logout()
    {
        Session::forget(['panitia_id', 'panitia_username', 'panitia_nama']);
        return redirect()->route('admin.login')->with('success', 'Logout berhasil');
    }
}
