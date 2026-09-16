# Service Architecture (DRAFT)

**Date:** 2026-09-16. Inputs: `01-research/services.md`, `02-keyword-map.md`, `03-search-intent-map.md`.
Every service below is **conditional on owner confirmation** (`00-business-verification.md` §C). A service the business doesn't genuinely provide is not built.

## 1. Service set

| # | Service (nav label) | URL | Build condition | Ads landing |
|---|---|---|---|---|
| S0 | Junk Removal (homepage) | `/en/` | Always | Yes |
| S1 | Junk Pickup | `/en/junk-pickup-dubai/` | C + D2 | Yes |
| S2 | Furniture Removal | `/en/furniture-removal-dubai/` | C | Yes |
| S3 | Apartment Clearance | `/en/apartment-clearance-dubai/` | C | Yes |
| S4 | Villa Clearance | `/en/villa-clearance-dubai/` | C | Yes |
| S5 | House Clearance | `/en/house-clearance-dubai/` | C + D3 | Optional |
| S6 | Office Clearance | `/en/office-clearance-dubai/` | C | Yes |
| S7 | Commercial Junk Removal | `/en/commercial-junk-removal-dubai/` | C | Yes |
| S8 | Construction Waste Removal | `/en/construction-waste-removal-dubai/` | C + **E14 licence** | Only with licence |

## 2. What makes each page substantively different

The uniqueness test: if you swap the service name, the page must become **wrong**, not just differently titled.

### S0 Homepage — Junk Removal in Dubai
- **Scope:** the full offer — any unwanted items, any property type (verified), overview of every service.
- **Unique content:** how the company works end to end; what we take / don't take (master list); pricing factors; areas served; proof (projects, reviews).
- **Customers:** everyone; routes to specific services.

### S1 Junk Pickup — small loads & single items
- **Scope:** a few items or a partial load, quick booking by WhatsApp photo.
- **Unique content:** "what counts as a pickup vs a clearance", typical single-item examples (verified list), what the customer should prepare (items accessible, lift/parking), booking flow via photos.
- **Customers:** residents with one sofa, a mattress, a broken appliance; small offices with a few items.
- **Risk:** if pickup and removal aren't operationally different (D2), merge into homepage.

### S2 Furniture Removal — including old furniture disposal
- **Scope:** sofas, beds, mattresses, wardrobes, tables, chairs, cabinets, office furniture (only verified items — D1).
- **Unique content:** dismantling (if E11), bulky/heavy item handling, condition matters (what happens to reusable vs damaged furniture — only verified E4), honest comparison with Dubai Municipality free bulky waste service and with used-furniture buyers (who fits whom), mattress-specific notes.
- **Customers:** upgrading households, landlords replacing furnishings, move-outs.

### S3 Apartment Clearance — move-out & handover
- **Scope:** full or partial clearance of flats.
- **Unique content:** deadline-driven scheduling (tenancy end, handover inspection), building logistics (service lift booking, management notice, loading bay/parking — framed as "check with your building", not stated as universal rules), studio vs 1–3 BR planning, what "cleared" means (verify whether cleaning is offered — likely not; say so).
- **Customers:** tenants, landlords, property managers, agencies.

### S4 Villa Clearance — large-volume residential
- **Scope:** whole-villa or partial (storage rooms, garage, maid's room, garden furniture — verified).
- **Unique content:** multi-load / multi-truck planning (if capability), site visit or video/photo assessment, outdoor items, clearing before sale/lease/renovation, typical timeline factors.
- **Customers:** villa owners, families relocating, estate situations, landlords.

### S5 House Clearance — hub (if D3 keeps it)
- **Scope:** short page for users searching "house clearance" — explains options and routes to S3/S4.
- **Unique content:** apartment vs villa comparison table, when full vs partial clearance makes sense.
- **Note:** kept deliberately brief; not competing for apartment/villa terms.

### S6 Office Clearance — B2B relocation/closure
- **Scope:** desks, chairs, cabinets, workstations, partitions, meeting room furniture, general office junk (verified).
- **Unique content:** planning with facilities managers (inventory, access permissions, loading dock booking, after-hours if E3), data-bearing equipment (we do **not** claim data destruction unless verified — advise customer to wipe/remove drives), invoicing/payment (E9), lease handover deadline.
- **Customers:** SMEs, office managers, co-working operators, landlords of commercial units.

### S7 Commercial Junk Removal — shops, warehouses, F&B
- **Scope:** retail fit-out leftovers, shelving, warehouse pallets/racking junk, restaurant furniture (verified); one-off or recurring (if offered).
- **Unique content:** commercial site types, volume/vehicle planning, working around business hours, what commercial waste we **can't** take (food/hazardous/regulated — verify), difference from office clearance.
- **Customers:** store managers, warehouse operators, F&B owners, property managers.

### S8 Construction Waste Removal — HOLD
- **Build only if** the business holds the required Dubai Municipality licence and issues Waste Transfer Notes (E14; confirm legal requirement on official DM source).
- **Unique content (if built):** accepted debris types, licence display, WTN process, truck/skip options, renovation vs site-scale jobs.
- **If not licensed:** do not build; FAQ/guide may explain that construction waste needs a licensed contractor (G5) without offering the service.

## 3. Shared page components (same design, different content)
Hero (service-specific H1 + intro + CTAs) → what's included → items handled / not handled → who it's for → process (service-specific steps) → pricing factors (no figures) → important information (access, prep, limits) → relevant areas → real projects → genuine reviews → service-specific FAQs → final CTA.

Components with no real data (projects, reviews) are **omitted**, not filled with placeholders.

## 4. Adjacent services pending owner input
| Service | Question | If yes |
|---|---|---|
| Truck rental with driver | E13 | Separate page `/en/truck-rental-with-driver-dubai/` (distinct intent) |
| Buying used furniture | E12 | Section on furniture page + Arabic targeting "شراء اثاث مستعمل" |
| Free pickup of reusable items | E12 | Clear eligibility rules on furniture & pickup pages |
| Cleaning after clearance | new | Mention only if genuinely offered |

## 5. Relationships (DB)
- `service_related`: S1↔S0-type items, S2↔S3/S4, S3↔S4↔S5, S6↔S7.
- `area_service`: filled per qualified area.
- FAQs tagged by `service_id`; projects/reviews by `service_id`.
