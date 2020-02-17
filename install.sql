-- Creating database...
CREATE DATABASE IF NOT EXISTS `apiv1`;

-- Creating tables

-- users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `age` INT NOT NULL,
    `job` VARCHAR(255) NOT NULL
);

-- inserting users...
INSERT INTO `users` (`id`, `name`, `age`, `job`) VALUES
    (1, 'Yuu Hirokabe', 18, 'Programmer'),
    (2, 'CrackX', 17, 'Cryptographer'),
    (3, 'K4LI L1nux', 16, 'Hacker')