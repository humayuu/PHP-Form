/*
Navicat MySQL Data Transfer

Source Server         : Home
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : php_form_db

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-08-12 09:58:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `brand_tbl`
-- ----------------------------
DROP TABLE IF EXISTS `brand_tbl`;
CREATE TABLE `brand_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) DEFAULT NULL,
  `brand_description` text DEFAULT NULL,
  `brand_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of brand_tbl
-- ----------------------------

-- ----------------------------
-- Table structure for `category_tbl`
-- ----------------------------
DROP TABLE IF EXISTS `category_tbl`;
CREATE TABLE `category_tbl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) DEFAULT NULL,
  `category_description` text DEFAULT NULL,
  `category_slug` varchar(100) DEFAULT NULL,
  `category_status` varchar(255) DEFAULT NULL,
  `created_at` time DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of category_tbl
-- ----------------------------

-- ----------------------------
-- Table structure for `product_tbl`
-- ----------------------------
DROP TABLE IF EXISTS `product_tbl`;
CREATE TABLE `product_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `product_stock` varchar(255) NOT NULL,
  `product_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of product_tbl
-- ----------------------------

-- ----------------------------
-- Table structure for `sub_category_tbl`
-- ----------------------------
DROP TABLE IF EXISTS `sub_category_tbl`;
CREATE TABLE `sub_category_tbl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `sub_category_name` varchar(255) NOT NULL,
  `sub_category_description` text DEFAULT NULL,
  `sub_category_slug` varchar(255) NOT NULL,
  `sub_category_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sub_category_slug` (`sub_category_slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `sub_category_tbl_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sub_category_tbl
-- ----------------------------
