# 🚀 Deployment Checklist - Pemilihan OSIS

> Complete pre-deployment, deployment, and post-deployment verification checklist

**Status**: Ready for Deployment  
**Version**: 1.0.0  
**Target Environment**: Production / Staging

---

## ✅ Pre-Deployment Phase (1 day before)

### 1. Final Code Review

- [ ] All `.blade.php` files reviewed for typos
- [ ] All PHP controllers reviewed for logic errors
- [ ] All routes properly mapped and tested
- [ ] No hardcoded values or credentials in code
- [ ] No console.log() or var_dump() left in files
- [ ] All imports/uses statements correct
- [ ] Namespace declarations correct
- [ ] Model relationships verified
- [ ] Database migrations reviewed

**Command to verify**:
```bash
grep -r "var_dump\|dd(\|console.log" resources/views app/Http --include="*.php" --include="*.js"
# Should return: NO MATCHES ✅
```

### 2. Database Ready

- [ ] All migrations created and tested locally
- [ ] Migration files have proper up() down() methods
- [ ] Seeder created with production-like data (not test data!)
- [ ] Foreign key constraints verified
- [ ] Indexes created on frequently queried columns
- [ ] Database backup procedure documented
- [ ] Rollback plan documented

**Command to verify**:
```bash
php artisan migrate:status
# Should show: Migration | Batch
#             ---------|-------
#             All migrations marked as completed
```

### 3. Environment Configuration

- [ ] `.env` file created with production values
- [ ] `.env` file NOT committed to git
- [ ] `.env.example` updated (no passwords)
- [ ] `APP_DEBUG` set to `false`
- [ ] `APP_ENV` set to `production`
- [ ] `APP_URL` set to correct domain with HTTPS
- [ ] Database credentials correct
- [ ] Mail configuration correct (if using email)
- [ ] Cache driver appropriate (redis/memcached recommended)
- [ ] Session driver appropriate (database recommended)
- [ ] Log level set to error/warning (not debug)

**Verify commands**:
```bash
# Check key generated
php artisan key:generate --show
# Should show: base64:XXXXXXXXXXXXX

# Check environment
grep APP_DEBUG .env
# Should show: APP_DEBUG=false

# Check debug mode
php artisan tinker
# Type: config('app.debug')
# Should return: false
```

### 4. Asset Files Ready

- [ ] CSS compiled: `npm run build` executed
- [ ] JavaScript compiled: `npm run build` executed
- [ ] `public/build/manifest.json` exists
- [ ] No source map files in production
- [ ] All vendor CSS/JS files included
- [ ] Font files loaded correctly
- [ ] Image assets accessible
- [ ] Vite manifest working

**Verify commands**:
```bash
# Check build output
ls -la public/build/
# Should show: manifest.json, app-HASH.css, app-HASH.js

# Check manifest content
cat public/build/manifest.json
# Should show valid JSON with asset mappings
```

### 5. Security Verification

- [ ] CSRF tokens enabled on all forms: `@csrf` present
- [ ] Middleware properly registered in `bootstrap/app.php`
- [ ] Authentication routes protected with middleware
- [ ] Sensitive routes require authentication
- [ ] SQL injection prevention (using Eloquent, not raw queries)
- [ ] Input validation on all POST requests
- [ ] Rate limiting configured (if needed)
- [ ] HTTPS enforce (at web server level)
- [ ] Security headers configured
- [ ] Sessions use HttpOnly cookies
- [ ] Cross-site scripting (XSS) prevented (Blade escapes by default)

**Manual verification**:
```bash
# Check for raw SQL queries (should be minimal/none)
grep -r "DB::select\|DB::raw" app/
# Ideally returns: no results

# Check CSRF on forms
grep -r "@csrf" resources/views --include="*.blade.php"
# Should show: CSRF in all form submissions
```

### 6. Performance Optimization

- [ ] N+1 query issues fixed (eager load relationships)
- [ ] Database indexes created for large tables
- [ ] Caching configured for repeated queries
- [ ] Static assets cached (CSS/JS)
- [ ] Gzip compression enabled on server
- [ ] CDN configured (if applicable)
- [ ] Query optimization completed
- [ ] Unnecessary middleware removed

