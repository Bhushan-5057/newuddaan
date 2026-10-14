-- ---------------------------------------------------------------------------
-- My Profile: member-editable personal and nominee fields
--
-- Equivalent to the Laravel migration
--   database/migrations/2026_09_12_100000_add_profile_fields_to_users_table.php
--
-- Run this ONLY if you cannot run `php artisan migrate --force` on the server.
-- The last statement marks the migration applied so artisan will not repeat it.
--
-- Safe to run more than once: every statement uses IF NOT EXISTS
-- (MariaDB 10.0+). Existing rows are untouched - every column is NULLable with
-- no default, so nothing changes for members who have not filled these in.
--
-- NOTE: nothing here duplicates KYC. PAN, Aadhaar, address and the bank block
-- stay in `kyc_details` and are shown read-only on the profile page.
-- ---------------------------------------------------------------------------


-- === personal ===============================================================
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `middle_name`          VARCHAR(255) NULL AFTER `first_name`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `date_of_birth`        DATE         NULL AFTER `gender`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `marriage_anniversary` DATE         NULL AFTER `date_of_birth`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `profession`           VARCHAR(100) NULL AFTER `marriage_anniversary`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nationality`          VARCHAR(100) NULL AFTER `profession`;


-- === address ================================================================
-- `state` and `city` already exist; these complete the set.
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `country`  VARCHAR(100) NULL AFTER `state`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `district` VARCHAR(100) NULL AFTER `city`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `pin_code` VARCHAR(10)  NULL AFTER `district`;


-- === extra contact numbers ==================================================
-- `mobile` already exists and stays the primary (login/OTP) number.
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `alternate_mobile` VARCHAR(20) NULL AFTER `mobile`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `std_code`         VARCHAR(10) NULL AFTER `alternate_mobile`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone_number`     VARCHAR(20) NULL AFTER `std_code`;


-- === nominee ================================================================
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_title`       VARCHAR(20)  NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_first_name`  VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_middle_name` VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_last_name`   VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_gender`      VARCHAR(10)  NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_email`       VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_mobile`      VARCHAR(20)  NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_dob`         DATE         NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `nominee_relation`    VARCHAR(100) NULL;


-- === record the migration ===================================================
-- Tells Laravel this migration is already applied, so a later
-- `php artisan migrate` skips it instead of trying to add the columns again.
--
-- Built in a one-row derived table so the WHERE can actually filter it out on a
-- re-run (an aggregate with no GROUP BY always returns a row, which would
-- insert a duplicate every time).
INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.`m`, t.`b`
FROM (
    SELECT
        '2026_09_12_100000_add_profile_fields_to_users_table' AS `m`,
        (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM (SELECT `batch` FROM `migrations`) b) AS `b`
) t
WHERE NOT EXISTS (
    SELECT 1 FROM (SELECT `migration` FROM `migrations`) x
    WHERE x.`migration` = t.`m`
);
