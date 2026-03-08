<?php

namespace App\Http\Controllers;

use App\Models\Pemilih;
use App\Models\TokenPemilih;
use App\Models\PeriodePemilihan;
use App\Models\Kelas;
use App\Models\Suara;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PemilihController extends Controller
{
    public function index()
    {
        // Get periode from filter
        $selectedPeriode = request('periode_id') 
            ? PeriodePemilihan::find(request('periode_id'))
            : null;

        // Jika ada parameter periode_id, gunakan untuk token query
        // Jika tidak, gunakan periode aktif atau terakhir untuk kebutuhan lain
        if ($selectedPeriode) {
            $periodeIdForTokens = $selectedPeriode->id;
        } else {
            $defaultPeriode = PeriodePemilihan::where('status', 'aktif')->first();
            if (!$defaultPeriode) {
                $defaultPeriode = PeriodePemilihan::orderByDesc('id')->first();
            }
            $periodeIdForTokens = $defaultPeriode?->id ?? -1;
        }

        $query = Pemilih::with([
            'kelas',
            'periodePemilihan',
            'tokens' => function ($query) use ($periodeIdForTokens) {
                $query->where('periode_id', $periodeIdForTokens);
            }
        ]);

        // Filter by periode hanya jika user memilih periode spesifik
        if ($selectedPeriode) {
            $query->where('periode_pemilihan_id', $selectedPeriode->id);
        }
        // Jika tidak ada filter, tampilkan SEMUA pemilih dari SEMUA periode

        if (request()->filled('q')) {
            $q = request('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('nisn', 'like', '%' . $q . '%');
            });
        }

        if (request()->filled('jenis')) {
            $query->where('jenis', request('jenis'));
        }

        if (request()->filled('tingkat')) {
            $query->whereHas('kelas', function ($sub) {
                $sub->where('tingkat', request('tingkat'));
            });
        }

        if (request()->filled('kelas_id')) {
            $query->where('kelas_id', request('kelas_id'));
        }

        $pemilih = $query->orderBy('nama')->paginate(10)->withQueryString();

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        // Ambil SEMUA periode untuk dropdown filter, tidak ada limit atau filter
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
        ], [
            'nama.required' => 'Nama pemilih harus diisi',
            'nis.required' => 'NISN/NIP harus diisi',
            'jenis.required' => 'Jenis pemilih harus dipilih',
            'periode_pemilihan_id.required' => 'Periode pemilihan harus dipilih',
        ]);

        // Check composite unique: nisn + periode_pemilihan_id
        $exists = Pemilih::where('nisn', $request->nis)
            ->where('periode_pemilihan_id', $request->periode_pemilihan_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nis' => 'NISN sudah digunakan di periode ini'])->withInput();
        }

        if ($request->jenis === 'siswa' && !$request->filled('kelas_id')) {
            return back()->withErrors(['kelas_id' => 'Kelas wajib dipilih untuk siswa'])->withInput();
        }

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
        ], [
            'nama.required' => 'Nama pemilih harus diisi',
            'nis.required' => 'NISN/NIP harus diisi',
            'jenis.required' => 'Jenis pemilih harus dipilih',
            'periode_pemilihan_id.required' => 'Periode pemilihan harus dipilih',
        ]);

        // Check composite unique: nisn + periode_pemilihan_id (exclude current pemilih)
        $exists = Pemilih::where('nisn', $request->nis)
            ->where('periode_pemilihan_id', $request->periode_pemilihan_id)
            ->where('id', '!=', $pemilih->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nis' => 'NISN sudah digunakan di periode ini'])->withInput();
        }

        if ($request->jenis === 'siswa' && !$request->filled('kelas_id')) {
            return back()->withErrors(['kelas_id' => 'Kelas wajib dipilih untuk siswa'])->withInput();
        }

        $pemilih->update([
            'nama' => $request->nama,
            'nisn' => $request->nis,
            'jenis' => $request->jenis,
            'kelas_id' => $request->jenis === 'siswa' ? $request->kelas_id : null,
            'periode_pemilihan_id' => $request->periode_pemilihan_id,
            'aktif' => true,
        ]);

        return redirect()->route('admin.pemilih.index')->with('success', 'Pemilih berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pemilih = Pemilih::findOrFail($id);

        $suaraCount = Suara::where('pemilih_id', $pemilih->id)->count();
        if ($suaraCount > 0) {
            return back()->withErrors(['pemilih' => 'Pemilih tidak bisa dihapus karena sudah memiliki suara']);
        }

        TokenPemilih::where('pemilih_id', $pemilih->id)->delete();
        $pemilih->delete();

        return back()->with('success', 'Pemilih berhasil dihapus');
    }

    public function generateTokens(Request $request)
    {
        // Hanya generate token untuk periode yang aktif atau draft
        $pemilihList = Pemilih::whereHas('periodePemilihan', function ($query) {
            $query->whereIn('status', ['aktif', 'draf']);
        })
        ->where('aktif', true)
        ->get();

        if ($pemilihList->isEmpty()) {
            return back()->withErrors(['pemilih' => 'Data pemilih kosong atau semua periode sudah ditutup']);
        }

        $created = 0;
        // Generate token untuk setiap pemilih berdasarkan periode pemilih itu sendiri
        foreach ($pemilihList as $pemilih) {
            // Token harus di-generate untuk periode yang sama dengan periode_pemilihan_id pemilih
            $periodeId = $pemilih->periode_pemilihan_id;
            
            // Cek apakah sudah ada token untuk pemilih ini di periode tersebut
            $existing = TokenPemilih::where('periode_id', $periodeId)
                ->where('pemilih_id', $pemilih->id)
                ->exists();
            
            if ($existing) {
                continue;
            }

            $token = 'VOTE-' . Str::random(16);
            TokenPemilih::create([
                'periode_id' => $periodeId,
                'pemilih_id' => $pemilih->id,
                'kelas_id' => $pemilih->kelas_id,
                'token_hash' => Hash::make($token),
                'token' => $token,
                'status' => 'aktif',
                'sudah_memilih' => false,
                'nama_pemilih' => $pemilih->nama,
                'nis_pemilih' => $pemilih->nisn,
            ]);
            $created++;
        }

        return back()->with('success', "Token berhasil dibuat untuk {$created} pemilih");
    }

    public function resetToken($id)
    {
        $pemilih = Pemilih::findOrFail($id);
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $token = TokenPemilih::where('periode_id', $periode->id)
            ->where('pemilih_id', $pemilih->id)
            ->first();

        if (!$token) {
            return back()->withErrors(['token' => 'Token belum dibuat untuk pemilih ini']);
        }

        Suara::where('periode_id', $periode->id)
            ->where('pemilih_id', $pemilih->id)
            ->delete();

        $newToken = 'VOTE-' . Str::random(16);
        $token->update([
            'token' => $newToken,
            'token_hash' => Hash::make($newToken),
            'status' => 'aktif',
            'sudah_memilih' => false,
            'digunakan_pada' => null,
            'kadaluarsa_pada' => null,
        ]);

        return back()->with('success', "Token untuk {$pemilih->nama} berhasil direset");
    }

    public function printTokens(Request $request)
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $query = Pemilih::with([
            'kelas',
            'tokens' => function ($q) use ($periode) {
                $q->where('periode_id', $periode->id);
            }
        ])->whereHas('tokens', function ($q) use ($periode) {
            $q->where('periode_id', $periode->id);
        });

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('tingkat')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('tingkat', $request->tingkat);
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $pemilih = $query->orderBy('nama')->get();

        if ($pemilih->isEmpty()) {
            return back()->withErrors(['token' => 'Tidak ada token untuk dicetak dengan filter tersebut.']);
        }

        return view('admin.tokens.print-all', [
            'pemilih' => $pemilih,
            'periode' => $periode,
        ]);
    }

    public function deleteAllTokens()
    {
        $periode = PeriodePemilihan::where('status', 'aktif')->first();
        if (!$periode) {
            $periode = PeriodePemilihan::orderByDesc('id')->first();
        }
        if (!$periode) {
            return back()->withErrors(['periode' => 'Belum ada periode pemilihan. Silakan buat periode terlebih dahulu.']);
        }

        $tokens = TokenPemilih::where('periode_id', $periode->id)
            ->whereNotNull('pemilih_id')
            ->get();

        if ($tokens->isEmpty()) {
            return back()->withErrors(['token' => 'Belum ada token untuk dihapus.']);
        }

        $pemilihIds = $tokens->pluck('pemilih_id')->all();
        Suara::where('periode_id', $periode->id)
            ->whereIn('pemilih_id', $pemilihIds)
            ->delete();

        TokenPemilih::where('periode_id', $periode->id)
            ->whereNotNull('pemilih_id')
            ->delete();

        return back()->with('success', 'Semua token berhasil dihapus.');
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
                if (class_exists('\PhpOffice\PhpSpreadsheet\Reader\Xlsx')) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load($file->path());
                    $worksheet = $spreadsheet->getActiveSheet();
                    $rows = $worksheet->toArray();
                    
                    // Skip header row
                    unset($rows[0]);
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
                $nisn = isset($row['NISN']) ? trim($row['NISN']) : (isset($row[0]) ? trim($row[0]) : null);
                $nama = isset($row['Nama']) ? trim($row['Nama']) : (isset($row[1]) ? trim($row[1]) : null);
                $tingkat = isset($row['Tingkat']) ? trim($row['Tingkat']) : (isset($row[2]) ? trim($row[2]) : null);
                $namaKelas = isset($row['Nama Kelas']) ? trim($row['Nama Kelas']) : (isset($row[3]) ? trim($row[3]) : null);

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
                return back()->with('success', $message)->with('errors', $errors);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()]);
        }
    }
}
