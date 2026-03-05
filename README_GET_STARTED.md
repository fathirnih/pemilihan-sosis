# 📱 Pemilihan OSIS - Voting System

> Complete online voting system built with **Laravel 11** + **Blade** + **Tailwind CSS**

---

## ✨ What This Is

A modern, secure voting application for student council elections (OSIS). Students log in with a token, select their choice, and see live results instantly.

**Status**: ✅ **PRODUCTION READY**

---

## 🚀 Quick Start (3 commands)

```bash
composer install && npm install
php artisan migrate && php artisan db:seed --class=PemilihanSeeder
npm run build && php artisan serve
```

Then go to: **http://localhost:8000** 🎉

---

## 📖 Documentation

**👉 Start here**: [📖 Full Documentation Index](INDEX_DOCUMENTATION.md)

### Quick Links by Role:

- **👤 First Timer?** → [📋 Developer Checklist](DEVELOPER_CHECKLIST.md) (30 mins)
- **💻 Need Code Patterns?** → [⚡ Quick Start](QUICK_START.md) (5 mins)
- **🔧 Deploying?** → [🚀 Deployment Guide](DEPLOYMENT_CHECKLIST.md) (reference)
- **📊 Need Overview?** → [🎯 Project Summary](PROJECT_SUMMARY.md) (5 mins)
- **⚙️ Configuration Help?** → [⚙️ Config Reference](CONFIG_REFERENCE.md) (reference)
- **✅ Checking Status?** → [✅ Feature Checklist](FRONTEND_CHECKLIST.md) (2 mins)

---

## 🎯 Features

✅ Token-based authentication  
✅ One-time vote enforcement  
✅ Real-time results display (5-sec refresh)  
✅ Fully responsive (mobile → desktop)  
✅ Professional UI (no tacky design)  
✅ Secure CSRF protection  
✅ Session-based protection  
✅ Production-ready  

---

## 📁 What's Inside

```
Frontend (5 Pages):
  ├── Login page
  ├── Voting page (candidates selection)
  ├── Confirmation page
  ├── Results page (live)
  └── Landing page

Backend:
  ├── AuthController - Login/logout
  ├── VotingController - Vote submission
  ├── ResultsController - Results display
  └── Middleware - Route protection

Database:
  ├── token_pemilih - Voter tokens
  ├── suara - Vote records
  ├── kandidat - Candidates
  └── 5 more tables
```

---

## ⚡ Commands You'll Need

```bash
# Development
php artisan serve              # Start server
npm run dev                    # Watch CSS/JS

# Database
php artisan migrate            # Run migrations
php artisan db:seed           # Add test data
php artisan tinker            # Interactive shell

# Build
npm run build                  # Production build

# Maintenance
php artisan cache:clear       # Clear cache
php artisan route:clear       # Clear route cache
php artisan view:clear        # Clear view cache
```

---

## 🎨 Design System

