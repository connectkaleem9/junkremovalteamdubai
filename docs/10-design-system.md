# UI/UX Design System (DRAFT)

**Date:** 2026-09-16. Palette is provisional until the owner supplies logo/brand assets (B10). All tokens live in one CSS file as custom properties.

## 1. Direction
Professional · clean · industrial · trustworthy · Dubai-focused.
Deliberate contrast with competitors: they lean on bright green ("eco") and orange. We use deep steel blue + teal with a restrained amber highlight — no implied eco claims, no "cheap template" look.

## 2. Color tokens (contrast verified with WCAG 2.x formula, 2026-09-16)

```css
:root {
  --color-primary:        #12324A; /* deep steel blue — header, headings accents */
  --color-primary-900:    #0C2233; /* footer, dark sections */
  --color-accent:         #0A6570; /* CTA buttons, links, focus */
  --color-accent-hover:   #07505A;
  --color-highlight:      #F2B84B; /* small highlights on dark bg only; never body text on light bg */
  --color-whatsapp:       #13733F; /* WhatsApp button (darkened for contrast; brand #25D366 fails with white text) */
  --color-text:           #1A2530;
  --color-text-muted:     #56616B;
  --color-text-on-dark:   #FFFFFF;
  --color-text-on-dark-muted: #B8C4CE;
  --color-bg:             #F7F8F6;
  --color-surface:        #FFFFFF;
  --color-border:         #DDE2E6; /* decorative dividers only */
  --color-border-input:   #7B8691; /* form controls (non-text 3:1) */
  --color-error:          #B42318;
  --color-success:        #17693A;
  --color-focus:          #0A6570;
  --color-focus-on-dark:  #F2B84B;
}
```

| Pair | Ratio | Requirement | Result |
|---|---|---|---|
| text on bg | 14.59 | 4.5 | Pass AAA |
| muted text on white / bg | 6.33 / 5.94 | 4.5 | Pass |
| white on primary | 13.28 | 4.5 | Pass AAA |
| white on accent (CTA) | 6.76 | 4.5 | Pass |
| white on accent-hover | 9.11 | 4.5 | Pass |
| accent links on white / bg | 6.76 / 6.34 | 4.5 | Pass |
| white on WhatsApp button | 5.91 | 4.5 | Pass |
| error text on white; white on error | 6.57 | 4.5 | Pass |
| success on white | 6.73 | 4.5 | Pass |
| input border on white | 3.71 | 3.0 (non-text) | Pass |
| focus ring accent on white | 6.76 | 3.0 | Pass |
| focus ring highlight on primary | 7.42 | 3.0 | Pass |
| muted on primary; footer muted on primary-900 | 7.48 / 7.85 | 4.5 | Pass |
| highlight on primary-900 | 9.08 | 4.5 | Pass |

Color is never the only carrier of meaning (errors also get icon + text).

## 3. Typography

| Role | English | Arabic |
|---|---|---|
| Family | **IBM Plex Sans** (400, 600) | **IBM Plex Sans Arabic** (400, 600) |
| Why | Industrial, highly legible, open licence (OFL), designed as a matched pair → consistent look across languages | |
| Fallback | `system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif` | `"Segoe UI", Tahoma, "Geeza Pro", Arial, sans-serif` |

```css
:root {
  --font-sans: "IBM Plex Sans", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
  --font-arabic: "IBM Plex Sans Arabic", "Segoe UI", Tahoma, "Geeza Pro", Arial, sans-serif;
  --fs-300: clamp(0.875rem, 0.85rem + 0.1vw, 0.9375rem);  /* small */
  --fs-400: clamp(1rem, 0.96rem + 0.2vw, 1.0625rem);      /* body */
  --fs-500: clamp(1.125rem, 1.05rem + 0.35vw, 1.25rem);   /* lead */
  --fs-600: clamp(1.375rem, 1.2rem + 0.8vw, 1.75rem);     /* h3 */
  --fs-700: clamp(1.75rem, 1.45rem + 1.4vw, 2.375rem);    /* h2 */
  --fs-800: clamp(2.125rem, 1.7rem + 2.1vw, 3.25rem);     /* h1 */
  --lh-body: 1.6;  --lh-heading: 1.2;
}
:lang(ar) { font-family: var(--font-arabic); --lh-body: 1.8; --lh-heading: 1.4; letter-spacing: 0; }
```
Body minimum 16px. Measure 60–75 characters (`max-inline-size: 68ch`). No uppercase transforms on Arabic; avoid on English beyond tiny labels.

## 4. Spacing, layout, radius, elevation

