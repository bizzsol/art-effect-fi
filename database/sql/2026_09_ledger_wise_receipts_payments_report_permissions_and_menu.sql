-- =============================================================================
--  Ledger-wise Receipts & Payments Statement report: permissions + sidebar entry
--  Generated 2026-09-20
--
--  Adds four permissions for the new Ledger-wise Receipts & Payments Statement report (matching the
--  existing balance-sheet / profit-loss / trial-balance pattern) and grants
--  them to Super Admin and Accounts. Also adds a "Ledger-wise Receipts & Payments Statement" row
--  under the Reports menu in the Finance sidebar (module = 'finance').
--
--  SAFE TO RE-RUN. Every statement is guarded, so nothing errors or
--  duplicates if part of it has already been applied.
--
--  Companion code: app/Http/Controllers/Myaccounting/LedgerWiseReceiptsPaymentsController.php
--                   resources/views/accounting/backend/pages/reports/ledgerWiseReceiptsPayments/
--                   routes/modules/accounting.php (accounting/ledger-wise-receipts-payments)
--                   database/migrations/2026_09_20_120000_add_ledger_wise_receipts_payments_report_permissions_and_menu.php
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- 0. The original summary report keeps the accounting/cash-flow entry: rename it
-- -----------------------------------------------------------------------------
UPDATE `sub_menus` SET `name` = 'Cashflow Statement', `updated_at` = NOW() WHERE `url` = 'accounting/cash-flow';

-- -----------------------------------------------------------------------------
-- 1. Permissions
-- -----------------------------------------------------------------------------
INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'ledger-wise-receipts-payments', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'ledger-wise-receipts-payments' AND p.`guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'ledger-wise-receipts-payments-print', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'ledger-wise-receipts-payments-print' AND p.`guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'ledger-wise-receipts-payments-pdf', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'ledger-wise-receipts-payments-pdf' AND p.`guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'ledger-wise-receipts-payments-excel', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'ledger-wise-receipts-payments-excel' AND p.`guard_name` = 'web');

-- -----------------------------------------------------------------------------
-- 2. Grant to Super Admin and Accounts
-- -----------------------------------------------------------------------------
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p
JOIN `roles` r ON r.`name` IN ('Super Admin', 'Accounts')
WHERE p.`name` IN ('ledger-wise-receipts-payments', 'ledger-wise-receipts-payments-print', 'ledger-wise-receipts-payments-pdf', 'ledger-wise-receipts-payments-excel')
  AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rp
      WHERE rp.`permission_id` = p.id AND rp.`role_id` = r.id
  );

-- -----------------------------------------------------------------------------
-- 3. Sidebar entry
--
--    Menu resolved by name/module rather than a hardcoded id for portability.
--    Verify before running elsewhere:
--      SELECT id, module, name FROM menus WHERE name = 'Reports' AND module = 'finance';
-- -----------------------------------------------------------------------------
INSERT INTO `sub_menus`
    (`menu_id`, `module`, `name`, `url`, `icon_class`, `slug`, `serial_num`, `menu_for`, `status`, `created_by`, `created_at`, `updated_at`)
SELECT
    m.id,
    'finance',
    'Ledger-wise Receipts & Payments Statement',
    'accounting/ledger-wise-receipts-payments',
    'las la-exchange-alt',
    '["ledger-wise-receipts-payments"]',
    (SELECT COALESCE(MAX(sm.serial_num), 0) + 1 FROM `sub_menus` sm WHERE sm.`menu_id` = m.id),
    'Sub menu for admin',
    'Active',
    1,
    NOW(),
    NOW()
FROM `menus` m
WHERE m.`name` = 'Reports' AND m.`module` = 'finance'
  AND NOT EXISTS (SELECT 1 FROM `sub_menus` s WHERE s.`url` = 'accounting/ledger-wise-receipts-payments');

-- =============================================================================
--  VERIFY
-- =============================================================================
SELECT 'permissions' AS object, IF(COUNT(*) = 4, 'OK', CONCAT('MISSING (', COUNT(*), '/4)')) AS state
FROM `permissions`
WHERE `name` IN ('ledger-wise-receipts-payments', 'ledger-wise-receipts-payments-print', 'ledger-wise-receipts-payments-pdf', 'ledger-wise-receipts-payments-excel') AND `guard_name` = 'web'
UNION ALL
SELECT 'granted to Super Admin', IF(COUNT(*) = 4, 'OK', CONCAT('MISSING (', COUNT(*), '/4)'))
FROM `role_has_permissions` rp
JOIN `permissions` p ON p.id = rp.permission_id
JOIN `roles` r ON r.id = rp.role_id
WHERE p.`name` IN ('ledger-wise-receipts-payments', 'ledger-wise-receipts-payments-print', 'ledger-wise-receipts-payments-pdf', 'ledger-wise-receipts-payments-excel') AND r.`name` = 'Super Admin'
UNION ALL
SELECT 'granted to Accounts', IF(COUNT(*) = 4, 'OK', CONCAT('MISSING (', COUNT(*), '/4)'))
FROM `role_has_permissions` rp
JOIN `permissions` p ON p.id = rp.permission_id
JOIN `roles` r ON r.id = rp.role_id
WHERE p.`name` IN ('ledger-wise-receipts-payments', 'ledger-wise-receipts-payments-print', 'ledger-wise-receipts-payments-pdf', 'ledger-wise-receipts-payments-excel') AND r.`name` = 'Accounts'
UNION ALL
SELECT 'sidebar entry', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM `sub_menus` WHERE `url` = 'accounting/ledger-wise-receipts-payments';

-- =============================================================================
--  AFTER RUNNING
--    php artisan permission:cache-reset && php artisan view:clear
--    Then LOG OUT AND BACK IN - the sidebar is cached in the session under
--    'finance-menus', and this insert does not touch that cache.
-- =============================================================================
