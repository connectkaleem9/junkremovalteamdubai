# Performance Specification (DRAFT)

**Date:** 2026-09-16.

## 1. Targets (mobile, 75th percentile field data; Lighthouse lab on "Slow 4G / Moto G Power")

| Metric | Target | Lab gate (CI) |
|---|---|---|
| LCP | < 2.5 s | < 2.0 s |
| INP | < 200 ms | TBT < 150 ms |
| CLS | < 0.1 | < 0.05 |
| TTFB | < 600 ms | < 400 ms (cached HTML) |
| Lighthouse Performance | — | ≥ 90 (EN and AR templates) |

## 2. Budgets per page (first visit, compressed)

| Resource | Budget |
|---|---|
| HTML | ≤ 60 KB |
| CSS (all) | ≤ 35 KB |
| First-party JS | ≤ 25 KB |
| Third-party JS (GTM + GA4) | ≤ 120 KB, loaded non-blocking |
| Fonts | ≤ 2 families/language, ≤ 3 files per page, ≤ 90 KB total |
| LCP image (mobile) | ≤ 100 KB |
| Total page weight | ≤ 800 KB |
| Requests | ≤ 35 |

Budgets enforced by Lighthouse CI `budgets.json`.

## 3. HTML & rendering
- Server-rendered complete HTML; no client-side rendering of content.
- Critical CSS (header, hero, above-fold layout) inlined ≤ 14 KB; rest in one hashed stylesheet with `<link rel="stylesheet">` (small enough that async loading tricks aren't needed).
- JS: one `app.{hash}.js`, `type="module"`/`defer`; features: mobile menu, form enhancements (validation hints, file preview), tracking beacons. No libraries.
- No sliders, carousels, autoplay/background video, parallax, scroll-triggered animation libraries. CSS transitions ≤ 200 ms and respect `prefers-reduced-motion`.

## 4. Images
- Pipeline on upload (GD/Imagick): widths 480, 768, 1200, 1600 → AVIF (if supported) + WebP + JPEG fallback; quality tuned (WebP ~75, AVIF ~50).
- `<picture>` with `srcset` + `sizes`; explicit `width`/`height` always.
- LCP (hero) image: `loading="eager"`, `fetchpriority="high"`, `<link rel="preload" as="image" imagesrcset=… imagesizes=…>`; everything else `loading="lazy" decoding="async"`.
- Hero design should allow a text-first LCP on mobile (headline as LCP element) if no genuine photo is available.
- Icons: inline SVG sprite, no icon fonts.

## 5. Fonts
- Self-hosted WOFF2, subset (Latin for EN; Arabic + Latin digits/punctuation for AR).
- `font-display: swap`; preload only the primary text weight of the current language.
- Metric-compatible fallback (`size-adjust`, `ascent-override`) to prevent CLS on swap.
- Max 2 weights per family (e.g. 400, 700).

## 6. Third-party scripts
- GTM loaded after `DOMContentLoaded` with `async`; only GA4 + Google Ads conversion tags inside. No chat widgets, heatmaps, social embeds by default.
- WhatsApp CTA is a plain `https://wa.me/…` link — no widget script.
- Google Maps: static image or link to Maps; interactive embed only on Contact page, loaded on user click (facade).
- Consent Mode: tags fire according to consent without blocking rendering.

## 7. Caching & delivery
| Resource | Cache-Control |
|---|---|
| `/assets/*.{hash}.*`, `/media/*` | `public, max-age=31536000, immutable` |
| Public HTML | `public, max-age=0, s-maxage=300, stale-while-revalidate=60` when page has no session/CSRF; otherwise `private, no-cache` |
| Sitemaps | `public, max-age=3600` |
| Admin / forms with tokens | `no-store` |

- Brotli (fallback Gzip) for HTML/CSS/JS/SVG/XML/JSON.
- HTTP/2 or HTTP/3.
- **Cacheable pages with forms:** CSRF token injected via a tiny uncached `GET /api/csrf` fetch when the user focuses the form (or forms rendered with a double-submit cookie pattern). Decide one approach in implementation; keeps landing pages edge/server cacheable.
- Optional server-side full-page cache (file cache in `storage/cache/pages/`) invalidated on content save; or Cloudflare in front with cache rules.
- ETag/Last-Modified for HTML.

## 8. PHP & MySQL
- PHP 8.3+ with OPcache (`opcache.validate_timestamps=0` in production, reset on deploy), JIT not required.
- PHP-FPM `pm=ondemand`/`dynamic` sized to server RAM.
- Query budget: ≤ 15 queries per public page; no N+1 (eager-load translations, related items with `IN (...)`).
- Development: query log + timing in debug toolbar; slow query log ≥ 200 ms in production.
- Settings and nav data cached in APCu/file cache (invalidated on save).

## 9. Measurement
- CI: Lighthouse CI against staging for home, one service, quote page, one project — EN and AR, mobile + desktop.
- Post-launch: Search Console Core Web Vitals report, PageSpeed Insights field data, GA4 web-vitals events (optional small script).
- Regression rule: a merge that breaks a budget or lab gate is blocked unless explicitly waived with a reason.
