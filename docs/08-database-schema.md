# Database Schema (DRAFT) — MySQL 8.x

**Date:** 2026-09-16. Engine InnoDB, charset `utf8mb4`, collation `utf8mb4_unicode_ci`, timezone stored UTC (display Asia/Dubai).

## 1. Design decisions (deviations from blueprint flagged)

| # | Decision | Reason |
|---|---|---|
| DB1 | **One `users` table with `role`** instead of separate `users` + `admins` | Only staff log in; one auth path is simpler and safer. Roles: `admin`, `editor`, `sales`. |
| DB2 | **One `leads` table with `lead_type`** (`quote`,`contact`,`booking`) instead of separate `quote_requests` / `contact_messages` | One pipeline, one status history, one attribution model; type-specific fields nullable. |
| DB3 | Meta title/description live in `*_translations`; `seo_meta` holds per-language overrides (canonical, robots, OG, schema type) | Avoids two sources of truth for titles. |
| DB4 | Language = `ENUM('en','ar')` | Two fixed languages; enforced by DB. |
| DB5 | Translation rows carry `translation_status` (`draft`,`in_review`,`approved`) | Arabic publishes only when approved (bilingual rule). |
| DB6 | Reviews store original language + `is_translation` flag on translations | Displaying a translated review must be disclosed. |
| DB7 | No raw IPs stored; `ip_hash` = HMAC-SHA256(ip, APP_KEY) | Rate limiting/spam without retaining personal data. |
| DB8 | Added tables: `area_service`, `area_nearby`, `service_related`, `project_media`, `media_translations`, `lead_attachments`, `rate_limits`, `audit_log` | Required by linking, area, upload and security specs. |

## 2. ERD (relationships)

```
users 1─* lead_notes, lead_status_history(changed_by), audit_log, blog_posts(author_id)

services 1─* service_translations
services *─* areas            (area_service)
services *─* services         (service_related)
services 1─* projects, reviews, faqs, leads

areas 1─* area_translations
areas *─* areas               (area_nearby)
areas 1─* projects, reviews, faqs, leads

projects 1─* project_translations
projects *─* media            (project_media: before/after/gallery)

reviews 1─* review_translations
faqs 1─* faq_translations
pages 1─* page_translations
blog_posts 1─* blog_post_translations
media 1─* media_translations

leads 1─* lead_notes, lead_status_history, lead_attachments, conversion_events
seo_meta → (entity_type, entity_id, language)   polymorphic
```

## 3. DDL (to become migrations after review)

