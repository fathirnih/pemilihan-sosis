# 📋 Developer's Day 1 Checklist

> Complete this checklist on your first day working with the frontend. Should take ~30 minutes.

---

## Phase 1: Environment Setup (5 min)

- [ ] Clone repository
  ```bash
  git clone [repository-url]
  cd pemilihan-sosis
  ```

- [ ] Install dependencies
  ```bash
  composer install
  npm install
  ```

- [ ] Create `.env` file
  ```bash
  cp .env.example .env
  ```

- [ ] Generate application key
  ```bash
  php artisan key:generate
  ```

- [ ] Verify `.env` has correct database credentials
  - Database host: localhost
  - Database name: pemilihan-sosis
  - Database user: root (default)
  - Database password: (usually empty for local)

---

## Phase 2: Database Setup (5 min)

- [ ] Run migrations
  ```bash
  php artisan migrate
  ```
  Expected output: Multiple migrations created including `token_pemilihs` table

- [ ] Seed test data
  ```bash
  php artisan db:seed --class=PemilihanSeeder
  ```
  Expected output: 7 siswa, 3 kandidat, 1 token pemilih created

- [ ] Verify database structure
  ```sql
  -- Quick check commands
  SELECT COUNT(*) FROM token_pemilih;    -- Should be 1
  SELECT COUNT(*) FROM kandidat;         -- Should be 3
  SELECT COUNT(*) FROM siswa;            -- Should be 7
  
  -- Get test token
  SELECT token FROM token_pemilih WHERE status = 'aktif' LIMIT 1;
  ```

---

## Phase 3: Build Frontend Assets (3 min)

- [ ] Build CSS and JS
  ```bash
  npm run build
  ```
  Expected: `public/build/manifest.json` created

- [ ] Verify build output
  ```bash
  ls -la public/build/    # or dir public\build on Windows
  ```
  Should see: `manifest.json`, CSS file, JS file

---

## Phase 4: Start Development Server (2 min)

- [ ] Start Laravel server
  ```bash
  php artisan serve
  ```
  Expected output: `Server running on [http://127.0.0.1:8000]`

