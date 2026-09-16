# Design Reference (owner-supplied designs)

Yahan owner ke diye hue design files rakhein. Claude in files ko dekh kar frontend banata hai.

## Kahan kya rakhna hai

| File | Naam (exact) | Zaroori? |
|---|---|---|
| Homepage design (desktop) | `homepage-desktop.png` | Haan |
| Homepage design (mobile), agar hai | `homepage-mobile.png` | Optional |
| Baaki pages ke designs | `<page-name>-desktop.png` (e.g. `service-desktop.png`) | Optional |
| Logo (PNG/SVG, transparent background best) | `assets/logo.png` ya `assets/logo.svg` | Optional |
| Design me use hui asli photos | `assets/` folder me | Optional |

Agar design lamba hai to full-page screenshot behtar hai (poora page ek hi image me).
Multiple parts hain to: `homepage-desktop-1.png`, `homepage-desktop-2.png` … (upar se neeche order me).

## Design ke sath ye batayein (agar pata ho)

- Exact colors (hex codes) — warna Claude image se nikal lega
- Fonts ke naam — warna milte-julte free fonts use honge
- Kaunsa section clickable/scrollable hona chahiye (slider, accordion, etc.)
- Agar design me koi claim likha hai (24/7, licensed, prices, "No.1") to wo `../00-business-verification.md` me confirm hona zaroori hai — bina confirm ke wo text site par nahi jayega.

## Kya hoga iske baad

1. Claude image dekh kar layout, spacing, colors, typography note karega.
2. HTML + CSS me exactly wahi design banayega (mobile + desktop responsive).
3. Arabic RTL version bhi usi design par banega.
4. Fonts, images optimize honge (WebP/AVIF) — performance spec ke mutabiq.
