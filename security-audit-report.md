# Security Audit Report — 1DollarDigitizing
**Date:** 2026-05-06  
**Scope:** Laravel application, database layer, file uploads, authentication/authorization, session management, XSS/CSRF/SQLi vectors  
**Platform:** Windows + XAMPP + PHP 8.3.30 + MySQL

---

## Executive Summary

The codebase is **reasonably well-secured** for a legacy migration project. No critical remote-code-execution or SQL-injection vulnerabilities were found. The main concerns are **configuration-level exposures** (`APP_DEBUG=true`), **mass-assignment openness** on most Eloquent models, and **stored-XSS potential** in blog/email content areas. All findings are fixable with configuration changes and minor code hardening.

| Severity | Count | Categories |
|----------|-------|------------|
| Critical | 2 | Debug mode, hardcoded APP_KEY |
| High | 2 | Mass assignment, stored XSS |
| Medium | 3 | Session driver, simulation power, Turnstile disabled |
| Low / Info | 5 | Hardening recommendations |

---

## Critical Findings

### 1. `APP_DEBUG=true` — Stack Trace & Environment Exposure
**File:** `.env`  
**Impact:** If this deployment is reachable from the internet (or from other hosts on the LAN), any exception will render a full Laravel debug page including environment variables, config values, file paths, and stack traces. This leaks the APP_KEY, database credentials, and server layout.

**Fix:**
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Hardcoded `APP_KEY` Should Be Rotated
**File:** `.env`  
**Impact:** The APP_KEY is visible in the `.env` file. Anyone with file-system or Git access can forge signed URLs, decrypt cookies/session payloads (if session encryption is enabled), and bypass certain Laravel security features.

**Fix:**
```bash
php artisan key:generate
```
> **Warning:** Rotating the key will invalidate all existing sessions, password-reset tokens, and signed URLs. Coordinate this during a maintenance window.

---

## High Findings

### 3. Mass Assignment Risk (`$guarded = []` on Most Models)
**Files:** `app/Models/*.php` — 21 models use `protected $guarded = []`  
**Impact:** Every field on these models can be mass-assigned. While current controllers mostly use explicit arrays or validated payloads, future changes or third-party integrations could accidentally allow attackers to overwrite protected fields (`is_active`, `usre_type_id`, `password_hash`, `user_term`, etc.).

**Affected models include:** `AdminUser`, `Order`, `Billing`, `Attachment`, `Site`, `SitePromotion`, etc.

**Fix:** Convert to explicit `$fillable` arrays:
```php
// Instead of:
protected $guarded = [];

// Use:
protected $fillable = ['user_name', 'user_email', 'first_name', 'last_name', ...];
```
For `AdminUser`, never include `password_hash`, `user_password`, `is_active`, or `usre_type_id` in fillable.

### 4. Stored XSS Potential in Blog & Email Content
**Files:**
- `resources/views/public/blog/show.blade.php:80` → `{!! $post->content !!}`
- `resources/views/customer/emails/layout.blade.php:61` → `{!! $content !!}`
- `resources/views/shared/rich-text-editor.blade.php:28` → `{!! $editorValue !!}`

**Impact:** Blog posts and email templates render raw HTML. If an admin account is compromised (or if a CSRF flaw is introduced later), an attacker can inject `<script>` tags that execute in visitors' browsers.

**Fix:**
- Blog: run blog content through an HTML sanitizer (e.g., `stevebauman/purify` or `mews/purifier`) that strips `<script>`, event handlers, and dangerous tags while preserving safe formatting.
- Email: the email layout already uses `{!! !!}` by design for rich email formatting. Ensure that email template editing is restricted to trusted admins only (it is, via `admin.auth` middleware).

---

## Medium Findings

### 5. Session Driver = `file`
**File:** `.env` → `SESSION_DRIVER=file`  
**Impact:** Session files are written to `storage/framework/sessions`. On a shared server or compromised host, other users/processes may be able to read these files. Also, file sessions do not scale across multiple app servers.

**Fix:** Switch to the `database` or `redis` driver:
```env
SESSION_DRIVER=database
```
Ensure the `sessions` migration table exists (`php artisan session:table` then migrate).

### 6. Admin Simulation = Full Account Takeover
**File:** `app/Http/Controllers/AdminSimulationController.php`  
**Impact:** The `simulate-login/{user}` feature lets an admin fully impersonate any customer, team member, or supervisor. This is correctly gated to `TYPE_ADMIN` only and is auditable (logged to `security_audit_events`).

**Mitigation already in place:**
- Requires active admin session
- Requires `usre_type_id === TYPE_ADMIN`
- Target must be `is_active === 1`
- Logs every start/stop event

