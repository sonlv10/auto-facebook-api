-- Add new column auto_facebook_api
ALTER TABLE `auto_facebook_api`.`users`
ADD COLUMN `params` json NULL AFTER `fb_access_token`;