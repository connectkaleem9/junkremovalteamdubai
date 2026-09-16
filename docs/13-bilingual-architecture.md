# Bilingual Architecture — English / Arabic (DRAFT)

**Date:** 2026-09-16.

## 1. URL strategy — decision
**English-word slugs under both prefixes:** `/en/furniture-removal-dubai/` ↔ `/ar/furniture-removal-dubai/`.

| Option | Pros | Cons |
|---|---|---|
| **English slugs in /ar/ (chosen)** | 1 slug per entity → trivial hreflang pairing, clean shareable URLs (no `%D8%…` encoding in WhatsApp/ads), simpler redirects | Arabic keyword not in URL (minor ranking signal) |
| Arabic-script slugs | Keyword in URL for Arabic users | Percent-encoded when copied, two slugs to maintain, harder redirects/QA |

Arabic relevance comes from Arabic title, H1, content, anchors and `lang="ar"` — far stronger signals than the slug.

## 2. Language resolution
- Language is taken **only** from the URL prefix. No cookie/IP/Accept-Language redirects.
- `/` → 301 → `/en/` (x-default is English).
- Language switcher links to the **equivalent** page in the other language. If no approved translation exists: switcher links to the other language's homepage and is labelled accordingly; no hreflang is emitted.
- Switcher labels: "العربية" on English pages, "English" on Arabic pages, each with `lang` attribute and `hreflang` on the `<a>`.

## 3. Content storage
| Content | Stored in |
|---|---|
| UI strings (nav labels, buttons, form labels, validation messages, footer headings) | `resources/lang/{en,ar}/*.php` returning arrays; key lookup helper `__('forms.name')` |
| Page/service/area/project/FAQ/blog/review/media text | `*_translations` tables |
| Business settings with language variants (address, hours text) | `settings` with `language` column |

**No fallback on public pages.** A missing Arabic string in a lang file fails CI (key parity test). A missing/unapproved Arabic content translation → the Arabic URL returns 404.

## 4. Markup & RTL
- `<html lang="ar" dir="rtl">` on all `/ar/` pages.
- CSS written once with **logical properties** (`margin-inline-start`, `padding-inline-end`, `inset-inline-start`, `text-align: start`, `border-inline-start`). No separate RTL stylesheet; a small `[dir="rtl"]` block only for exceptions (icon mirroring).
- Mirror directional icons (arrows, chevrons, "next"), **don't** mirror phone, WhatsApp, logos, checkmarks, media controls.
- Phone numbers, emails, URLs, prices, dates in digits: wrap in `<bdi>` or `<span dir="ltr">` to prevent reordering (`+971 56 702 1884` must not render reversed).
- Form inputs for phone and email: `dir="ltr"` with `inputmode="tel"` / `type="email"`; text inputs inherit RTL.
- Numerals: Western Arabic digits (0–9) on both languages (standard in UAE commerce, consistent with phone/WhatsApp). Confirm with native reviewer.
- Breadcrumb separators and carousels (none planned) follow `dir`.

## 5. Typography
- Separate font stacks per language, selected via `:lang(ar)`. Arabic font must support Gulf readability at small sizes (candidates for design system: IBM Plex Sans Arabic, Noto Sans Arabic, Tajawal). Final choice in `10-design-system.md`.
- Arabic line-height larger (≈1.7–1.8 body) than English (≈1.5–1.6). No letter-spacing on Arabic (breaks joining). No all-caps styles relied upon for meaning.
- Self-host subsetted WOFF2; Arabic font only loaded on `/ar/` pages.

## 6. SEO per language
- Unique Arabic `<title>`, meta description, H1, H2s written for Arabic search intent (see `01-research/keywords.md`), not translated line-by-line.
- `og:locale` `ar_AE`, `og:locale:alternate` `en_AE`.
- JSON-LD `inLanguage: "ar"`; business `name` stays the registered name; `alternateName` in Arabic only if the business actually uses one.
- Arabic sitemap entries in the same sub-sitemaps with `xhtml:link` alternates.
- Arabic internal links point only to Arabic pages.

## 7. Translation workflow
1. English content brief approved.
2. Arabic brief created from Arabic keyword research (may differ in H2s/FAQs).
3. Arabic copy written (Claude may draft) → `translation_status = in_review`.
4. Native Arabic reviewer (verification H6) checks accuracy, tone, terminology, claims → `approved`.
5. Only then Arabic URL goes live and hreflang pairs are emitted.

Claims in Arabic must be exactly as verified as in English — translators may not add softeners/superlatives (e.g. "الأفضل", "رقم 1", "على مدار الساعة") unless verified.

## 8. Dates, times, forms, emails
- Dates displayed with `IntlDateFormatter` (`en_AE` / `ar_AE`, Gregorian calendar, Western digits).
- Validation messages returned in the page language.
- Customer-facing confirmation email in the language of submission; internal lead notification always includes lead `language`.
- WhatsApp prefilled message in the page language.

## 9. Testing (see `16-testing-spec.md`)
- Key parity test for `resources/lang/en` vs `ar`.
- Visual regression of each template in LTR and RTL at mobile + desktop.
- Crawl check: every `/ar/` page has `lang="ar" dir="rtl"`, reciprocal hreflang, Arabic title differing from English.
- Manual native-speaker review before launch.
