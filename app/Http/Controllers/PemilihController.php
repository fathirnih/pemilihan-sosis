<?php

namespace App\Http\Controllers;

use App\Models\Pemilih;
use App\Models\TokenPemilih;
use App\Models\PeriodePemilihan;
use App\Models\Kelas;
use App\Models\Suara;
use App\Models\KandidatAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\DB;

class PemilihController extends Controller
{
    public function index()
    {
        $selectedPeriode = request('periode_id') 
            ? PeriodePemilihan::find(request('periode_id'))
            : null;

        if ($selectedPeriode) {
            $periodeIdForTokens = $selectedPeriode->id;
        } else {
            $defaultPeriode = PeriodePemilihan::where('status', 'aktif')->first() 
                              ?? PeriodePemilihan::orderByDesc('id')->first();
            $periodeIdForTokens = $defaultPeriode?->id ?? -1;
        }

        $query = Pemilih::with([
            'kelas',
            'periodePemilihan',
            'tokens' => function ($query) use ($periodeIdForTokens) {
                $query->where('periode_id', $periodeIdForTokens);
            }
        ]);

        if ($selectedPeriode) {
            $query->where('periode_pemilihan_id', $selectedPeriode->id);
        }

        if (request()->filled('q')) {
            $q = request('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('nisn', 'like', '%' . $q . '%');
            });
        }

        if (request()->filled('jenis')) $query->where('jenis', request('jenis'));

        if (request()->filled('tingkat')) {
            $query->whereHas('kelas', function ($sub) {
                $sub->where('tingkat', request('tingkat'));
            });
        }

        if (request()->filled('kelas_id')) $query->where('kelas_id', request('kelas_id'));

        $pemilih = $query->orderBy('nama')->paginate(10)->withQueryString();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $periodeList = PeriodePemilihan::orderBy('nama_periode')->get();

