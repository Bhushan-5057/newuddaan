-- ---------------------------------------------------------------------------
-- Performance indexes
--
-- Equivalent to the Laravel migration
--   database/migrations/2026_09_11_120000_add_performance_indexes.php
--
-- Run this ONLY if you cannot run `php artisan migrate --force` on the server.
-- If you do run it by hand, also mark the migration as applied so artisan does
-- not try to repeat it later - see the last statement in this file.
--
-- Safe to run more than once: every statement uses IF NOT EXISTS / IF EXISTS
-- (MariaDB 10.0+).
--
-- No rows are inserted, updated or deleted here. Indexes only.
-- ---------------------------------------------------------------------------


-- === orders =================================================================
-- Had no index at all beyond the primary key, yet every dashboard aggregate
-- filters on owner + type + status.
ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_txuser_txtype_status` (`tx_user_id`, `tx_type`, `status`);
ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_ucode_txtype_status`  (`u_code`, `tx_type`, `status`);
ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_added_on`             (`added_on`);
ALTER TABLE `orders` ADD INDEX IF NOT EXISTS `idx_orders_status`               (`status`);


-- === transaction ============================================================
-- Same story: wallet, income and withdrawal totals all scanned the whole table.
ALTER TABLE `transaction` ADD INDEX IF NOT EXISTS `idx_tx_ucode_txtype_status` (`u_code`, `tx_type`, `status`);
ALTER TABLE `transaction` ADD INDEX IF NOT EXISTS `idx_tx_ucode_txtype_source` (`u_code`, `tx_type`, `source`);
ALTER TABLE `transaction` ADD INDEX IF NOT EXISTS `idx_tx_date`                (`date`);


-- === settings tables ========================================================
-- Looked up by label on every page load.
-- advanced_info.label is a LONGTEXT, so it needs a prefix length.
ALTER TABLE `company_info`  ADD INDEX IF NOT EXISTS `idx_company_info_label_status` (`label`, `status`);
ALTER TABLE `advanced_info` ADD INDEX IF NOT EXISTS `idx_advanced_info_label`       (`label`(100));


-- === order_items ============================================================
ALTER TABLE `order_items` ADD INDEX IF NOT EXISTS `idx_order_items_ucode` (`u_code`);


-- === users: drop redundant indexes ==========================================
-- Each of these duplicates an existing unique key or the left-most prefix of a
-- composite index, so they cost write time on every INSERT/UPDATE (i.e. every
-- registration) and buy nothing on reads.
--
--   idx_sponsor_id   -> already covered by idx_sponsor_id_username
--   idx_unique_name  -> duplicate of users_unique_name_unique
--   idx_pan          -> duplicate of users_pan_unique
--   idx_aadhaar      -> duplicate of users_aadhaar_unique
--   idx_mobile       -> duplicate of users_mobile_index
--
-- idx_username_prefix is KEPT: there is no unique key on username.
ALTER TABLE `users` DROP INDEX IF EXISTS `idx_sponsor_id`;
ALTER TABLE `users` DROP INDEX IF EXISTS `idx_unique_name`;
ALTER TABLE `users` DROP INDEX IF EXISTS `idx_pan`;
ALTER TABLE `users` DROP INDEX IF EXISTS `idx_aadhaar`;
ALTER TABLE `users` DROP INDEX IF EXISTS `idx_mobile`;


-- === record the migration ===================================================
-- Tells Laravel this migration is already applied, so a later
-- `php artisan migrate` skips it instead of failing.
--
-- The row is built in a one-row derived table so the WHERE can actually filter
-- it out on a re-run. (A plain `SELECT MAX(batch) ... WHERE NOT EXISTS` would
-- still return one row, because an aggregate with no GROUP BY always does - and
-- would insert a duplicate every time.)
INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.`m`, t.`b`
FROM (
    SELECT
        '2026_09_11_120000_add_performance_indexes' AS `m`,
        (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM (SELECT `batch` FROM `migrations`) b) AS `b`
) t
WHERE NOT EXISTS (
    SELECT 1 FROM (SELECT `migration` FROM `migrations`) x
    WHERE x.`migration` = t.`m`
);
