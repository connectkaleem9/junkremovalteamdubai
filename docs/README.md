# Planning Deliverables Tracker (Phase 0)

Blueprint §112: all items below must be created **and reviewed** before production code begins.

Status values: `Not started` · `Draft` · `Needs owner input` · `Reviewed`

| # | Deliverable | File | Owner agent | Status |
|---|---|---|---|---|
| 0 | Business fact verification | `00-business-verification.md` | Local SEO | Needs owner input |
| 1 | Research documentation | `01-research/services.md`, `customer-intent.md` | Research | Draft |
| 2 | Competitor analysis | `01-research/competitors.md`, `content-gaps.md` | Research | Draft |
| 3 | Keyword map | `02-keyword-map.md` (+ `01-research/keywords.md`) | SEO Strategist | Needs owner input (volumes, D1–D4) |
| 4 | Search-intent map | `03-search-intent-map.md` | SEO Strategist | Draft |
| 5 | Service architecture | `04-service-architecture.md` | SEO Strategist | Draft (services conditional on §C) |
| 6 | Area architecture | `05-area-architecture.md` | Local SEO | Draft |
| 7 | URL architecture | `06-url-architecture.md` | Technical SEO | Draft (assumes D1=A, D4=add -dubai) |
| 8 | Internal-linking architecture | `07-internal-linking.md` | SEO Strategist | Draft |
| 9 | Database ERD / schema | `08-database-schema.md` | MySQL | Draft (DB1–DB8 need approval) |
| 10 | Content briefs | `09-content-briefs/*.md` | Content + Arabic SEO | Draft (EN; `[[VERIFY]]` markers pending owner answers; AR briefs not started) |
| 11 | UI/UX design system | `10-design-system.md` | UI/UX | Draft (palette provisional until logo) |
| 12 | Google Ads landing-page strategy | `11-google-ads-landing-pages.md` | Google Ads | Draft |
| 13 | Technical SEO specification | `12-technical-seo-spec.md` | Technical SEO | Draft |
| 14 | Bilingual architecture | `13-bilingual-architecture.md` | Arabic SEO | Draft |
| 15 | Security specification | `14-security-spec.md` | Security | Draft |
| 16 | Performance specification | `15-performance-spec.md` | Performance | Draft |
| 17 | Testing specification | `16-testing-spec.md` | QA | Draft |
| 18 | Final CLAUDE.md | `../CLAUDE.md` | SEO Strategist + QA | Draft |

## Design mockups

`mockups/index.html` — open in a browser for the review board (homepage + furniture removal page, English LTR and Arabic RTL, shown at mobile 390 px and desktop 1440 px).
Prototype only: sample copy, `VERIFY` tags, no photos, forms don't submit. Production CSS will be rebuilt in `resources/css`.

## Suggested order

1. **#0** — owner answers verification questions (blocks copy, schema, Ads, legal).
2. **#1–#4** — research, competitors, keywords, intent (needs live SERP research).
3. **#5–#8, #13, #14** — architecture specs.
4. **#9, #15, #16** — database, security, performance.
5. **#11, #12** — design system, Ads strategy.
6. **#10** — content briefs (depend on everything above).
7. **#17, #18** — testing spec, finalize CLAUDE.md → owner review → implementation.
