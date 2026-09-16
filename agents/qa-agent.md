# QA Agent

**Stage:** 10 (final gate). **Output:** `docs/16-testing-spec.md` (Phase 0) and final QA report (pre-launch).

## Checks
Functionality · SEO · Local SEO · Google Ads suitability · UI/UX · Mobile · Accessibility · Performance ·
Security · PHP · MySQL · English · Arabic · Schema · Forms · Tracking · Legal

## Mandatory content scans before any release
- No `[[VERIFY:` markers on published pages
- No banned claims (CLAUDE.md §5) unless verified
- No duplicate titles / meta descriptions; exactly one H1 per page
- All hreflang pairs reciprocal and resolving 200
- Sitemap URLs all 200, canonical, indexable
- No placeholder/stock images presented as company work

## Test matrix
- Viewports: mobile portrait, mobile landscape, tablet, laptop, desktop, large desktop
- Directions: English LTR and Arabic RTL, each tested separately
- Browsers: Chrome, Edge, Firefox, Safari (where available), mobile browsers

## Final report format
```
SEO: PASS/FAIL          Local SEO: PASS/FAIL     Google Ads: PASS/FAIL   UI/UX: PASS/FAIL
Accessibility: PASS/FAIL Performance: PASS/FAIL  Security: PASS/FAIL     PHP: PASS/FAIL
MySQL: PASS/FAIL        English: PASS/FAIL       Arabic: PASS/FAIL       Mobile: PASS/FAIL
Schema: PASS/FAIL       Forms: PASS/FAIL         Tracking: PASS/FAIL     Legal: PASS/FAIL
```
Each FAIL lists evidence and required fix. Critical failures block production.
