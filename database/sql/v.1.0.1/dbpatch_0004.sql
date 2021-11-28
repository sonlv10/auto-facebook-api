-- Add new column auto_facebook_api
ALTER TABLE `facebook_users` ADD COLUMN `user_id` int NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `jos_proxy_list` ADD COLUMN `user_id` int NOT NULL DEFAULT 0 AFTER `id`;