# Pemilihan OSIS - Platform Voting Digital

Sistem pemilihan OSIS digital yang aman, transparan, dan mudah digunakan. Dibangun dengan Laravel 11 dan Tailwind CSS.

## 🎯 Fitur Utama

- ✅ **Voting Aman**: Sistem keamanan berlapis dengan token unik untuk setiap pemilih
- ✅ **Real-time Results**: Lihat hasil pemilihan secara langsung dengan update otomatis
- ✅ **Transparan**: Hasil dapat diverifikasi dan diamati oleh semua pihak
- ✅ **Responsif**: Desain modern yang responsif di semua perangkat
- ✅ **User-friendly**: Interface yang intuitif dan mudah digunakan

## 📋 Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Server**: PHP 8.2+

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi
- Composer
- Node.js & npm

### Instalasi

1. **Clone Repository**
```bash
git clone [repo-url]
cd pemilihan-sosis
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**
Ubah `.env` dengan konfigurasi database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pemilihan-osis
DB_USERNAME=root
DB_PASSWORD=
```

5. **Migrasi Database**
```bash
php artisan migrate
```

6. **Seed Data Testing (Opsional)**
```bash
php artisan db:seed --class=PemilihanSeeder
```

7. **Build Assets**
```bash
npm run build
```

8. **Jalankan Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 📱 Halaman-Halaman Utama

### 1. **Landing Page** (`/`)
- Informasi tentang aplikasi
- Fitur-fitur utama
- Call to action untuk mulai pemilihan

### 2. **Login Page** (`/login`)
- Input token pemilihan
- Validasi token
- Pesan error yang jelas

### 3. **Halaman Voting** (`/voting`)
- Tampilkan semua kandidat
- Informasi visi, misi, dan anggota tim
- Tombol untuk pilih kandidat

### 4. **Confirmation** (`/voting/confirmation`)
- Konfirmasi suara yang dipilih
- Link ke hasil pemilihan
- Tombol logout

### 5. **Results Page** (`/results`)
- Hasil pemilihan real-time
- Progress bar untuk visualisasi
- Update otomatis setiap 5 detik

## 🗄️ Struktur Database

### Tabel Utama

- **periode_pemilihan**: Data periode pemilihan
- **kandidat**: Data kandidat
- **kandidat_anggota**: Anggota tim kandidat
- **siswa**: Data siswa/pemilih
- **kelas**: Data kelas
- **token_pemilih**: Token untuk voting
- **suara**: Pencatatan suara/votes

## 🔐 Keamanan

- **Token-based Authentication**: Setiap pemilih mendapat token unik
- **One-time Vote**: Satu pemilih hanya bisa memilih satu kali
- **Session Management**: Session management yang aman
- **CSRF Protection**: Perlindungan dari CSRF attacks

## 🎨 Design & UX

- **Modern Design**: Clean dan minimal tanpa "alay"
- **Color Scheme**: Biru profesional (#2563eb) sebagai warna primary
- **Typography**: Font Inter untuk tampilan yang rapi
- **Responsive**: Desain responsive mobile-first
- **Smooth Animations**: Animasi halus yang tidak mengganggu

## 📊 Data Flow

```
1. User mengakses halaman login
2. User memasukkan token pemilihan
3. Sistem validasi token
4. User diarahkan ke halaman voting
5. User melihat daftar kandidat
6. User memilih kandidat
7. Sistem mencatat suara
8. User di-redirect ke halaman konfirmasi
9. User dapat melihat hasil pemilihan real-time
```

## 🔧 Maintenance

### Database Backup
```bash
php artisan db:seed --class=PemilihanSeeder
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Timezone Management
Pastikan timezone di `.env` sesuai:
```
APP_TIMEZONE=Asia/Jakarta
```

## 📝 API Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/` | Landing page |
| GET | `/login` | Login page |
| POST | `/login` | Process login |
| POST | `/logout` | Logout |
| GET | `/voting` | List kandidat |
| POST | `/voting` | Submit vote |
| GET | `/results` | Hasil pemilihan |

## 🐛 Troubleshooting

### Token tidak valid
- Pastikan token ada di database
- Cek status token (aktif/digunakan)
- Pastikan token belum kadaluarsa

### Hasil tidak muncul
- Refresh halaman
- Cek koneksi database
- Clear cache browser

### Migration failed
```bash
php artisan migrate:rollback
php artisan migrate
```

## 📚 Dokumentasi Lebih Lanjut

- [Laravel Official Docs](https://laravel.com/docs)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

## 👥 Tim Pengembang

Dikembangkan untuk Pemilihan OSIS 2026

## 📄 Lisensi

Proprietary - Khusus untuk penggunaan internal sekolah

## 📞 Support

Hubungi Panitia OSIS untuk bantuan teknis

---

**Versi**: 1.0.0  
**Last Updated**: March 6, 2026
