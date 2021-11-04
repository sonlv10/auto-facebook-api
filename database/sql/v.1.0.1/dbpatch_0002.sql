-- Add new column auto_facebook_api
ALTER TABLE `auto_facebook_api`.`facebook_users`
ADD COLUMN `check_point` tinyint(1) NULL DEFAULT 0 AFTER `proxy_id`;