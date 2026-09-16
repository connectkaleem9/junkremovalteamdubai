# URL Architecture (DRAFT)

**Date:** 2026-09-16. Applies recommended defaults for open decisions — marked **[D#]** — see `02-keyword-map.md`.

## 1. Global rules

| Rule | Value |
|---|---|
| Scheme | `https` only; HTTP → 301 → HTTPS |
| Host | `junkremovalteamdubai.com` (non-www); `www.` → 301 → non-www |
| Case | lowercase only; any uppercase path → 301 → lowercase |
| Trailing slash | required on all HTML pages (`/en/faqs/`); missing → 301 → with slash |
| Files | no trailing slash: `/sitemap.xml`, `/robots.txt`, `/assets/...` |
| Language prefix | every HTML page lives under `/en/` or `/ar/` |
| Root `/` | 301 → `/en/` (no IP/Accept-Language redirects — crawlers must reach both languages) |
| Slugs | English words, hyphenated, ASCII, in **both** `/en/` and `/ar/` (see `13-bilingual-architecture.md`) |
| Query strings | never used for indexable content; tracking params ignored for canonical |
| `index.php`, `.php` extensions | never exposed; `/index.php/...` → 301 → clean URL |
| Duplicate slashes | `//` collapsed → 301 |

All normalizations resolve in **one** 301 hop (compute final URL, redirect once).

## 2. Public URL inventory — English (Arabic mirrors with `/ar/`)

### Core
| URL | Page | Indexable | Notes |
|---|---|---|---|
| `/en/` | Homepage | Yes | Owns "junk removal dubai" **[D1 = A]** |
| `/en/about-us/` | About | Yes | |
| `/en/contact-us/` | Contact | Yes | |
| `/en/services/` | Services hub | Yes | Short intro + cards; not a keyword page |
| `/en/areas/` | Areas served | Yes | Lists served areas; links only to qualified area pages |
| `/en/projects/` | Projects listing | Yes once ≥1 real project; noindex while empty | |
| `/en/projects/{slug}/` | Project | Yes | Real projects only |
| `/en/reviews/` | Reviews | Yes once genuine reviews exist; noindex while empty | |
| `/en/faqs/` | FAQs | Yes | |
| `/en/blog/` | Blog listing | Yes once ≥1 post | `/en/blog/page/2/` for pagination |
| `/en/blog/{slug}/` | Blog post | Yes | |
| `/en/get-a-quote/` | Quote form page | Yes | Form also embedded on other pages |
| `/en/thank-you/` | Post-submit confirmation | **No** (noindex) | Ads/GA4 conversion confirmation; reachable only after POST-redirect |

### Services
| URL | Status |
|---|---|
| `/en/junk-pickup-dubai/` | Build [D2 pending] |
| `/en/furniture-removal-dubai/` | Build |
| `/en/apartment-clearance-dubai/` | Build if offered |
| `/en/villa-clearance-dubai/` | Build if offered |
| `/en/house-clearance-dubai/` | [D3 pending] |
| `/en/office-clearance-dubai/` | Build if offered |
| `/en/commercial-junk-removal-dubai/` | Build if offered **[D4 = add -dubai]** |
| `/en/construction-waste-removal-dubai/` | Hold (licence) **[D4]** |

Service pages sit at the **language root** (not under `/en/services/`) per blueprint §10 — short, keyword-relevant URLs. Breadcrumb still shows Home › Services › Page.

### Areas
`/en/areas/{area-slug}/` — only for areas passing `05-area-architecture.md` checks. Unqualified areas have **no URL**.

### Legal
`/en/privacy-policy/` · `/en/terms-and-conditions/` · `/en/cookie-policy/` · `/en/disclaimer/`
(indexable, low priority; excluded from Ads)

## 3. Non-HTML & system URLs

| URL | Purpose | Crawl/Index |
|---|---|---|
| `/robots.txt` | robots | — |
| `/sitemap.xml` | sitemap index | — |
| `/sitemap-pages.xml`, `/sitemap-services.xml`, `/sitemap-areas.xml`, `/sitemap-projects.xml`, `/sitemap-blog.xml` | sub-sitemaps (omitted from index when empty) | — |
| `/assets/{type}/{name}.{hash}.{ext}` | versioned static assets | crawlable |
| `/media/{yyyy}/{mm}/{random}-{width}.{webp|avif|jpg}` | public optimized images | crawlable |
| `POST /en/get-a-quote/`, `POST /en/contact-us/` | form submissions (same URL as page, PRG → thank-you) | — |
| `POST /api/track` | conversion event beacon (204) | Disallow |
| `/admin/...` | admin | Disallow + `X-Robots-Tag: noindex` + auth |

## 4. Reserved slugs
`admin, api, assets, media, en, ar, sitemap, robots, thank-you, get-a-quote, search, login, logout` — admin UI must reject these for new content slugs.

## 5. Slug change policy
Published slugs are permanent. If a change is unavoidable, admin creates the new slug and automatically inserts a 301 in `redirects` (and rewrites existing redirects pointing at the old URL to prevent chains).
