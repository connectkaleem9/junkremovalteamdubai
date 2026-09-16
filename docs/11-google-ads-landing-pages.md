# Google Ads Landing-Page Strategy (DRAFT)

**Date:** 2026-09-16. Complements blueprint §41–44, §74–75. Budget, bids and schedules are owner decisions — not set here.

## 1. Campaign structure (Search, English first)

| Campaign | Ad group | Core keywords (phrase/exact) | Landing page |
|---|---|---|---|
| Junk Removal – Core | Junk removal | junk removal dubai, junk removal services dubai, junk removal company dubai | `/en/` [D1=A] |
| | Rubbish removal | rubbish removal dubai | `/en/` |
| Junk Pickup | Pickup | junk pickup dubai, junk collection dubai | `/en/junk-pickup-dubai/` |
| Furniture | Furniture removal | furniture removal dubai, old furniture removal dubai | `/en/furniture-removal-dubai/` |
| | Furniture disposal | furniture disposal dubai, mattress disposal dubai | `/en/furniture-removal-dubai/` |
| Property Clearance | Apartment | apartment clearance dubai, move out clearance dubai | `/en/apartment-clearance-dubai/` |
| | Villa | villa clearance dubai | `/en/villa-clearance-dubai/` |
| | House | house clearance dubai | `/en/house-clearance-dubai/` or apartment/villa page [D3] |
| Commercial | Office | office clearance dubai, office furniture removal dubai | `/en/office-clearance-dubai/` |
| | Commercial | commercial junk removal dubai | `/en/commercial-junk-removal-dubai/` |
| Construction (only if licensed) | Construction waste | construction waste removal dubai | `/en/construction-waste-removal-dubai/` |
| Arabic (phase 2) | per Arabic keyword research | — | matching `/ar/` pages |

Brand campaign once the brand has search demand.

## 2. Targeting
- **Location:** Dubai — "Presence: people in or regularly in your targeted locations" (not "interest"), unless owner serves other emirates (F2).
- **Schedule:** only during verified business hours (B5); don't imply 24/7.
- **Language:** English campaigns target English + Arabic browser languages (UAE users often use English interfaces); Arabic ad copy in separate campaigns.
- **Devices:** mobile-heavy; call assets on.

## 3. Negative keywords (starter list — refine with search-terms report)
Intent mismatch: `jobs, job, vacancy, hiring, salary, driver job, careers`
Different service: `buy used furniture` / `sell furniture` / `used furniture for sale` (unless E12), `movers` / `moving company` / `packers` (unless offered), `cleaning company`, `skip hire` (unless offered), `scrap buyer`, `scrap metal price`
Free/government: `dubai municipality`, `municipality`, `800900`, `free` (decide after E12 — if the business offers no free service, add `free`)
Out of area: `sharjah, ajman, abu dhabi, al ain, rak, fujairah` (unless F2)
Informational: `how to`, `diy`, `meaning`, `what is`
Competitor brands: add as seen in search terms; don't bid on competitor trademarks in ad text.

## 4. Ad copy rules
- Headlines reflect the landing page H1 and service (relevance).
- Allowed without verification: service descriptions, "Serving Dubai", "Call or WhatsApp", "Send Photos for a Quote" (if process confirmed), "Furniture, Appliances & More" (if items verified).
- Requires verification: "Free Quote" (E1), "Same-Day" (E3), "Licensed" (E5/E14), "Insured" (E6), "X Years" (E7), "Eco-Friendly/Recycled" (E4), any price.
- Never: #1, Best, Cheapest, Guaranteed, Government Approved, 24/7 (unless verified).
- Assets: sitelinks (services, contact, projects when real), callouts (verified facts only), call asset with the business number (Google forwarding number optional for call tracking), location asset once GBP linked, image assets = genuine photos only.

## 5. Conversion actions

| Conversion | Source | Primary for bidding? | Value |
|---|---|---|---|
| `quote_submit` | GA4 import or gtag on thank-you page | Yes | owner-defined estimate or none |
| `booking_submit` | same | Yes | — |
| `contact_submit` | same | Secondary | — |
| Calls from ads (call asset, ≥ 60 s) | Google Ads call reporting | Yes | — |
| `phone_click` (website) | GA4 event | Secondary (clicks ≠ calls) | — |
| `whatsapp_click` | GA4 event | Secondary initially; promote if WhatsApp is the main sales channel | — |
| Offline: lead → `booked` / `completed` | Upload by gclid from `leads` table (manual CSV first, API later) | Best signal long-term | job value if owner shares |

Attribution flow: landing page stores first-touch `gclid`, UTMs, landing page, referrer (first-party cookie, 90 days) → submitted with form → saved on `leads` → admin export of booked/completed leads with gclid + conversion time for offline import.

Consent Mode v2 must be implemented; conversions modelled where consent is denied.

## 6. Landing-page compliance checklist (sign off per URL before any ad goes live)

| # | Check | Pass criteria |
|---|---|---|
| 1 | Loads & renders | 200, no interstitials, LCP < 2.5 s mobile |
| 2 | Matches ad | H1 and first screen describe the advertised service |
| 3 | Business identity | Business name, logo, phone, email, address (or service-area statement) visible; About & Contact linked |
| 4 | Working contact | `tel:` link dials correct number; WhatsApp opens correct number; form submits and confirms |
| 5 | Original, useful content | Service details, items, process, FAQs — not a thin form page |
| 6 | Claims | No unverified claims in page **or** ad (CLAUDE.md §5) |
| 7 | Navigation | Header nav and footer present (not hidden) |
| 8 | Crawlable | Not blocked by robots.txt; not noindex; no cloaking; AdsBot can fetch |
| 9 | No deceptive redirects | Final URL = landing URL (tracking template may add params only) |
| 10 | Mobile usability | CTA reachable without horizontal scroll; bottom bar doesn't cover content |
| 11 | Privacy | Privacy Policy linked from form; consent banner functional |
| 12 | Language | English ads → `/en/`; Arabic ads → `/ar/` |
| 13 | Tracking | Test click with `gclid` → lead row has gclid; conversion recorded in Tag Assistant |

Sign-off recorded in admin (`services.is_ads_landing` + checklist timestamp) and in the QA report.

## 7. Launch sequence
1. Verification items for claims used in ads resolved.
2. Landing pages pass checklist on production.
3. Conversion tracking tested end-to-end.
4. Launch 2–3 highest-intent campaigns (Core, Furniture, Apartment/Villa) with tight negatives.
5. Weekly search-terms review for 8 weeks; add negatives; expand only with data.
