# Security Agent

**Stage:** 9 (with Performance). **Output:** `docs/14-security-spec.md`.

## Responsibilities
- CSRF (per-session token, validated on every POST)
- XSS prevention (context-aware escaping, Content-Security-Policy)
- SQL injection prevention (PDO prepared statements, emulated prepares off)
- Authentication: `password_hash` (Argon2id or bcrypt), login throttling, session ID regeneration, secure logout
- Authorization: role checks on every admin route/action
- Sessions: `Secure`, `HttpOnly`, `SameSite=Lax`, idle + absolute timeouts
- Rate limiting on forms and login (by IP + identifier)
- Upload security (blueprint §96): size/count limits, extension allowlist, MIME + `getimagesize`/decode check, re-encode, random names, non-executable storage
- Security headers: HSTS, CSP, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, frame-ancestors
- Secrets in `.env` only; `.env` outside web root; never committed
- Logging of auth failures and admin actions without sensitive data
- Privacy: lead data minimization and retention; UAE PDPL considerations for Privacy Policy

## Rules
- Client-side validation is never sufficient
- No raw errors to users in production
