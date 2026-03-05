<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Token - {{ $token->nama_pemilih }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }
        }

        .print-container {
            max-width: 350px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .token-card {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .token-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(20px, 20px); }
        }

        .token-card-content {
            position: relative;
            z-index: 1;
        }

        .school-name {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .header-divider {
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        .student-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .student-nis {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
            margin: 20px 0;
        }

        .token-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.85;
            margin-bottom: 8px;
        }

        .token-value {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            background: rgba(255, 255, 255, 0.1);
            padding: 12px;
            border-radius: 6px;
            word-break: break-all;
            margin-bottom: 15px;
        }

        .instruction {
            font-size: 10px;
            opacity: 0.8;
            line-height: 1.4;
            margin-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 15px;
        }

        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #475569;
            font-size: 12px;
        }

        .print-controls {
            text-align: center;
            margin-top: 20px;
        }

        .print-btn {
            padding: 10px 20px;
            font-size: 14px;
            background-color: #1e40af;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .print-btn:hover {
            background-color: #1e3a8a;
        }

        @media print {
            .print-controls {
                display: none;
            }
            
            .print-container {
                max-width: 100%;
                box-shadow: none;
                margin: 0;
            }

            body {
                padding: 0;
                margin: 0;
            }
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            opacity: 0.05;
            color: #000;
            pointer-events: none;
            z-index: 0;
        }

        @media print {
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">🗳️</div>

    <div class="print-container">
        <div class="token-card">
            <div class="token-card-content">
                <div class="school-name">PEMILIHAN OSIS</div>

                <div class="header-divider"></div>

                <div class="student-name">{{ $token->nama_pemilih }}</div>
                <div class="student-nis">NIS: {{ $token->nis_pemilih }}</div>

                <div class="divider"></div>

                <div class="token-label">Kode Akses Pemilihan</div>
                <div class="token-value">{{ $token->token }}</div>

                <div class="instruction">
                    ✓ Simpan kode ini dengan baik<br>
                    ✓ Gunakan saat login pemilihan<br>
                    ✓ Satu kode hanya untuk satu pemilih
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ date('d/m/Y H:i') }}</strong></p>
            <p>Dihasilkan oleh Sistem Pemilihan OSIS</p>
        </div>
    </div>

    <div class="print-controls">
        <button class="print-btn" onclick="window.print()">🖨️ Cetak Kartu Token</button>
    </div>

    <script>
        // Trigger print dialog on page load if requested
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', function() {
                window.print();
            });
        }
    </script>
</body>
</html>