| Element | Value |
|---------|-------|
| **Primary Color** | Blue (#2563eb) |
| **Font** | Inter (sans-serif) |
| **Framework** | Tailwind CSS |
| **Spacing Unit** | 4px (Tailwind default) |
| **Typography** | Clean, professional |

---

## ✅ Setup Checklist (First Time)

- [ ] Cloned repository
- [ ] Ran `composer install` + `npm install`
- [ ] Created `.env` file
- [ ] Ran `php artisan key:generate`
- [ ] Ran `php artisan migrate`
- [ ] Ran `php artisan db:seed`
- [ ] Ran `npm run build`
- [ ] Started server with `php artisan serve`
- [ ] Accessed http://localhost:8000
- [ ] Tested login with test token
- [ ] **✅ Everything works!**

**Not sure about any step?** → Check [📋 Developer Checklist](DEVELOPER_CHECKLIST.md)

---

## 🗣️ User Flow

```
Anonymous User
    ↓ visits /
Landing Page (learn about voting)
    ↓ clicks "Login"
Login Page
    ↓ enters token, submits
Voting Page (see candidates)
    ↓ clicks vote button
Confirmation Page (vote recorded!)
    ↓ clicks "View Results"
Results Page (live vote counting)
    ↓ votes auto-sync every 5 seconds
```

---

## 🔐 Security Features

✅ Token-based voter authentication  
✅ One-vote-per-token guarantee (token marked used)  
✅ CSRF protection on all forms (`@csrf`)  
✅ SQL injection prevention (Eloquent ORM)  
✅ Session HttpOnly cookies  
✅ Route middleware protection  
✅ Input validation on all endpoints  
✅ No credentials in code  

---

## 📊 Database Quick Info

**Main Tables**:
- `token_pemilih` - Voter tokens (login credentials)
- `suara` - Vote records (one per vote cast)
- `kandidat` - Candidates running
- `periode_pemilihan` - Voting periods
- `siswa` - Students (voters)

**Test Data Included**: 7 students, 3 candidates, 1 test token

---

## 🚀 Deployment Ready?

1. Read [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)
2. Follow pre-deployment phase
3. Execute deployment steps
4. Run post-deployment verification
5. 🎉 Go live!

---

## 🐛 Something Wrong?

### Common Issues

| Problem | Quick Fix |
|---------|-----------|
| "CSS not loading" | Run `npm run build` |
| "Token not found" | Run `php artisan db:seed` |
| "Class not found" | Run `composer dump-autoload` |
| "Page blank" | Check `resources/views/` folder |
| "Database error" | Check `.env` credentials |

**Still stuck?** → Check [⚙️ Config Reference](CONFIG_REFERENCE.md#troubleshooting)

---

## 📚 Learn More

- **Laravel Docs**: https://laravel.com/docs/11.x
- **Blade Templating**: https://laravel.com/docs/11.x/blade
- **Tailwind CSS**: https://tailwindcss.com
- **This Project Docs**: [INDEX_DOCUMENTATION.md](INDEX_DOCUMENTATION.md)

---

## 🤝 Contributing

1. Read [QUICK_START.md](QUICK_START.md) for code patterns
2. Follow Laravel/Tailwind conventions
3. Test your changes locally
4. Commit with clear messages
5. Create pull request

---

## 📋 Project Status

```
Phase 1: Planning ...................... ✅ COMPLETE
Phase 2: Database Design ............. ✅ COMPLETE
Phase 3: Backend Development ........ ✅ COMPLETE
Phase 4: Frontend Development ...... ✅ COMPLETE
Phase 5: Testing & Documentation ... ✅ COMPLETE
Phase 6: Production Deployment ...... 🔄 READY
```

---

## 👥 Team

Built with ❤️ for OSIS 2026

| Role | Name | Contact |
|------|------|---------|
| Tech Lead | [Name] | [contact] |
| Frontend Dev | [Name] | [contact] |
| Backend Dev | [Name] | [contact] |

---

## 📞 Need Help?

### For Setup Issues
→ See [QUICK_START.md](QUICK_START.md)

### For Code Questions
→ See [DEVELOPER_CHECKLIST.md](DEVELOPER_CHECKLIST.md) or [CONFIG_REFERENCE.md](CONFIG_REFERENCE.md)

### For Deployment
→ See [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)

### For Full Documentation
→ See [INDEX_DOCUMENTATION.md](INDEX_DOCUMENTATION.md)

---

## 📄 License

[Your License Here - MIT, GPL, etc.]

---

**Last Updated**: March 6, 2026  
**Version**: 1.0.0 FINAL

👉 **Start here**: [📖 Full Documentation](INDEX_DOCUMENTATION.md)

```
 _____           _ _ _ _     _                                
|_   _|__ ___    | _| (_) | | | ___  ___ _ __ ___
  | |/ _ \_ \| | '_ \| | | |/ _ \/ _ \ '__/ _ \
  | | (_) | | | | | | | | | | | (_) | (_) | | | (_) |
  |_|\___/\_\/ |_| |_|_|_|_| |_|\___/ \___/_|  \___/
  
✅ READY FOR PRODUCTION
```
