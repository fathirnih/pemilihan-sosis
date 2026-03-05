<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Token - {{ $token->nama_pemilih }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            padding: 24px;
        }

        .wrapper {
            max-width: 420px;
            margin: 0 auto;
        }

        .token-card {
            background: #ffffff;
            border: 2px solid #1e3a8a;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
        }

        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
        }

        .title {
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 700;
            opacity: 0.95;
        }

        .subtitle {
            margin-top: 6px;
            font-size: 13px;
            opacity: 0.9;
        }

        .card-body {
            padding: 18px 20px 16px;
        }

        .name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .meta {
            font-size: 13px;
            color: #334155;
            margin-bottom: 2px;
        }

        .token-box {
            margin-top: 14px;
            padding: 12px;
            border: 1px dashed #1e3a8a;
            border-radius: 10px;
            background: #eff6ff;
        }

        .token-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #1e3a8a;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .token-value {
            font-size: 18px;
            line-height: 1.4;
            letter-spacing: 1.2px;
            font-family: "Courier New", monospace;
            color: #0f172a;
            word-break: break-all;
            font-weight: 700;
        }

        .hint {
            margin-top: 14px;
            border-top: 1px solid #dbeafe;
            padding-top: 10px;
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        .card-footer {
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 12px 20px;
            font-size: 12px;
            color: #475569;
        }

        .print-controls {
            text-align: center;
            margin-top: 16px;
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

        .print-btn:hover {
            background: #1e3a8a;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .wrapper {
                max-width: 100%;
            }

            .token-card {
                box-shadow: none;
            }

            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $kelasNama = $token->kelas?->nama_kelas ?? $token->siswa?->kelas?->nama_kelas ?? '-';
    @endphp

    <div class="wrapper">
        <div class="token-card">
            <div class="card-header">
                <div class="title">Pemilihan OSIS</div>
                <div class="subtitle">Kartu Token Pemilih</div>
            </div>

            <div class="card-body">
                <div class="name">{{ $token->nama_pemilih }}</div>
                <div class="meta">NIS: {{ $token->nis_pemilih }}</div>
                <div class="meta">Kelas: {{ $kelasNama }}</div>

                <div class="token-box">
                    <div class="token-label">Kode Token</div>
                    <div class="token-value">{{ $token->token }}</div>
                </div>

                <div class="hint">
                    Simpan token ini dengan baik. Token hanya dapat digunakan satu kali saat pemungutan suara.
                </div>
            </div>

            <div class="card-footer">
                Dicetak: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="print-controls">
            <button class="print-btn" onclick="window.print()">Cetak Kartu</button>
        </div>
    </div>

    <script>
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', function () {
                window.print();
            });
        }
    </script>
</body>
</html>
