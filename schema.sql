-- =====================================
-- Database Setup (Optional)
-- =====================================
-- CREATE DATABASE IF NOT EXISTS your_db_name;
-- USE your_db_name;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =========================
-- Admin Table
-- =========================
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `admin` (`username`, `password`, `status`)
VALUES ('admin', MD5('12345'), 1);

-- =========================
-- Blogs Table
-- =========================
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT NOT NULL,
  `image` VARCHAR(255),
  `image_alt_tag` VARCHAR(255),
  `description` LONGTEXT NOT NULL,
  `blog_url` VARCHAR(255) NOT NULL UNIQUE,
  `meta_title` VARCHAR(255),
  `meta_desc` VARCHAR(500),
  `meta_keyword` VARCHAR(500),
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`status`)
) ENGINE=InnoDB;

-- =========================
-- Services Table
-- =========================
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT NOT NULL,
  `image` VARCHAR(255),
  `icon` VARCHAR(255),
  `image_alt_tag` VARCHAR(255),
  `description` LONGTEXT NOT NULL,
  `url` VARCHAR(255) NOT NULL UNIQUE,
  `meta_title` VARCHAR(255),
  `meta_desc` VARCHAR(500),
  `meta_keyword` VARCHAR(500),
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`status`)
) ENGINE=InnoDB;

-- =========================
-- Appointments Table
-- =========================
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `treatment` VARCHAR(255) NOT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `message` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`email`)
) ENGINE=InnoDB;

-- =========================
-- Gallery Table
-- =========================
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `short_description` TEXT,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`status`)
) ENGINE=InnoDB;

-- =========================
-- Contacts Table
-- =========================
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `services` VARCHAR(255),
  `message` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`email`)
) ENGINE=InnoDB;

-- =========================
-- Testimonials Table
-- =========================
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `comment` TEXT NOT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`status`)
) ENGINE=InnoDB;

-- =========================
-- Videos Table
-- =========================
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `video_url` VARCHAR(500) NOT NULL,
  `short_description` TEXT,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`status`)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;