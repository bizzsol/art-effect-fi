-- =============================================================================
--  Developer Links page: permission + sidebar entry
--  Generated 2026-08-08
--
--  Adds a "Developer Links" row under the Integration menu in the Finance
--  sidebar (accounting.backend.menus.left-menu, module = 'finance'), gated
--  by a new "developer-links" permission. Super Admin sees it automatically
--  via the Gate::before bypass in AuthServiceProvider - no grant needed.
--  Anyone else needs the permission granted explicitly.
--
--  SAFE TO RE-RUN. Every statement is guarded, so nothing errors or
--  duplicates if part of it has already been applied.
--
--  Companion code: app/Http/Controllers/Myaccounting/DeveloperLinksController.php
--                   resources/views/accounting/backend/pages/developer-links/index.blade.php
--                   routes/modules/accounting.php (accounting/developer-links)
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- 1. Permission
-- -----------------------------------------------------------------------------
INSERT INTO `permissions` (`name`, `guard_name`, `module`, `created_at`, `updated_at`)
SELECT 'developer-links', 'web', 'Finance', NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `permissions` p WHERE p.`name` = 'developer-links' AND p.`guard_name` = 'web'
);

-- Grant to Super Admin only. Deliberately not granted to any other role -
-- everyone else needs this switched on for them by hand.
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.id, r.id
FROM `permissions` p
JOIN `roles` r ON r.`name` = 'Super Admin'
WHERE p.`name` = 'developer-links'
  AND p.`guard_name` = 'web'
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rp
      WHERE rp.`permission_id` = p.id AND rp.`role_id` = r.id
  );

-- -----------------------------------------------------------------------------
-- 2. Sidebar entry
--
--    menu_id 95 is "Integration" in the Finance sidebar (module = 'finance').
--    Verify before running elsewhere:
--      SELECT id, module, name FROM menus WHERE name = 'Integration' AND module = 'finance';
-- -----------------------------------------------------------------------------
INSERT INTO `sub_menus`
    (`menu_id`, `module`, `name`, `url`, `icon_class`, `slug`, `serial_num`, `menu_for`, `status`, `created_by`, `created_at`, `updated_at`)
SELECT 95, 'finance', 'Developer Links', 'accounting/developer-links', 'las la-user-secret',
       '["developer-links"]', 8, 'Sub menu for admin', 'Active', 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `sub_menus` s WHERE s.`url` = 'accounting/developer-links');

-- =============================================================================
--  VERIFY
-- =============================================================================
SELECT 'permission' AS object, IF(COUNT(*) = 1, 'OK', 'MISSING') AS state
FROM `permissions` WHERE `name` = 'developer-links' AND `guard_name` = 'web'
UNION ALL
SELECT 'granted to Super Admin', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM `role_has_permissions` rp
JOIN `permissions` p ON p.id = rp.permission_id
JOIN `roles` r ON r.id = rp.role_id
WHERE p.`name` = 'developer-links' AND r.`name` = 'Super Admin'
UNION ALL
SELECT 'sidebar entry', IF(COUNT(*) = 1, 'OK', 'MISSING')
FROM `sub_menus` WHERE `url` = 'accounting/developer-links';

-- =============================================================================
--  AFTER RUNNING
--    php artisan permission:cache-reset && php artisan view:clear
--    Then LOG OUT AND BACK IN - the sidebar is cached in the session under
--    'finance-menus', and this insert does not touch that cache.
-- =============================================================================
