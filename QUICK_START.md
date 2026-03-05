# 🚀 QUICK START GUIDE - Pemilihan OSIS Frontend

## ⚡ 5 Menit Setup

```bash
# Clone & Setup
git clone [repo]
cd pemilihan-sosis
composer install && npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed --class=PemilihanSeeder

# Build & Run
npm run build
php artisan serve
```

**Akses**: `http://localhost:8000`  
**Test Token**: Cek di database `token_pemilih` table

---

## 📄 Pages Overview

| Page | URL | Status | Purpose |
|------|-----|--------|---------|
| 🏠 Landing | `/` | ✅ | Informasi aplikasi |
| 🔐 Login | `/login` | ✅ | Input token |
| 🗳️ Voting | `/voting` | ✅ Protected | List & pilih kandidat |
| ✔️ Confirm | `/voting/confirmation` | ✅ Protected | Konfirmasi suara |
| 📊 Results | `/results` | ✅ Protected | Hasil real-time |

---

## 🎨 Design Quick Reference

### Colors
- **Primary**: `#2563eb` (Blue) - CTA buttons
- **Text**: `#1e293b` (Dark) - Main text
- **Muted**: `#64748b` (Gray) - Secondary text
- **Success**: `#10b981` (Green)
- **Danger**: `#ef4444` (Red)

### Typography
- **Font**: Inter (sans-serif)
- **Hero**: 48px bold
- **Heading**: 30px semi-bold
- **Body**: 16px normal
- **Small**: 14px

### Spacing
- **Container**: `max-w-6xl`
- **Padding**: `px-4 py-6/8`
- **Gap**: `gap-4/6/8`

---

## 📁 Key Files to Know

```
Frontend View Files:
├── layouts/app.blade.php ..................... Base template
├── welcome-new.blade.php .................... Landing page (public)
├── auth/login.blade.php ..................... Login form
├── voting/index.blade.php ................... Kandidat list
├── voting/confirmation.blade.php ........... Vote confirmation
└── results/index.blade.php ................. Results display

Backend Logic:
├── Controllers/AuthController.php ......... Login & logout
├── Controllers/VotingController.php ....... Voting logic
├── Controllers/ResultsController.php ..... Results display
└── Middleware/EnsurePemilihAuthenticated.php ... Auth check

Routes & Config:
├── routes/web.php .......................... All routes
├── bootstrap/app.php ....................... Middleware setup
└── resources/css/app.css ................... Tailwind config
```

---

## 🔄 Component Pattern

### Card Component
```blade
<div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
    <!-- Content -->
</div>
```

### Button Styles
```blade
{{-- Primary --}}
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg">

{{-- Secondary --}}
<button class="bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium px-6 py-3 rounded-lg">
```

### Form Input
```blade
<input type="text" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500">
```

---

## 🔐 Authentication Flow

```
Token in request → Check middleware → Session created → Protected routes accessible
                            ↓
                    Token not valid → Redirect to login
```

### Session Keys
- `pemilih_token_id` - ID dari TokenPemilih record
- `pemilih_periode_id` - ID dari periode pemilihan aktif

---

## 📊 Database Quick Reference

### token_pemilih table fields
- `id` - Primary key
- `periode_id` - FK to periode_pemilihan
- `tipe_pemilih` - 'siswa' atau 'guru'
- `pemilih_id` - ID siswa/guru
- `token` - Login token (NEW)
- `status` - 'aktif', 'digunakan', 'kadaluarsa' (NEW)
- `sudah_memilih` - Boolean (NEW)

### suara table fields  
- `id` - Primary key
- `periode_id` - FK to periode_pemilihan
- `kandidat_id` - FK to kandidat
- `tipe_pemilih` - 'siswa' atau 'guru'
- `pemilih_id` - ID siswa/guru
- `created_at` - Vote timestamp

---

## 🧪 Testing Data

### Default Test Data (from seeder)
- **Periode**: "Pemilihan OSIS 2026" (aktif)
- **Kandidat**: 3 candidates (nomor urut 1, 2, 3)
- **Students**: 7 siswa dengan nama berbeda
- **Token**: 1 active token siap untuk ditest

Cek token di:
```sql
SELECT token, status FROM token_pemilih WHERE tipe_pemilih = 'siswa';
```

---

## ⚠️ Common Pitfalls

| Issue | Fix |
|-------|-----|
| Token not found | Pastikan `php artisan db:seed` sudah dijalankan |
| CSS not loading | Run `npm run build` lagi |
| Vote tidak terlihat di results | Refresh halaman atau tunggu 5 detik |
| Tidak bisa logout | Check session, kemungkinan sudah auto-clear |

---

## 🚀 Development Workflow

### 1. Buat View Baru
```bash
touch resources/views/[folder]/[page].blade.php
```

### 2. Buat Controller Method
```bash
php artisan make:controller [ControllerName]
```

### 3. Add Route
```php
// routes/web.php
Route::get('/path', [Controller::class, 'method'])->name('name');
```

### 4. Build CSS
```bash
npm run dev    # (watch mode)
npm run build  # (production)
```

---

## 📱 Responsive Breakpoints

```css
Mobile:      < 768px   (full width, single column)
Tablet:      768px+    (2 columns, optimized spacing)
Desktop:     1024px+   (3+ columns, max-width containers)
```

---

## 🎯 Key Performance Tips

- ✅ Use `@can` directives untuk conditional rendering
- ✅ Eager load relationships: `with('relation')`
- ✅ Cache results kalau tidak update real-time
- ✅ Minimize HTTP requests
- ✅ Use CDN untuk vendor assets

---

## 💡 Pro Tips

### Auto-reload CSS
```bash
npm run dev
# Jangan lupa ubah `@vite` di blade jadi `npm run build` untuk production
```

### Debug Mode
```php
// Di .env
APP_DEBUG=true   # Development
APP_DEBUG=false  # Production
```

### Quick Database Reset
```bash
php artisan migrate:fresh --seed
```

---

## 📞 Support & Debugging

### Laravel Debugging
```php
// Temporary debug logging
Log::info('Message', $data);
// Check: storage/logs/laravel.log
```

### Browser DevTools
- Inspector → Check class names & structure
- Network → Check asset loading
- Console → JS errors

### Check Routes
```bash
php artisan route:list
```

---

## ✅ Pre-Deployment Checklist

- [ ] Test all pages work
- [ ] Database seeded with production data
- [ ] `.env` configured correctly
- [ ] `npm run build` executed
- [ ] Cache cleared: `php artisan cache:clear`
- [ ] **APP_DEBUG set to false**
- [ ] HTTPS configured
- [ ] Session timeout tested
- [ ] Error pages customized (if needed)

---

## 📚 Learn More

- [Laravel Docs](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Blade Templating](https://laravel.com/docs/blade)

---

**Last Updated**: March 6, 2026  
**Version**: 1.0.0
