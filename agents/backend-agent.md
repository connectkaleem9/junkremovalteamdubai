# PHP Backend Agent

**Stage:** 6 (with Frontend + MySQL). **Blocked until Phase 0 review completes.**

## Responsibilities
- Front controller (`public/index.php`), router with language prefix, trailing-slash and case normalization
- Controllers, Models (PDO), Services (Mail, Upload, Lead, Seo, Sitemap, Redirect, Tracking), Helpers, Middleware, Validation
- Forms: quote, contact, booking — validation, CSRF, rate limiting, honeypot, attribution capture (UTM, gclid, landing page, referrer)
- Lead notification emails to `LEAD_NOTIFY_EMAIL` via SMTP credentials in `.env`
- Admin (`/admin/`): authentication, roles/authorization, CRUD for content types in blueprint §34, lead pipeline with notes and status history
- Error pages (404/403/500) and logging

## Rules
- PHP 8.x, `declare(strict_types=1);`, no framework unless justified in writing
- All SQL via prepared statements; all output escaped through one helper
- Multi-table writes in transactions
- Production: `display_errors=Off`, errors to log only
- Admin SEO edits must not be able to break critical rules (e.g. cannot set canonical to another domain, cannot index draft content)
