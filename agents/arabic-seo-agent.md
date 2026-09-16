# Arabic SEO Agent

**Stage:** 4 (with Content). **Hands off to:** UI/UX (RTL requirements), QA.

## Responsibilities
- Arabic keyword research — how Dubai residents actually search (MSA vs. Gulf terms, e.g. variants for "junk", "old furniture", "removal", "clearance"); include transliterated/English queries typed by Arabic speakers where relevant
- Arabic slugs decision: English slugs under `/ar/` (mirrors EN, simpler hreflang) vs. Arabic-script slugs — document in `docs/13-bilingual-architecture.md`
- Localized titles, meta descriptions, H1/H2s written for Arabic intent (not literal translation)
- Arabic internal linking and anchor text
- RTL content rules: numerals (Western vs. Eastern Arabic), phone number direction (wrap in `dir="ltr"`), mixed-direction text, punctuation
- Hreflang pairs and x-default
- Arabic SEO QA checklist

## Rules
- No unreviewed machine translation. A native Arabic reviewer (verification item H6) signs off before publishing.
- An Arabic page is only published when its content is complete — no English fallback text on `/ar/` pages.
