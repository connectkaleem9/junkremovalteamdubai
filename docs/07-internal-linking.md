# Internal Linking Architecture (DRAFT)

**Date:** 2026-09-16.

## 1. Model: hub → service → proof

```
Homepage (/en/)
 ├── Services hub (/en/services/) ──► each service page
 ├── Each service page (direct links from homepage service section)
 │     ├──► related services (2–3, curated)
 │     ├──► qualified areas where service is relevant
 │     ├──► real projects for this service
 │     ├──► supporting guide(s)
 │     └──► get-a-quote (service preselected)
 ├── Areas (/en/areas/) ──► qualified area pages ──► services + projects in that area
 ├── Projects ──► service + area of each project
 ├── Reviews ──► service/area where linked
 ├── FAQs ──► relevant service pages
 └── Blog guides ──► 1–2 most relevant service pages (contextual, in body)
```

Every indexable page must be reachable within **3 clicks** from the homepage and have **≥2 internal inbound links** (orphan check in QA).

## 2. Link placement rules by page type

| Page type | Required outbound links |
|---|---|
| Homepage | All built service pages; `/en/areas/`; latest projects; reviews; FAQs; contact; quote |
| Service | Breadcrumb; 2–3 related services; relevant qualified areas; relevant projects/reviews; 1 guide if exists; quote with `?service={slug}` (query param is **not** canonicalized content — quote page canonical stays `/en/get-a-quote/`) |
| Area | Breadcrumb; services relevant here; nearby qualified areas; area projects |
| Project | Its service; its area (if qualified); 2 related projects; quote |
| Blog post | Contextual links to 1–2 services; related posts; no sitewide keyword blocks |
| Legal | Contact only |

## 3. Anchor text rules
- Natural and varied: "furniture removal", "remove old furniture", "our furniture removal service", "see how furniture removal works".
- Exact-match anchors ("furniture removal Dubai") at most once per page per target, and not in nav/footer.
- Nav and footer use short labels: "Furniture Removal", "Office Clearance".
- Never "click here"; anchors must describe the destination (accessibility).

## 4. Navigation (blueprint §36, §83 adjusted for merged services)
Desktop: Home · Services ▾ · Areas · Projects · Reviews · About · FAQs · Contact · **[Get a Quote]** · العربية
Services dropdown: only built services (currently ≤8).
Hide Projects/Reviews menu items until real content exists (avoid empty pages).

Mobile: logo · Call icon · Menu; fixed bottom bar: Call · WhatsApp · Get Quote.

## 5. Footer
Columns: About blurb + NAP · Services (built services, short labels) · Areas (qualified areas + "All areas") · Company (About, Projects, Reviews, FAQs, Contact) · Legal.
No keyword-heavy link walls; no links to unqualified areas.

## 6. Breadcrumbs
Visible on all pages except homepage; `BreadcrumbList` schema mirrors visible breadcrumb exactly.
- Service: Home › Services › Furniture Removal
- Area: Home › Areas › Al Quoz
- Project: Home › Projects › {title}
- Post: Home › Blog › {title}

## 7. DB-driven related content
Related blocks are generated from relationships (`projects.service_id`, `area_service`, curated `service_related`), never random. Blocks with no real data are omitted, not filled with placeholders.