        return view('admin.tokens.index', compact('pemilih', 'kelasList', 'periodeList', 'selectedPeriode'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $periodeList = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        return view('admin.tokens.create', compact('kelasList', 'periodeList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:30',
            'jenis' => 'required|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
            'periode_pemilihan_id' => 'required|exists:periode_pemilihan,id',
        ]);

        $exists = Pemilih::where('nisn', $request->nis)
            ->where('periode_pemilihan_id', $request->periode_pemilihan_id)
            ->exists();

        if ($exists) return back()->withErrors(['nis' => 'NISN sudah digunakan di periode ini'])->withInput();

        Pemilih::create([
            'nama' => $request->nama,
            'nisn' => $request->nis,
            'jenis' => $request->jenis,
            'kelas_id' => $request->jenis === 'siswa' ? $request->kelas_id : null,
            'periode_pemilihan_id' => $request->periode_pemilihan_id,
            'aktif' => true,
        ]);

        return redirect()->route('admin.pemilih.index')->with('success', 'Pemilih berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pemilih = Pemilih::findOrFail($id);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $periodeList = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        return view('admin.tokens.edit', compact('pemilih', 'kelasList', 'periodeList'));
    }

    public function update(Request $request, $id)
    {
        $pemilih = Pemilih::findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:100',
            'nis' => 'required|string|max:30',
            'jenis' => 'required|in:siswa,guru',
            'kelas_id' => 'nullable|exists:kelas,id',
            'periode_pemilihan_id' => 'required|exists:periode_pemilihan,id',
        ]);

        $pemilih->update([
            'nama' => $request->nama,
            'nisn' => $request->nis,
            'jenis' => $request->jenis,
            'kelas_id' => $request->jenis === 'siswa' ? $request->kelas_id : null,
            'periode_pemilihan_id' => $request->periode_pemilihan_id,
        ]);

        return redirect()->route('admin.pemilih.index')->with('success', 'Pemilih berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pemilih = Pemilih::findOrFail($id);

        try {
            DB::transaction(function () use ($pemilih) {
                KandidatAnggota::where('pemilih_id', $pemilih->id)->delete();
                Suara::where('pemilih_id', $pemilih->id)->delete();
                TokenPemilih::where('pemilih_id', $pemilih->id)->delete();
                $pemilih->delete();
            });
            return back()->with('success', 'Pemilih dan data terkait berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['pemilih' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    public function generateTokens(Request $request)
    {
        $periodeId = $request->periode_id ?? PeriodePemilihan::where('status', 'aktif')->first()?->id;
        
        if (!$periodeId) return back()->withErrors(['error' => 'Periode aktif tidak ditemukan']);

        $query = Pemilih::where('periode_pemilihan_id', $periodeId)->where('aktif', true);

        if ($request->has('ids') && is_array($request->ids) && $request->select_all_pages != '1') {
            $query->whereIn('id', $request->ids);
        }

        $pemilihList = $query->whereDoesntHave('tokens', function ($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        })->get();

        if ($pemilihList->isEmpty()) return back()->with('success', 'Tidak ada pemilih baru yang memerlukan token.');

        $count = 0;
        DB::transaction(function () use ($pemilihList, $periodeId, &$count) {
            foreach ($pemilihList as $pemilih) {
                $tokenRaw =  strtoupper(Str::random(8));  
                TokenPemilih::create([
                    'periode_id'    => $periodeId,
                    'pemilih_id'    => $pemilih->id,
                    'kelas_id'      => $pemilih->kelas_id,
                    'token'         => $tokenRaw,
                    'token_hash'    => Hash::make($tokenRaw),
                    'status'        => 'aktif',
                    'sudah_memilih' => false,
                    'nama_pemilih'  => $pemilih->nama,
                    'nis_pemilih'   => $pemilih->nisn,
                ]);
                $count++;
            }
        });

        return back()->with('success', "Berhasil men-generate $count token baru.");
    }

    public function resetToken($id)
    {
        $pemilih = Pemilih::findOrFail($id);
        $token = TokenPemilih::where('pemilih_id', $pemilih->id)
                             ->where('periode_id', $pemilih->periode_pemilihan_id)
                             ->first();

        if (!$token) return back()->withErrors(['error' => 'Token tidak ditemukan']);

        try {
            DB::transaction(function () use ($token, $pemilih) {
                Suara::where('periode_id', $token->periode_id)->where('pemilih_id', $pemilih->id)->delete();
                $newToken = 'VOTE-' . strtoupper(Str::random(8));
                $token->update([
                    'token' => $newToken,
                    'token_hash' => Hash::make($newToken),
                    'status' => 'aktif',
                    'sudah_memilih' => false,
                    'digunakan_pada' => null,
                ]);
            });
            return back()->with('success', "Token {$pemilih->nama} berhasil direset.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal reset: ' . $e->getMessage()]);
        }
    }

    public function deleteAllTokens(Request $request)
    {
        $selectedIds = collect($request->input('ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $pemilihIds = $request->input('select_all_pages') === '1'
            ? $this->buildFilteredPemilihQuery($request)->pluck('id')->all()
            : $selectedIds;

        if (empty($pemilihIds)) {
            return back()->withErrors(['error' => 'Tidak ada pemilih yang dipilih.']);
        }

        try {
            $deletedTokenCount = 0;
            DB::transaction(function () use ($pemilihIds, &$deletedTokenCount) {
                Suara::whereIn('pemilih_id', $pemilihIds)->delete();
                $deletedTokenCount = TokenPemilih::whereIn('pemilih_id', $pemilihIds)->delete();
            });

            if ($deletedTokenCount === 0) {
                return back()->withErrors(['error' => 'Token untuk data terpilih tidak ditemukan.']);
            }

            return back()->with('success', "Berhasil menghapus {$deletedTokenCount} token terpilih.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    public function hapusSatuToken($id)
    {
        $pemilih = Pemilih::findOrFail($id);

        try {
            $deletedTokenCount = 0;
            DB::transaction(function () use ($pemilih, &$deletedTokenCount) {
                Suara::where('pemilih_id', $pemilih->id)->delete();
                $deletedTokenCount = TokenPemilih::where('pemilih_id', $pemilih->id)->delete();
            });

            if ($deletedTokenCount === 0) {
                return back()->withErrors(['error' => "Token untuk {$pemilih->nama} tidak ditemukan."]);
            }

            return back()->with('success', "Token {$pemilih->nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus token: ' . $e->getMessage()]);
        }
    }

    public function bulkHapusToken(Request $request)
    {
        return $this->deleteAllTokens($request);
    }

    private function buildFilteredPemilihQuery(Request $request)
    {
        $query = Pemilih::query();

        if ($request->filled('periode_id')) {
            $query->where('periode_pemilihan_id', $request->input('periode_id'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('nisn', 'like', '%' . $q . '%');
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        if ($request->filled('tingkat')) {
            $tingkat = $request->input('tingkat');
            $query->whereHas('kelas', function ($sub) use ($tingkat) {
                $sub->where('tingkat', $tingkat);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->input('kelas_id'));
        }

        return $query;
    }

    public function printTokens(Request $request)
    {
        $periodeId = $request->periode_id ?? PeriodePemilihan::where('status', 'aktif')->first()?->id;
        if (!$periodeId) return back()->withErrors(['error' => 'Periode tidak ditemukan.']);

        $query = Pemilih::with(['kelas', 'tokens' => function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        }])->whereHas('tokens', function ($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        });

        if ($request->has('ids') && is_array($request->ids) && $request->select_all_pages != '1') {
            $query->whereIn('id', $request->ids);
        }

        $pemilih = $query->orderBy('nama')->get();
        if ($pemilih->isEmpty()) return back()->withErrors(['error' => 'Tidak ada token untuk dicetak.']);

        return view('admin.tokens.print-all', ['pemilih' => $pemilih, 'periode' => PeriodePemilihan::find($periodeId)]);
    }

    public function showImport()
    {
        $periodeList = PeriodePemilihan::orderByDesc('mulai_pada')->get();
        return view('admin.pemilih.import', compact('periodeList'));
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'periode_pemilihan_id' => 'required|exists:periode_pemilihan,id',
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx) atau CSV (.csv)',
            'periode_pemilihan_id.required' => 'Periode pemilihan harus dipilih',
        ]);

        $periode = PeriodePemilihan::find($request->periode_pemilihan_id);
        $file = $request->file('file');
        
        $imported = 0;
        $errors = [];
        
        try {
            // Handle Excel files
            if ($file->getClientOriginalExtension() === 'xlsx') {
                // Gunakan PhpSpreadsheet jika tersedia
                if (class_exists(Xlsx::class)) {
                    $reader = new Xlsx();
                    $spreadsheet = $reader->load($file->path());
                    $worksheet = $spreadsheet->getActiveSheet();
                    $allRows = $worksheet->toArray();
                    
                    // Find first non-empty row as header
                    $headerRowIndex = 0;
                    foreach ($allRows as $index => $row) {
                        if (!empty(array_filter($row))) {
                            $headerRowIndex = $index;
                            break;
                        }
                    }
                    
                    $header = array_filter($allRows[$headerRowIndex], function($val) {
                        return $val !== null && $val !== '';
                    });
                    
                    // Process data rows
                    $rows = [];
                    for ($i = $headerRowIndex + 1; $i < count($allRows); $i++) {
                        if (!empty(array_filter($allRows[$i]))) { // Skip empty rows
                            $rowData = $allRows[$i];
                            // Filter out null values and match with header
                            $cleanedRow = [];
                            $headerIndex = 0;
                            foreach ($rowData as $cellValue) {
                                if ($cellValue !== null && isset(array_values($header)[$headerIndex])) {
                                    $cleanedRow[array_values($header)[$headerIndex]] = $cellValue;
                                    $headerIndex++;
                                }
                            }
                            if (!empty($cleanedRow)) {
                                $rows[] = $cleanedRow;
                            }
                        }
                    }
                } else {
                    return back()->withErrors(['file' => 'Library Excel tidak tersedia. Silakan save file Excel sebagai CSV terlebih dahulu.']);
                }
            } else {
                // Handle CSV files
                $rows = [];
                $handle = fopen($file->path(), 'r');
                $header = fgetcsv($handle, 1000, ',');
                
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $rows[] = array_combine($header, $data);
                }
                fclose($handle);
            }

            // Process each row
            foreach ($rows as $rowNum => $row) {
                $rowNumber = $rowNum + 2; // +2 karena array mulai dari 0 dan header di row 1
                
                // Get values from row
                $nisn = isset($row['NISN']) ? trim((string)$row['NISN']) : '';
                $nama = isset($row['Nama']) ? trim((string)$row['Nama']) : '';
                $tingkat = isset($row['Tingkat']) ? trim((string)$row['Tingkat']) : '';
                $namaKelas = isset($row['Nama Kelas']) ? trim((string)$row['Nama Kelas']) : '';

                // Validasi kolom wajib
                if (empty($nisn) || empty($nama)) {
                    $errors[] = "Baris {$rowNumber}: NISN dan Nama harus diisi";
                    continue;
                }

                // Tentukan jenis berdasarkan tingkat
                $jenis = empty($tingkat) ? 'guru' : 'siswa';
                
                // Find kelas jika siswa
                $kelasId = null;
                if ($jenis === 'siswa' && !empty($namaKelas) && !empty($tingkat)) {
                    $kelas = Kelas::where('nama_kelas', $namaKelas)
                        ->where('tingkat', $tingkat)
                        ->first();
                    
                    if (!$kelas) {
                        $errors[] = "Baris {$rowNumber}: Kelas '{$namaKelas}' tingkat {$tingkat} tidak ditemukan";
                        continue;
                    }
                    $kelasId = $kelas->id;
                }

                // Check if pemilih already exists
                $exists = Pemilih::where('nisn', $nisn)
                    ->where('periode_pemilihan_id', $periode->id)
                    ->exists();

                if ($exists) {
                    $errors[] = "Baris {$rowNumber}: NISN {$nisn} sudah ada di periode ini";
                    continue;
                }

                // Create pemilih
                try {
                    Pemilih::create([
                        'nisn' => $nisn,
                        'nama' => $nama,
                        'jenis' => $jenis,
                        'kelas_id' => $kelasId,
                        'periode_pemilihan_id' => $periode->id,
                        'aktif' => true,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: Gagal menyimpan - " . $e->getMessage();
                }
            }

            $message = "Import selesai! {$imported} pemilih berhasil ditambahkan.";
            if (!empty($errors)) {
                return back()->with('success', $message)->with('import_errors', $errors);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()]);
        }
    }
}
