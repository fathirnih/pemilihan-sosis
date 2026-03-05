<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemilihan OSIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        }

        .feature-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Header Navigation -->
    <nav class="border-b border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                    P
                </div>
                <span class="text-xl font-bold text-slate-900">Pemilihan OSIS</span>
            </div>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg transition-colors">
                Masuk
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient py-24">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold text-slate-900 mb-4 leading-tight">
                        Suara Anda Menentukan Masa Depan OSIS
                    </h1>
                    <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                        Pemilihan OSIS digital yang aman, terpercaya, dan transparan. Berpartisipasi aktif dalam memilih kepemimpinan siswa terbaik.
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-8 py-3.5 rounded-lg transition-colors">
                            Mulai Pemilihan
                        </a>
                        <button class="border border-slate-300 hover:bg-slate-100 text-slate-900 font-medium px-8 py-3.5 rounded-lg transition-colors">
                            Lihat Panduan
                        </button>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-200">
                        <svg class="w-full text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Mengapa Memilih Platform Ini?</h2>
                <p class="text-xl text-slate-600">Sistem pemilihan yang dirancang untuk transparansi dan kemudahan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-xl border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="feature-icon w-12 h-12 rounded-lg flex items-center justify-center text-white mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5s-5 2.24-5 5v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6-2c1.66 0 3 1.34 3 3v2h-6V6c0-1.66 1.34-3 3-3zm6 12H6V10h12v8zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Aman & Terpercaya</h3>
                    <p class="text-slate-600">Sistem keamanan berlapis dengan verifikasi token unik untuk setiap pemilih</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-xl border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="feature-icon w-12 h-12 rounded-lg flex items-center justify-center text-white mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6h2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Real-time Results</h3>
                    <p class="text-slate-600">Lihat hasil pemilihan secara langsung dengan update otomatis setiap saat</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-xl border border-slate-200 hover:shadow-lg transition-shadow">
                    <div class="feature-icon w-12 h-12 rounded-lg flex items-center justify-center text-white mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Transparan</h3>
                    <p class="text-slate-600">Hasil pemilihan dapat diverifikasi dan diamati oleh semua pihak</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-600">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Memulai?</h2>
            <p class="text-xl text-blue-100 mb-8">Masukkan token pemilihan Anda dan ikuti proses pemilihan dengan mudah</p>
            <a href="{{ route('login') }}" class="inline-block bg-white hover:bg-slate-50 text-blue-600 font-bold px-8 py-3.5 rounded-lg transition-colors">
                Masuk Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4">Tentang</h4>
                    <p class="text-sm leading-relaxed">Platform pemilihan OSIS digital yang aman dan transparan untuk setiap sekolah</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Panduan Pemilihan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak Panitia</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Informasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Syarat Layanan</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Pemilihan OSIS. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
