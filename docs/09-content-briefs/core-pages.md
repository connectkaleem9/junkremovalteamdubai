# Briefs — Core Pages

## About Us — `/en/about-us/`
| Field | Value |
|---|---|
| Intent | Legitimacy check (also a Google Ads trust requirement) |
| Title | `About {Brand} – Based in Al Quoz` `[[VERIFY: A1, A2]]` |
| Meta description | `Who we are, where we're based in Dubai and how we handle junk removal and clearance jobs for homes and businesses. Real photos, clear process, direct contact.` |
| H1 | About {Brand} |
| Schema | AboutPage/WebPage, LocalBusiness @id reference, BreadcrumbList |

Outline: H2 Who we are (legal/trading name, base location `[[VERIFY: A1, A2, B1]]`) → H2 What we do (services summary, links) → H2 How we work (process, communication, what customers can expect — verified only) → H2 Our team and equipment (genuine photos `[[VERIFY: G2, E8]]`; omit if none) → H2 Where we work (areas `[[VERIFY: F1]]`) → H2 Contact details (NAP, hours `[[VERIFY: B5]]`) → CTA.
**Never:** founding year, team size, customer counts, awards, licences unless verified (E5–E8).
~500–800 words.

## Contact Us — `/en/contact-us/`
| Field | Value |
|---|---|
| Intent | Ready to contact / verify identity |
| Title | `Contact {Brand} – Call, WhatsApp or Email` |
| Meta description | `Call, WhatsApp or email {Brand}, or send us a message. Find our Dubai address, business hours and the details that help us prepare a quote.` |
| H1 | Contact Us |
| Schema | ContactPage/WebPage, LocalBusiness reference, BreadcrumbList |

Outline: contact options first (Call `tel:+971567021884` `[[VERIFY: A3]]`, WhatsApp `[[VERIFY: B4]]`, email) → H2 Send us a message (contact form) → H2 Our location (`<address>` `[[VERIFY: A2]]`; map facade only for verified pin `[[VERIFY: B6, B7]]`) → H2 Business hours `[[VERIFY: B5]]` → H2 For a faster quote (what to include: photos, location, floor/lift, preferred date) → link to Get a Quote.

## Get a Quote — `/en/get-a-quote/`
| Field | Value |
|---|---|
| Intent | Submit job details |
| Title | `Get a Junk Removal Quote in Dubai | {Brand}` (add "Free" only if E1) |
| Meta description | `Tell us what needs removing, where and when. Add photos for a more accurate price. We'll reply by phone, WhatsApp or email to confirm your junk removal quote.` `[[VERIFY: response channels]]` |
| H1 | Get a Quote |
| Schema | WebPage, BreadcrumbList |

Outline: short intro (what happens after submitting — verified response process `[[VERIFY: typical response time — do not state unless confirmed]]`) → form (fields per design system §5) → aside: prefer WhatsApp? button; call button → privacy note linked.
`?service=` preselects service; canonical always `/en/get-a-quote/`. Bottom bar hidden on this page.

## Thank You — `/en/thank-you/` (noindex)
H1 "Thanks — we've received your request". Next steps (verified), reference summary (service, preferred date — no sensitive data echoed in URL), WhatsApp/Call buttons for urgent jobs, links to FAQs and services. Fires `quote_submit`/`contact_submit` only when reached via PRG with a one-time flash token (prevents inflated conversions on reload).

## Services Hub — `/en/services/`
Title `Junk Removal Services in Dubai | {Brand}`. H1 "Our Services". Short intro, cards grouped: For homes (pickup, furniture, apartment, villa, house) · For businesses (office, commercial). No keyword paragraph blocks. ~250–400 words + cards.

## Areas Hub — `/en/areas/`
Title `Areas We Serve in Dubai | {Brand}`. H1 "Areas We Serve". Honest list of served areas `[[VERIFY: F1]]` grouped by district; links only to qualified area pages; note "Don't see your area? Contact us" (only if true). noindex until F1 answered.

## FAQs — `/en/faqs/`
Title `Junk Removal FAQs | {Brand}`. H1 "Frequently Asked Questions". Grouped by category (pricing, booking, items, areas, process, residential, commercial) with jump links; each answer links to the most relevant service. FAQPage schema. Only questions with verified answers.

## 404 — any unknown URL
H1 "Page not found". One sentence, links: Home, Services, Areas, Contact, Get a Quote; Call/WhatsApp buttons. Status 404, noindex. Arabic version on `/ar/*` paths.

## Legal pages
Privacy Policy, Terms & Conditions, Cookie Policy, Disclaimer — drafted after B1 (legal entity), E9–E10 (payment, cancellation), H4 (analytics/ads tools in use) and data-retention decision; **must be reviewed by owner/legal adviser**. Not copied from competitors.
