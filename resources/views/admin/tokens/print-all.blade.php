<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Token Pemilih - {{ $periode->nama_periode }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            padding: 24px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 6px;
        }
        .header p {
            font-size: 12px;
            color: #475569;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
        }
        .token-card {
            background: #ffffff;
            border: 2px solid #1e3a8a;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.12);
            page-break-inside: avoid;
        }
        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 14px 18px;
        }
        .title { font-size: 11px; letter-spacing: 1px; text-transform: uppercase; font-weight: 700; }
        .subtitle { margin-top: 4px; font-size: 12px; opacity: 0.9; }
        .card-body { padding: 16px 18px 14px; }
        .name { font-size: 18px; font-weight: 700; margin-bottom: 4px; word-break: break-word; }
        .meta { font-size: 12px; color: #334155; margin-bottom: 2px; }
        .token-box {
            margin-top: 12px;
            padding: 10px;
            border: 1px dashed #1e3a8a;
            border-radius: 10px;
            background: #eff6ff;
        }
        .token-label { font-size: 10px; text-transform: uppercase; color: #1e3a8a; font-weight: 700; margin-bottom: 6px; }
        .token-value {
            font-size: 16px;
            letter-spacing: 1.1px;
            font-family: "Courier New", monospace;
            color: #0f172a;
            word-break: break-all;
            font-weight: 700;
        }
        .hint {
            margin-top: 12px;
            border-top: 1px solid #dbeafe;
            padding-top: 8px;
            font-size: 10px;
            color: #475569;
            line-height: 1.5;
        }
        .card-footer {
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 10px 18px;
            font-size: 11px;
            color: #475569;
        }
        .print-controls {
            text-align: center;
            margin: 16px 0 6px;
        }
        .print-btn {
            background: #1e40af;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .print-btn:hover { background: #1e3a8a; }
        @media print {
            body { background: #ffffff; padding: 0; }
            .print-controls { display: none; }
            .grid { gap: 10px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cetak Token Pemilih</h1>
        <p>{{ $periode->nama_periode }} • Dicetak {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">Cetak Semua</button>
    </div>

    <div class="grid">
        @foreach ($pemilih as $row)
            @php
                $token = $row->tokens->first();
                $kelasNama = $row->kelas?->nama_kelas ?? '-';
            @endphp
            <div class="token-card">
                <div class="card-header">
                    <div class="title">Pemilihan OSIS</div>
                    <div class="subtitle">Kartu Token Pemilih</div>
                </div>
                <div class="card-body">
                    <div class="name">{{ $row->nama }}</div>
                    <div class="meta">NISN/NIP: {{ $row->nisn }}</div>
                    <div class="meta">Kelas: {{ $kelasNama }}</div>
                    <div class="token-box">
                        <div class="token-label">Kode Token</div>
                        <div class="token-value">{{ $token?->token }}</div>
                    </div>
                    <div class="hint">
                        Simpan token ini dengan baik. Token hanya dapat digunakan satu kali saat pemungutan suara.
                    </div>
                </div>
                <div class="card-footer">
                    Dicetak: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
