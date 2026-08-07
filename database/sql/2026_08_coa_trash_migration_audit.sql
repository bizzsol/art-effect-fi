-- =============================================================================
--  Chart of Accounts: Trash, company-scoped ledger migration, and audit trail
--  Generated from the verified schema on 2026-08-04
--
--  Covers Laravel migrations:
--    2026_08_03_100000_chart_of_account_logs
--    2026_08_03_100100_ledger_migrations
--    2026_08_03_100200_ledger_migration_items
--    2026_08_03_100300_add_deleted_by_to_chart_of_accounts
--    2026_08_03_100400_add_coa_cost_centre_index_to_entry_items
--    2026_08_03_100500_add_reversal_links_to_ledger_migrations
--    2026_08_04_090000_add_mismatch_aggregation_index_to_entry_items
--
--  SAFE TO RE-RUN. Every statement is guarded, so nothing errors or duplicates
--  if part of it has already been applied.
--
--  BEFORE YOU RUN THIS
--    1. Take a backup:  mysqldump -u root -p <db> > backup_before_coa_audit.sql
--    2. Read section 6 - the two index builds rewrite entry_items and will take
--       a few minutes on a large table. Run them in a quiet window.
--    3. Section 7 (permissions/menus) contains IDs that are correct for THIS
--       database. Verify them against production before running that section.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 1;


