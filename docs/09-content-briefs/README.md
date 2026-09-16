# Content Briefs

Standard: blueprint §86. One file per URL. Briefs specify *what* a page must contain; final copy is written only after the
verification items each brief lists are answered.

**Markers:** `[[VERIFY: E1]]` = fact must be confirmed in `../00-business-verification.md` item E1 before this text is used.
A page containing any marker cannot be published (enforced by admin + QA crawler).

**Brand placeholder:** `{Brand}` = final business name (A1). Title lengths below were measured with "Junk Removal Team Dubai" (23 chars).

**Length guidance:** title ≤ 60 characters, meta description 120–160 characters (measured by `scripts` check, see brief status table).

| Brief | URL | Status |
|---|---|---|
| `home.md` | `/en/` | Draft |
| `junk-pickup-dubai.md` | `/en/junk-pickup-dubai/` | Draft (D2) |
| `furniture-removal-dubai.md` | `/en/furniture-removal-dubai/` | Draft |
| `apartment-clearance-dubai.md` | `/en/apartment-clearance-dubai/` | Draft |
| `villa-clearance-dubai.md` | `/en/villa-clearance-dubai/` | Draft |
| `house-clearance-dubai.md` | `/en/house-clearance-dubai/` | Draft (D3) |
| `office-clearance-dubai.md` | `/en/office-clearance-dubai/` | Draft |
| `commercial-junk-removal-dubai.md` | `/en/commercial-junk-removal-dubai/` | Draft |
| `core-pages.md` | about, contact, get-a-quote, thank-you, services hub, areas hub, faqs, 404 | Draft |

Arabic briefs are created per page after Arabic keyword data and native reviewer are available (`../13-bilingual-architecture.md` §7).

## Writing rules recap
- Customer first; specific; short paragraphs; no filler intros ("In today's fast-paced world…").
- No banned claims (CLAUDE.md §5). No invented numbers, timelines, prices, disposal routes.
- Each FAQ answer must be answerable from verified facts; otherwise drop the question.
- Merged keywords (e.g. "furniture disposal") appear where natural — once in an H2 or FAQ is enough.
