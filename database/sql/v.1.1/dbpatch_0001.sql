-- ----------------------------
-- Table structure for facebook_users
-- ----------------------------
DROP TABLE IF EXISTS `facebook_users`;
CREATE TABLE `facebook_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fb_uid` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `avatar` varchar(512) DEFAULT NULL,
  `cookies` json DEFAULT NULL,
  `access_token` varchar(512) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)