-- -----------------------------------------------------------------------------
-- 1. chart_of_account_logs
--
--    Who changed what on the Chart of Accounts, including company assign and
--    unassign. Written explicitly by the application, not by model events: the
--    COA pivots are rewritten with a hard DELETE plus a bulk INSERT, which fires
--    no Eloquent events, so an observer physically cannot see those changes.
--
--    Deliberately NO foreign keys. An audit row must outlive the ledger, company
--    or user it refers to, and writing a log must never fail on a constraint.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chart_of_account_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chart_of_account_id` bigint unsigned DEFAULT NULL,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned DEFAULT NULL,
  `company_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `context` longtext COLLATE utf8mb4_unicode_ci,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `caused_by` bigint unsigned DEFAULT NULL,
  `causer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_coa_logs_coa` (`chart_of_account_id`,`id`),
  KEY `idx_coa_logs_action` (`action`),
  KEY `idx_coa_logs_company` (`company_id`),
  KEY `idx_coa_logs_causer` (`caused_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------------------------------
-- 2. ledger_migrations  (header, one row per run)
--
--    Includes the 2026_08_03_100500 reversal columns and the 'reversed' status,
--    so this is the final shape - no follow-up ALTER needed.
--
--    Source/target code and name are stored as plain columns, not just IDs:
--    ledgers get renamed, and the audit has to still read correctly years later.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ledger_migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `source_chart_of_account_id` bigint unsigned NOT NULL,
  `target_chart_of_account_id` bigint unsigned NOT NULL,
  `company_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_account_group_id` bigint unsigned DEFAULT NULL,
  `source_account_class_id` bigint unsigned DEFAULT NULL,
  `target_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_account_group_id` bigint unsigned DEFAULT NULL,
  `target_account_class_id` bigint unsigned DEFAULT NULL,
  `cost_centre_ids` longtext COLLATE utf8mb4_unicode_ci,
  `fiscal_year_ids` longtext COLLATE utf8mb4_unicode_ci,
  `items_expected` bigint unsigned NOT NULL DEFAULT '0',
  `items_moved` bigint unsigned NOT NULL DEFAULT '0',
  `items_residue` bigint unsigned NOT NULL DEFAULT '0',
  `live_items` bigint unsigned NOT NULL DEFAULT '0',
  `trashed_items` bigint unsigned NOT NULL DEFAULT '0',
  `items_on_trashed_entries` bigint unsigned NOT NULL DEFAULT '0',
  `total_debit_moved` double NOT NULL DEFAULT '0',
  `total_credit_moved` double NOT NULL DEFAULT '0',
  `control_totals_before` longtext COLLATE utf8mb4_unicode_ci,
  `control_totals_after` longtext COLLATE utf8mb4_unicode_ci,
  `warnings` longtext COLLATE utf8mb4_unicode_ci,
  `reclose_from_fiscal_year_id` bigint unsigned DEFAULT NULL,
  `reclose_to_fiscal_year_id` bigint unsigned DEFAULT NULL,
  `reclosed_at` timestamp NULL DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `reverses_migration_id` bigint unsigned DEFAULT NULL,
  `reversed_by_migration_id` bigint unsigned DEFAULT NULL,
  `status` enum('running','awaiting_reclose','completed','failed','reversed')
      COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `error` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ledger_migrations_reference_unique` (`reference`),
  KEY `idx_ledger_migrations_src` (`source_chart_of_account_id`,`company_id`,`status`),
  KEY `idx_ledger_migrations_tgt` (`target_chart_of_account_id`),
  KEY `idx_ledger_migrations_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------------------------------
-- 3. ledger_migration_items  (detail, one row per moved entry item)
--
--    This is the frozen proof of what moved. cost_centre_id and the amounts are
--    captured at move time on purpose: cost centres get re-parented later, and
--    re-running the original WHERE clause afterwards returns a different set, so
--    a stored query is not an audit trail. It is also what makes a reversal
--    exact rather than a best guess.
--
--    No FK to entry_items - this row must outlive the item it describes.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ledger_migration_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ledger_migration_id` bigint unsigned NOT NULL,
  `entry_item_id` bigint unsigned NOT NULL,
  `entry_id` bigint unsigned DEFAULT NULL,
  `entry_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `fiscal_year_id` bigint unsigned DEFAULT NULL,
  `entry_type_id` bigint unsigned DEFAULT NULL,
  `is_approved_at_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_centre_id` bigint unsigned DEFAULT NULL,
  `from_chart_of_account_id` bigint unsigned NOT NULL,
  `to_chart_of_account_id` bigint unsigned NOT NULL,
  `debit_credit` enum('D','C') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `reporting_amount` double NOT NULL DEFAULT '0',
  `item_was_trashed` tinyint NOT NULL DEFAULT '0',
  `entry_was_trashed` tinyint NOT NULL DEFAULT '0',
  `moved_at` timestamp NULL DEFAULT NULL,
  `reverted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lmi_migration_item` (`ledger_migration_id`,`entry_item_id`),
  KEY `idx_lmi_entry_item` (`entry_item_id`),
  CONSTRAINT `ledger_migration_items_ledger_migration_id_foreign`
      FOREIGN KEY (`ledger_migration_id`) REFERENCES `ledger_migrations` (`id`)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------------------------------
-- 4. deleted_by on chart_of_accounts and account_groups
--
--    The 2023 soft-delete migration added only deleted_at, so a trashed ledger
--    was completely anonymous - the stale updated_by from its last edit was the
--    only, misleading, clue.
--
--    Wrapped in a procedure because MySQL has no ADD COLUMN IF NOT EXISTS.
-- -----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `sslz_add_col`;
DELIMITER $$
CREATE PROCEDURE `sslz_add_col`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
    PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
  END IF;
END$$
DELIMITER ;

CALL sslz_add_col('chart_of_accounts', 'deleted_by', '`deleted_by` bigint unsigned NULL AFTER `updated_by`');
CALL sslz_add_col('account_groups',    'deleted_by', '`deleted_by` bigint unsigned NULL AFTER `updated_by`');

DROP PROCEDURE IF EXISTS `sslz_add_col`;


-- -----------------------------------------------------------------------------
-- 5. Reversal columns + 'reversed' status on ledger_migrations
--
--    Only does anything if ledger_migrations already existed from an earlier
--    partial run. A fresh CREATE in section 2 already includes all of this.
-- -----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `sslz_fix_ledger_migrations`;
DELIMITER $$
CREATE PROCEDURE `sslz_fix_ledger_migrations`()
BEGIN
  IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ledger_migrations'
                   AND COLUMN_NAME = 'reverses_migration_id') THEN
    ALTER TABLE `ledger_migrations`
      ADD COLUMN `reverses_migration_id` bigint unsigned NULL AFTER `reason`,
      ADD COLUMN `reversed_by_migration_id` bigint unsigned NULL AFTER `reverses_migration_id`;
  END IF;

  -- Idempotent: setting the ENUM to the same definition is a no-op.
  ALTER TABLE `ledger_migrations`
    MODIFY COLUMN `status`
      enum('running','awaiting_reclose','completed','failed','reversed')
      COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running';
END$$
DELIMITER ;

CALL sslz_fix_ledger_migrations();
DROP PROCEDURE IF EXISTS `sslz_fix_ledger_migrations`;


-- -----------------------------------------------------------------------------
-- 6. Indexes on entry_items
--
--    *** THESE TWO STATEMENTS REWRITE entry_items (~2.3M rows here). ***
--    Roughly 35s each on this dataset; expect longer on production hardware and
--    plan a quiet window. Everything above is instant by comparison.
--
--    idx_entry_items_coa_cc_dc  - company-scoped ledger usage counts and the
--        migration scope filter, which match on (chart_of_account_id,
--        cost_centre_id) and split by debit_credit.
--
--    idx_entry_items_cover_agg  - the mismatch report's per-voucher aggregation.
--        Leading with entry_id lets MySQL walk the GROUP BY in index order, and
--        carrying deleted_at, cost_centre_id, debit_credit and reporting_amount
--        makes the whole aggregate index-only. Measured on the largest
--        company-year: 5.4s with it versus 11-17s without, against 91s for the
--        query this replaced.
-- -----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `sslz_add_index`;
DELIMITER $$
CREATE PROCEDURE `sslz_add_index`(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN cols TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx
  ) THEN
    SET @s = CONCAT('CREATE INDEX `', idx, '` ON `', tbl, '` (', cols, ')');
    PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
  END IF;
END$$
DELIMITER ;

CALL sslz_add_index('entry_items', 'idx_entry_items_coa_cc_dc',
     '`chart_of_account_id`, `cost_centre_id`, `debit_credit`');

CALL sslz_add_index('entry_items', 'idx_entry_items_cover_agg',
     '`entry_id`, `deleted_at`, `cost_centre_id`, `debit_credit`, `reporting_amount`');

DROP PROCEDURE IF EXISTS `sslz_add_index`;


-- -----------------------------------------------------------------------------
-- 7. Permissions and menu entries
--
--    *** CHECK THE IDs BELOW AGAINST PRODUCTION BEFORE RUNNING THIS SECTION. ***
--    Role IDs and menu_id 19 are taken from this database. The inserts look up
--    roles by NAME so they survive different role IDs, but menu_id 19 is the
--    menu that holds "Chart of Accounts" here and may differ on production.
--
--    The three chart-of-accounts-* permissions already existed here. The inserts
--    below create anything missing, so this section is safe either way.
--
--    fiscal-year-re-closing and entries-bulk-delete are NEW gates on routes that
--    previously had no permission middleware. Run this section BEFORE deploying
--    the code, or those two screens go dark for everyone until you do.
-- -----------------------------------------------------------------------------

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT v.name, 'web', 'Finance', NOW(), NOW()
FROM (
    SELECT 'chart-of-accounts-restore' AS name
    UNION ALL SELECT 'chart-of-accounts-migrate'
    UNION ALL SELECT 'chart-of-accounts-logs'
    UNION ALL SELECT 'fiscal-year-re-closing'
    UNION ALL SELECT 'entries-bulk-delete'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `permissions` p WHERE p.`name` = v.name AND p.`guard_name` = 'web'
);

-- Grant to the roles that already administer the Chart of Accounts.
--
-- Two of these are load-bearing rather than optional. Both fiscal-year-re-closing
-- and entries-bulk-delete gate routes that previously had NO permission middleware
-- at all: gating them without granting the permission locks those screens out for
-- everyone, including Accounts.
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p
JOIN `roles` r ON r.`name` IN ('Super Admin', 'Accounts')
WHERE p.`name` IN ('chart-of-accounts-restore', 'chart-of-accounts-migrate',
                   'chart-of-accounts-logs', 'fiscal-year-re-closing',
                   'entries-bulk-delete')
  AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rp
      WHERE rp.`permission_id` = p.id AND rp.`role_id` = r.id
  );

-- Sidebar entries. The sidebar is database-driven: sub_menus.slug is a JSON
-- array of permission names that @canany checks.
--
-- Replace 19 with the menu_id that holds Chart of Accounts on production:
--   SELECT menu_id, name, url FROM sub_menus WHERE url LIKE '%chart-of-accounts%';
INSERT INTO `sub_menus`
    (`menu_id`, `module`, `name`, `url`, `slug`, `serial_num`, `menu_for`, `status`, `created_at`, `updated_at`)
SELECT * FROM (
    SELECT 19 AS menu_id, 'finance' AS module, 'COA Trash' AS name,
           'accounting/chart-of-accounts-trash' AS url,
           '["chart-of-accounts-restore"]' AS slug, 7 AS serial_num,
           'Sub menu for admin' AS menu_for, 'Active' AS status, NOW() AS created_at, NOW() AS updated_at
    UNION ALL SELECT 19, 'finance', 'COA Logs', 'accounting/chart-of-accounts-logs',
           '["chart-of-accounts-logs"]', 8, 'Sub menu for admin', 'Active', NOW(), NOW()
    UNION ALL SELECT 19, 'finance', 'Ledger Migrations', 'accounting/ledger-migrations',
           '["chart-of-accounts-migrate"]', 9, 'Sub menu for admin', 'Active', NOW(), NOW()
) v
WHERE NOT EXISTS (SELECT 1 FROM `sub_menus` s WHERE s.`url` = v.url);


-- -----------------------------------------------------------------------------
-- 8. Record the migrations so `php artisan migrate` does not re-run them
--
--    The PHP migrations are individually guarded and would be harmless if they
--    did run, but recording them keeps the migration table honest.
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT v.migration, (SELECT COALESCE(MAX(batch), 0) + 1 FROM `migrations` m2)
FROM (
    SELECT '2026_08_03_100000_chart_of_account_logs' AS migration
    UNION ALL SELECT '2026_08_03_100100_ledger_migrations'
    UNION ALL SELECT '2026_08_03_100200_ledger_migration_items'
    UNION ALL SELECT '2026_08_03_100300_add_deleted_by_to_chart_of_accounts'
    UNION ALL SELECT '2026_08_03_100400_add_coa_cost_centre_index_to_entry_items'
    UNION ALL SELECT '2026_08_03_100500_add_reversal_links_to_ledger_migrations'
    UNION ALL SELECT '2026_08_04_090000_add_mismatch_aggregation_index_to_entry_items'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` m WHERE m.`migration` = v.migration
);