```sql
-- ============ USERS & SECURITY ============
CREATE TABLE users (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name             VARCHAR(100) NOT NULL,
  email            VARCHAR(190) NOT NULL,
  password_hash    VARCHAR(255) NOT NULL,
  role             ENUM('admin','editor','sales') NOT NULL DEFAULT 'sales',
  is_active        TINYINT(1) NOT NULL DEFAULT 1,
  last_login_at    TIMESTAMP NULL,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email)
);

CREATE TABLE rate_limits (
  bucket        VARCHAR(40)  NOT NULL,          -- 'login','quote_form','contact_form','track'
  key_hash      CHAR(64)     NOT NULL,          -- HMAC of ip / ip+email
  hits          INT UNSIGNED NOT NULL DEFAULT 0,
  window_start  TIMESTAMP    NOT NULL,
  PRIMARY KEY (bucket, key_hash),
  KEY ix_rate_window (window_start)
);

CREATE TABLE audit_log (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id      INT UNSIGNED NULL,
  action       VARCHAR(60) NOT NULL,            -- 'login_success','login_failed','service.update',...
  entity_type  VARCHAR(40) NULL,
  entity_id    BIGINT UNSIGNED NULL,
  details      JSON NULL,                       -- no passwords / tokens / full lead PII
  ip_hash      CHAR(64) NULL,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_audit_user (user_id, created_at),
  KEY ix_audit_entity (entity_type, entity_id),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============ SETTINGS ============
CREATE TABLE settings (
  setting_key   VARCHAR(80) NOT NULL,           -- business_name, phone_e164, phone_display, whatsapp_e164, email,
                                                -- address, city, country, business_hours, social_profiles, map_url
  language      ENUM('any','en','ar') NOT NULL DEFAULT 'any',
  value         TEXT NULL,                      -- JSON for structured values (hours, socials)
  is_verified   TINYINT(1) NOT NULL DEFAULT 0,  -- schema/Ads output uses only verified settings
  updated_by    INT UNSIGNED NULL,
  updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (setting_key, language),
  CONSTRAINT fk_settings_user FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ============ MEDIA ============
CREATE TABLE media (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  filename        VARCHAR(120) NOT NULL,        -- random, generated
  path            VARCHAR(255) NOT NULL,        -- relative to media root
  mime_type       VARCHAR(40)  NOT NULL,
  width           SMALLINT UNSIGNED NOT NULL,
  height          SMALLINT UNSIGNED NOT NULL,
  file_size       INT UNSIGNED NOT NULL,
  variants        JSON NULL,                    -- {"webp":[480,768,1200],"avif":[...],"jpg":[...]}
  is_genuine      TINYINT(1) NOT NULL DEFAULT 0,-- 1 = real company photo; only genuine media allowed on projects
  uploaded_by     INT UNSIGNED NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_media_path (path),
  CONSTRAINT fk_media_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE media_translations (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  media_id   INT UNSIGNED NOT NULL,
  language   ENUM('en','ar') NOT NULL,
  alt_text   VARCHAR(250) NOT NULL,
  title      VARCHAR(150) NULL,
  caption    VARCHAR(300) NULL,
  UNIQUE KEY uq_media_lang (media_id, language),
  CONSTRAINT fk_mt_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE
);

-- ============ SERVICES ============
CREATE TABLE services (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug               VARCHAR(120) NOT NULL,
  status             ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  indexable          TINYINT(1) NOT NULL DEFAULT 1,
  is_ads_landing     TINYINT(1) NOT NULL DEFAULT 0,   -- triggers Ads compliance checklist in admin
  hero_media_id      INT UNSIGNED NULL,
  sort_order         SMALLINT NOT NULL DEFAULT 0,
  created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_services_slug (slug),
  KEY ix_services_status (status, sort_order),
  CONSTRAINT fk_services_hero FOREIGN KEY (hero_media_id) REFERENCES media(id) ON DELETE SET NULL
);

CREATE TABLE service_translations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_id          INT UNSIGNED NOT NULL,
  language            ENUM('en','ar') NOT NULL,
  name                VARCHAR(120) NOT NULL,     -- nav/footer label
  h1                  VARCHAR(160) NOT NULL,
  short_description   VARCHAR(300) NOT NULL,     -- cards
  description         TEXT NULL,                 -- intro
  content             MEDIUMTEXT NULL,           -- sanitized HTML from admin editor
  items_accepted      JSON NULL,                 -- ["Sofas","Beds",...] only verified items
  items_refused       JSON NULL,
  meta_title          VARCHAR(70)  NOT NULL,
  meta_description    VARCHAR(170) NOT NULL,
  translation_status  ENUM('draft','in_review','approved') NOT NULL DEFAULT 'draft',
  updated_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_service_lang (service_id, language),
  KEY ix_st_meta_title (language, meta_title),   -- duplicate-title check
  CONSTRAINT fk_st_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

CREATE TABLE service_related (
  service_id          INT UNSIGNED NOT NULL,
  related_service_id  INT UNSIGNED NOT NULL,
  sort_order          SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (service_id, related_service_id),
  CONSTRAINT fk_sr_a FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
  CONSTRAINT fk_sr_b FOREIGN KEY (related_service_id) REFERENCES services(id) ON DELETE CASCADE,
  CONSTRAINT ck_sr_self CHECK (service_id <> related_service_id)
);

-- ============ AREAS ============
CREATE TABLE areas (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug               VARCHAR(120) NOT NULL,
  status             ENUM('candidate','draft','published','archived') NOT NULL DEFAULT 'candidate',
  indexable          TINYINT(1) NOT NULL DEFAULT 0,  -- only 1 when all qualification checks pass
  is_served          TINYINT(1) NOT NULL DEFAULT 0,  -- owner-confirmed coverage (F1)
  qualification      JSON NULL,                      -- {"1":{"pass":true,"evidence":"..."}, ... "7":{...}}
  sort_order         SMALLINT NOT NULL DEFAULT 0,
  created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_areas_slug (slug),
  KEY ix_areas_status (status, indexable)
);

CREATE TABLE area_translations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  area_id             INT UNSIGNED NOT NULL,
  language            ENUM('en','ar') NOT NULL,
  name                VARCHAR(120) NOT NULL,
  h1                  VARCHAR(160) NULL,
  short_description   VARCHAR(300) NULL,
  description         TEXT NULL,
  content             MEDIUMTEXT NULL,
  meta_title          VARCHAR(70)  NULL,
  meta_description    VARCHAR(170) NULL,
  translation_status  ENUM('draft','in_review','approved') NOT NULL DEFAULT 'draft',
  updated_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_area_lang (area_id, language),
  CONSTRAINT fk_at_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE CASCADE
);

CREATE TABLE area_service (
  area_id     INT UNSIGNED NOT NULL,
  service_id  INT UNSIGNED NOT NULL,
  PRIMARY KEY (area_id, service_id),
  KEY ix_as_service (service_id),
  CONSTRAINT fk_as_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE CASCADE,
  CONSTRAINT fk_as_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

CREATE TABLE area_nearby (
  area_id         INT UNSIGNED NOT NULL,
  nearby_area_id  INT UNSIGNED NOT NULL,
  PRIMARY KEY (area_id, nearby_area_id),
  CONSTRAINT fk_an_a FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE CASCADE,
  CONSTRAINT fk_an_b FOREIGN KEY (nearby_area_id) REFERENCES areas(id) ON DELETE CASCADE,
  CONSTRAINT ck_an_self CHECK (area_id <> nearby_area_id)
);

-- ============ PROJECTS ============
CREATE TABLE projects (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug               VARCHAR(150) NOT NULL,
  service_id         INT UNSIGNED NULL,
  area_id            INT UNSIGNED NULL,
  location_label     VARCHAR(150) NULL,           -- e.g. "Villa, Al Barsha" — no customer-identifying detail
  project_date       DATE NOT NULL,
  featured_media_id  INT UNSIGNED NULL,
  customer_consent   TINYINT(1) NOT NULL DEFAULT 0, -- photo/publication permission recorded
  status             ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_projects_slug (slug),
  KEY ix_projects_listing (status, project_date),
  KEY ix_projects_service (service_id, status),
  KEY ix_projects_area (area_id, status),
  CONSTRAINT fk_p_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
  CONSTRAINT fk_p_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE SET NULL,
  CONSTRAINT fk_p_media FOREIGN KEY (featured_media_id) REFERENCES media(id) ON DELETE SET NULL
);

CREATE TABLE project_translations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id          INT UNSIGNED NOT NULL,
  language            ENUM('en','ar') NOT NULL,
  title               VARCHAR(160) NOT NULL,
  summary             VARCHAR(400) NOT NULL,
  challenge           TEXT NULL,
  solution            TEXT NULL,
  result              TEXT NULL,
  meta_title          VARCHAR(70)  NOT NULL,
  meta_description    VARCHAR(170) NOT NULL,
  translation_status  ENUM('draft','in_review','approved') NOT NULL DEFAULT 'draft',
  UNIQUE KEY uq_project_lang (project_id, language),
  CONSTRAINT fk_pt_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

CREATE TABLE project_media (
  project_id  INT UNSIGNED NOT NULL,
  media_id    INT UNSIGNED NOT NULL,
  role        ENUM('before','after','gallery') NOT NULL,
  sort_order  SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (project_id, media_id),
  CONSTRAINT fk_pm_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT fk_pm_media FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE RESTRICT
);

-- ============ REVIEWS ============
CREATE TABLE reviews (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_name      VARCHAR(100) NOT NULL,       -- as permitted by customer (e.g. "Sarah M.")
  rating             TINYINT UNSIGNED NULL,       -- NULL if source has no rating
  source             ENUM('google','facebook','whatsapp','email','other') NOT NULL,
  source_url         VARCHAR(500) NULL,
  original_language  ENUM('en','ar') NOT NULL,
  review_date        DATE NOT NULL,
  service_id         INT UNSIGNED NULL,
  area_id            INT UNSIGNED NULL,
  featured           TINYINT(1) NOT NULL DEFAULT 0,
  display_permission TINYINT(1) NOT NULL DEFAULT 0,
  verified_by        INT UNSIGNED NULL,           -- staff member who checked it is genuine
  status             ENUM('pending','published','hidden') NOT NULL DEFAULT 'pending',
  created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY ix_reviews_listing (status, featured, review_date),
  KEY ix_reviews_service (service_id, status),
  KEY ix_reviews_area (area_id, status),
  CONSTRAINT ck_reviews_rating CHECK (rating IS NULL OR rating BETWEEN 1 AND 5),
  CONSTRAINT fk_r_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
  CONSTRAINT fk_r_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE SET NULL,
  CONSTRAINT fk_r_verifier FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE review_translations (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  review_id       INT UNSIGNED NOT NULL,
  language        ENUM('en','ar') NOT NULL,
  review_text     TEXT NOT NULL,
  is_translation  TINYINT(1) NOT NULL DEFAULT 0,  -- UI shows "Translated from English/Arabic"
  UNIQUE KEY uq_review_lang (review_id, language),
  CONSTRAINT fk_rt_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE
);

-- ============ FAQS ============
CREATE TABLE faqs (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category    ENUM('general','pricing','booking','items','areas','process','timing','residential','commercial') NOT NULL,
  service_id  INT UNSIGNED NULL,
  area_id     INT UNSIGNED NULL,
  sort_order  SMALLINT NOT NULL DEFAULT 0,
  status      ENUM('draft','published') NOT NULL DEFAULT 'draft',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY ix_faqs_service (service_id, status, sort_order),
  KEY ix_faqs_area (area_id, status, sort_order),
  KEY ix_faqs_category (category, status, sort_order),
  CONSTRAINT fk_f_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
  CONSTRAINT fk_f_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE SET NULL
);

CREATE TABLE faq_translations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  faq_id              INT UNSIGNED NOT NULL,
  language            ENUM('en','ar') NOT NULL,
  question            VARCHAR(300) NOT NULL,
  answer              TEXT NOT NULL,
  translation_status  ENUM('draft','in_review','approved') NOT NULL DEFAULT 'draft',
  UNIQUE KEY uq_faq_lang (faq_id, language),
  CONSTRAINT fk_ft_faq FOREIGN KEY (faq_id) REFERENCES faqs(id) ON DELETE CASCADE
);

-- ============ PAGES & BLOG ============
CREATE TABLE pages (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(120) NOT NULL,               -- '' for homepage
  page_type   ENUM('home','about','contact','services_hub','areas_hub','projects_hub','reviews_hub','faqs','quote','thank_you','legal','generic') NOT NULL,
  status      ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  indexable   TINYINT(1) NOT NULL DEFAULT 1,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pages_slug (slug)
);

CREATE TABLE page_translations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_id             INT UNSIGNED NOT NULL,
  language            ENUM('en','ar') NOT NULL,
  title               VARCHAR(160) NOT NULL,
  h1                  VARCHAR(160) NOT NULL,
  content             MEDIUMTEXT NULL,
  meta_title          VARCHAR(70)  NOT NULL,
  meta_description    VARCHAR(170) NOT NULL,
  translation_status  ENUM('draft','in_review','approved') NOT NULL DEFAULT 'draft',
  UNIQUE KEY uq_page_lang (page_id, language),
  CONSTRAINT fk_pgt_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);

CREATE TABLE blog_posts (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug               VARCHAR(150) NOT NULL,
  author_id          INT UNSIGNED NULL,
  featured_media_id  INT UNSIGNED NULL,
  status             ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  indexable          TINYINT(1) NOT NULL DEFAULT 1,
  published_at       TIMESTAMP NULL,
  fact_checked_at    TIMESTAMP NULL,               -- required before publish
  created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_blog_slug (slug),
  KEY ix_blog_listing (status, published_at),
  CONSTRAINT fk_b_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_b_media FOREIGN KEY (featured_media_id) REFERENCES media(id) ON DELETE SET NULL
);

CREATE TABLE blog_post_translations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  blog_post_id        INT UNSIGNED NOT NULL,
  language            ENUM('en','ar') NOT NULL,
  title               VARCHAR(160) NOT NULL,
  excerpt             VARCHAR(400) NOT NULL,
  content             MEDIUMTEXT NOT NULL,
  meta_title          VARCHAR(70)  NOT NULL,
  meta_description    VARCHAR(170) NOT NULL,
  translation_status  ENUM('draft','in_review','approved') NOT NULL DEFAULT 'draft',
  UNIQUE KEY uq_blog_lang (blog_post_id, language),
  CONSTRAINT fk_bt_post FOREIGN KEY (blog_post_id) REFERENCES blog_posts(id) ON DELETE CASCADE
);

-- ============ SEO ============
CREATE TABLE seo_meta (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entity_type      ENUM('page','service','area','project','blog_post') NOT NULL,
  entity_id        INT UNSIGNED NOT NULL,
  language         ENUM('en','ar') NOT NULL,
  canonical_url    VARCHAR(500) NULL,             -- NULL = self; app rejects off-domain values
  robots           ENUM('index,follow','noindex,follow','noindex,nofollow') NULL, -- NULL = derive from status/indexable
  og_title         VARCHAR(100) NULL,
  og_description   VARCHAR(200) NULL,
  og_image_media_id INT UNSIGNED NULL,
  schema_type      VARCHAR(40) NULL,
  updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_seo_entity (entity_type, entity_id, language),
  CONSTRAINT fk_seo_media FOREIGN KEY (og_image_media_id) REFERENCES media(id) ON DELETE SET NULL
);
-- Polymorphic: orphan cleanup handled in model delete methods (inside the same transaction).

CREATE TABLE redirects (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  source_path      VARCHAR(500) NOT NULL,         -- normalized path, e.g. /en/old-slug/
  destination_url  VARCHAR(500) NOT NULL,         -- path or same-domain URL; app rejects external targets
  status_code      SMALLINT UNSIGNED NOT NULL DEFAULT 301,
  active           TINYINT(1) NOT NULL DEFAULT 1,
  hits             INT UNSIGNED NOT NULL DEFAULT 0,
  last_hit_at      TIMESTAMP NULL,
  created_by       INT UNSIGNED NULL,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_redirect_source (source_path(191)),
  CONSTRAINT ck_redirect_code CHECK (status_code IN (301,302,410)),
  CONSTRAINT fk_redirect_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ============ LEADS ============
CREATE TABLE leads (
  id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_type        ENUM('quote','contact','booking') NOT NULL,
  name             VARCHAR(100) NOT NULL,
  phone            VARCHAR(20)  NOT NULL,         -- normalized E.164
  email            VARCHAR(190) NULL,
  language         ENUM('en','ar') NOT NULL,
  service_id       INT UNSIGNED NULL,
  area_id          INT UNSIGNED NULL,
  area_other       VARCHAR(100) NULL,
  message          TEXT NULL,
  preferred_date   DATE NULL,
  preferred_time   ENUM('morning','afternoon','evening','flexible') NULL,
  source           ENUM('organic','google_ads','direct','referral','social','whatsapp','other') NOT NULL DEFAULT 'other',
  landing_page     VARCHAR(500) NULL,
  referrer         VARCHAR(500) NULL,
  utm_source       VARCHAR(100) NULL,
  utm_medium       VARCHAR(100) NULL,
  utm_campaign     VARCHAR(150) NULL,
  utm_term         VARCHAR(150) NULL,
  utm_content      VARCHAR(150) NULL,
  gclid            VARCHAR(200) NULL,
  status           ENUM('new','contacted','quoted','booked','completed','lost','spam') NOT NULL DEFAULT 'new',
  spam_score       TINYINT UNSIGNED NOT NULL DEFAULT 0,
  ip_hash          CHAR(64) NULL,
  assigned_to      INT UNSIGNED NULL,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY ix_leads_pipeline (status, created_at),
  KEY ix_leads_phone (phone),
  KEY ix_leads_email (email),
  KEY ix_leads_gclid (gclid(64)),
  KEY ix_leads_campaign (utm_campaign, created_at),
  KEY ix_leads_service (service_id, created_at),
  KEY ix_leads_area (area_id, created_at),
  CONSTRAINT fk_l_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
  CONSTRAINT fk_l_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE SET NULL,
  CONSTRAINT fk_l_assignee FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE lead_attachments (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_id       BIGINT UNSIGNED NOT NULL,
  stored_path   VARCHAR(255) NOT NULL,           -- outside web root (storage/uploads/leads/...)
  mime_type     VARCHAR(40) NOT NULL,
  file_size     INT UNSIGNED NOT NULL,
  width         SMALLINT UNSIGNED NOT NULL,
  height        SMALLINT UNSIGNED NOT NULL,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_la_lead (lead_id),
  CONSTRAINT fk_la_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
);

CREATE TABLE lead_notes (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_id     BIGINT UNSIGNED NOT NULL,
  user_id     INT UNSIGNED NULL,
  note        TEXT NOT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_ln_lead (lead_id, created_at),
  CONSTRAINT fk_ln_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
  CONSTRAINT fk_ln_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE lead_status_history (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_id     BIGINT UNSIGNED NOT NULL,
  old_status  ENUM('new','contacted','quoted','booked','completed','lost','spam') NULL,
  new_status  ENUM('new','contacted','quoted','booked','completed','lost','spam') NOT NULL,
  changed_by  INT UNSIGNED NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_lsh_lead (lead_id, created_at),
  CONSTRAINT fk_lsh_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
  CONSTRAINT fk_lsh_user FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE conversion_events (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event_name    ENUM('phone_click','whatsapp_click','email_click','quote_start','quote_submit','contact_submit','booking_submit') NOT NULL,
  language      ENUM('en','ar') NOT NULL,
  page_path     VARCHAR(500) NOT NULL,
  landing_page  VARCHAR(500) NULL,
  referrer      VARCHAR(500) NULL,
  utm_source    VARCHAR(100) NULL,
  utm_medium    VARCHAR(100) NULL,
  utm_campaign  VARCHAR(150) NULL,
  utm_term      VARCHAR(150) NULL,
  gclid         VARCHAR(200) NULL,
  lead_id       BIGINT UNSIGNED NULL,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_ce_event (event_name, created_at),
  KEY ix_ce_campaign (utm_campaign, created_at),
  CONSTRAINT fk_ce_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE SET NULL
);
```

