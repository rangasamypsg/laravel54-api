-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 09, 2018 at 05:07 PM
-- Server version: 5.6.38
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zoin_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(12) UNSIGNED ZEROFILL NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `postcode` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `address`, `city`, `postcode`) VALUES
(000000000001, '24 street cbe', 'cbe', NULL),
(000000000002, 'Gandhipuram, Coimbatore', 'Coimbatore', NULL),
(000000000003, 'Gandhipuram, Coimbatore', 'Coimbatore', NULL),
(000000000004, 'Gandhipuram, Coimbatore', 'Coimbatore', NULL),
(000000000005, 'Gandhipuram, Coimbatore', 'Coimbatore', NULL),
(000000000006, 'gandhipuram', 'Coimbatore', NULL),
(000000000007, 'Gandhipuram', 'Coimbatore', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `email`, `password`) VALUES
(1, 'admin@zoin.in', 'ff9f92cf2804120fac283f7f1dd43766');

-- --------------------------------------------------------

--
-- Table structure for table `business_rule`
--

CREATE TABLE `business_rule` (
  `business_type` varchar(100) NOT NULL,
  `max_loyalty_amount` int(8) NOT NULL,
  `zoin_points` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `business_rule`
--

INSERT INTO `business_rule` (`business_type`, `max_loyalty_amount`, `zoin_points`) VALUES
('Restaurants', 2500, 250),
('Restaurants', 2000, 200),
('Restaurants', 1500, 150),
('Restaurants', 1000, 100),
('Restaurants', 500, 50);

-- --------------------------------------------------------

--
-- Table structure for table `business_types`
--

CREATE TABLE `business_types` (
  `id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `business_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `business_types`
--

INSERT INTO `business_types` (`id`, `business_type`) VALUES
(001, 'Restaurant');

-- --------------------------------------------------------

--
-- Table structure for table `checkin_limit`
--

CREATE TABLE `checkin_limit` (
  `maximum_checkin_available` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `checkin_limit`
--

INSERT INTO `checkin_limit` (`maximum_checkin_available`) VALUES
(99);

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `mobile_number` varchar(12) NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `user_type` varchar(3) DEFAULT NULL,
  `is_mobile_verified` int(1) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`mobile_number`, `password`, `user_type`, `is_mobile_verified`) VALUES
('8248014315', NULL, 'V', 1),
('9566309844', NULL, 'V', 1),
('9566425554', NULL, 'V', 1),
('9600402030', NULL, 'V', 1),
('9655926263', NULL, 'V', 1),
('9789108964', NULL, 'V', 1),
('9894571615', NULL, 'v', 1);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty`
--

CREATE TABLE `loyalty` (
  `id` int(12) UNSIGNED ZEROFILL NOT NULL,
  `loyalty_id` varchar(28) NOT NULL,
  `loyalty_status` enum('Created','Inactive','Open','Closed','Denied') DEFAULT 'Created',
  `max_checkin` int(2) UNSIGNED DEFAULT NULL,
  `max_bill_amount` int(8) UNSIGNED DEFAULT NULL,
  `offer_type` varchar(50) DEFAULT NULL,
  `zoin_point` varchar(50) NOT NULL,
  `description` text,
  `loyalty_pics_path` varchar(500) DEFAULT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loyalty`
--

INSERT INTO `loyalty` (`id`, `loyalty_id`, `loyalty_status`, `max_checkin`, `max_bill_amount`, `offer_type`, `zoin_point`, `description`, `loyalty_pics_path`, `vendor_id`, `created_at`, `updated_at`) VALUES
(000000000007, 'ZLY007', 'Open', 1, 1000, 'zoin', '100', 'Lorem ipsum dolor sit er elit lamet, consectetaur cillium adipisicing pecu, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation', NULL, 'ZVR003', '2018-02-06 06:03:56', '2018-02-06 06:31:18'),
(000000000025, 'ZLY025', 'Open', 2, 500, NULL, '50', 'hi', NULL, 'ZVR001', '2018-02-08 05:44:16', '2018-02-08 05:44:16'),
(000000000027, 'ZLY027', 'Open', 3, 1000, NULL, '1000', 'Lorem ipsum dolor sit er elit lamet, consectetaur cillium adipisicing pecu, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation', NULL, 'ZVR002', '2018-02-08 05:56:53', '2018-02-08 05:56:53'),
(000000000028, 'ZLY028', 'Open', 3, 1000, NULL, '500', 'Lorem ipsum dolor sit er elit lamet, consectetaur cillium adipisicing pecu, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation', NULL, 'ZVR007', '2018-02-08 06:14:44', '2018-02-08 06:15:20');

--
-- Triggers `loyalty`
--
DELIMITER $$
CREATE TRIGGER `loyalty_auto_id` BEFORE INSERT ON `loyalty` FOR EACH ROW SET NEW.loyalty_id = CONCAT("ZLY",LPAD((SELECT AUTO_INCREMENT 
       FROM information_schema.TABLES 
       WHERE TABLE_SCHEMA = DATABASE() AND 
       TABLE_NAME = 'loyalty'), 3, '0'))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_balance`
--

CREATE TABLE `loyalty_balance` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) DEFAULT NULL,
  `user_id` varchar(25) DEFAULT NULL,
  `total_loyalty` int(8) NOT NULL DEFAULT '0',
  `claimed_loyalty` int(8) DEFAULT '0',
  `user_balance` float(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loyalty_balance`
--

INSERT INTO `loyalty_balance` (`id`, `vendor_id`, `user_id`, `total_loyalty`, `claimed_loyalty`, `user_balance`) VALUES
(1, 'ZVR002', NULL, 17, 0, NULL),
(2, 'ZVR001', NULL, 5, 2, NULL),
(3, 'ZVR003', NULL, 4, 2, NULL),
(4, 'ZVR006', NULL, 1, 0, NULL),
(5, NULL, 'ZUR002', 1, 2, 0.00),
(6, NULL, 'ZUR001', 1, 2, 10.00),
(7, 'ZVR007', NULL, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_bank_details`
--

CREATE TABLE `merchant_bank_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `gst_number` varchar(25) NOT NULL,
  `author_number` varchar(25) NOT NULL,
  `pan_number` varchar(25) NOT NULL,
  `ifsc_code` varchar(25) NOT NULL,
  `account_number` varchar(25) NOT NULL,
  `account_name` varchar(150) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `bank_address` text NOT NULL,
  `account_type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_bank_details`
--

INSERT INTO `merchant_bank_details` (`id`, `vendor_id`, `gst_number`, `author_number`, `pan_number`, `ifsc_code`, `account_number`, `account_name`, `bank_name`, `bank_address`, `account_type`) VALUES
(1, 'ZVR001', 'GST-123', '4321421432142314', 'AOW348438', 'UTIB0008701', '34552324253254323', 'Rangasamy', 'SBI', 'coimbatore', 'Personal');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_details`
--

CREATE TABLE `merchant_details` (
  `id` int(9) UNSIGNED ZEROFILL NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `email_id` varchar(150) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `mobile_number` varchar(12) NOT NULL,
  `address_id` int(12) NOT NULL,
  `is_email_verified` int(1) UNSIGNED ZEROFILL NOT NULL,
  `confirmation_code` varchar(250) DEFAULT NULL,
  `profile_pic_path` varchar(500) DEFAULT NULL,
  `business_type` int(3) NOT NULL,
  `location` varchar(80) DEFAULT NULL,
  `merchant_level` int(2) UNSIGNED ZEROFILL NOT NULL,
  `is_admin_approved` int(1) UNSIGNED ZEROFILL NOT NULL,
  `is_login_approved` tinyint(3) NOT NULL DEFAULT '0',
  `description` text,
  `website` varchar(250) DEFAULT NULL,
  `start_time` varchar(12) DEFAULT NULL,
  `end_time` varchar(12) DEFAULT NULL,
  `closed` varchar(250) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_details`
--

INSERT INTO `merchant_details` (`id`, `vendor_id`, `company_name`, `email_id`, `contact_person`, `mobile_number`, `address_id`, `is_email_verified`, `confirmation_code`, `profile_pic_path`, `business_type`, `location`, `merchant_level`, `is_admin_approved`, `is_login_approved`, `description`, `website`, `start_time`, `end_time`, `closed`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(000000001, 'ZVR001', 'sachin Restaurant', 'tamil@thegang.in', 'sachin', '9894571615', 1, 0, NULL, NULL, 1, '0', 01, 1, 1, 'http://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.php', 'http://localhost/adminzoin/raw/add_merchant.php', '9:00 AM', '9:50 PM', 'Sunday', '12.21212', '21.222222', '2018-02-05 04:56:37', '2018-02-06 06:31:18'),
(000000002, 'ZVR002', 'Ds NV centre', 'karthick@thegang.in', 'Karthick', '9566425554', 2, 0, NULL, NULL, 1, '0', 01, 1, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'https://www.w3schools.com/php/php_file_upload.asp\r\n', '12:30 AM\r\n', '12:30 AM\r\n', 'Tuesday,Wednesday\r\n', '1.33', '2.33', '2018-02-05 04:59:59', '2018-02-08 06:09:33'),
(000000003, 'ZVR003', 'PH Biriyani Center', 'pragdeesh@gmail.com', 'Pragdeesh', '9566309844', 3, 0, NULL, NULL, 1, '0', 01, 1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'https://www.bobgaly.com', '6:00 AM', '5:00 PM', 'Monday,Tuesday', '23.2323', '23.2332', '2018-02-05 05:11:33', '2018-02-08 05:53:37'),
(000000004, 'ZVR004', 'st chicken', 'sti@gmail.com', 'st', '8248014315', 4, 0, NULL, NULL, 1, '0', 01, 1, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'http://localhost/adminzoin/raw/add_merchant.php', '4:00 AM', '5:00 PM', 'Friday,Saturday', '12.1212', '12.12121', '2018-02-05 06:48:52', '2018-02-06 01:59:42'),
(000000005, 'ZVR005', 'Ranga Canteen', 'ranga@thegang.in', 'Ranga', '9789108964', 5, 0, NULL, NULL, 1, '0', 01, 1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'http://localhost/adminzoin/raw/add_merchant.php', '9:00 AM', '9:50 PM', 'Friday,Saturday', '12.123', '12.123', '2018-02-05 23:18:03', '2018-02-06 00:33:37'),
(000000006, 'ZVR006', 'Happy', 'anand@thegang.in', 'Jsc Anand', '9655926263', 6, 0, NULL, NULL, 1, '0', 01, 1, 0, 'http://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.phphttp://localhost/adminzoin/raw/add_merchant.php', 'http://localhost/adminzoin/raw/add_merchant.php', '9:00 AM', '9:50 PM', 'Tuesday,Wednesday', '675', '2.121', '2018-02-06 02:13:56', '2018-02-06 07:27:03'),
(000000007, 'ZVR007', 'vim company', 'vim@gmail.com', 'Vimal', '9600402030', 7, 0, NULL, NULL, 1, '0', 01, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-08 06:11:19', '2018-02-08 06:49:43');

--
-- Triggers `merchant_details`
--
DELIMITER $$
CREATE TRIGGER `vendor_auto_id` BEFORE INSERT ON `merchant_details` FOR EACH ROW SET NEW.vendor_id = CONCAT("ZVR",LPAD((SELECT AUTO_INCREMENT 
       FROM information_schema.TABLES 
       WHERE TABLE_SCHEMA = DATABASE() AND 
       TABLE_NAME = 'merchant_details'), 3, '0'))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `merchant_feature_details`
--

CREATE TABLE `merchant_feature_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `feature_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `merchant_feature_details`
--

INSERT INTO `merchant_feature_details` (`id`, `vendor_id`, `feature_id`) VALUES
(1, 'ZVR001', 8),
(2, 'ZVR001', 4),
(3, 'ZVR001', 7),
(4, 'ZVR001', 2),
(5, 'ZVR001', 8),
(6, 'ZVR001', 6),
(7, 'ZVR001', 5),
(8, 'ZVR002', 1),
(9, 'ZVR002', 2),
(10, 'ZVR002', 3),
(11, 'ZVR002', 4),
(12, 'ZVR002', 5),
(13, 'ZVR002', 6),
(14, 'ZVR002', 7),
(15, 'ZVR002', 8),
(16, 'ZVR003', 1),
(17, 'ZVR003', 2),
(18, 'ZVR003', 3),
(19, 'ZVR003', 4),
(20, 'ZVR003', 5),
(21, 'ZVR001', 1),
(22, 'ZVR001', 5),
(23, 'ZVR001', 7),
(24, 'ZVR001', 8),
(25, 'ZVR001', 9),
(26, 'ZVR001', 11),
(27, 'ZVR001', 13),
(28, 'ZVR001', 14),
(29, 'ZVR001', 1),
(30, 'ZVR001', 5),
(31, 'ZVR001', 7),
(32, 'ZVR001', 8),
(33, 'ZVR001', 9),
(34, 'ZVR001', 11),
(35, 'ZVR001', 13),
(36, 'ZVR001', 14),
(37, 'ZVR004', 1),
(38, 'ZVR004', 5),
(39, 'ZVR004', 7),
(40, 'ZVR004', 8),
(41, 'ZVR004', 9),
(42, 'ZVR004', 11),
(43, 'ZVR004', 13),
(44, 'ZVR004', 14),
(87, 'ZVR004', 12),
(96, 'ZVR005', 1),
(97, 'ZVR005', 5),
(98, 'ZVR005', 8),
(99, 'ZVR005', 9),
(100, 'ZVR005', 10),
(101, 'ZVR005', 12),
(102, 'ZVR005', 13),
(103, 'ZVR005', 1),
(104, 'ZVR005', 5),
(105, 'ZVR005', 8),
(106, 'ZVR005', 9),
(107, 'ZVR005', 10),
(108, 'ZVR005', 12),
(109, 'ZVR005', 13),
(182, 'ZVR006', 3),
(183, 'ZVR006', 5),
(184, 'ZVR006', 7),
(185, 'ZVR006', 8),
(186, 'ZVR006', 9),
(187, 'ZVR006', 10),
(188, 'ZVR006', 14),
(189, 'ZVR006', 3),
(190, 'ZVR006', 5),
(191, 'ZVR006', 7),
(192, 'ZVR006', 8),
(193, 'ZVR006', 9),
(194, 'ZVR006', 10),
(195, 'ZVR006', 14),
(196, 'ZVR006', 3),
(197, 'ZVR006', 5),
(198, 'ZVR006', 7),
(199, 'ZVR006', 8),
(200, 'ZVR006', 9),
(201, 'ZVR006', 10),
(202, 'ZVR006', 14),
(203, 'ZVR006', 3),
(204, 'ZVR006', 5),
(205, 'ZVR006', 7),
(206, 'ZVR006', 8),
(207, 'ZVR006', 9),
(208, 'ZVR006', 10),
(209, 'ZVR006', 14),
(210, 'ZVR006', 3),
(211, 'ZVR006', 5),
(212, 'ZVR006', 7),
(213, 'ZVR006', 8),
(214, 'ZVR006', 9),
(215, 'ZVR006', 10),
(216, 'ZVR006', 14),
(217, 'ZVR006', 3),
(218, 'ZVR006', 5),
(219, 'ZVR006', 7),
(220, 'ZVR006', 8),
(221, 'ZVR006', 9),
(222, 'ZVR006', 10),
(223, 'ZVR006', 14),
(224, 'ZVR006', 3),
(225, 'ZVR006', 5),
(226, 'ZVR006', 7),
(227, 'ZVR006', 8),
(228, 'ZVR006', 9),
(229, 'ZVR006', 10),
(230, 'ZVR006', 14),
(231, 'ZVR006', 3),
(232, 'ZVR006', 5),
(233, 'ZVR006', 7),
(234, 'ZVR006', 8),
(235, 'ZVR006', 9),
(236, 'ZVR006', 10),
(237, 'ZVR006', 14),
(238, 'ZVR006', 3),
(239, 'ZVR006', 5),
(240, 'ZVR006', 7),
(241, 'ZVR006', 8),
(242, 'ZVR006', 9),
(243, 'ZVR006', 10),
(244, 'ZVR006', 14),
(245, 'ZVR001', 1),
(246, 'ZVR001', 4),
(247, 'ZVR001', 7),
(248, 'ZVR001', 8),
(249, 'ZVR001', 10),
(250, 'ZVR001', 12),
(251, 'ZVR001', 13),
(252, 'ZVR006', 1),
(253, 'ZVR006', 6),
(254, 'ZVR006', 7),
(255, 'ZVR006', 9),
(256, 'ZVR006', 10),
(257, 'ZVR006', 11),
(258, 'ZVR006', 14),
(259, 'ZVR006', 1),
(260, 'ZVR006', 6),
(261, 'ZVR006', 7),
(262, 'ZVR006', 9),
(263, 'ZVR006', 10),
(264, 'ZVR006', 11),
(265, 'ZVR006', 14),
(266, 'ZVR006', 1),
(267, 'ZVR006', 6),
(268, 'ZVR006', 7),
(269, 'ZVR006', 9),
(270, 'ZVR006', 10),
(271, 'ZVR006', 11),
(272, 'ZVR006', 14),
(273, 'ZVR006', 1),
(274, 'ZVR006', 6),
(275, 'ZVR006', 7),
(276, 'ZVR006', 9),
(277, 'ZVR006', 10),
(278, 'ZVR006', 11),
(279, 'ZVR006', 14),
(280, 'ZVR006', 1),
(281, 'ZVR006', 6),
(282, 'ZVR006', 7),
(283, 'ZVR006', 9),
(284, 'ZVR006', 10),
(285, 'ZVR006', 11),
(286, 'ZVR006', 14),
(287, 'ZVR006', 1),
(288, 'ZVR006', 6),
(289, 'ZVR006', 7),
(290, 'ZVR006', 9),
(291, 'ZVR006', 10),
(292, 'ZVR006', 11),
(293, 'ZVR006', 14);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_feature_images`
--

CREATE TABLE `merchant_feature_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `feature_type` varchar(25) NOT NULL,
  `feature_name` varchar(255) NOT NULL,
  `feature_image` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `merchant_feature_images`
--

INSERT INTO `merchant_feature_images` (`id`, `feature_type`, `feature_name`, `feature_image`) VALUES
(1, 'food', 'veg', 'http://zoin.in/feature_image/veg.png'),
(2, 'food', 'Non Veg', 'http://zoin.in/feature_image/veg-nonveg.png'),
(3, 'food', 'veg/Non veg', 'http://zoin.in/feature_image/veg-nonveg.png'),
(4, 'room', 'Ac', 'http://zoin.in/feature_image/ac.png'),
(5, 'room', 'Non Ac', 'http://zoin.in/feature_image/nonac.png'),
(6, 'room', 'Ac/Non A/c', 'http://zoin.in/feature_image/ac-nonac.png'),
(7, 'card_payment', 'Card Payment', 'http://zoin.in/feature_image/card.png'),
(8, 'wifi', 'wifi', 'http://zoin.in/feature_image/wifi.png'),
(9, 'rest_room', 'Rest Room', 'http://zoin.in/feature_image/restroom.png'),
(10, 'self_services', 'Self Services', 'http://zoin.in/feature_image/selfservice.png'),
(11, 'parking', 'Parking', 'http://zoin.in/feature_image/parking.png'),
(12, 'disabled_access', 'Disabled Access', 'http://zoin.in/feature_image/access.png'),
(13, 'cctv', 'cctv', 'http://zoin.in/feature_image/cctv.png'),
(14, 'alcohol_serving', 'Alcohol Serving', 'http://zoin.in/feature_image/bar.png');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_images`
--

CREATE TABLE `merchant_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `profile_image` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_images`
--

INSERT INTO `merchant_images` (`id`, `vendor_id`, `profile_image`) VALUES
(1, 'ZVR002', 'http://zoin.in/adminpanel/database/uploads/R5.jpg'),
(2, 'ZVR002', 'http://zoin.in/adminpanel/database/uploads/R4.jpg'),
(3, 'ZVR002', 'http://zoin.in/adminpanel/database/uploads/R3.jpg'),
(4, 'ZVR002', 'http://zoin.in/adminpanel/database/uploads/R2.jpg'),
(5, 'ZVR002', 'http://zoin.in/adminpanel/database/uploads/R1.jpg'),
(6, 'ZVR003', 'http://zoin.in/adminpanel/database/uploads/R1.jpg'),
(7, 'ZVR003', 'http://zoin.in/adminpanel/database/uploads/R2.jpg'),
(8, 'ZVR003', 'http://zoin.in/adminpanel/database/uploads/R3.jpg'),
(9, 'ZVR003', 'http://zoin.in/adminpanel/database/uploads/R4.jpg'),
(10, 'ZVR003', 'http://zoin.in/adminpanel/database/uploads/R5.jpg'),
(11, 'ZVR001', 'http://zoin.in/adminpanel/database/uploads/R1.jpg'),
(12, 'ZVR001', 'http://zoin.in/adminpanel/database/uploads/R2.jpg'),
(13, 'ZVR001', 'http://zoin.in/adminpanel/database/uploads/R3.jpg'),
(14, 'ZVR001', 'http://zoin.in/adminpanel/database/uploads/R4.jpg'),
(15, 'ZVR001', 'http://zoin.in/adminpanel/database/uploads/R5.jpg'),
(60, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517918886Fullsize-Damai.jpg'),
(61, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517918886sumatran_tiger_2.jpg'),
(62, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517918886Sumatran-Tiger-Hero.jpg'),
(66, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517919272Fullsize-Damai.jpg'),
(67, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517919272sumatran_tiger_2.jpg'),
(68, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517919272Sumatran-Tiger-Hero.jpg'),
(69, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921267Fullsize-Damai.jpg'),
(70, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921267sumatran_tiger_2.jpg'),
(71, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921267Sumatran-Tiger-Hero.jpg'),
(72, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921367Fullsize-Damai.jpg'),
(73, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921367sumatran_tiger_2.jpg'),
(74, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921367Sumatran-Tiger-Hero.jpg'),
(75, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921823Fullsize-Damai.jpg'),
(76, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921823sumatran_tiger_2.jpg'),
(77, 'ZVR006', 'http://zoin.in/adminpanel/admin/database/images/1517921823Sumatran-Tiger-Hero.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_social_media`
--

CREATE TABLE `merchant_social_media` (
  `id` int(11) NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `social_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_social_media`
--

INSERT INTO `merchant_social_media` (`id`, `vendor_id`, `social_name`) VALUES
(3, 'ZVR003', 'http://zoin.in/adminpanel/admin/view_merchant.php'),
(4, 'ZVR003', 'http://zoin.in/adminpanel/admin/view_merchant.php'),
(5, 'ZVR004', 'http://localhost/adminzoin/raw/add_merchant.php'),
(6, 'ZVR004', 'http://localhost/adminzoin/raw/add_merchant.php'),
(7, 'ZVR004', 'http://localhost/adminzoin/raw/add_merchant.php'),
(42, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(43, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(44, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(45, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(46, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(47, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(48, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(49, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(50, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(51, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(52, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(53, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(54, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(55, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(56, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(57, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(58, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(59, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(60, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(61, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(62, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(63, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(64, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(65, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(66, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(67, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(68, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(69, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(70, 'ZVR001', 'http://localhost/adminzoin/raw/add_merchant.php'),
(71, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(72, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(73, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(74, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(75, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(76, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(77, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(78, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(79, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(80, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(81, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php'),
(82, 'ZVR006', 'http://localhost/adminzoin/raw/add_merchant.php');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_status`
--

CREATE TABLE `merchant_status` (
  `id` int(11) UNSIGNED NOT NULL,
  `status_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merchant_status`
--

INSERT INTO `merchant_status` (`id`, `status_name`) VALUES
(1, 'Approved'),
(2, 'Un Approved'),
(3, 'Pending'),
(4, 'Block');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_tags`
--

CREATE TABLE `merchant_tags` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_otp`
--

CREATE TABLE `mobile_otp` (
  `mobile_number` varchar(12) NOT NULL,
  `otp` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mobile_otp`
--

INSERT INTO `mobile_otp` (`mobile_number`, `otp`) VALUES
('9585537309', '5988');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` varchar(28) NOT NULL,
  `subject_id` varchar(25) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  `message` text NOT NULL,
  `amount` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `subject_id`, `image`, `message`, `amount`, `created_at`) VALUES
(1, 'ZVR001', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-05 04:59:48'),
(2, 'ZVR002', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-05 05:01:24'),
(3, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 05:01:53'),
(4, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 05:02:01'),
(5, 'ZVR002', 'ZLY001', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-05 05:02:15'),
(6, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 05:04:35'),
(7, 'ZVR002', 'ZLY001', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty Status Was changed to Inactive successfully.', '', '2018-02-05 05:07:30'),
(8, 'ZVR002', 'ZLY001', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-05 05:08:04'),
(9, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-05 05:08:33'),
(10, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-05 05:14:34'),
(11, 'ZVR001', 'ZLY002', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-05 05:14:38'),
(12, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 05:15:46'),
(13, 'ZVR001', 'ZLY002', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-05 05:15:55'),
(14, 'ZVR003', 'ZLY003', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-05 05:16:23'),
(15, 'ZVR003', 'ZLY003', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty Status Was changed to Inactive successfully.', '', '2018-02-05 05:16:52'),
(16, 'ZVR001', 'ZLY002', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-05 05:20:48'),
(17, 'ZVR003', 'ZLY003', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-05 05:21:46'),
(18, 'ZVR003', 'ZLY003', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-05 05:22:00'),
(19, 'ZVR001', 'ZLY002', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-05 05:22:11'),
(20, 'ZVR004', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-05 06:52:58'),
(21, 'ZVR004', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 06:53:55'),
(22, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:08:19'),
(23, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:08:32'),
(24, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:16:26'),
(25, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:17:45'),
(26, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:18:39'),
(27, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:24:23'),
(28, 'ZVR004', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-05 07:29:55'),
(29, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 07:30:24'),
(30, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'Edit Profile updated', NULL, '2018-02-05 07:35:01'),
(31, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-05 23:05:39'),
(32, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 23:13:49'),
(33, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-05 23:14:04'),
(34, 'ZVR005', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-05 23:18:59'),
(35, 'ZVR005', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 23:20:17'),
(36, 'ZVR005', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-05 23:29:26'),
(37, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 23:30:09'),
(38, 'ZUR001', '1733', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, '2018-02-05 23:40:38'),
(39, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-05 23:54:14'),
(40, 'ZVR005', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-05 23:55:16'),
(41, 'ZVR005', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 00:33:37'),
(42, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 00:36:02'),
(43, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 00:36:54'),
(44, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 01:00:01'),
(45, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 01:06:37'),
(46, 'ZVR005', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 01:07:19'),
(47, 'ZVR002', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:25:33'),
(48, 'ZVR002', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:25:38'),
(49, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:25:42'),
(50, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:25:52'),
(51, 'ZVR003', 'ZLY003', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Created.', '', '2018-02-06 01:26:13'),
(52, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:26:27'),
(53, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:26:33'),
(54, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:28:07'),
(55, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:28:17'),
(56, 'ZVR001', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:28:54'),
(57, 'ZVR001', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:29:09'),
(58, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:31:38'),
(59, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:31:57'),
(60, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:32:15'),
(61, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:32:51'),
(62, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has was Pending Our customer support will contact you.', '', '2018-02-06 01:32:54'),
(63, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 01:33:00'),
(64, 'ZVR003', 'ZLY003', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-06 01:34:09'),
(65, 'ZVR006', '', 'http://zoin.in/feature_image/submit.png', 'Your Account was Approved.', '', '2018-02-06 02:18:31'),
(66, 'ZVR006', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 02:18:54'),
(67, 'ZVR006', 'ZLY004', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-06 02:19:15'),
(68, 'ZVR006', 'ZLY004', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-06 02:30:07'),
(69, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 02:32:13'),
(70, 'ZVR006', 'ZLY004', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 02:34:12'),
(71, 'ZVR006', 'ZLY004', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 03:29:42'),
(72, 'ZVR006', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 05:29:08'),
(73, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 05:29:23'),
(74, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 05:29:52'),
(75, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 05:30:15'),
(76, 'ZVR003', 'ZLY005', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-06 05:30:35'),
(77, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 05:40:02'),
(78, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 05:40:58'),
(79, 'ZVR003', 'ZLY006', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-06 05:41:22'),
(80, 'ZVR003', 'ZLY006', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-06 05:42:56'),
(81, 'ZVR003', 'ZLY006', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Created.', '', '2018-02-06 05:53:32'),
(82, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 05:54:03'),
(83, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 05:54:45'),
(84, 'ZVR003', 'ZLY006', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-06 05:55:33'),
(85, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 05:55:41'),
(86, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 05:56:38'),
(87, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-06 05:58:01'),
(88, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 05:58:37'),
(89, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 06:03:22'),
(90, 'ZVR003', 'ZLY007', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-06 06:04:00'),
(91, 'ZVR003', 'ZLY007', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-06 06:31:18'),
(92, 'ZVR003', 'ZLY007', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 06:43:53'),
(93, 'ZVR003', 'ZLY007', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 06:44:05'),
(94, 'ZVR003', 'ZLY007', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 06:44:05'),
(95, 'ZVR003', 'ZLY007', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 06:52:07'),
(96, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 06:55:30'),
(97, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-06 06:58:48'),
(98, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:18:36'),
(99, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:18:43'),
(100, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:18:47'),
(101, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:31:55'),
(102, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 07:32:09'),
(103, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:36:36'),
(104, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:39:02'),
(105, 'ZVR001', 'ZLY008', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-06 07:39:22'),
(106, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-06 23:27:40'),
(107, 'ZVR002', 'ZLY009', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 00:46:44'),
(108, 'ZVR002', 'ZLY009', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 00:55:14'),
(109, 'ZVR002', 'ZLY009', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:02:57'),
(110, 'ZVR002', 'ZLY009', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:03:09'),
(111, 'ZVR002', 'ZLY009', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:07:44'),
(112, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-07 01:12:24'),
(113, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 01:14:11'),
(114, 'ZVR002', 'ZLY010', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 01:14:25'),
(115, 'ZVR002', 'ZLY010', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:15:54'),
(116, 'ZVR002', 'ZLY011', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 01:23:29'),
(117, 'ZVR002', 'ZLY011', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:28:59'),
(118, 'ZVR002', 'ZLY012', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 01:37:26'),
(119, 'ZVR002', 'ZLY012', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:38:21'),
(120, 'ZVR002', 'ZLY012', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 01:43:32'),
(121, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-07 02:01:13'),
(122, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 02:01:46'),
(123, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 02:02:51'),
(124, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 02:06:18'),
(125, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 02:27:32'),
(126, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-07 06:04:35'),
(127, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 06:05:16'),
(128, 'ZVR002', 'ZLY013', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 07:07:00'),
(129, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 07:10:37'),
(130, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-07 07:11:51'),
(131, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:12:26'),
(132, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:12:29'),
(133, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:12:31'),
(134, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:12:34'),
(135, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:12:59'),
(136, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:00'),
(137, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:00'),
(138, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:00'),
(139, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:01'),
(140, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:04'),
(141, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:06'),
(142, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:07'),
(143, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:07'),
(144, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:07'),
(145, 'ZVR002', 'ZLY014', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 07:13:08'),
(146, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-07 07:30:26'),
(147, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 07:32:49'),
(148, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-07 22:51:56'),
(149, 'ZVR002', 'ZLY015', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 22:52:32'),
(150, 'ZVR001', 'ZLY016', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 22:55:00'),
(151, 'ZVR002', 'ZLY015', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 22:56:10'),
(152, 'ZVR002', 'ZLY015', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 23:00:57'),
(153, 'ZVR002', 'ZLY017', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 23:03:17'),
(154, 'ZVR002', 'ZLY017', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 23:08:30'),
(155, 'ZVR001', 'ZLY016', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 23:10:17'),
(156, 'ZVR002', 'ZLY018', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-07 23:12:32'),
(157, 'ZVR002', 'ZLY018', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-07 23:15:07'),
(158, 'ZUR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 00:35:19'),
(159, 'ZUR002', '2873', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, '2018-02-08 00:35:47'),
(160, 'ZVR003', 'ZTN002', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '1000', '2018-02-08 00:37:04'),
(161, 'ZUR002', 'ZTN002', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'User zoin has been point added', '+100', '2018-02-08 00:37:04'),
(162, 'ZVR003', 'ZTN002', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Merchant zoin point has been deducted', '-100', '2018-02-08 00:37:04'),
(163, 'ZUR002', '1503', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, '2018-02-08 00:37:41'),
(164, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 00:52:23'),
(165, 'ZUR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 00:52:36'),
(166, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 00:55:23'),
(167, 'ZVR001', 'ZLY019', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 01:47:02'),
(168, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 02:29:41'),
(169, 'ZUR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 02:34:32'),
(170, 'ZVR003', 'ZTN003', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '1000', '2018-02-08 02:38:37'),
(171, 'ZUR002', 'ZTN003', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'User zoin has been point added', '+100', '2018-02-08 02:38:37'),
(172, 'ZVR003', 'ZTN003', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Merchant zoin point has been deducted', '-100', '2018-02-08 02:38:37'),
(173, 'ZVR002', 'ZLY020', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 02:53:57'),
(174, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 02:57:47'),
(175, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 02:58:16'),
(176, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 03:04:04'),
(177, 'ZVR002', 'ZLY021', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 03:05:30'),
(178, 'ZVR002', 'ZLY022', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 04:54:23'),
(179, 'ZVR002', 'ZLY023', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 05:12:53'),
(180, 'ZVR002', 'ZLY023', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-08 05:27:40'),
(181, 'ZVR001', 'ZLY019', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-08 05:29:28'),
(182, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 05:30:20'),
(183, 'ZUR001', '1746', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, '2018-02-08 05:30:34'),
(184, 'ZVR001', 'ZTN004', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '2400', '2018-02-08 05:31:20'),
(185, 'ZVR001', 'ZTN005', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '150', '2018-02-08 05:31:55'),
(188, 'ZVR002', 'ZLY024', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 05:35:10'),
(189, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 05:44:18'),
(190, 'ZVR001', 'ZLY025', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 05:44:20'),
(191, 'ZVR002', 'ZLY026', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 05:44:32'),
(192, 'ZVR001', 'ZLY025', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-08 05:46:17'),
(193, 'ZUR001', '6251', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, '2018-02-08 05:46:56'),
(194, 'ZVR001', 'ZTN006', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '60', '2018-02-08 05:48:38'),
(195, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 05:49:42'),
(196, 'ZVR001', 'ZTN007', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '400', '2018-02-08 05:50:11'),
(197, 'ZUR001', 'ZTN007', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'User zoin has been point added', '+50', '2018-02-08 05:50:11'),
(198, 'ZVR001', 'ZTN007', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Merchant zoin point has been deducted', '-50', '2018-02-08 05:50:11'),
(199, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 05:50:12'),
(200, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 05:53:37'),
(201, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 05:55:05'),
(202, 'ZVR002', 'ZLY027', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 05:56:54'),
(203, 'ZVR002', 'ZLY027', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-08 06:07:49'),
(204, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 06:09:33'),
(205, 'ZVR007', '', 'http://zoin.in/feature_image/submit.png', 'Your Account has is Pending Our customer support will contact you.', '', '2018-02-08 06:13:03'),
(206, 'ZVR007', '', 'http://zoin.in/feature_image/submit.png', 'Your Account is Approved.', '', '2018-02-08 06:13:33'),
(207, 'ZVR007', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 06:14:27'),
(208, 'ZVR007', 'ZLY028', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty created successfully', NULL, '2018-02-08 06:14:46'),
(209, 'ZVR007', 'ZLY028', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'Loyalty set to Inactive.', '', '2018-02-08 06:15:20'),
(210, 'ZVR007', 'ZLY028', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'Loyalty Active successfully', NULL, '2018-02-08 06:43:31'),
(211, 'ZVR007', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-08 06:49:43'),
(212, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 06:49:58'),
(213, 'ZVR001', 'ZTN008', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '20', '2018-02-08 06:53:44'),
(214, 'ZVR001', 'ZTN009', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'Transaction successful', '10', '2018-02-08 06:56:13'),
(215, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 07:36:34'),
(216, 'ZUR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 07:45:11'),
(217, 'ZUR003', '5540', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, '2018-02-08 07:45:26'),
(218, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-08 07:46:04'),
(219, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, '2018-02-09 04:11:47'),
(220, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, '2018-02-09 04:50:35');

-- --------------------------------------------------------

--
-- Table structure for table `postcode`
--

CREATE TABLE `postcode` (
  `postcode` varchar(12) DEFAULT NULL,
  `area` varchar(80) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `state` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `redeem_code`
--

CREATE TABLE `redeem_code` (
  `id` int(11) NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `loyalty_id` varchar(28) NOT NULL,
  `redeem_code` varchar(10) NOT NULL,
  `mobile_number` varchar(12) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `user_checkin` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `redeem_code`
--

INSERT INTO `redeem_code` (`id`, `vendor_id`, `user_id`, `loyalty_id`, `redeem_code`, `mobile_number`, `status`, `user_checkin`) VALUES
(2, 'ZVR003', 'ZUR002', 'ZLY007', '2873', '9566425554', 0, '0'),
(3, 'ZVR002', 'ZUR002', 'ZLY018', '1503', '9566425554', 0, '0'),
(5, 'ZVR001', 'ZUR001', 'ZLY025', '6251', '9894571615', 0, '2'),
(6, 'ZVR003', 'ZUR003', 'ZLY007', '5540', '9566309844', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `tag_merchants`
--

CREATE TABLE `tag_merchants` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(15) UNSIGNED ZEROFILL NOT NULL,
  `transaction_id` varchar(30) NOT NULL,
  `vendor_id` varchar(30) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `transaction_type` varchar(20) DEFAULT NULL,
  `transaction_status` varchar(20) DEFAULT NULL,
  `loyalty_id` varchar(28) NOT NULL,
  `user_checkin` int(2) DEFAULT NULL,
  `bill_path` varchar(400) DEFAULT NULL,
  `user_bill_amount` int(8) DEFAULT NULL,
  `last_checkin_data` date DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_id`, `vendor_id`, `user_id`, `transaction_type`, `transaction_status`, `loyalty_id`, `user_checkin`, `bill_path`, `user_bill_amount`, `last_checkin_data`, `creation_date`, `status`) VALUES
(000000000000001, 'ZTN001', 'ZVR004', 'ZUR004', 'zoin', NULL, 'ZLY001', 1, NULL, NULL, NULL, NULL, 0),
(000000000000002, 'ZTN002', 'ZVR003', 'ZUR002', 'ZOIN', 'Approved', 'ZLY007', 1, 'http://zoin.in/zoin/public/images/1518070023.bmp', 1000, NULL, '2018-02-08 00:37:04', 1),
(000000000000003, 'ZTN003', 'ZVR003', 'ZUR002', 'ZOIN', 'Approved', 'ZLY007', 1, 'http://zoin.in/zoin/public/images/1518077316.bmp', 1000, NULL, '2018-02-08 02:38:37', 1),
(000000000000006, 'ZTN006', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLY025', 1, 'http://zoin.in/zoin/public/images/1518088717.bmp', 60, NULL, '2018-02-08 05:48:38', 1),
(000000000000007, 'ZTN007', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLY025', 1, 'http://zoin.in/zoin/public/images/1518088811.bmp', 400, NULL, '2018-02-08 05:50:11', 1),
(000000000000008, 'ZTN008', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLY025', 1, 'http://zoin.in/zoin/public/images/1518092624.bmp', 20, NULL, '2018-02-08 06:53:44', 0),
(000000000000009, 'ZTN009', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLY025', 1, 'http://zoin.in/zoin/public/images/1518092773.bmp', 10, NULL, '2018-02-08 06:56:13', 0);

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `transaction_auto_id` BEFORE INSERT ON `transactions` FOR EACH ROW SET NEW.transaction_id = CONCAT("ZTN",LPAD((SELECT AUTO_INCREMENT 
       FROM information_schema.TABLES 
       WHERE TABLE_SCHEMA = DATABASE() AND 
       TABLE_NAME = 'transactions'), 3, '0'))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(9) UNSIGNED ZEROFILL NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `email_id` varchar(150) DEFAULT NULL,
  `mobile_number` varchar(12) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `occupation` varchar(40) DEFAULT NULL,
  `address_id` int(12) DEFAULT NULL,
  `is_email_verified` int(1) UNSIGNED ZEROFILL NOT NULL,
  `profile_pic_path` varchar(500) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `user_level` int(2) UNSIGNED ZEROFILL NOT NULL,
  `user_type` varchar(3) NOT NULL,
  `is_mobile_verified` tinyint(4) NOT NULL DEFAULT '0',
  `is_login_approved` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `full_name`, `email_id`, `mobile_number`, `date_of_birth`, `occupation`, `address_id`, `is_email_verified`, `profile_pic_path`, `badge`, `user_level`, `user_type`, `is_mobile_verified`, `is_login_approved`) VALUES
(000000001, 'ZUR001', 'sachin user', 'tamil@thegang.in', '9894571615', NULL, NULL, NULL, 0, NULL, NULL, 01, 'u', 1, 0),
(000000002, 'ZUR002', 'ken', 'karthick@gmail.com', '9566425554', NULL, NULL, NULL, 0, NULL, NULL, 01, 'u', 1, 1),
(000000003, 'ZUR003', 'Pragadeesh', 'pragadeesh@thegang.in', '9566309844', NULL, NULL, NULL, 0, NULL, NULL, 01, 'u', 1, 1);

--
-- Triggers `user_details`
--
DELIMITER $$
CREATE TRIGGER `user_auto_id` BEFORE INSERT ON `user_details` FOR EACH ROW SET NEW.user_id = CONCAT("ZUR",LPAD((SELECT AUTO_INCREMENT 
       FROM information_schema.TABLES 
       WHERE TABLE_SCHEMA = DATABASE() AND 
       TABLE_NAME = 'user_details'), 3, '0'))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mobile_otp`
--

CREATE TABLE `user_mobile_otp` (
  `id` int(11) UNSIGNED NOT NULL,
  `mobile_number` varchar(12) NOT NULL,
  `otp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE `version` (
  `id` int(11) UNSIGNED NOT NULL,
  `version_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `version`
--

INSERT INTO `version` (`id`, `version_name`, `created_at`) VALUES
(1, '1.0\r\n', '2018-02-02 01:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `zoin_balance`
--

CREATE TABLE `zoin_balance` (
  `vendor_or_user_id` varchar(25) NOT NULL,
  `zoin_balance` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zoin_balance`
--

INSERT INTO `zoin_balance` (`vendor_or_user_id`, `zoin_balance`) VALUES
('ZVR001', 700),
('ZVR002', 1000),
('ZVR003', 300),
('ZVR004', 500),
('ZVR005', 500),
('ZVR006', 500),
('ZUR002', 200),
('ZUR001', 300),
('ZVR007', 500);

-- --------------------------------------------------------

--
-- Table structure for table `zoin_open_balance`
--

CREATE TABLE `zoin_open_balance` (
  `id` int(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zoin_open_balance`
--

INSERT INTO `zoin_open_balance` (`id`, `user_type`, `amount`) VALUES
(1, 'vendor', '500'),
(2, 'user', '500');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `business_types`
--
ALTER TABLE `business_types`
  ADD PRIMARY KEY (`business_type`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`mobile_number`);

--
-- Indexes for table `loyalty`
--
ALTER TABLE `loyalty`
  ADD PRIMARY KEY (`loyalty_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `loyalty_balance`
--
ALTER TABLE `loyalty_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_bank_details`
--
ALTER TABLE `merchant_bank_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_details`
--
ALTER TABLE `merchant_details`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `merchant_feature_details`
--
ALTER TABLE `merchant_feature_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_feature_images`
--
ALTER TABLE `merchant_feature_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_images`
--
ALTER TABLE `merchant_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_social_media`
--
ALTER TABLE `merchant_social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_status`
--
ALTER TABLE `merchant_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_tags`
--
ALTER TABLE `merchant_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `redeem_code`
--
ALTER TABLE `redeem_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag_merchants`
--
ALTER TABLE `tag_merchants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_mobile_otp`
--
ALTER TABLE `user_mobile_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zoin_open_balance`
--
ALTER TABLE `zoin_open_balance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(12) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `business_types`
--
ALTER TABLE `business_types`
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `loyalty`
--
ALTER TABLE `loyalty`
  MODIFY `id` int(12) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `loyalty_balance`
--
ALTER TABLE `loyalty_balance`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `merchant_bank_details`
--
ALTER TABLE `merchant_bank_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `merchant_details`
--
ALTER TABLE `merchant_details`
  MODIFY `id` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `merchant_feature_details`
--
ALTER TABLE `merchant_feature_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;
--
-- AUTO_INCREMENT for table `merchant_feature_images`
--
ALTER TABLE `merchant_feature_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `merchant_images`
--
ALTER TABLE `merchant_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `merchant_social_media`
--
ALTER TABLE `merchant_social_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `merchant_status`
--
ALTER TABLE `merchant_status`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `merchant_tags`
--
ALTER TABLE `merchant_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;
--
-- AUTO_INCREMENT for table `redeem_code`
--
ALTER TABLE `redeem_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tag_merchants`
--
ALTER TABLE `tag_merchants`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(15) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_mobile_otp`
--
ALTER TABLE `user_mobile_otp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `version`
--
ALTER TABLE `version`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `zoin_open_balance`
--
ALTER TABLE `zoin_open_balance`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
