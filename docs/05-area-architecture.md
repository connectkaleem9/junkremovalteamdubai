# Area Architecture (DRAFT)

**Date:** 2026-09-16. Implements blueprint §18–19 and CLAUDE.md rules 23–24.

## 1. Model

```
/en/areas/                      ← always exists: honest list of areas served (plain text + links to qualified pages only)
/en/areas/{slug}/               ← exists only for QUALIFIED areas
```

Area lifecycle (`areas.status` + `areas.indexable`):

| Stage | Has URL? | Indexable | In sitemap | Linked from nav/footer |
|---|---|---|---|---|
| `candidate` — on the research list | No | — | No | No (may appear as plain text on `/en/areas/` if F1 confirms service) |
| `draft` — page being written | Admin preview only | No | No | No |
| `published` + indexable=0 — live but not yet strong enough | Yes | No (noindex, follow) | No | No |
| `published` + indexable=1 — passed all checks | Yes | Yes | Yes | Yes |

## 2. Qualification scorecard (all must be YES to set indexable=1)

| # | Check | Evidence required |
|---|---|---|
| 1 | Business genuinely serves the area | Owner confirmation (F1) + at least one real job record |
| 2 | Meaningful search/user intent | Keyword Planner data or Search Console impressions |
| 3 | Unique useful information | Brief lists ≥3 area-specific, verifiable points |
| 4 | Genuine local context | Property/business types, access/logistics realities — no filler landmarks |
| 5 | Relevant services explainable | Which services are common here and why |
| 6 | Real projects where available | ≥1 real project linked, or documented reason none can be shown |
| 7 | Substantially different from other area pages | Side-by-side review; no shared paragraphs beyond CTA/NAP blocks |

The scorecard is stored with the area record in admin (checkbox + evidence note per check) so the decision is auditable.

## 3. Initial candidates

| Area | Why considered | Blocking data |
|---|---|---|
| Al Quoz | Business base (per blueprint address); industrial + residential mix | F1, F4, projects |
| Jumeirah | Villa-heavy; fits villa clearance | F1, F4 |
| Dubai Marina | High-rise apartments, frequent move-outs | F1, F4 |
| Business Bay | Offices + apartments | F1, F4 |
| Downtown Dubai | Apartments, offices | F1, F4 |
| Al Barsha | Mixed villas/apartments, near Al Quoz | F1, F4 |
| JVC, Arabian Ranches, Dubai Hills, Palm Jumeirah, Deira, Bur Dubai, Mirdif, International City, Al Nahda, Silicon Oasis | Research list (blueprint §18) | All checks |

**Recommendation:** launch with **zero or one** indexable area page (Al Quoz, if real projects exist). Add more as real jobs accumulate. Competitors' identical area pages are a weakness, not a model.

## 4. Area page structure (when qualified)
Area hero (H1 e.g. "Junk Removal in Al Quoz") → local service introduction → property/business types here → services commonly needed → common situations → practical local considerations (only verified) → real projects → genuine reviews from the area → nearby qualified areas → FAQs specific to the area → CTA.

## 5. Data relationships
- `area_service` — which services are offered/relevant in the area
- `area_nearby` — manual nearby-area links (only to qualified areas)
- `projects.area_id`, `reviews.area_id`, `leads.area_id` — drives real content and later demand analysis

## 6. Lead form area field
Dropdown lists all **served** areas (candidate stage allowed) + "Other (type area)". Lead data by area informs which area pages to build next.
