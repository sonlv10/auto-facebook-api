-- Add column fb_access_token in table users
ALTER TABLE `auto_facebook_api`.`users`
ADD COLUMN `fb_access_token` varchar(512) NULL AFTER `remember_token`;