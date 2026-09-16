# CLAUDE.md — Junk Removal Team Dubai (Master Rulebook)

Read this file in full before making any change to the project.
Source of truth for intent: `JunkRemovalTeamDubai_Complete_Website_Blueprint.txt`.
Where this file and the blueprint differ, this file wins (it records decisions made since).

---

## 1. Project identity

| Field | Value | Verified? |
|---|---|---|
| Brand / business name | Junk Removal Team Dubai | **NO** — blueprint also says "Junk Removal Dubai" |
| Domain | https://junkremovalteamdubai.com/ | NO |
| Address | Al Qouz 3 Industrial Areas, Dubai, UAE | **NO** — spelling/format unconfirmed |
| Phone | 0567021884 (intl: +971 56 702 1884) | NO |
| Email | contact.junkremovalteam@gmail.com | NO |
| Target market | Dubai, United Arab Emirates | — |
| Languages | English (`/en/`, LTR), Arabic (`/ar/`, RTL) | — |

Open verification items live in `docs/00-business-verification.md`.
Until an item is verified, use neutral wording and do not put it in schema, Ads copy, or legal pages.

## 2. Core objective

A local-SEO-first, Google-Ads-ready, bilingual **lead-generation platform** — not a brochure site.
Priority order when trade-offs arise:
1. SEO architecture 2. Trust 3. Conversion 4. Google Ads suitability
5. Performance 6. Accessibility 7. Security 8. Visual design

Primary conversions: phone call, WhatsApp, quote request, contact form, booking request.

## 3. Technology stack

- PHP 8.x, lightweight MVC-style, server-side rendered HTML, reusable PHP view components
- MySQL 8.x via PDO with prepared statements only
- HTML5 + CSS3 (CSS custom properties) + vanilla JavaScript + inline SVG
- **No** React / Vue / Next.js / jQuery / CSS frameworks unless a documented requirement justifies it
- Server target: Nginx or Apache, PHP-FPM, OPcache, HTTPS, Brotli/Gzip
- Front controller: `public/index.php` is the only web-exposed PHP file

Local environment note (2026-09-16): PHP, MySQL and Composer are **not installed** on the dev machine yet.

## 4. Current phase — PHASE GATE

**Phase 0: Planning. Do NOT write production PHP/HTML/CSS/JS yet.**

Implementation may begin only when every deliverable in `docs/README.md` is marked
`Reviewed` by the project owner. This prevents rebuilding because SEO, database,
Arabic, Ads, URL or content architecture was decided after development.

## 5. Non-negotiable rules

1. Never invent business information.
2. Never invent reviews.
3. Never invent customers.
4. Never invent projects.
5. Never invent certifications.
6. Never invent licenses.
7. Never invent awards.
8. Never invent pricing.
9. Never invent guarantees.
10. Never invent years of experience.
11. Never invent team members.
12. Never invent partnerships.
13. Never invent recycling/disposal claims.
14. Never copy competitor content.
15. Never use hidden SEO text.
16. Never keyword stuff.
17. Never create fake schema.
18. Never create fake ratings.
19. Never create misleading Google Ads landing pages.
20. Never create deceptive redirects.
21. Never expose database credentials.
22. Never commit secrets to Git.
23. Never create mass low-value AI pages.
24. Never create doorway-style location pages.
25. Never sacrifice accessibility for design.
26. Never sacrifice performance for animations.
27. Never modify working functionality unnecessarily.

### Banned claims unless verified in `docs/00-business-verification.md`
"#1", "No.1", "Best", "Cheapest", "Guaranteed lowest price", "Government approved",
"Licensed", "Certified", "Insured", "24/7", "Same-day (guaranteed)", "100% guaranteed",
"Eco-friendly / recycled", "X years of experience", "X happy customers", any price figure.

"Free quote" is itself a claim — confirm the business really does not charge for quotes.

