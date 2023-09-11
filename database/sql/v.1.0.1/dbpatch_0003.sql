-- Add new column auto_facebook_api
ALTER TABLE `users`
ADD COLUMN `params` json NULL AFTER `fb_access_token`;