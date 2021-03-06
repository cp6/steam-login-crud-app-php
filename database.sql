/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

CREATE DATABASE IF NOT EXISTS `steam_login` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `steam_login`;

CREATE TABLE IF NOT EXISTS `accounts`
(
    `uid`             char(8)      NOT NULL DEFAULT '',
    `steam_id`        varchar(124) NOT NULL,
    `username`        varchar(64)           DEFAULT NULL,
    `key`             char(32)     NOT NULL DEFAULT '',
    `first_login`     datetime              DEFAULT current_timestamp(),
    `last_login`      datetime              DEFAULT NULL,
    `times_logged_in` int(11)      NOT NULL DEFAULT 0,
    `email`           varchar(255)          DEFAULT NULL,
    PRIMARY KEY (`uid`) USING BTREE,
    UNIQUE KEY `steam_id_key` (`steam_id`),
    UNIQUE KEY `user_key_index` (`key`),
    UNIQUE KEY `email_index` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES = IFNULL(@OLD_SQL_NOTES, 1) */;
