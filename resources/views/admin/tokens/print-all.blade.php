<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>Cetak Masal - {{ $periode->nama_periode }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=JetBrains+Mono:wght@700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .token-font { font-family: 'JetBrains Mono', monospace; }
        
        /* PENGATURAN WAJIB UNTUK 6 KARTU PER A4 */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0.5cm; /* Margin kertas super tipis */
            }
            body { 
                background: white; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            
            /* Grid 2 Kolom */
            .print-grid { 
                display: grid; 
                grid-template-columns: repeat(2, 1fr); 
                gap: 0.5cm; 
                padding: 0.5cm;
            }
            
            /* Kunci tinggi kartu di 8.5cm */
            .print-card { 
                height: 8.5cm !important; 
                page-break-inside: avoid;
                break-inside: avoid;
                border: 2px solid #e2e8f0 !important;
                box-shadow: none !important;
                display: flex;
                flex-direction: column;
            }

            /* Perkecil padding & font khusus saat diprint agar tidak meluber */
            .print-p { padding: 0.75rem !important; }
            .print-text-name { font-size: 14px !important; margin-bottom: 4px !important; }
            .print-text-token { font-size: 18px !important; }
            .print-gap { gap: 10px !important; margin-bottom: 8px !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4">

    <div class="max-w-5xl mx-auto mb-6 no-print bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h1 class="text-lg font-black text-slate-800 uppercase">Cetak Token Masal</h1>
            <p class="text-xs text-slate-500">{{ $periode->nama_periode }}</p>
        </div>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-indigo-700">
            Cetak ({{ $pemilih->count() }} Data)
        </button>
    </div>

    <div class="max-w-5xl mx-auto print-grid grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach ($pemilih as $row)
            @php
                $tokenData = $row->tokens->first();
                $kelasNama = $row->kelas?->nama_kelas ?? '-';
            @endphp
            
            <div class="bg-white border-2 border-slate-200 rounded-xl flex flex-col shadow-sm print-card">
                
                <div class="p-3 border-b border-slate-100 flex items-center gap-2 bg-white rounded-t-xl shrink-0">
                    <div class="w-5 h-5 bg-blue-600 rounded flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                    </div>
                    <h2 class="text-[10px] font-black text-slate-800 uppercase tracking-tight">Panitia Pemilihan OSIS</h2>
                </div>

                <div class="p-4 flex-grow flex flex-col justify-center print-p">
                    <div class="text-center mb-3">
                        <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block">Nama Pemilih</label>
                        <p class="text-base font-black text-slate-800 uppercase truncate print-text-name">{{ $row->nama }}</p>
                    </div>
                    
                    <div class="flex justify-center gap-6 mb-4 print-gap">
                        <div class="text-center">
                            <label class="text-[8px] font-bold text-slate-400 uppercase">NISN</label>
                            <p class="text-[11px] font-bold text-slate-700">{{ $row->nisn }}</p>
                        </div>
                        <div class="text-center border-l border-slate-200 pl-6">
                            <label class="text-[8px] font-bold text-slate-400 uppercase">Kelas</label>
                            <p class="text-[11px] font-bold text-slate-700">{{ $kelasNama }}</p>
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-2.5 rounded-lg border border-blue-100 text-center mt-auto">
                        <label class="text-[8px] font-bold text-blue-500 uppercase tracking-widest block mb-0.5">Kode Token Rahasia</label>
                        <p class="token-font text-xl font-black text-blue-700 tracking-widest print-text-token">
                            {{ $tokenData?->token ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <div class="px-4 py-2 bg-slate-50 text-[7px] text-slate-400 flex justify-between items-center border-t border-slate-100 rounded-b-xl shrink-0">
                    <span class="font-bold uppercase tracking-widest">E-Voting System</span>
                    <span class="font-mono">Valid: {{ now()->format('Y') }}</span>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>