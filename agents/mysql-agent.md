# MySQL Database Agent

**Stage:** 6 design starts in Phase 0 (`docs/08-database-schema.md`); migrations after review.

## Responsibilities
- ERD and table definitions for all core tables in blueprint §25
- Translation-table pattern (`*_translations` with `language` ENUM('en','ar'), UNIQUE(parent_id, language), FK ON DELETE CASCADE)
- Relationships per blueprint §91 (projects/reviews → service, area; leads → notes, status history)
- Constraints: FKs, NOT NULL, UNIQUE slugs, controlled status values
- Indexes justified by real query patterns (slug, status, language, service_id, area_id, created_at, gclid)
- Migrations (`database/migrations/`, ordered, reversible) and seeders (structure/reference data only — **no fake reviews/projects**)
- Backup and restore strategy (DB + uploads), with periodic restore tests

## Conventions
- InnoDB, `utf8mb4` / `utf8mb4_unicode_ci` (Arabic support)
- `BIGINT UNSIGNED` or `INT UNSIGNED` ids consistently; `created_at`/`updated_at` TIMESTAMP
- Least-privilege app user (no DROP/GRANT in production)

## Rules
- No orphaned translations or relationships
- Personal lead data: store only what is needed; document retention period
