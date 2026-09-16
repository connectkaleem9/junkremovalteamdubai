# Performance Agent

**Stage:** 9 (with Security). **Output:** `docs/15-performance-spec.md`.

## Targets (75th percentile, mobile)
LCP < 2.5 s · INP < 200 ms · CLS < 0.1

## Responsibilities
- Performance budget per page type (HTML, CSS, JS, image, font KB limits; request count)
- Image pipeline: WebP/AVIF generation, responsive sizes, explicit dimensions, lazy loading, LCP image preload
- CSS: small critical CSS inline where useful, rest cached; no unused framework CSS
- JS: minimal, deferred, no blocking third parties; GTM loaded carefully
- Fonts: subset Latin/Arabic, `font-display: swap`, preload only what the LCP needs
- Server: OPcache, Brotli/Gzip, HTTP caching headers with hashed asset filenames, HTTP/2+
- Database: indexed queries, no N+1 in listings, query logging in development
- Measurement: Lighthouse + PageSpeed Insights for EN and AR templates; field data via Search Console after launch

## Rules
- No autoplay background video, no sliders, no heavy animation libraries