## 4. Integrity & query rules
- Multi-table writes (entity + translations + seo_meta + relationships; lead + attachments + first status history row) run in a single transaction.
- Public queries always filter `status='published'` **and** join the translation for the requested language with `translation_status='approved'`.
- Status change on a lead writes `lead_status_history` in the same transaction.
- Sitemap query: published + indexable + approved translation, per language.

## 5. Retention & backups
- Leads marked `spam`: purge after 30 days. Other leads: retention period to be set by owner (suggest 24 months) → documented in Privacy Policy.
- `rate_limits` rows older than 24 h purged by cron; `audit_log` kept 12 months.
- Backups: nightly `mysqldump --single-transaction` (compressed, encrypted, off-server copy) + nightly sync of `storage/uploads` and `public/media`; keep 7 daily, 4 weekly, 3 monthly; **test restore monthly** to staging.
- DB users: `app_rw` (SELECT/INSERT/UPDATE/DELETE only), `migrator` (DDL, used only during deploys), `backup` (SELECT, LOCK TABLES, SHOW VIEW, EVENT, TRIGGER).

## 6. Seeders (allowed content)
Reference data only: admin user (password from env at seed time), service/area slugs with **empty/draft** translations, settings keys with `is_verified=0`. **Never** seed reviews, projects, ratings or testimonials.