### When a fact is unknown
Write neutral copy, or insert a visible marker `[[VERIFY: what is needed]]` in drafts.
A page containing `[[VERIFY:` must never be published or indexed.

## 6. SEO rules (summary — full spec in docs/12-technical-seo-spec.md)

- URLs: HTTPS, non-www host, lowercase, hyphenated, trailing slash, language-prefixed (`/en/…`, `/ar/…`), English-word slugs in both languages, `/` → 301 → `/en/`, no query strings for core pages. Full rules: `docs/06-url-architecture.md`.
- One primary keyword per URL (`docs/02-keyword-map.md`). Provisional decisions until owner confirms: **D1** homepage owns "junk removal dubai" (no `/en/junk-removal-dubai/`); **D4** all service slugs end in `-dubai`; junk collection → pickup page, furniture disposal → furniture removal page, junk clearance → house clearance; construction waste page on hold pending licence.
- Single `users` table with roles and single `leads` table with `lead_type` (see `docs/08-database-schema.md` DB1–DB8).
- Every indexable page: unique title, unique meta description, exactly one H1, self-referencing canonical (HTTPS, preferred host, no tracking params), hreflang en/ar/x-default (x-default → English), Open Graph, relevant schema matching visible content.
- Area pages are `noindex` and excluded from sitemaps unless they pass all 7 checks in blueprint §18.
- Sitemaps list only canonical, indexable, published URLs.
- Never auto-redirect 404s to the homepage. No redirect chains.
- Footer and nav links use natural anchors — no exact-match keyword dumps.

## 7. Bilingual rules

- `<html lang="en" dir="ltr">` / `<html lang="ar" dir="rtl">`.
- Arabic is written/reviewed for Arabic search intent — never unreviewed machine translation.
- Use CSS logical properties (`margin-inline-start`, `padding-inline`, `inset-inline-end`) so RTL works without duplicate stylesheets.
- Hreflang only between pages that both exist and are published.

## 8. Security rules

- PDO prepared statements for every query; no string-built SQL.
- Escape all output (`htmlspecialchars` with ENT_QUOTES, UTF-8) via a single helper.
- CSRF token on every state-changing form; server-side validation; length limits; rate limiting; honeypot/spam checks.
- `password_hash` / `password_verify`; session regeneration on login; secure, HttpOnly, SameSite cookies.
- Uploads: size limit, extension + MIME + actual image decode check, random filenames, stored outside web root or in a no-execute directory, re-encoded.
- Secrets only in `.env` (never committed). `.env.example` documents keys without values.
- Never show raw PHP/MySQL errors in production; log instead (no sensitive data in logs).
- `/admin/` is noindex, blocked in robots.txt, and requires authentication + authorization.

## 9. Performance targets

LCP < 2.5 s · INP < 200 ms · CLS < 0.1 — explicit image dimensions, WebP/AVIF, lazy loading below the fold,
minimal JS, no sliders/autoplay video/heavy libraries, OPcache, compression, indexed queries.

## 10. Accessibility

WCAG 2.2 AA: semantic HTML, heading order, labelled form fields, keyboard access, visible focus,
AA contrast, accessible error messages, tested separately in English and Arabic.

## 11. Agent workflow

Role definitions are in `agents/`. Work flows:

Research → SEO Strategist → Technical SEO + Local SEO → Content + Arabic SEO → UI/UX
→ Frontend + PHP Backend + MySQL → Schema → Google Ads Compliance → Performance + Security → QA → Production

Each agent's output must respect sections 5–10 above. QA (`agents/qa-agent.md`) is the final gate.

## 12. Repository conventions

- Planning deliverables: `docs/` (tracked in `docs/README.md`).
- Business settings (name, phone, address, hours, WhatsApp, socials) come from the `settings` table/config — never hardcode them across files.
- Schema changes only via files in `database/migrations/`.
- Branches: `main`, `develop`, `feature/*`. Never develop directly on production.
