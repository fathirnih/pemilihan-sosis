<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemilihan OSIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #081225;
            --ink-soft: #1b2944;
            --accent: #0ea5e9;
            --accent-strong: #0284c7;
            --gold: #f59e0b;
            --paper: #f7fafc;
        }

        * {
            font-family: 'Manrope', sans-serif;
        }

        h1,
        h2,
        h3,
        .display-font {
            font-family: 'Sora', sans-serif;
        }

        .site-bg {
            background:
                radial-gradient(1200px 600px at 85% -10%, rgba(14, 165, 233, 0.18), transparent 60%),
                radial-gradient(900px 500px at 0% 20%, rgba(245, 158, 11, 0.13), transparent 58%),
                linear-gradient(180deg, #f8fbff 0%, #f2f7fb 45%, #eef4f9 100%);
        }

        .hero-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(3px);
            animation: float 9s ease-in-out infinite;
        }

        .hero-orb--a {
            width: 220px;
            height: 220px;
            background: radial-gradient(circle at 30% 30%, rgba(14, 165, 233, 0.5), rgba(14, 165, 233, 0.06));
            top: -40px;
            right: -30px;
        }

        .hero-orb--b {
            width: 180px;
            height: 180px;
            background: radial-gradient(circle at 30% 30%, rgba(245, 158, 11, 0.45), rgba(245, 158, 11, 0.05));
            bottom: -30px;
            left: -20px;
            animation-delay: 1.6s;
        }

        .glass-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.86), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.28);
            box-shadow: 0 18px 45px rgba(8, 18, 37, 0.12);
        }

        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }

        .reveal.in-view {
            opacity: 1;
            transform: translateY(0);
        }

        .soft-grid {
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.14) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.14) 1px, transparent 1px);
            background-size: 26px 26px;
        }

        .feature-card {
            border: 1px solid rgba(148, 163, 184, 0.25);
            background: linear-gradient(170deg, #ffffff, #f8fbff);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.12);
        }

        .dark-band {
            background: radial-gradient(600px 240px at 90% 10%, rgba(14, 165, 233, 0.24), transparent 65%), #0a1224;
        }

        .cta-shell {
            background:
                radial-gradient(500px 220px at 10% 0%, rgba(14, 165, 233, 0.22), transparent 65%),
                radial-gradient(420px 220px at 90% 100%, rgba(245, 158, 11, 0.2), transparent 60%),
                linear-gradient(135deg, #09162d 0%, #0f2140 50%, #112b55 100%);
        }

        .btn-luxe {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .btn-luxe::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -120%;
            width: 50%;
            height: 210%;
            background: linear-gradient(105deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            transform: rotate(18deg);
            transition: left 0.7s ease;
        }

        .btn-luxe:hover::after {
            left: 155%;
        }

        .candidate-card {
            border: 1px solid rgba(148, 163, 184, 0.22);
            background: linear-gradient(165deg, #ffffff, #f6fbff);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .candidate-photo-wrap {
            background:
                radial-gradient(circle at 25% 20%, rgba(14, 165, 233, 0.12), transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.12), transparent 45%),
                #e2e8f0;
        }

        .candidate-photo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center top;
        }

        .candidate-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 36px rgba(15, 23, 42, 0.12);
        }

        @keyframes float {
            0%,
            100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-16px);
            }
        }
    </style>