- [ ] Keep command running (don't close terminal)

---

## Phase 5: Test All Pages (10 min)

### Test 1: Landing Page
- [ ] Open `http://localhost:8000` in browser
- [ ] Verify:
  - [ ] Hero section displays with title
  - [ ] Features section shows 3 feature cards
  - [ ] CTA button is visible
  - [ ] Footer appears at bottom
  - [ ] No console errors (F12 → Console tab)

### Test 2: Login Page
- [ ] Click "Login" button or go to `/login`
- [ ] Verify:
  - [ ] Form has "Token" input field
  - [ ] Submit button is visible
  - [ ] Try submitting empty form → See error message
  - [ ] No page refresh on error (AJAX-friendly)

### Test 3: Login with Token
- [ ] Enter test token from database (from Phase 2)
- [ ] Click login
- [ ] Expected: Redirected to `/voting` page

### Test 4: Voting Page
- [ ] Verify:
  - [ ] Period name shows "Pemilihan OSIS 2026"
  - [ ] "Dibuka" status badge shows
  - [ ] 3 candidate cards display in grid
  - [ ] Each card shows: nomor urut, nama, visi/misi, team members
  - [ ] Vote button on each card is clickable

### Test 5: Vote Submission
- [ ] Click vote button on any candidate
- [ ] Expected: Redirected to `/voting/confirmation`

### Test 6: Confirmation Page
- [ ] Verify:
  - [ ] Green success message appears
  - [ ] Shows which candidate was selected
  - [ ] "View Results" button visible
  - [ ] "Logout" option available

### Test 7: Results Page
- [ ] Click "View Results" button
- [ ] Verify:
  - [ ] 3 candidates listed with vote counts
  - [ ] Progress bars show vote distribution
  - [ ] Percentages calculated correctly
  - [ ] Page auto-refreshes every 5 seconds (watch the counter)
  - [ ] No console errors

### Test 8: Logout
- [ ] Click logout from results page
- [ ] Expected: Redirected to login with cleared session
- [ ] Try accessing `/voting` directly → Should redirect to login

### Test 9: Reuse Token (Security Test)
- [ ] Try logging in with same token again
- [ ] Expected: Error message "Token sudah digunakan"
- [ ] This confirms one-time vote protection works ✅

### Test 10: Double-Vote Prevention
- [ ] Get a new test token (create another in database if needed)
- [ ] Vote for candidate A
- [ ] Try going back to `/voting` manually
- [ ] Expected: Error or redirect (session should be cleared)

---

## Phase 6: Code Structure Review (5 min)

Navigate to each file and confirm structure:

### Views Layer
- [ ] `resources/views/layouts/app.blade.php`
  - [ ] Has `@yield('content')`
  - [ ] Imports Tailwind CSS
  - [ ] Has `<head>` and `<body>` tags

- [ ] `resources/views/auth/login.blade.php`
  - [ ] Has CSRF token: `@csrf`
  - [ ] Form posts to `/login`
  - [ ] Error display logic present

- [ ] `resources/views/voting/index.blade.php`
  - [ ] Loops through `$kandidats`
  - [ ] Each card has vote form/button
  - [ ] Posts to `/voting` route

- [ ] `resources/views/results/index.blade.php`
  - [ ] Shows vote counts
  - [ ] Has JavaScript for auto-refresh

### Controller Layer
- [ ] `app/Http/Controllers/AuthController.php`
  - [ ] Has `showLogin()`, `login()`, `logout()` methods
  - [ ] Validates token from `TokenPemilih` model

- [ ] `app/Http/Controllers/VotingController.php`
  - [ ] Has `index()` - shows voting page
  - [ ] Has `store()` - saves vote to database
  - [ ] Uses `EnsurePemilihAuthenticated` middleware

- [ ] `app/Http/Controllers/ResultsController.php`
  - [ ] Retrieves candidates with vote counts
  - [ ] Calculates percentages and totals

### Middleware Layer
- [ ] `app/Http/Middleware/EnsurePemilihAuthenticated.php`
  - [ ] Checks `Session::has('pemilih_token_id')`
  - [ ] Redirects to login if missing

### Routes
- [ ] `routes/web.php`
  - [ ] Looking at file, you should see:
    - 2 public routes: `/login` (GET/POST)
    - 4 protected routes with `'pemilih'` middleware
  - [ ] No syntax errors

---

## Phase 7: Browser DevTools Check (2 min)

### In browser, press F12:

- [ ] **Console tab**
  - [ ] No red errors
  - [ ] No warnings about missing scripts
  - [ ] No CORS issues

- [ ] **Network tab**
  - [ ] All resources load (green 200 status)
  - [ ] CSS file loaded (in build manifest)
  - [ ] No 404s for images/fonts

- [ ] **Elements tab** (Inspect Element)
  - [ ] Tailwind classes applied (e.g., `bg-blue-600`)
  - [ ] Hover effects work
  - [ ] Responsive (resize window, see layout adjust)

---

## Phase 8: Database Verification (2 min)

Open database client (MySQL Workbench, phpMyAdmin, etc.):

```sql
-- Table structure check
DESCRIBE token_pemilih;
-- Should show columns: id, periode_id, tipe_pemilih, pemilih_id, token, status, sudah_memilih, ...

DESCRIBE suara;
-- Should show: id, periode_id, kandidat_id, tipe_pemilih, pemilih_id, created_at, ...

-- Data check
SELECT * FROM token_pemilih LIMIT 1;
SELECT * FROM kandidat LIMIT 1;
SELECT * FROM suara;  -- Should be empty initially, then 1 row after vote
```

- [ ] Confirm all tables exist
- [ ] Confirm columns match migration file
- [ ] Confirm test data inserted correctly

---

## Phase 9: Git & Version Control (2 min)

- [ ] Check git status
  ```bash
  git status
  ```
  Should show modified `.env` (possibly), but main files are clean

- [ ] Create local feature branch
  ```bash
  git checkout -b feature/frontend-initial-setup
  ```

- [ ] DON'T commit yet (wait for team lead approval on structure)

---

## Phase 10: Documentation Review (2 min)

- [ ] Read `README_FRONTEND.md`
  - [ ] Understand page descriptions
  - [ ] Review design system section
  - [ ] Note security features

- [ ] Read `QUICK_START.md`
  - [ ] Bookmark for future reference
  - [ ] Note color codes for design tweaks

- [ ] Read this checklist (`DEVELOPER_CHECKLIST.md`)
  - [ ] All items completed successfully = ✅ ready to code

---

## ✅ Final Sign-Off

| Task | Status | Notes |
|------|--------|-------|
| Environment setup | ⬜ | |
| Database configured | ⬜ | |
| All pages accessible | ⬜ | |
| Voting flow works end-to-end | ⬜ | |
| One-time vote enforced | ⬜ | |
| No console errors | ⬜ | |
| Code structure understood | ⬜ | |
| Ready to develop | ⬜ | |

---

## 🎯 Completed? Next Steps:

Once all checkboxes are completed ✅:

1. **For Feature Development**
   ```bash
   git checkout -b feature/your-feature-name
   # Make changes
   # Test locally
   # Commit: git add . && git commit -m "feat: description"
   ```

2. **For CSS Tweaks**
   - Edit `resources/css/app.css` (Tailwind components)
   - Edit `.blade.php` files (inline Tailwind classes)
   - Test with `npm run dev` (watch mode)

3. **For Page Updates**
   - Find page in `resources/views/`
   - Update HTML/Blade syntax
   - Test in browser
   - Check responsive on mobile

4. **For Database Changes**
   - Create migration: `php artisan make:migration [name]`
   - Write migration logic
   - Test: `php artisan migrate`
   - Update models if needed

---

## 🐛 If Something Goes Wrong

| Problem | Solution |
|---------|----------|
| "Token not found" | Check `token_pemilih` table has data |
| "CSS not loaded" | Run `npm run build` |
| "DB Connection error" | Check `.env` host/user/password |
| "Class not found" | Run `composer dump-autoload` |
| "View not found" | Check file path and spelling |
| "Route not found" | Run `php artisan route:clear` |
| "Session not working" | Check `sessions` table exists |

---

## 📝 Notes

- Keep this terminal window open: `php artisan serve`
- For CSS changes while developing: `npm run dev` (separate terminal)
- Your `.env` file is gitignored (don't commit it)
- Database is local - changes don't affect others
- Test token location: `php artisan tinker` → `TokenPemilih::first()->token`

---

**Duration Target**: 30-45 minutes for complete setup
**Success Criteria**: All checkboxes ✅ and voting flow works end-to-end
**Date Completed**: ________________________
**Developer Name**: ________________________

---

Last Updated: March 6, 2026
