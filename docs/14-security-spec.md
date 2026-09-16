# Security Specification (DRAFT)

**Date:** 2026-09-16. Baseline: OWASP Top 10 / OWASP ASVS Level 2 for admin, Level 1 for public site.

## 1. Deployment layout
- Web server document root = `public/` only. `app/`, `resources/`, `database/`, `storage/`, `.env` are outside it.
- Deny all dotfiles (`/\.`) and any `.php` except `public/index.php`.
- `public/media/` (optimized public images) — static only, PHP execution disabled.
- Lead photo uploads stored in `storage/uploads/leads/` (not web accessible); served to admins via an authenticated controller with `Content-Disposition: inline`, correct `Content-Type`, `X-Content-Type-Options: nosniff`.

Nginx example (reference):
```nginx
root /var/www/junkremoval/public;
location ~ /\.            { deny all; }
location ^~ /media/       { location ~ \.php$ { deny all; } try_files $uri =404; }
location /                { try_files $uri /index.php$is_args$args; }
location = /index.php     { include fastcgi_params; fastcgi_pass php-fpm; fastcgi_param SCRIPT_FILENAME $document_root/index.php; }
location ~ \.php$         { deny all; }
```

## 2. HTTP security headers

| Header | Value |
|---|---|
| Strict-Transport-Security | `max-age=31536000; includeSubDomains` (add `preload` only after stable) |
| Content-Security-Policy | `default-src 'self'; script-src 'self' 'nonce-{n}' https://www.googletagmanager.com; img-src 'self' data: https://www.google-analytics.com https://www.googletagmanager.com; connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://www.googletagmanager.com; frame-src https://www.googletagmanager.com https://www.google.com; style-src 'self'; font-src 'self'; form-action 'self'; frame-ancestors 'none'; base-uri 'self'; object-src 'none'` — extend precisely when Ads/Maps tags are added (start in Report-Only on staging) |
| X-Content-Type-Options | `nosniff` |
| Referrer-Policy | `strict-origin-when-cross-origin` |
| Permissions-Policy | `camera=(), microphone=(), geolocation=(), payment=()` |
| Cross-Origin-Opener-Policy | `same-origin` |
| X-Robots-Tag | `noindex, nofollow` on `/admin/*`, `/api/*`, staging |
| Cache-Control | `no-store` on admin and form responses containing tokens |

## 3. Input & output
- **SQL:** PDO, `PDO::ATTR_EMULATE_PREPARES=false`, `ERRMODE_EXCEPTION`, prepared statements always; identifiers (ORDER BY columns) from allowlists only.
- **Output:** single `e()` helper = `htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`; attribute and URL contexts use dedicated helpers; JSON-LD built with `json_encode(..., JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)`.
- **Rich text (admin editor):** sanitized server-side with a strict allowlist (p, h2–h4, ul/ol/li, strong, em, a[href] with http(s)/relative only, img from own media) — using a vetted sanitizer (e.g. HTML Purifier / symfony/html-sanitizer via Composer).
- **Validation:** server-side for every field; length limits match DB columns; phone normalized to E.164 (UAE numbers) — reject otherwise with helpful message; email `filter_var`; dates not in the past for preferred date; enums against allowlists.

## 4. CSRF
- Per-session random token (32 bytes, `random_bytes`), hidden field in every POST form + `X-CSRF-Token` header for fetch requests.
- Validated with `hash_equals`; failure → 419-style page, logged (without token).
- `SameSite=Lax` cookies as defence in depth.

## 5. Sessions
```
session.use_strict_mode=1
session.use_only_cookies=1
session.cookie_secure=1
session.cookie_httponly=1
session.cookie_samesite=Lax
session.sid_length=48 / sid_bits_per_character=6
```
- Public visitors: no session unless a form is rendered (keeps pages cacheable — CSRF token for cached pages fetched via small endpoint or forms rendered uncached; decide in implementation, documented in performance spec).
- Admin: `session_regenerate_id(true)` on login and privilege change; idle timeout 30 min, absolute 8 h; logout destroys session server-side and clears cookie.

## 6. Authentication (admin)
- `password_hash(PASSWORD_ARGON2ID)` (fallback `PASSWORD_BCRYPT` cost 12 if Argon2 unavailable); `password_needs_rehash` on login.
- Minimum 12-char passwords; check against common-password list.
- Login throttling: 5 failures per (ip_hash + email) per 15 min → lock 15 min; 20 per ip_hash per hour. Generic error message ("Invalid email or password").
- Optional TOTP 2FA for `admin` role — recommended before launch.
- Password reset: single-use token (hashed in DB), 30-min expiry, invalidates sessions.