-- =============================================================================
--  VERIFY  - all seven rows should report OK
-- =============================================================================
SELECT 'chart_of_account_logs' AS object,
       IF(COUNT(*) = 1, 'OK', 'MISSING') AS state
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chart_of_account_logs'
UNION ALL
SELECT 'ledger_migrations', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ledger_migrations'
UNION ALL
SELECT 'ledger_migration_items', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ledger_migration_items'
UNION ALL
SELECT 'chart_of_accounts.deleted_by', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chart_of_accounts' AND COLUMN_NAME = 'deleted_by'
UNION ALL
SELECT 'account_groups.deleted_by', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'account_groups' AND COLUMN_NAME = 'deleted_by'
UNION ALL
SELECT 'idx_entry_items_coa_cc_dc', IF(COUNT(*) = 3, 'OK', 'MISSING')
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'entry_items' AND INDEX_NAME = 'idx_entry_items_coa_cc_dc'
UNION ALL
SELECT 'idx_entry_items_cover_agg', IF(COUNT(*) = 5, 'OK', 'MISSING')
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'entry_items' AND INDEX_NAME = 'idx_entry_items_cover_agg'
UNION ALL
SELECT 'permissions granted', IF(COUNT(*) >= 10, 'OK', CONCAT('ONLY ', COUNT(*), ' OF 10'))
FROM `role_has_permissions` rp
JOIN `permissions` p ON p.id = rp.permission_id
JOIN `roles` r ON r.id = rp.role_id
WHERE p.`name` IN ('chart-of-accounts-restore','chart-of-accounts-migrate',
                   'chart-of-accounts-logs','fiscal-year-re-closing',
                   'entries-bulk-delete')
  AND r.`name` IN ('Super Admin','Accounts')
UNION ALL
SELECT 'sidebar entries', IF(COUNT(*) = 3, 'OK', CONCAT('ONLY ', COUNT(*), ' OF 3'))
FROM `sub_menus`
WHERE `url` IN ('accounting/chart-of-accounts-trash',
                'accounting/chart-of-accounts-logs',
                'accounting/ledger-migrations');

-- =============================================================================
--  AFTER RUNNING
--    php artisan cache:clear && php artisan view:clear && php artisan route:clear
--    Then LOG OUT AND BACK IN. The sidebar is cached in the session under
--    'finance-menus' and /reboot does not clear it, so the new links will not
--    appear until you re-login.
-- =============================================================================
