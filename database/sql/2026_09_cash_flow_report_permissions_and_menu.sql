-- =============================================================================
--  Cash Flow Statement report: permissions + sidebar entry
--  Generated 2026-09-15
--
--  Adds four permissions for the new Cash Flow Statement report (matching the
--  existing balance-sheet / profit-loss / trial-balance pattern) and grants
--  them to Super Admin and Accounts. Also adds a "Cash Flow Statement" row
--  under the Reports menu in the Finance sidebar (module = 'finance').
--
--  SAFE TO RE-RUN. Every statement is guarded, so nothing errors or
--  duplicates if part of it has already been applied.
--
--  Companion code: app/Http/Controllers/Myaccounting/CashFlowController.php
--                   resources/views/accounting/backend/pages/reports/cashFlow/
--                   routes/modules/accounting.php (accounting/cash-flow)
--                   database/migrations/2026_09_15_120000_add_cash_flow_report_permissions_and_menu.php
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- 1. Permissions
-- -----------------------------------------------------------------------------
INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'cash-flow', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'cash-flow' AND p.`guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'cash-flow-print', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'cash-flow-print' AND p.`guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'cash-flow-pdf', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'cash-flow-pdf' AND p.`guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'cash-flow-excel', 'web', 'Finance Reprots', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = 'cash-flow-excel' AND p.`guard_name` = 'web');

-- -----------------------------------------------------------------------------
-- 2. Grant to Super Admin and Accounts
-- -----------------------------------------------------------------------------
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p
JOIN `roles` r ON r.`name` IN ('Super Admin', 'Accounts')
WHERE p.`name` IN ('cash-flow', 'cash-flow-print', 'cash-flow-pdf', 'cash-flow-excel')
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
    'Cash Flow Statement',
    'accounting/cash-flow',
    'las la-money-bill-wave',
    '["cash-flow"]',
    (SELECT COALESCE(MAX(sm.serial_num), 0) + 1 FROM `sub_menus` sm WHERE sm.`menu_id` = m.id),
    'Sub menu for admin',
    'Active',
    1,
    NOW(),
    NOW()
FROM `menus` m
WHERE m.`name` = 'Reports' AND m.`module` = 'finance'
  AND NOT EXISTS (SELECT 1 FROM `sub_menus` s WHERE s.`url` = 'accounting/cash-flow');

-- =============================================================================
--  VERIFY
-- =============================================================================
SELECT 'permissions' AS object, IF(COUNT(*) = 4, 'OK', CONCAT('MISSING (', COUNT(*), '/4)')) AS state
FROM `permissions`
WHERE `name` IN ('cash-flow', 'cash-flow-print', 'cash-flow-pdf', 'cash-flow-excel') AND `guard_name` = 'web'
UNION ALL
SELECT 'granted to Super Admin', IF(COUNT(*) = 4, 'OK', CONCAT('MISSING (', COUNT(*), '/4)'))
FROM `role_has_permissions` rp
JOIN `permissions` p ON p.id = rp.permission_id
JOIN `roles` r ON r.id = rp.role_id
WHERE p.`name` IN ('cash-flow', 'cash-flow-print', 'cash-flow-pdf', 'cash-flow-excel') AND r.`name` = 'Super Admin'
UNION ALL
SELECT 'granted to Accounts', IF(COUNT(*) = 4, 'OK', CONCAT('MISSING (', COUNT(*), '/4)'))
FROM `role_has_permissions` rp
JOIN `permissions` p ON p.id = rp.permission_id
JOIN `roles` r ON r.id = rp.role_id
WHERE p.`name` IN ('cash-flow', 'cash-flow-print', 'cash-flow-pdf', 'cash-flow-excel') AND r.`name` = 'Accounts'
UNION ALL
SELECT 'sidebar entry', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM `sub_menus` WHERE `url` = 'accounting/cash-flow';

-- =============================================================================
--  AFTER RUNNING
--    php artisan permission:cache-reset && php artisan view:clear
--    Then LOG OUT AND BACK IN - the sidebar is cached in the session under
--    'finance-menus', and this insert does not touch that cache.
-- =============================================================================