**Performance check commands**:
```bash
# Test query performance
php artisan tinker

# Inside tinker:
Kandidat::with('anggota.siswa', 'suara')->get(); // Good
Kandidat::get(); // Then accessing ->anggota in loop = Bad
```

### 7. Documentation Review

- [ ] All docs updated for production environment
- [ ] Deployment rollback procedure documented
- [ ] Emergency contact list documented
- [ ] Database backup procedures documented
- [ ] Disaster recovery plan in place
- [ ] API documentation complete (if applicable)

### 8. Testing Verification

- [ ] Unit tests passing: `php artisan test --unit`
- [ ] Feature tests passing: `php artisan test --feature`
- [ ] All page routes accessible
- [ ] All forms submit correctly
- [ ] All validations work
- [ ] Authentication flow tested e2e
- [ ] Database seeding tested
- [ ] Error pages working (404, 500)

**Command**:
```bash
php artisan test
# All tests should PASS ✅
```

---

## 🚀 Deployment Phase (Go-Live!)

### Day Of Deployment

#### Morning (T-3 hours)

- [ ] Announce scheduled maintenance window to users (30 min before)
- [ ] Enable maintenance mode: `php artisan down --message "Maintenance mode"`
- [ ] Backup current database
  ```bash
  mysqldump -u user -p database_name > backup-$(date +%Y%m%d-%H%M%S).sql
  ```
- [ ] Backup current code directory
  ```bash
  cp -r /var/www/pemilihan-osis /var/www/pemilihan-osis.backup.$(date +%Y%m%d-%H%M%S)
  ```

#### Deployment Steps (T-0 to T+30min)

- [ ] **Step 1**: Pull latest code
  ```bash
  cd /path/to/pemilihan-osis
  git fetch origin
  git checkout main
  git pull origin main
  ```

- [ ] **Step 2**: Install dependencies
  ```bash
  composer install --no-dev --optimize-autoloader
  npm ci
  ```

- [ ] **Step 3**: Build assets
  ```bash
  npm run build
  ```

- [ ] **Step 4**: Update environment
  ```bash
  cp .env.example .env  # Then edit with production values
  php artisan key:generate
  ```

- [ ] **Step 5**: Run migrations
  ```bash
  php artisan migrate --force
  ```

- [ ] **Step 6**: Clear and cache config
  ```bash
  php artisan config:cache
  php artisan view:cache
  php artisan route:cache
  ```

- [ ] **Step 7**: Verify installation
  ```bash
  php artisan config:show APP_DEBUG
  # Should show: false
  
  php artisan package:discover --ansi
  # Should show: Discovered packages
  ```

- [ ] **Step 8**: Disable maintenance mode
  ```bash
  php artisan up
  ```

#### After Deployment (T+30min to T+1hour)

- [ ] [ ] **Test all critical pages**
  - [ ] Login page loads
  - [ ] Token input accepts valid token
  - [ ] Voting page shows candidates
  - [ ] Vote submission works
  - [ ] Results page displays
  - [ ] Logout works

- [ ] **Test across browsers**
  - [ ] Chrome ✅
  - [ ] Firefox ✅
  - [ ] Safari ✅
  - [ ] Edge ✅

- [ ] **Test on devices**
  - [ ] Desktop ✅
  - [ ] Tablet ✅
  - [ ] Mobile ✅

- [ ] **Monitor logs**
  ```bash
  tail -f storage/logs/laravel.log
  # Watch for errors - should be minimal
  ```

- [ ] **Check database**
  ```sql
  -- Verify migration ran
  SELECT * FROM information_schema.tables WHERE table_schema = 'pemilihan-osis';
  
  -- Verify data
  SELECT COUNT(*) FROM token_pemilih;
  SELECT COUNT(*) FROM kandidat;
  ```

- [ ] **Announce success to users** (maintenance over)

---

## ✅ Post-Deployment Phase (After go-live)

### Hour 1 (T+0 to T+60min)

