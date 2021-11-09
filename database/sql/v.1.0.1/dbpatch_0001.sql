-- Add new column auto_facebook_api
ALTER TABLE `auto_facebook_api`.`facebook_users`
ADD COLUMN `email` varchar(255) NOT NULL AFTER `id`,
ADD COLUMN `password` varchar(255) NOT NULL AFTER `email`,
ADD COLUMN `secret` varchar(255) NULL AFTER `password`,
ADD COLUMN `proxy_id` int NULL AFTER `updated_at`,
MODIFY COLUMN `fb_uid` varchar(255) NULL AFTER `secret`;

-- Add new table proxy_list
DROP TABLE IF EXISTS `jos_proxy_list`;
CREATE TABLE `jos_proxy_list` (
`id` int unsigned NOT NULL AUTO_INCREMENT,
`host` varchar(255) NOT NULL,
`port` varchar(255) DEFAULT NULL,
`user_name` varchar(50) DEFAULT NULL,
`password` varchar(50) DEFAULT NULL,
`type` varchar(11) DEFAULT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`)
);