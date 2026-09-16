# Technical SEO Specification (DRAFT)

**Date:** 2026-09-16. Testable rules. URL rules are in `06-url-architecture.md`; bilingual rules in `13-bilingual-architecture.md`.

## 1. HTTP behaviour

| Request | Expected |
|---|---|
| `http://junkremovalteamdubai.com/en/` | 301 → `https://junkremovalteamdubai.com/en/` |
| `https://www.junkremovalteamdubai.com/en/faqs` | **single** 301 → `https://junkremovalteamdubai.com/en/faqs/` |
| `/EN/Junk-Pickup-Dubai/` | 301 → `/en/junk-pickup-dubai/` |
| `/` | 301 → `/en/` |
| `/en/junk-pickup-dubai/?utm_source=google&gclid=x` | 200, canonical without params |
| Unknown path `/en/xyz/` | 404 with real 404 page (no redirect) |
| Draft/unpublished content URL (public) | 404 |
| Archived content previously public | 410, or 301 to closest equivalent if one exists |
| Arabic URL whose translation isn't approved | 404 (and no hreflang pointing to it) |
| Redirects table match | configured code; checked **before** routing |
| `/admin/*` unauthenticated | 302 → `/admin/login/` with `X-Robots-Tag: noindex, nofollow` |
| Server/DB failure | 500/503 with static error page; 503 + `Retry-After` during maintenance |
| `HEAD` requests | same status/headers as GET |

No soft 404s: empty listing pages either show useful content or are `noindex`.

## 2. `<head>` requirements (every HTML page)

```html
<html lang="en" dir="ltr">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{meta_title}</title>                       <!-- ≤ 60 chars target, unique per language -->
<meta name="description" content="{meta_description}"> <!-- ≤ 160 chars, unique -->
<meta name="robots" content="index,follow">         <!-- or noindex,follow -->
<link rel="canonical" href="https://junkremovalteamdubai.com/en/{slug}/">
<link rel="alternate" hreflang="en" href="https://junkremovalteamdubai.com/en/{slug}/">
<link rel="alternate" hreflang="ar" href="https://junkremovalteamdubai.com/ar/{slug}/">
<link rel="alternate" hreflang="x-default" href="https://junkremovalteamdubai.com/en/{slug}/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{business_name}">
<meta property="og:locale" content="en_AE">          <!-- ar_AE on Arabic -->
<meta property="og:locale:alternate" content="ar_AE">
<meta property="og:title" content="…">
<meta property="og:description" content="…">
<meta property="og:url" content="{canonical}">
<meta property="og:image" content="…1200x630…">
<meta name="twitter:card" content="summary_large_image">
<script type="application/ld+json">…</script>
```

- Hreflang tags emitted **only** when both language versions are published and approved; reciprocal; absolute URLs; self-reference included.
- `noindex` pages: no canonical to another URL, no hreflang.
- Canonical is always self unless `seo_meta.canonical_url` set (must be same domain, validated in admin).

## 3. Indexation matrix

| Page type | robots | Sitemap |
|---|---|---|
| Home, core, service (published) | index,follow | Yes |
| Area (indexable=1) | index,follow | Yes |
| Area (indexable=0) | noindex,follow | No |
| Project / blog post (published) | index,follow | Yes |
| Projects/Reviews/Blog listing while empty | noindex,follow | No |
| Blog pagination `/page/N/` | index,follow, self-canonical | No (only page 1) |
| Thank-you | noindex,nofollow | No |
| Legal | index,follow | Yes (low priority) |
| Admin, API, search, filters | noindex (header) + robots Disallow | No |
| Staging environment (all) | `X-Robots-Tag: noindex` + HTTP basic auth | — |

Admin cannot set `index` on a draft, on a page containing `[[VERIFY:`, or on an area failing qualification (enforced server-side).

## 4. robots.txt (production)

```
User-agent: *
Disallow: /admin/
Disallow: /api/
Disallow: /*?*sort=
Disallow: /*?*filter=

Sitemap: https://junkremovalteamdubai.com/sitemap.xml
```
Never disallow `/assets/` or `/media/`. UTM/gclid URLs are **not** disallowed (Ads crawlers must fetch landing pages; canonical handles duplication). Staging robots.txt: `Disallow: /`.

## 5. XML sitemaps
- `/sitemap.xml` = sitemap index listing non-empty sub-sitemaps with `<lastmod>`.
- Sub-sitemaps contain both languages; each `<url>` includes `<xhtml:link rel="alternate" hreflang>` entries when both versions exist.
- `<lastmod>` = real content `updated_at` (not generation time). No `priority`/`changefreq` (ignored by Google).
- Generated dynamically and cached; cache invalidated on content save.
- Only 200, canonical, indexable, published, approved-translation URLs.

## 6. Structured data (JSON-LD) per page type

| Page | Types | Notes |
|---|---|---|
| All pages | `WebSite` (home only), `Organization`/`LocalBusiness` referenced by `@id` | Business node emitted once on home, referenced elsewhere |
| Home | `LocalBusiness` + `WebPage` | Only verified settings (`settings.is_verified=1`); omit unverified fields |
| Service | `Service` (provider → LocalBusiness @id, `areaServed` only if verified) + `BreadcrumbList` + `WebPage` | No `offers`/price |
| Area | `WebPage` + `BreadcrumbList` (+ `Service` with `areaServed` = that area if verified) | |
| Project | `WebPage`/`Article` + `BreadcrumbList` + `ImageObject` for genuine images | |
| Blog post | `BlogPosting` + `BreadcrumbList` | real author/organization |
| FAQs page / FAQ sections | `FAQPage` only when Q&As visible on that page | Google shows FAQ rich results rarely for non-authoritative sites — implement for correctness, not expectation |
| Reviews | **No `aggregateRating` / `Review` markup on own LocalBusiness** — Google treats self-serving review markup as ineligible | Display reviews visibly; link to Google profile |

LocalBusiness `@type`: use `LocalBusiness` until a more specific accurate type is confirmed. If service-area business without customer-visitable address (B6), omit `address.streetAddress` per GBP policy alignment.

## 7. Headings & content
- Exactly one `<h1>` per page; H2/H3 hierarchy without skips.
- Titles and descriptions unique per language — admin warns on duplicates via `ix_*_meta_title` lookup; QA crawler fails build on duplicates.

## 8. Images
- Descriptive filenames are **not** used for uploads (random names for security); SEO relies on alt text, captions, surrounding content and `ImageObject` where relevant.
- Every content image: `alt` (empty `alt=""` for decorative), `width`/`height`, responsive `srcset`.
- OG image 1200×630 per key page (genuine photo or branded graphic, not fake project evidence).

## 9. Breadcrumbs
Visible breadcrumb + matching `BreadcrumbList`; last item not linked; RTL order mirrors visually on Arabic.

## 10. Launch checklist (Technical SEO)
- [ ] Staging noindex/auth removed only on production host
- [ ] HTTPS + HSTS; all variants single-hop 301
- [ ] robots.txt production version live
- [ ] Sitemap submitted in Search Console (domain property, DNS verification)
- [ ] URL Inspection on home + every Ads landing page
- [ ] Crawl with QA crawler: 0 broken links, 0 duplicate titles, 0 missing canonicals, hreflang reciprocal
- [ ] Rich Results Test on home, one service, one project, FAQs
- [ ] 404 page returns 404 status