</head>
<body class="site-bg text-slate-800">
    <div class="fixed inset-0 -z-10 soft-grid opacity-40"></div>

    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl">
        <nav class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--ink)] text-lg font-bold text-white shadow-lg shadow-slate-900/20">OS</div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Digital Election</p>
                    <p class="display-font text-lg font-semibold text-[var(--ink)]">Pemilihan OSIS</p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="#kandidat" class="hidden rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:inline-flex">
                    Kandidat
                </a>
                <a href="#alur" class="hidden rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:inline-flex">
                    Alur Pemilihan
                </a>
                <a href="{{ route('login') }}" class="btn-luxe inline-flex items-center rounded-lg bg-[var(--ink)] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:bg-slate-800">
                    Masuk Sekarang
                </a>
            </div>
        </nav>
    </header>

    <main>
        @php
            $landingPeriode = $landingPeriode ?? null;
            $landingKandidats = $landingKandidats ?? collect();
            $landingTotalSuara = $landingTotalSuara ?? 0;
            $landingTotalKandidat = $landingTotalKandidat ?? $landingKandidats->count();
        @endphp
        <section class="relative overflow-hidden py-16 sm:py-20 lg:py-24">
            <div class="mx-auto grid w-full max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
                <div class="reveal">
                    <span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-sky-700">
                        Sistem Pemilihan Resmi Sekolah
                    </span>
                    <h1 class="display-font mt-5 text-4xl font-extrabold leading-tight text-[var(--ink)] sm:text-5xl lg:text-[56px]">
                        Platform Pemilihan OSIS
                        <span class="text-sky-600">Mewah, Aman, dan Transparan</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                        Bangun kepercayaan seluruh siswa lewat pengalaman voting digital yang profesional. Cepat diakses, validasi token ketat, dan hasil dapat dipantau real-time.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('login') }}" class="btn-luxe inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-sky-500 to-sky-600 px-7 py-3.5 text-sm font-bold text-white shadow-xl shadow-sky-500/30 transition hover:from-sky-600 hover:to-sky-700">
                            Mulai Voting
                        </a>
                        <a href="#kandidat" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-3.5 text-sm font-bold text-slate-800 transition hover:bg-slate-100">
                            Lihat Kandidat
                        </a>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-200 bg-white/90 p-4">
                            <p class="display-font text-2xl font-bold text-[var(--ink)]">100%</p>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Token Unik</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white/90 p-4">
                            <p class="display-font text-2xl font-bold text-[var(--ink)]">Real-time</p>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Monitoring</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white/90 p-4 col-span-2 sm:col-span-1">
                            <p class="display-font text-2xl font-bold text-[var(--ink)]">Audit Ready</p>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Transparansi</p>
                        </div>
                    </div>
                </div>

                <div class="reveal relative lg:pl-6">
                    <div class="hero-orb hero-orb--a"></div>
                    <div class="hero-orb hero-orb--b"></div>
                    <div class="glass-panel relative rounded-3xl p-6 sm:p-8">
                        <div class="mb-6 flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-500">Pusat Kontrol Pemilihan</p>
                            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-700">Partisipasi Pemilih</p>
                                    <p class="text-sm font-bold text-slate-900">78%</p>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-[78%] rounded-full bg-gradient-to-r from-sky-400 to-sky-600"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Suara Masuk</p>
                                    <p class="display-font mt-1 text-2xl font-bold text-[var(--ink)]">{{ number_format($landingTotalSuara, 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Kandidat</p>
                                    <p class="display-font mt-1 text-2xl font-bold text-[var(--ink)]">{{ str_pad((string) $landingTotalKandidat, 2, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between text-xs font-semibold text-slate-500">
                                    <span>Integritas Sistem</span>
                                    <span>99.9%</span>
                                </div>
                                <div class="flex gap-2">
                                    <span class="h-2 w-full rounded-full bg-sky-500"></span>
                                    <span class="h-2 w-full rounded-full bg-sky-500"></span>
                                    <span class="h-2 w-full rounded-full bg-sky-500"></span>
                                    <span class="h-2 w-full rounded-full bg-sky-500"></span>
                                    <span class="h-2 w-full rounded-full bg-amber-400"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="py-16 sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="reveal mb-12 max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-sky-700">Keunggulan Platform</p>
                    <h2 class="display-font mt-3 text-3xl font-bold text-[var(--ink)] sm:text-4xl">Dirancang untuk pengalaman voting berkelas.</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <article class="feature-card reveal rounded-2xl p-7">
                        <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-sky-700 text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <h3 class="display-font text-xl font-bold text-[var(--ink)]">Keamanan Berlapis</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">Validasi token unik dan pembatasan satu suara per akun untuk menjaga hasil tetap valid.</p>
                    </article>

                    <article class="feature-card reveal rounded-2xl p-7">
                        <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3v18h18"></path>
                                <path d="M7 13l3-3 4 4 5-6"></path>
                            </svg>
                        </div>
                        <h3 class="display-font text-xl font-bold text-[var(--ink)]">Data Real-time</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">Panitia memantau perolehan suara langsung dengan tampilan analitik yang mudah dibaca.</p>
                    </article>

                    <article class="feature-card reveal rounded-2xl p-7">
                        <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c.552 0 1 .449.963 1-.268 3.89-3.504 7-7.463 7h-5A7.5 7.5 0 0 1 2 12.5"></path>
                                <path d="M3 12C3 7.03 7.03 3 12 3h5c4.03 0 7.363 3.11 7.637 7"></path>
                            </svg>
                        </div>
                        <h3 class="display-font text-xl font-bold text-[var(--ink)]">Transparan & Akuntabel</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">Riwayat perhitungan dan rekap akhir disusun rapi untuk kebutuhan pelaporan resmi sekolah.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="kandidat" class="py-16 sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="reveal mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-sky-700">Profil Kandidat</p>
                        <h2 class="display-font mt-3 text-3xl font-bold text-[var(--ink)] sm:text-4xl">Kenali kandidat sebelum memilih.</h2>
                        <p class="mt-2 text-sm text-slate-600">
                            @if ($landingPeriode)
                                Periode: <span class="font-semibold text-slate-800">{{ $landingPeriode->nama_periode }}</span>
                            @else
                                Data periode pemilihan belum tersedia.
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('login') }}" class="btn-luxe inline-flex items-center justify-center rounded-xl bg-[var(--ink)] px-6 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Pilih Kandidat
                    </a>
                </div>

                @if ($landingKandidats->isNotEmpty())
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($landingKandidats as $kandidat)
                            @php
                                $ketua = $kandidat->anggota->firstWhere('peran', 'ketua')?->pemilih?->nama ?? '-';
                                $wakil = $kandidat->anggota->firstWhere('peran', 'wakil')?->pemilih?->nama ?? '-';
                                $fotoUtama = $kandidat->foto ?: ($kandidat->foto_ketua ?: $kandidat->foto_wakil);
                            @endphp
                            <article class="candidate-card reveal overflow-hidden rounded-2xl">
                                <div class="candidate-photo-wrap relative h-56">
                                    @if ($fotoUtama)
                                        <img src="{{ asset('storage/' . $fotoUtama) }}" alt="Kandidat {{ $kandidat->nomor_urut }}" class="candidate-photo p-2">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-4xl font-black text-slate-500">
                                            {{ str_pad((string) $kandidat->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                    @endif
                                    <span class="absolute left-3 top-3 inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-slate-800">
                                        Nomor {{ $kandidat->nomor_urut }}
                                    </span>
                                </div>
                                <div class="p-5">
                                    <h3 class="display-font text-xl font-bold text-[var(--ink)]">{{ $ketua }}</h3>
                                    <p class="mt-1 text-sm font-medium text-slate-500">Wakil: {{ $wakil }}</p>
                                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                                        {{ \Illuminate\Support\Str::limit($kandidat->visi, 120) }}
                                    </p>
                                    <p class="mt-2 text-xs text-slate-500">
                                        {{ \Illuminate\Support\Str::limit($kandidat->misi, 150) }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="reveal rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
                        <p class="text-sm text-slate-600">Belum ada kandidat yang dipublikasikan di landing page. Atur dari menu admin kandidat.</p>
                    </div>
                @endif
            </div>
        </section>

        <section id="alur" class="py-16 sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="reveal mb-10 text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-600">Alur Cepat</p>
                    <h2 class="display-font mt-3 text-3xl font-bold text-[var(--ink)] sm:text-4xl">3 langkah menuju pemilihan yang rapi.</h2>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <article class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="display-font text-3xl font-extrabold text-sky-600">01</p>
                        <h3 class="mt-3 text-lg font-bold text-[var(--ink)]">Masuk dengan Token</h3>
                        <p class="mt-2 text-sm text-slate-600">Pemilih login menggunakan token unik yang diberikan panitia.</p>
                    </article>
                    <article class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="display-font text-3xl font-extrabold text-sky-600">02</p>
                        <h3 class="mt-3 text-lg font-bold text-[var(--ink)]">Pilih Kandidat</h3>
                        <p class="mt-2 text-sm text-slate-600">Satu kali klik untuk menyalurkan suara sesuai pilihan.</p>
                    </article>
                    <article class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="display-font text-3xl font-extrabold text-sky-600">03</p>
                        <h3 class="mt-3 text-lg font-bold text-[var(--ink)]">Pantau Rekap</h3>
                        <p class="mt-2 text-sm text-slate-600">Hasil terhitung otomatis dan dapat dipantau panitia secara real-time.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="dark-band py-16 text-white sm:py-20">
            <div class="mx-auto grid w-full max-w-7xl gap-8 px-4 sm:px-6 md:grid-cols-3 lg:px-8">
                <div class="reveal">
                    <p class="text-sm font-semibold uppercase tracking-[0.12em] text-sky-200">Kecepatan Sistem</p>
                    <p class="display-font mt-2 text-4xl font-bold">&lt; 1 detik</p>
                    <p class="mt-2 text-sm text-slate-300">Respon login dan proses voting tetap cepat saat trafik tinggi.</p>
                </div>
                <div class="reveal">
                    <p class="text-sm font-semibold uppercase tracking-[0.12em] text-sky-200">Integritas Data</p>
                    <p class="display-font mt-2 text-4xl font-bold">99.9%</p>
                    <p class="mt-2 text-sm text-slate-300">Skema validasi menjaga satu akun untuk satu suara.</p>
                </div>
                <div class="reveal">
                    <p class="text-sm font-semibold uppercase tracking-[0.12em] text-sky-200">Pengalaman Pengguna</p>
                    <p class="display-font mt-2 text-4xl font-bold">Mobile First</p>
                    <p class="mt-2 text-sm text-slate-300">Tampilan tetap nyaman dipakai dari HP hingga desktop.</p>
                </div>
            </div>
        </section>

        <section class="py-16 sm:py-20">
            <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="cta-shell reveal overflow-hidden rounded-3xl border border-white/20 p-8 text-center text-white shadow-2xl shadow-slate-900/25 sm:p-12">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-100">Saatnya Berpartisipasi</p>
                    <h2 class="display-font mx-auto mt-4 max-w-3xl text-3xl font-extrabold leading-tight sm:text-4xl">
                        Jadikan pemilihan OSIS lebih modern, lebih elegan, dan lebih dipercaya semua pihak.
                    </h2>
                    <p class="mx-auto mt-5 max-w-2xl text-sm leading-relaxed text-slate-200 sm:text-base">
                        Masuk sekarang dengan token Anda dan bantu memilih pemimpin terbaik untuk masa depan sekolah.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('login') }}" class="btn-luxe inline-flex items-center justify-center rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-slate-900 transition hover:bg-slate-100">
                            Masuk Pemilih
                        </a>
                        <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/35 bg-white/10 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-white/20">
                            Masuk Admin/Panitia
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white py-10">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 text-sm text-slate-500 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <p>&copy; {{ date('Y') }} Pemilihan OSIS. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="font-medium transition hover:text-slate-800">Panduan</a>
                <a href="#" class="font-medium transition hover:text-slate-800">Privasi</a>
                <a href="#" class="font-medium transition hover:text-slate-800">Kontak Panitia</a>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const nodes = document.querySelectorAll('.reveal');
            if (!nodes.length) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.14,
                rootMargin: '0px 0px -8% 0px',
            });

            nodes.forEach((node, index) => {
                node.style.transitionDelay = `${Math.min(index * 40, 320)}ms`;
                observer.observe(node);
            });
        })();
    </script>
</body>
</html>