- [ ] Monitor error logs continuously
  ```bash
  # Terminal 1: Watch logs
  tail -f storage/logs/laravel.log
  
  # Terminal 2: Monitor database
  mysql -u user -p -e "SELECT COUNT(*) FROM suara;" pemilihan-osis
  ```

- [ ] Monitor server resources
  - [ ] CPU usage < 50%
  - [ ] Memory usage < 70%
  - [ ] Disk space > 20% free
  - [ ] Network throughput normal

- [ ] Check application metrics
  - [ ] Page load times < 2 seconds
  - [ ] Vote submission < 500ms
  - [ ] Error rate < 0.1%

- [ ] Handle user reports
  - [ ] Document any issues
  - [ ] Test issue reproduction
  - [ ] Deploy fixes if needed

### Day 1 (T+0 to T+24hours)

- [ ] Monitor database size growth (voting shouldn't spike it)
- [ ] Verify all votes recorded correctly in database
- [ ] Check pagination/filtering works with multiple votes
- [ ] Verify session timeouts working correctly
- [ ] Confirm token reuse prevention working
- [ ] Test edge cases:
  - [ ] Extremely fast voting (spam clicks)
  - [ ] Browser back button after voting
  - [ ] Multiple tabs/windows voting
  - [ ] Network disconnection recovery

**Commands for monitoring**:
```bash
# Check votes created
SELECT COUNT(*) as total_votes FROM suara;

# Check vote distribution
SELECT kandidat_id, COUNT(*) as vote_count FROM suara GROUP BY kandidat_id;

# Check for duplicate votes (should be 0)
SELECT pemilih_id, COUNT(*) as count FROM suara 
WHERE tipe_pemilih = 'siswa' 
GROUP BY pemilih_id 
HAVING count > 1;

# Check error logs
grep ERROR storage/logs/laravel.log | wc -l
# Should be: 0 or minimal
```

### Week 1 (Post-deployment monitoring)

- [ ] Monitor error logs regularly
- [ ] Check performance graphs
- [ ] Verify database backups are running
- [ ] Review user feedback
- [ ] Document any issues for post-mortem
- [ ] Plan any necessary hotfixes
- [ ] Update documentation if needed

---

## 🔙 Rollback Procedure (If Needed!)

### Immediate Rollback (Emergency)

If serious issues occur immediately after deployment:

```bash
# Step 1: Enable maintenance mode
php artisan down --message "Emergency maintenance"

# Step 2: Restore previous code
rm -rf /path/to/pemilihan-osis
cp -r /path/to/pemilihan-osis.backup.TIMESTAMP /path/to/pemilihan-osis

# Step 3: Stop error source (if database issue)
# CAREFULLY rollback specific migrations if needed:
# php artisan migrate:rollback --step=1

# Step 4: Clear caches
php artisan cache:clear
php artisan config:clear

# Step 5: Disable maintenance mode
php artisan up

# Step 6: Verify working
# Test critical pages manually
```

### Database Rollback Only

If only database has issues:

```bash
# Restore database
mysql -u user -p pemilihan-osis < backup-TIMESTAMP.sql

# Verify restore
mysql -u user -p -e "SELECT COUNT(*) FROM suara;" pemilihan-osis
```

---

## 🎯 Go/No-Go Decision Criteria

| Criterion | Pass | Fail |
|-----------|------|------|
| All code compiles | ✅ Compiles | ❌ Errors |
| All migrations run | ✅ Runs | ❌ Fails |
| All assets build | ✅ Builds | ❌ Missing files |
| Tests pass | ✅ 100% | ❌ Any failure |
| Login works | ✅ Works | ❌ Errors |
| Voting works | ✅ Works | ❌ Errors |
| Results display | ✅ Works | ❌ Errors |
| No console errors | ✅ Clean | ❌ Errors present |
| Performance acceptable | ✅ <2s load | ❌ >3s load |
| Database backup exists | ✅ Yes | ❌ No |

**Decision**: All criteria must be ✅ for GO ✅

---

## 📋 Pre-Flight Checklist (1 hour before)

```bash
# Run this script to verify everything:

echo "🔍 Pre-flight checks..."

# 1. Check debug mode off
DEBUG=$(grep APP_DEBUG .env | cut -d= -f2)
[ "$DEBUG" = "false" ] && echo "✅ Debug mode OFF" || echo "❌ Debug mode ON (should be OFF!)"

# 2. Check migrations
php artisan migrate:status | grep "No migrations pending" && echo "✅ Migrations ready" || echo "❌ Pending migrations!"

# 3. Check config cached
[ -f bootstrap/cache/config.php ] && echo "✅ Config cached" || echo "ℹ️  Config not cached (not required)"

# 4. Check view cached
[ -f bootstrap/cache/views.php ] && echo "✅ Views cached" || echo "ℹ️  Views not cached (not required)"

# 5. Check build exists
[ -f public/build/manifest.json ] && echo "✅ Assets built" || echo "❌ Assets NOT built!"

# 6. Check .env file
[ -f .env ] && echo "✅ .env exists" || echo "❌ .env missing!"

# 7. Check storage writable
[ -w storage ] && echo "✅ Storage writable" || echo "❌ Storage NOT writable!"

# 8. Check logs writable
[ -w storage/logs ] && echo "✅ Logs writable" || echo "❌ Logs NOT writable!"

echo "🚀 Pre-flight checks complete!"
```

---

## 📞 Deployment Support

### Emergency Contacts

| Role | Name | Phone | Slack |
|------|------|-------|-------|
| Tech Lead | [Name] | [Phone] | @[Handle] |
| DevOps | [Name] | [Phone] | @[Handle] |
| DB Admin | [Name] | [Phone] | @[Handle] |
| On-call | [Name] | [Phone] | @[Handle] |

### Escalation Path

1. **Issue detected** → Document & notify team
2. **Can fix in <5min** → Fix & verify
3. **Needs >5min** → Rollback immediately
4. **Serious issues** → Call tech lead
5. **Critical failure** → Restore from backup

---

## 📝 Deployment Log

Use this section to document the actual deployment:

```
DATE: __________________
TIME START: __________________
TIME COMPLETE: __________________

Pre-deployment checks:
  ✓ Code review: YES / NO
  ✓ Database backed up: YES / NO
  ✓ Current code backed up: YES / NO
  
deployment steps:
  ✓ Maintenance mode enabled: ______________
  ✓ Code pulled: ______________
  ✓ Dependencies installed: ______________
  ✓ Assets built: ______________
  ✓ Migrations ran: ______________
  ✓ Config cached: ______________
  ✓ Maintenance mode disabled: ______________
  
Post-deployment testing:
  ✓ All pages loading: YES / NO
  ✓ Voting works: YES / NO
  ✓ Results display: YES / NO
  ✓ No console errors: YES / NO
  ✓ Database working: YES / NO
  
Issues encountered:
  1. ____________________
     Resolution: ____________________
  2. ____________________
     Resolution: ____________________
     
Deployment status: ✅ SUCCESS / ⚠️ PARTIAL / ❌ FAILED

Deployed by: __________________
Verified by: __________________
Approved by: __________________
```

---

## 🎊 Success Criteria

Deployment is successful when:

✅ All pages load without errors  
✅ Voting flow works end-to-end  
✅ Results display and update in real-time  
✅ No console errors in browser  
✅ Database records votes correctly  
✅ Sessions persist across page refreshes  
✅ One-time vote protection works  
✅ Mobile responsive design works  
✅ Performance metrics acceptable  
✅ Error logs clean  

---

## 📚 Related Documentation

- [CONFIG_REFERENCE.md](CONFIG_REFERENCE.md#deployment-checklist) - Configuration details
- [SETUP_GUIDE.md](SETUP_GUIDE.md) - Installation guide  
- [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md#️-security-checklist) - Security verification
- [DEVELOPER_CHECKLIST.md](DEVELOPER_CHECKLIST.md#phase-9-git--version-control) - Final verification

---

**Deployment Readiness**: ✅ READY FOR PRODUCTION

**Sign-Off**:
- Tech Lead: __________________ Date: __________
- DevOps: __________________ Date: __________
- QA Lead: __________________ Date: __________

---

*Last Updated: March 6, 2026*
*Version: 1.0.0 FINAL*