**Recommendation:** Ensure admin accounts use strong passwords + 2FA (already supported).

### 7. Cloudflare Turnstile Disabled
**File:** `.env` → `TURNSTILE_ENABLED=false`  
**Impact:** Bot protection is inactive. The login, signup, password-reset, and contact forms all have Turnstile widgets, but verification returns `true` unconditionally because the service is disabled.

**Fix:**
```env
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=your-site-key
TURNSTILE_SECRET_KEY=your-secret-key
```

---

## Low / Informational Findings

### 8. `SESSION_SECURE_COOKIE=false`
**Impact:** Session cookies are transmitted over HTTP. This is expected for localhost/XAMPP but must be enabled before deploying with HTTPS:
```env
SESSION_SECURE_COOKIE=true
```

### 9. `SESSION_DOMAIN` is Empty
**Impact:** Cookies default to the current host. If you run multiple subdomains, set this explicitly to prevent session leakage:
```env
SESSION_DOMAIN=.yourdomain.com
```

### 10. No Directory Listing Protection on `uploads/`
**Impact:** The `uploads/` folder (sibling to the project root) stores customer files. If the web server is ever misconfigured to serve that directory, files could be listed.

**Fix:** Ensure `uploads/` is **outside the web root** or protected by a deny rule. The current `SharedUploads::root()` resolves to `../upload`, which is outside `public/` — good. Verify the web server cannot serve `../upload` directly.

### 11. `database/database.sqlite` Exists (0 bytes)
**Impact:** Even though it is empty, having a `.sqlite` file in the project may confuse backups or migrations.

**Fix:** Remove it if unused:
```bash
rm database/database.sqlite
```

### 12. Log File Growth
**File:** `storage/logs/laravel.log` (~1.7 MB)  
**Impact:** Log rotation is not configured. Over time this file can grow unbounded and may contain stack traces or request data.

**Fix:** Configure Laravel log rotation in `config/logging.php` or set up a Windows scheduled task to archive/truncate logs weekly.

---

## What Was Checked and Found SAFE

| Check | Result |
|-------|--------|
| **SQL Injection** | ✅ Safe. All dynamic queries use Eloquent parameter binding. `sortColumn()` uses strict allowlists. `DB::raw()` is only used for `CAST(amount AS DECIMAL(12,2))` with no user input. |
| **CSRF Protection** | ✅ Safe. Every local POST form includes `@csrf`. The checkout form posts to an external payment gateway (no CSRF needed). |
| **File Upload** | ✅ Secure. `UploadSecurity` blocks dangerous extensions, checks MIME types, scans SVG for active content, detects double extensions, and validates `is_uploaded_file()`. |
| **Path Traversal** | ✅ Protected. `SharedUploads::cleanRelativePath()` rejects `..` and null bytes. `UploadController::normalizePath()` blocks `..`. |
| **IDOR (Insecure Direct Object Reference)** | ✅ Protected. Customers can only access their own orders (`where('user_id', $customer->user_id)`). Team members can only access orders assigned to them (`whereIn('assign_to', TeamAccess::accessibleUserIds($teamUser))`). |
| **Command Injection** | ✅ None found. No `eval()`, `exec()`, `shell_exec()`, `system()`, or `passthru()` usage. |
| **Rate Limiting** | ✅ Present on admin, customer, and team login endpoints, plus password reset and contact forms. |
| **Password Hashing** | ✅ Modern. New passwords use `Hash::make()` (bcrypt). Legacy plaintext passwords are auto-migrated on next login via `PasswordManager`. |
| **Session Fixation** | ✅ Mitigated. `invalidate()` + `regenerateToken()` called on auth failure and logout. |
| **Open Redirects** | ✅ None found. All redirects use hardcoded paths or `url()` helper. |
| **Debug Dumps** | ✅ No `dd()`, `dump()`, or `var_dump()` in controller code. |

---

## Recommended Priority Actions

1. **Immediate (before any public access):**
   - Set `APP_DEBUG=false` and `APP_ENV=production`
   - Rotate `APP_KEY`
   - Enable Turnstile with real keys

2. **Short-term (within 1 week):**
   - Switch `SESSION_DRIVER` to `database`
   - Set `SESSION_SECURE_COOKIE=true` when HTTPS is ready
   - Add HTML sanitizer to blog content storage/rendering
   - Begin converting `$guarded = []` to explicit `$fillable` on critical models (`AdminUser`, `Order`, `Billing`)

3. **Medium-term (within 1 month):**
   - Review and lock down `$fillable` on all remaining models
   - Set up log rotation
   - Run an automated vulnerability scanner (e.g., OWASP ZAP) against the running application
   - Verify `uploads/` folder is completely outside web-server reach

---

*End of Report*
