-- ============================================================================
-- MySQL initial setup for Bagisto internal database
-- Executed only on first MySQL initialization (empty /var/lib/mysql)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `bagisto`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'bagisto'@'127.0.0.1'
    IDENTIFIED WITH mysql_native_password BY 'bagisto';

CREATE USER IF NOT EXISTS 'bagisto'@'localhost'
    IDENTIFIED WITH mysql_native_password BY 'bagisto';

GRANT ALL PRIVILEGES ON `bagisto`.* TO 'bagisto'@'127.0.0.1';
GRANT ALL PRIVILEGES ON `bagisto`.* TO 'bagisto'@'localhost';

FLUSH PRIVILEGES;