```css
:root {
  --space-1: 0.25rem; --space-2: 0.5rem; --space-3: 0.75rem; --space-4: 1rem;
  --space-5: 1.5rem;  --space-6: 2rem;   --space-7: 3rem;    --space-8: 4rem; --space-9: 6rem;
  --container: 72rem; --container-narrow: 48rem; --gutter: clamp(1rem, 4vw, 2rem);
  --radius-sm: 4px; --radius-md: 8px; --radius-lg: 12px; --radius-pill: 999px;
  --shadow-sm: 0 1px 2px rgb(18 50 74 / .08);
  --shadow-md: 0 4px 12px rgb(18 50 74 / .10);
  --shadow-lg: 0 12px 32px rgb(18 50 74 / .14);
  --bottom-bar-h: 64px;
}
```
Breakpoints (mobile-first, `min-width`): 36rem (576), 48rem (768), 64rem (1024), 80rem (1280).
Section vertical rhythm: `--space-8` mobile, `--space-9` desktop.

## 5. Components

### Buttons
| Variant | Use | Style |
|---|---|---|
| Primary | Get a Free Quote / Request Junk Removal | accent bg, white text, 600 weight, min-height 48px, radius-md |
| Call | Call Now | primary bg (on light) or white outline (on dark), phone icon |
| WhatsApp | WhatsApp Us | whatsapp bg, white text, WhatsApp glyph |
| Secondary | Learn more, view project | accent text, 2px accent border |
| Text link | inline | accent, underline (always underlined in body copy) |

All: touch target ≥ 48×48px; visible focus `outline: 3px solid var(--color-focus); outline-offset: 2px`; hover/active states; `:disabled` with reason text if applicable.

### Header
Desktop: logo (start) · nav · language switcher · primary CTA (end). Sticky, compact (≤ 72px), white surface, `--shadow-sm` on scroll.
Mobile: logo · Call icon button · Menu button (`aria-expanded`, `aria-controls`); menu opens as full-height panel from inline-end, focus trapped, Esc closes.

### Mobile bottom action bar (< 64rem)
Fixed, 3 equal buttons: Call · WhatsApp · Get Quote. Height `--bottom-bar-h` + `env(safe-area-inset-bottom)`. `body` gets matching `padding-block-end` so content is never covered. Hidden on the quote page (form is the page) and when the on-screen keyboard is open (focus within form).

### Hero (service pages & home)
H1 + 1–2 sentence value statement (verified facts only) + CTA group (Quote, Call, WhatsApp) + up to 3 **verified** trust points (e.g. "Based in Al Quoz, Dubai", "Send photos on WhatsApp for a quote").
Image: genuine photo if available (truck/team/real job); otherwise a clean text-led hero on `--color-primary` — no stock "happy movers".

### Cards
Service card: icon (inline SVG line icon), title, short description, link. Project card: genuine image (4:3), service + area badge, title, date. Review card: text, name as permitted, source label ("Google review"), date; star display only if a real rating exists.

### Forms
- Label above field, always visible (no placeholder-only labels); required marked with text "(required)" not only `*`.
- Controls min-height 48px, border `--color-border-input`, radius-sm; focus ring as buttons.
- Errors: summary at top (`role="alert"`, links to fields) + inline message (`aria-describedby`), icon + error color.
- Quote form order: Name · Phone · Email (optional) · Service · Area · What needs removing · Photos · Preferred date · Preferred time · consent note + Privacy link · Submit.
- File input: accessible native input styled, with selected-file list and remove buttons; client-side size check (server still validates).
- Success: dedicated thank-you page with next steps and WhatsApp/call options.

### Alerts & badges
Info/success/error alerts with icon + text. Badges for service/area labels (neutral surface, primary text).

### Footer
`--color-primary-900` background, white/muted text, columns per `07-internal-linking.md`, NAP block using `<address>`, legal links, copyright with real business name.

### Iconography
Single set of line icons (1.75px stroke, rounded caps) as inline SVG sprite: phone, whatsapp, mail, map-pin, clock, truck, sofa, box, building, check, arrow (mirrored in RTL), chevron (mirrored), camera, calendar. No cartoon trash graphics.

## 6. Imagery rules
Genuine photos first (blueprint §81). If none: typographic/illustrative layouts using brand colors and simple geometric/industrial motifs. Stock photography only as clearly generic mood imagery — never implying our team, truck or project. All images WebP/AVIF, sized per `15-performance-spec.md`.

## 7. Motion
Transitions ≤ 200ms on color/opacity/transform only; no entrance animations on scroll; `@media (prefers-reduced-motion: reduce)` disables all transitions.

## 8. RTL rules
Logical properties only; layout mirrors automatically. Mirror: arrows, chevrons, progress direction, breadcrumb order. Don't mirror: logos, phone/WhatsApp icons, checkmarks, numbers. Phone numbers wrapped `dir="ltr"`. Test every component in both directions.

## 9. Page templates to design (next: visual mockups)
Home · Service · Area · Projects list · Project · Reviews · FAQs · About · Contact · Get a Quote · Thank you · Blog list · Blog post · Legal · 404/500 · Admin (functional, same tokens, denser).

## Open items
- Logo & brand colors from owner (B10) may change `--color-primary` / `--color-accent`; re-run contrast checks if so.
- Visual mockups (mobile + desktop, EN + AR) for Home and one Service page before frontend build.
