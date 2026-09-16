# Testing Specification (DRAFT)

**Date:** 2026-09-16. Environments: development (local) → staging (production-like, noindex + basic auth) → production.

## 1. Tooling

| Layer | Tool | Notes |
|---|---|---|
| PHP unit/integration | PHPUnit (Composer dev dependency) | Integration tests against a disposable MySQL 8 test database |
| Static analysis | PHPStan (level 6+), PHP-CS-Fixer (PSR-12) | CI blocking |
| Dependency audit | `composer audit` | CI blocking on high severity |
| End-to-end (browser) | Playwright (Node — installed locally) | Chromium, Firefox, WebKit; mobile emulation |
| Accessibility | `@axe-core/playwright` + manual keyboard/screen reader checks | 0 serious/critical violations |
| Performance | Lighthouse CI with budgets | Gates in `15-performance-spec.md` |
| SEO crawler | Custom Node/PHP crawl script (`tests/seo/`) | Rules in §3 |
| Visual regression | Playwright screenshots | EN LTR + AR RTL |
| Security | OWASP ZAP baseline scan on staging + manual checklist in `14-security-spec.md` | |
| Schema | Schema.org validator / Rich Results Test (manual pre-launch) | |

Local prerequisite: PHP 8.3, MySQL 8, Composer are **not yet installed** on the dev machine (see CLAUDE.md §3).

## 2. Functional tests

| Area | Cases |
|---|---|
| Routing | All normalizations (§1 of technical SEO spec) produce single-hop 301; unknown → 404; draft → 404; unapproved AR → 404 |
| Redirects | table redirect applied before routing; no chains after slug change; loop prevented at save |
| Navigation | every nav/footer link 200; mobile menu keyboard operable; language switcher → equivalent page |
| Quote form | valid submit → lead row + attachments + status history + email + redirect to thank-you; attribution (UTM, gclid, landing page, referrer) captured from first touch cookie/session |
| Validation | each field: empty, too long, invalid phone/email, past date; errors shown next to fields in page language; input preserved (except files) |
| Spam/rate limits | honeypot filled → silently rejected; <3 s submit rejected; 6th submit/hour blocked |
| Uploads | valid jpg/png/webp accepted & re-encoded; EXIF stripped; `.php` renamed jpg rejected; >8 MB rejected; >5 files rejected; decompression bomb rejected |
| Email | notification contains all fields from blueprint §69; failure is logged and lead still saved |
| Tracking | click on `tel:`, `wa.me`, `mailto:` fires dataLayer event + `/api/track` beacon; `quote_start` fires once on first field focus |
| Admin auth | login, logout, throttling, session timeout, password reset expiry |
| Admin authorization | role matrix from security spec §7 (each forbidden action → 403) |
| Admin CRUD | create/edit/publish/archive for each content type incl. translations in a transaction; slug change creates redirect; indexable blocked when `[[VERIFY:` present or area checks fail |
| Leads pipeline | status change writes history; notes; filters by status/date/service/campaign |
| Sitemaps | only published + indexable + approved; lastmod = updated_at; hreflang alternates correct |
| Settings | changing phone updates header, footer, contact page, schema, WhatsApp links everywhere |

## 3. SEO crawler rules (run on staging, CI blocking)
For every URL reachable from `/en/` and `/ar/` plus sitemap URLs:
- status 200 (sitemap URLs), no redirect targets inside sitemap
- exactly one `<h1>`; heading levels don't skip
- `<title>` and meta description present, unique within language, within length guidance (warn)
- canonical present, absolute, HTTPS, self (unless configured), no query string
- robots meta matches indexation matrix
- hreflang: reciprocal, absolute, targets return 200 and are indexable; x-default present
- `<html lang dir>` correct for prefix
- no `[[VERIFY:` string in HTML
- no banned-claim terms (regex list from CLAUDE.md §5, EN + AR equivalents) unless whitelisted by verification record
- all internal links 200; no links to noindex area pages from nav/footer
- every indexable page has ≥ 2 inbound internal links (orphan check); depth ≤ 3
- images have `alt` attribute, `width` and `height`
- JSON-LD parses; no `aggregateRating`; LocalBusiness fields only from verified settings

## 4. Accessibility (WCAG 2.2 AA)
- axe: 0 serious/critical on all templates (EN + AR)
- Keyboard-only: reach and operate nav, switcher, all CTAs, form, file input, errors; visible focus everywhere
- Screen reader smoke test: NVDA + Firefox (EN), VoiceOver iOS (AR) — form labels, error announcements (`aria-live`/`aria-describedby`), landmarks
- Contrast verified for all design tokens
- 200% zoom and 320 px width: no loss of content or horizontal scroll
- `prefers-reduced-motion` respected
- Mobile bottom bar doesn't obscure focused elements or content

## 5. Responsive & browser matrix
Viewports: 360×800 (mobile portrait), 800×360 (landscape), 768×1024 (tablet), 1366×768 (laptop), 1920×1080 (desktop), 2560×1440 (large).
Browsers: Chrome, Edge, Firefox, Safari (macOS/iOS via real device or WebKit), Samsung Internet (manual).
Each template × {EN LTR, AR RTL}.

## 6. Language QA
- `resources/lang/en` and `ar` key parity test (CI)
- Native Arabic reviewer sign-off per page (recorded as `translation_status=approved` + reviewer in audit log)
- Bidi check: phone numbers, emails, prices, dates render correctly in RTL
- English proofreading pass

## 7. Release gates

| Gate | Blocking checks |
|---|---|
| Every merge to `develop` | PHPUnit, PHPStan, CS, composer audit, lang parity |
| Deploy to staging | + Playwright E2E, axe, SEO crawler, Lighthouse CI |
| Deploy to production | + manual security checklist, Google Ads landing-page checklist, native AR review, owner content sign-off, final QA report (blueprint §100) all PASS or accepted with documented reason |

## 8. Post-launch monitoring
- Uptime check on `/en/` and quote form endpoint (synthetic submit to test mode weekly)
- Search Console: coverage, CWV, manual actions — weekly for first 2 months
- Error log review daily first week, then weekly
- Monthly backup restore test
