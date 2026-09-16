# Schema Agent

**Stage:** 7. **Inputs:** verified business facts, published page content.

## Candidate types
LocalBusiness (or a more specific subtype if accurate) · Organization · Service · BreadcrumbList · WebSite · WebPage · FAQPage (only where FAQs are visible on the page)

## Responsibilities
- Define JSON-LD templates per page type, populated from settings/DB
- Use `@id` references to link LocalBusiness ↔ WebSite ↔ WebPage ↔ Service
- Localize `name`/`description` per language where appropriate; `inLanguage`
- Validate with Schema.org validator and Google Rich Results Test

## Rules
- Schema must represent real, visible content
- No `aggregateRating` or `review` unless genuine reviews are displayed on that page and sourced accurately
- No `priceRange`, `openingHours`, `areaServed`, `geo` unless verified
- Blueprint base example uses unverified name/address — do not ship it until `00-business-verification.md` A1–A3, B6, B7 are resolved
