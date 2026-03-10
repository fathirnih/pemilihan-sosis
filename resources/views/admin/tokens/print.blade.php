<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Cetak Token - {{ $token->nama_pemilih }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .token-font { font-family: 'JetBrains Mono', monospace; letter-spacing: 0.15em; }
        
        @media print { 
            .no-print { display: none !important; } 
            body { background: white; padding: 0; display: flex; align-items: flex-start; justify-content: center; }
            .print-card { 
                box-shadow: none !important; 
                border: 2px solid #e2e8f0 !important; 
                width: 10cm; /* Ukuran lebar standar kartu saat diprint satuan */
                margin-top: 1cm;
            }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm"> <div class="flex justify-between items-center mb-6 no-print">
            <h1 class="text-base font-bold text-slate-800">Preview Kartu</h1>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition-all">
                Cetak
            </button>
        </div>

        @php
            $kelasNama = $token->kelas?->nama_kelas ?? $token->pemilih?->kelas?->nama_kelas ?? '-';
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden print-card">
            
            <header class="p-5 bg-blue-600 flex flex-col items-center justify-center text-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center text-white mb-2 backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h2 class="text-[9px] font-black text-blue-200 uppercase tracking-[0.2em]">Panitia Pemilihan OSIS</h2>
                <h3 class="text-xl font-black text-white tracking-tight">KARTU TOKEN</h3>
            </header>

            <div class="p-6 flex flex-col items-center text-center">
                <div class="w-full mb-4">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Nama Lengkap</label>
                    <p class="text-base font-black text-slate-800 uppercase">{{ $token->nama_pemilih }}</p>
                </div>
                
                <div class="flex items-center justify-center gap-6 w-full border-y border-slate-100 py-3 mb-5">
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">NISN</label>
                        <p class="font-bold text-slate-700 text-sm">{{ $token->nis_pemilih }}</p>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Kelas</label>
                        <p class="font-bold text-slate-700 text-sm">{{ $kelasNama }}</p>
                    </div>
                </div>

                <div class="w-full bg-slate-50 border-2 border-dashed border-blue-200 rounded-xl p-4">
                    <label class="text-[9px] font-black text-blue-500 uppercase tracking-[0.2em] mb-1 block">Kode Rahasia</label>
                    <p class="token-font text-2xl font-black text-blue-700">{{ $token->token }}</p>
                </div>
            </div>

            <footer class="bg-slate-50 px-6 py-3 text-center border-t border-slate-100">
                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Sistem E-Voting &bull; {{ now()->format('Y') }}</p>
            </footer>
        </div>
    </div>

</body>
</html>