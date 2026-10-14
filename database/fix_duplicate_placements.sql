-- ---------------------------------------------------------------------------
-- Fix: two members sharing a binary slot with someone else
--
-- A parent can hold one member per side. Where two ended up on the SAME side,
-- only the first is drawn in the tree and the second is invisible. In both
-- cases below the opposite side is empty, so the hidden member simply moves
-- there.
--
--   NU25418 (id 5877) -> was LEFT under NU25407 (id 5865), moves to RIGHT
--   NU26546 (id 7014) -> was LEFT under NU26264 (id 6731), moves to RIGHT
--
-- IMPACT ON INCOME - read before running:
--   NU25418 carries 566 downline members and 13,700 BV. Moving it shifts that
--   business from NU25407's LEFT leg to their RIGHT leg, which changes
--   NU25407's own binary matching. Uplines above NU25407 are unaffected: that
--   whole branch stays on the same side of them.
--   NU26546 is a single inactive member with 0 BV - no income impact.
--
-- Each UPDATE is guarded: it only fires if the target side is still empty and
-- the member is still on the wrong side, so re-running it does nothing.
-- ---------------------------------------------------------------------------


-- === 1. NU25418 : LEFT -> RIGHT under NU25407 ===============================
UPDATE `users` u
SET u.`position` = 2
WHERE u.`id` = 5877
  AND u.`parent_id` = 5865
  AND u.`position` = 1
  AND NOT EXISTS (
      SELECT 1 FROM (SELECT `id`, `parent_id`, `position` FROM `users`) x
      WHERE x.`parent_id` = 5865 AND x.`position` = 2
  );

-- Keep the parent's cached right_id in step with the real placement.
UPDATE `users` p
SET p.`right_id` = 5877
WHERE p.`id` = 5865
  AND EXISTS (
      SELECT 1 FROM (SELECT `id`, `parent_id`, `position` FROM `users`) x
      WHERE x.`id` = 5877 AND x.`parent_id` = 5865 AND x.`position` = 2
  );


-- === 2. NU26546 : LEFT -> RIGHT under NU26264 ===============================
UPDATE `users` u
SET u.`position` = 2
WHERE u.`id` = 7014
  AND u.`parent_id` = 6731
  AND u.`position` = 1
  AND NOT EXISTS (
      SELECT 1 FROM (SELECT `id`, `parent_id`, `position` FROM `users`) x
      WHERE x.`parent_id` = 6731 AND x.`position` = 2
  );

UPDATE `users` p
SET p.`right_id` = 7014
WHERE p.`id` = 6731
  AND EXISTS (
      SELECT 1 FROM (SELECT `id`, `parent_id`, `position` FROM `users`) x
      WHERE x.`id` = 7014 AND x.`parent_id` = 6731 AND x.`position` = 2
  );


-- === 3. repoint the parents' cached left_id ================================
-- Both parents had left_id pointing at the member that just moved to the
-- right. Point it at whoever actually occupies the left slot now.
UPDATE `users` p
SET p.`left_id` = (
    SELECT x.`id` FROM (SELECT `id`, `parent_id`, `position` FROM `users`) x
    WHERE x.`parent_id` = p.`id` AND x.`position` = 1
    ORDER BY x.`id` LIMIT 1
)
WHERE p.`id` IN (5865, 6731);


-- === CHECK ==================================================================
-- Should return two rows, both with position = 2 and the parent's right_id
-- pointing at them.
SELECT c.`id`, c.`username`, c.`parent_id`, c.`position`, p.`username` AS parent_username, p.`right_id`
FROM `users` c
JOIN `users` p ON p.`id` = c.`parent_id`
WHERE c.`id` IN (5877, 7014);


-- ---------------------------------------------------------------------------
-- ROLLBACK - puts both members back exactly as they were
-- ---------------------------------------------------------------------------
-- UPDATE `users` SET `position` = 1 WHERE `id` IN (5877, 7014);
-- UPDATE `users` SET `right_id` = NULL WHERE `id` = 5865;
-- UPDATE `users` SET `right_id` = NULL WHERE `id` = 6731;
