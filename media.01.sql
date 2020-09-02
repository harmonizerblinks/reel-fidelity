CREATE DATABASE IF NOT EXISTS `reel_mediaresource`;
USE `reel_mediaresource`;


CREATE TABLE IF NOT EXISTS `resource_media_schedule`(
id int(10) unsigned not null auto_increment primary key,
fileHash varchar(64) not null,
scheduleName varchar(255) not null,
fileOrder int(10) unsigned not null,
KEY `key_filehash` (`fileHash`)
)Engine = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `resource_media_files`(
id int(10) unsigned not null auto_increment primary key,
fileHash varchar(64) not null,
fileName varchar(255) not null,
filePath varchar(255) not null,
fileType varchar(32) not null,
fileSize int(10) unsigned not null,
isVideo tinyint(1) not null default 0,
UNIQUE KEY `key_filehash` (`fileHash`)
)Engine = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `resource_media_ipaddress`(
id int(10) unsigned not null auto_increment primary key,
ipAddress varchar(64) not null,
clientName varchar(128) not null,
ipAddressHash varchar(64) not null,
UNIQUE KEY `key_iphash`(`ipAddressHash`)
)Engine = InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
  `admin_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `supper_admin` (
  `supper_admin_id` int(11) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
  `supper_admin_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `resource_media_schedule` ADD COLUMN `ipAddressHash` varchar(64) not null;
ALTER TABLE `resource_media_files` ADD COLUMN `associatedSchedules` varchar(100) not null;

ALTER TABLE `resource_media_schedule` ADD INDEX `key_iphash`(`ipAddressHash`);

