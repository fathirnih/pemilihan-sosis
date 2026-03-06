@extends('layouts.admin')

@section('title', 'Tokens Generated - Admin')

@section('admin.content')
<div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-3">
                <span class="text-4xl">✅</span>
                <div>
                    <h1 class="text-3xl font-bold">{{ $jumlah }} Token Berhasil Dibuat</h1>
                    <p class="text-green-100 mt-2">Salin token di bawah untuk dibagikan kepada pemilih</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6">
            ← Kembali ke Dashboard
        </a>

        <!-- Instruction -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-900 text-sm">
                <strong>ℹ️ Penting:</strong> Simpan token-token di bawah. Bagikan satu token ke setiap pemilih. Token hanya dapat digunakan satu kali untuk memilih.
            </p>
        </div>

        <!-- Copy All Button -->
        <div class="flex gap-3 mb-6">
            <button 
                id="copyAll" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
            >
                📋 Salin Semua Token
            </button>
            <button 
                onclick="window.print()" 
                class="bg-slate-600 hover:bg-slate-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
            >
                🖨️ Cetak
            </button>
        </div>

        <!-- Tokens List -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="space-y-3">
                @foreach ($tokens as $index => $token)
                    <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors group">
                        <div class="flex-1">
                            <p class="text-sm text-slate-600 mb-1">Token {{ $index + 1 }}</p>
                            <p class="font-mono text-lg font-semibold text-slate-900">{{ $token }}</p>
                        </div>
                        <button 
                            type="button" 
                            onclick="copyToken('{{ $token }}')"
                            class="ml-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            Copy
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Info -->
        <div class="mt-6 text-slate-600 text-sm space-y-2">
            <p><strong>Penggunaan Token:</strong></p>
            <p>1. Bagikan token ke setiap pemilih</p>
            <p>2. Pemilih login menggunakan token di halaman login pemilih</p>
            <p>3. Token akan ditandai sebagai "digunakan" setelah pemilih memilih</p>
            <p>4. Token tidak dapat digunakan untuk memilih dua kali</p>
        </div>
    </div>
</div>

<script>
function copyToken(token) {
    navigator.clipboard.writeText(token).then(() => {
        alert('Token sudah disalin: ' + token);
    });
}

document.getElementById('copyAll').addEventListener('click', function() {
    const tokens = @json($tokens);
    const text = tokens.join('\n');
    navigator.clipboard.writeText(text).then(() => {
        alert('Semua token sudah disalin!');
    });
});
</script>
@endsection
