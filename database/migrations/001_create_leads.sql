-- Junk Removal Team Dubai — leads table
--
-- Run once, after creating the database in the Hostinger control panel:
--   mysql -u <user> -p <database> < 001_create_leads.sql
--
-- A trimmed version of docs/08-database-schema.md: only what the live quote
-- form needs today. The rest of the schema follows when the admin is built.

CREATE TABLE IF NOT EXISTS leads (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100)  NOT NULL,
  phone         VARCHAR(20)   NOT NULL,           -- stored as +9715XXXXXXXX
  email         VARCHAR(190)  NULL,
  language      ENUM('en','ar') NOT NULL DEFAULT 'en',
  service       VARCHAR(60)   NULL,
  area          VARCHAR(60)   NULL,
  message       TEXT          NULL,

  -- where the enquiry came from
  landing_page  VARCHAR(500)  NULL,
  referrer      VARCHAR(500)  NULL,
  utm_source    VARCHAR(100)  NULL,
  utm_medium    VARCHAR(100)  NULL,
  utm_campaign  VARCHAR(150)  NULL,
  utm_term      VARCHAR(150)  NULL,
  utm_content   VARCHAR(150)  NULL,
  gclid         VARCHAR(200)  NULL,

  status        ENUM('new','contacted','quoted','booked','completed','lost','spam')
                NOT NULL DEFAULT 'new',
  ip_hash       CHAR(64)      NULL,               -- hashed, never the raw address
  created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  KEY ix_leads_pipeline (status, created_at),
  KEY ix_leads_phone (phone),
  KEY ix_leads_campaign (utm_campaign, created_at),
  KEY ix_leads_gclid (gclid(64))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
