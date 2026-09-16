# Keyword Map (DRAFT — provisional until Keyword Planner data + owner answers)

**Date:** 2026-09-16 · Inputs: `01-research/*`, blueprint §8, §10, §75, §87.
Principle: **one primary keyword → one URL**. Merge pages whose SERPs show the same intent.

Status: **Build** · **Merge** (target folded into another URL) · **Hold** (blocked on verification) · **Decide** (owner decision needed)

## 1. Core commercial pages (English)

| URL | Primary keyword | Secondary keywords | Status |
|---|---|---|---|
| `/en/` | junk removal dubai | junk removal services dubai, junk removal company dubai, rubbish removal dubai | Build — see Decision D1 |
| `/en/junk-pickup-dubai/` | junk pickup dubai | junk collection dubai, junk pickup services dubai, take my junk dubai | Build |
| `/en/furniture-removal-dubai/` | furniture removal dubai | old furniture removal dubai, furniture disposal dubai, furniture pickup dubai, mattress disposal dubai | Build |
| `/en/apartment-clearance-dubai/` | apartment clearance dubai | move out clearance dubai, flat clearance dubai | Build (if C confirmed) |
| `/en/villa-clearance-dubai/` | villa clearance dubai | villa junk removal dubai | Build (if C confirmed) |
| `/en/house-clearance-dubai/` | house clearance dubai | home clearance dubai, junk clearance dubai | Decide — D3 |
| `/en/office-clearance-dubai/` | office clearance dubai | office furniture removal dubai, office junk removal dubai | Build (if C confirmed) |
| `/en/commercial-junk-removal-dubai/` | commercial junk removal dubai | commercial waste removal dubai, warehouse clearance dubai, shop clearance dubai | Build (if C confirmed) — slug D4 |
| `/en/construction-waste-removal-dubai/` | construction waste removal dubai | construction junk removal dubai | **Hold** — DM licence (E5) |

## 2. Blueprint URLs merged or held

| Blueprint URL | Action | Reason |
|---|---|---|
| `/en/junk-removal-dubai/` | Decide — D1 | Same primary keyword as homepage → cannibalization |
| `/en/junk-collection-dubai/` | Merge → `/en/junk-pickup-dubai/` | Same transactional intent as pickup |
| `/en/junk-clearance-dubai/` | Merge → `/en/house-clearance-dubai/` (or homepage if D3 = drop) | Overlaps property clearance |
| `/en/junk-disposal-dubai/` | Hold → later guide or section | Only worth a page if disposal routes are verified (E4); SERP is partly informational |
| `/en/furniture-disposal-dubai/` | Merge → `/en/furniture-removal-dubai/` | Same customer need |

Merged keywords still appear naturally in the receiving page's H2s/FAQs — never as stuffed lists.
Because the site isn't live yet, merged URLs simply aren't created (no redirects needed).

## 3. Supporting content (informational, blog/guides)

| Proposed URL | Primary keyword | Supports |
|---|---|---|
| `/en/blog/junk-removal-cost-dubai/` | junk removal cost dubai | homepage, all services |
| `/en/blog/dubai-municipality-bulky-waste-vs-junk-removal/` | dubai municipality bulky waste collection | furniture removal, homepage |
| `/en/blog/move-out-clearance-checklist-dubai/` | move out checklist dubai | apartment, villa clearance |
| `/en/blog/how-to-dispose-of-old-furniture-dubai/` | how to dispose of old furniture in dubai | furniture removal |
| `/en/blog/office-clearance-checklist/` | office clearance checklist | office clearance |
| `/en/blog/construction-waste-rules-dubai/` | construction waste disposal rules dubai | construction page (if built) |

Publish only when each can be genuinely useful and fact-checked; 3–4 strong guides beat 20 thin ones.

## 4. Areas
No area URL receives a primary keyword until it passes the 7 checks (see `05-area-architecture.md`).
Candidate first set if F1/F4 confirm real work: Al Quoz (business base), then areas with documented projects.

## 5. Arabic
Mirror of the English structure under `/ar/`, with Arabic primary keywords chosen after Keyword Planner data
and native review (see `01-research/keywords.md`). If the owner does not buy used furniture (E12),
avoid targeting "شراء اثاث مستعمل".

## Decisions needed

**D1 — Homepage vs `/en/junk-removal-dubai/`**
- **Option A (recommended):** homepage owns "junk removal dubai"; `/en/junk-removal-dubai/` is not created. Google Ads for the head term land on the homepage, which is built to Ads landing-page standards. Simplest; no cannibalization; homepages carry the most authority.
- **Option B:** `/en/junk-removal-dubai/` owns the head term as a deep service page; homepage targets brand + broad "junk removal company dubai". Keeps the blueprint's Ads mapping but splits authority and risks the two pages swapping in rankings.

**D2 — Pickup vs removal distinction:** confirm the pickup page can be substantively different (e.g. single items/small loads, quick booking) from the homepage (full service). If not, merge pickup into homepage too.

**D3 — House clearance:** (a) hub page that introduces home clearance and links to apartment/villa pages, or (b) drop and redirect intent to villa/apartment pages. Decide once volume data shows whether "house clearance dubai" has meaningful demand.

**D4 — Slug consistency:** blueprint has `/en/commercial-junk-removal/` and `/en/construction-waste-removal/` without "-dubai" while siblings include it. Recommend adding "-dubai" to both for a consistent rule (the URLs are permanent after launch).
