-- ============================================================================
-- MariaDB initial setup for the Bagisto internal database.
--
-- Executed once during the image build, against a freshly initialised data
-- directory.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `bagisto`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'bagisto'@'127.0.0.1' IDENTIFIED BY 'bagisto';

CREATE USER IF NOT EXISTS 'bagisto'@'localhost' IDENTIFIED BY 'bagisto';

GRANT ALL PRIVILEGES ON `bagisto`.* TO 'bagisto'@'127.0.0.1';
GRANT ALL PRIVILEGES ON `bagisto`.* TO 'bagisto'@'localhost';

FLUSH PRIVILEGES;