## 7. Authorization

| Capability | admin | editor | sales |
|---|---|---|---|
| Leads: view, notes, status | ✓ | – | ✓ |
| Leads: delete / export | ✓ | – | – |
| Services, areas, projects, reviews, FAQs, blog, pages, media | ✓ | ✓ | – |
| SEO meta, redirects | ✓ | ✓ (redirect create only) | – |
| Settings (business info, verification flags) | ✓ | – | – |
| Users | ✓ | – | – |

Enforced in middleware per route **and** re-checked in controller actions that mutate data. Default deny.

## 8. Forms & spam
- Honeypot field + minimum time-to-submit (≥3 s via signed timestamp).
- Rate limits: quote/contact 5 per ip_hash per hour, 20 per day; `/api/track` 60/min.
- Link-count and disallowed-content heuristics raise `spam_score`; high scores stored as `spam` (not emailed).
- If spam persists post-launch: add Cloudflare Turnstile (privacy-friendlier, add to CSP) — not by default.
- Post/Redirect/Get to prevent resubmission.

## 9. File uploads (quote photos)
1. Max 5 files, 8 MB each (`UPLOAD_MAX_*` env); also enforced by `upload_max_filesize`/`post_max_size`/web server body limit.
2. Check `UPLOAD_ERR_OK`, `is_uploaded_file`.
3. Extension allowlist: jpg, jpeg, png, webp, heic (heic only if server can decode; otherwise ask user to send via WhatsApp).
4. MIME via `finfo_file` allowlist; must agree with extension.
5. Decode with GD/Imagick; reject on failure; reject images > 8000 px any side (decompression bomb).
6. **Re-encode** to WebP/JPEG (strips EXIF incl. GPS location — privacy), max 2000 px long side.
7. Save with random name (`bin2hex(random_bytes(16))`) under `storage/uploads/leads/{yyyy}/{mm}/`; original filename never used on disk.
8. Directory permissions 0750, files 0640; no execute.
9. Admin media uploads follow the same pipeline, then variants generated into `public/media/`.

## 10. Secrets & config
- `.env` outside web root, not committed; `.env.example` has keys only.
- `APP_KEY` (32 random bytes) for HMACs (ip_hash, signed timestamps).
- Production: `APP_DEBUG=false`, `display_errors=0`, `expose_php=0`.
- SMTP / API credentials only in `.env`; rotate if ever exposed.

## 11. Error handling & logging
- Global exception handler → logs to `storage/logs/app-YYYY-MM-DD.log` (JSON lines) → renders generic 500 page.
- Log: exceptions, auth failures, CSRF failures, rate-limit hits, admin mutations (`audit_log`), upload rejections.
- Never log: passwords, tokens, session IDs, full lead messages, raw IPs.
- Log rotation 30 days.

## 12. Dependencies
- Composer with committed `composer.lock`; minimal packages (mailer e.g. PHPMailer/Symfony Mailer, HTML sanitizer, optionally dotenv).
- `composer audit` in CI; monthly update review.

## 13. Privacy (UAE PDPL — Federal Decree-Law 45/2021)
- Collect only fields needed for quoting.
- Privacy Policy states: data collected, purpose (responding to enquiries), retention period, sharing (email provider, analytics), contact for requests.
- Analytics: no personal data (names, phones, emails) sent to GA4/GTM; hashed enhanced-conversions only after explicit decision + policy update.
- Cookie banner if non-essential cookies (GA4/Ads) are used; Consent Mode v2 configured.
- **Legal text must be reviewed by the owner / a legal adviser** — Claude drafts are not legal advice.

## 14. Pre-launch security checklist
- [ ] Headers verified (securityheaders.com / curl)
- [ ] `.env`, `/storage/`, `/app/`, `.git` return 403/404 from web
- [ ] SQLi/XSS probes on all forms and query params (manual + automated)
- [ ] CSRF missing/invalid token rejected on every POST
- [ ] Upload: PHP file renamed `.jpg`, polyglot image, oversized image, EXIF GPS stripped
- [ ] Login throttling and lockout verified
- [ ] Role matrix tested (sales cannot edit services, editor cannot see leads)
- [ ] Backups restore successfully on staging
