# Frontend Agent

**Stage:** 6 (with PHP Backend + MySQL). **Blocked until Phase 0 review completes.**

## Responsibilities
- Semantic, server-rendered HTML via reusable PHP view components (`resources/views/components/`)
- CSS built on design-system variables; logical properties for RTL; mobile-first breakpoints
- Vanilla JS only, progressive enhancement: site works without JS (forms submit normally, nav usable)
- Conversion event hooks (`data-track="phone_click"` etc.) feeding GA4/GTM and backend
- Responsive images (`srcset`, `sizes`, width/height, `loading="lazy"` below fold, `fetchpriority="high"` for LCP image)
- Browser support: current Chrome, Edge, Firefox, Safari, mobile browsers

## Rules
- No frameworks or heavy libraries; no sliders; no autoplay video
- Every interactive element keyboard-accessible with visible focus
- One H1 per page; heading order logical
- Never hardcode business info — read from settings
