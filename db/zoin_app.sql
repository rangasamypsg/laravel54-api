-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2018 at 11:04 AM
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
(000000000001, '22 mumbai street dubai', 'cbe', NULL),
(000000000002, 'P.n Palayam', 'Coimbatore', NULL),
(000000000003, 'Pn palayam', 'Coimbatore', NULL);

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
('9566309844', NULL, 'v', 1),
('9566425554', NULL, 'V', 1),
('9585537309', NULL, 'v', 1);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty`
--

CREATE TABLE `loyalty` (
  `id` int(12) UNSIGNED NOT NULL,
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
(1, 'ZLTY001', 'Open', 2, 1000, NULL, '100', 'new loyalty created', NULL, 'ZVR001', '2018-03-02 03:57:53', '2018-03-02 03:58:34'),
(2, 'ZLTY002', 'Created', 2, 1500, NULL, '150', 'Description', NULL, 'ZVR008', '2018-03-02 03:59:15', '2018-03-02 03:59:15'),
(3, 'ZLTY003', 'Open', 3, 1500, NULL, '150', 'Description details', NULL, 'ZVR002', '2018-03-02 04:00:40', '2018-03-02 04:02:28'),
(4, 'ZLTY004', 'Open', 3, 1500, NULL, '150', '10 plates of chicken', NULL, 'ZVR003', '2018-03-02 04:33:56', '2018-03-02 04:34:54');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_balance`
--

CREATE TABLE `loyalty_balance` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) DEFAULT NULL,
  `user_id` varchar(25) DEFAULT NULL,
  `total_loyalty` int(8) NOT NULL DEFAULT '0',
  `claimed_loyalty` int(8) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loyalty_balance`
--

INSERT INTO `loyalty_balance` (`id`, `vendor_id`, `user_id`, `total_loyalty`, `claimed_loyalty`) VALUES
(1, 'ZVR001', NULL, 1, 1),
(2, 'ZVR008', NULL, 1, 0),
(3, 'ZVR002', NULL, 1, 0),
(4, NULL, 'ZUR001', 1, 1),
(5, 'ZVR003', NULL, 1, 0);

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
(1, 'ZVR003', '27ABBFM2523J1ZE', '4991 1866 5246', 'AAAAA1111A', 'SBIN1234567', '9988889766666', 'Raj', 'Federalbank', 'P N palayam', 'Current Bank Account');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_details`
--

CREATE TABLE `merchant_details` (
  `id` int(9) UNSIGNED NOT NULL,
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
(1, 'ZVR001', 'cbe restaurant', 'tamil@thegang.in', 'nts', '9585537309', 1, 0, NULL, NULL, 1, '0', 01, 1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'http://google.com', '8:00 AM', '11:00 PM', 'Wednesday', '23.3333', '44.4444', '2018-03-02 03:51:04', '2018-03-02 04:23:08'),
(2, 'ZVR002', 'Jk Corp', 'karthick@thegang.in', '9566425554', '9566425554', 2, 0, NULL, NULL, 1, '0', 01, 1, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'https://www.google.co.in', '8:00 AM', '10:00 PM', '', '22.22222', '22.222222', '2018-03-02 03:52:09', '2018-03-02 03:59:55'),
(3, 'ZVR003', 'raj restaurant', 'pragdeesh@thegang.in', 'raj', '9566309844', 3, 0, NULL, NULL, 1, '0', 01, 1, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 'http://google.in', '10:00 AM', '10:30 PM', 'Thursday', '22.3333', '44.65666', '2018-03-02 04:29:34', '2018-03-02 05:06:15');

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
(1, 'ZVR002', 1),
(2, 'ZVR002', 6),
(3, 'ZVR002', 7),
(4, 'ZVR002', 0),
(5, 'ZVR002', 9),
(6, 'ZVR002', 0),
(7, 'ZVR002', 11),
(8, 'ZVR002', 0),
(9, 'ZVR002', 13),
(10, 'ZVR002', 0),
(11, 'ZVR001', 1),
(12, 'ZVR001', 6),
(13, 'ZVR001', 7),
(14, 'ZVR001', 0),
(15, 'ZVR001', 9),
(16, 'ZVR001', 0),
(17, 'ZVR001', 11),
(18, 'ZVR001', 0),
(19, 'ZVR001', 13),
(20, 'ZVR001', 0),
(21, 'ZVR003', 1),
(22, 'ZVR003', 5),
(23, 'ZVR003', 7),
(24, 'ZVR003', 0),
(25, 'ZVR003', 9),
(26, 'ZVR003', 10),
(27, 'ZVR003', 11),
(28, 'ZVR003', 12),
(29, 'ZVR003', 13),
(30, 'ZVR003', 0);

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
(3, 'food', 'Veg/Non Veg', 'http://zoin.in/feature_image/veg-nonveg.png'),
(4, 'room', 'Ac', 'http://zoin.in/feature_image/ac.png'),
(5, 'room', 'Non Ac', 'http://zoin.in/feature_image/nonac.png'),
(6, 'room', 'Ac/Non A/c', 'http://zoin.in/feature_image/ac-nonac.png'),
(7, 'card_payment', 'Card Payment', 'http://zoin.in/feature_image/card.png'),
(8, 'wifi', 'Wifi', 'http://zoin.in/feature_image/wifi.png'),
(9, 'rest_room', 'Rest Room', 'http://zoin.in/feature_image/restroom.png'),
(10, 'self_services', 'Self Services', 'http://zoin.in/feature_image/selfservice.png'),
(11, 'parking', 'Parking', 'http://zoin.in/feature_image/parking.png'),
(12, 'disabled_access', 'Disabled Access', 'http://zoin.in/feature_image/access.png'),
(13, 'cctv', 'CCTV', 'http://zoin.in/feature_image/cctv.png'),
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
(1, 'ZVR002', 'http://zoin.in/adminpanel/admin/database/images/1519982682photo_2018-02-28_14-41-26.jpg'),
(2, 'ZVR002', 'http://zoin.in/adminpanel/admin/database/images/1519982682pinviol1gl_1680x1050.jpg'),
(3, 'ZVR001', 'http://zoin.in/adminpanel/admin/database/images/1519982754photo_2018-02-28_14-41-26.jpg'),
(4, 'ZVR001', 'http://zoin.in/adminpanel/admin/database/images/1519982754pinviol1gl_1680x1050.jpg'),
(5, 'ZVR003', 'http://zoin.in/adminpanel/admin/database/images/1519984940image3.jpg'),
(6, 'ZVR003', 'http://zoin.in/adminpanel/admin/database/images/1519984940image2.jpg'),
(7, 'ZVR003', 'http://zoin.in/adminpanel/admin/database/images/1519984940image1.jpg');

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
(1, 'ZVR002', 'https://www.google.co.in/facebook'),
(2, 'ZVR001', 'http://google.com/facebook'),
(3, 'ZVR003', 'http://google/facebook'),
(4, 'ZVR003', 'http://google/googleplus');

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

--
-- Dumping data for table `merchant_tags`
--

INSERT INTO `merchant_tags` (`id`, `tag_name`) VALUES
(1, 'newtag'),
(2, 'hotel'),
(3, 'hello'),
(4, 'Ben'),
(5, 'Food'),
(6, 'Lunch'),
(7, 'foodone');

-- --------------------------------------------------------

--
-- Table structure for table `mobile_otp`
--

CREATE TABLE `mobile_otp` (
  `mobile_number` varchar(12) NOT NULL,
  `otp` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `subject_id`, `image`, `message`, `amount`, `status`, `created_at`) VALUES
(1, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, 0, '2018-03-02 03:54:41'),
(2, 'ZUR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, 0, '2018-03-02 03:55:13'),
(3, 'ZVR002', '', 'http://zoin.in/feature_image/submit.png', 'MERID-ZVR002 Approved.', '', 0, '2018-03-02 03:56:22'),
(4, 'ZVR001', '', 'http://zoin.in/feature_image/submit.png', 'MERID-ZVR001 Approved.', '', 0, '2018-03-02 03:56:31'),
(5, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'MERID-ZVR001 logged in.', NULL, 0, '2018-03-02 03:57:32'),
(6, 'ZVR001', 'ZLTY001', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'MERID-ZVR001 submitted | LTYID-ZLTY001 successfully', NULL, 1, '2018-03-02 03:57:53'),
(7, 'ZVR001', 'ZLTY001', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'MERID-ZVR001 active | LTYID-ZLTY001 successfully.', '', 0, '2018-03-02 03:58:34'),
(8, 'ZVR001', 'ZLTY001', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'MERID-ZVR001 activated | LTYID-ZLTY001 successfully', NULL, 0, '2018-03-02 03:59:24'),
(9, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'MERID-ZVR002 logged out.', NULL, 0, '2018-03-02 03:59:55'),
(10, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'MERID-ZVR002 logged in.', NULL, 0, '2018-03-02 04:00:16'),
(11, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR001 edited Tag successfully', NULL, 0, '2018-03-02 04:00:21'),
(12, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR001 edited Tag successfully', NULL, 0, '2018-03-02 04:00:27'),
(13, 'ZVR002', 'ZLTY003', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'MERID-ZVR002 submitted | LTYID-ZLTY003 successfully', NULL, 0, '2018-03-02 04:00:40'),
(14, 'ZVR002', 'ZLTY003', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'MERID-ZVR002 active | LTYID-ZLTY003 successfully.', '', 0, '2018-03-02 04:02:28'),
(15, 'ZUR001', 'OA0030', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, 0, '2018-03-02 04:02:30'),
(16, 'ZVR002', 'ZLTY003', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'MERID-ZVR002 activated | LTYID-ZLTY003 successfully', NULL, 0, '2018-03-02 04:02:49'),
(17, 'ZVR001', 'ZTN001', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '500', 0, '2018-03-02 04:03:13'),
(18, 'ZUR002', 'EU3645', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, 0, '2018-03-02 04:03:42'),
(19, 'ZVR001', 'ZTN002', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '500', 0, '2018-03-02 04:05:06'),
(20, 'ZUR001', 'ZTN002', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'MERID-ZVR001 added | 100 Zoin by USRID-ZUR001 successfully', '+100', 0, '2018-03-02 04:05:06'),
(21, 'ZVR001', 'ZTN002', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'MERID-ZVR001 deducted | 100 Zoin by USRID-ZUR001 successfully', '-100', 0, '2018-03-02 04:05:06'),
(22, 'ZVR002', 'ZTN003', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR002 redeemed | LTYID-ZLTY003 successfully', '100', 0, '2018-03-02 04:05:35'),
(23, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR002 edited Tag successfully', NULL, 0, '2018-03-02 04:08:35'),
(24, 'ZVR002', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR002 edited Tag successfully', NULL, 0, '2018-03-02 04:08:45'),
(25, 'ZVR002', 'ZTN004', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR002 redeemed | LTYID-ZLTY003 successfully', '300', 0, '2018-03-02 04:10:26'),
(26, 'ZVR001', 'ZTN005', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '10', 0, '2018-03-02 04:14:37'),
(27, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, 0, '2018-03-02 04:23:04'),
(28, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'MERID-ZVR001 logged out.', NULL, 1, '2018-03-02 04:23:08'),
(29, 'ZUR002', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, 0, '2018-03-02 04:24:41'),
(30, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'MERID-ZVR003 Approved.', '', 0, '2018-03-02 04:32:37'),
(31, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'MERID-ZVR003 logged in.', NULL, 0, '2018-03-02 04:33:21'),
(32, 'ZVR003', '', 'http://zoin.in/feature_image/submit.png', 'MERID-ZVR003 edited Tag successfully', '', 0, '0000-00-00 00:00:00'),
(33, 'ZVR003', 'ZLTY004', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'MERID-ZVR003 submitted | LTYID-ZLTY004 successfully', NULL, 0, '2018-03-02 04:33:58'),
(34, 'ZVR003', 'ZLTY004', 'http://zoin.in/zoin/public/images/notification/loyalty_submit.png', 'MERID-ZVR003 active | LTYID-ZLTY004 successfully.', '', 0, '2018-03-02 04:34:54'),
(35, 'ZVR003', 'ZLTY004', 'http://zoin.in/zoin/public/images/notification/loyalty_active.png', 'MERID-ZVR003 activated | LTYID-ZLTY004 successfully', NULL, 0, '2018-03-02 04:35:22'),
(36, 'ZUR003', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, 0, '2018-03-02 04:36:52'),
(37, 'ZUR003', 'FB2900', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, 0, '2018-03-02 04:38:20'),
(38, 'ZVR003', 'ZTN006', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR003 redeemed | LTYID-ZLTY004 successfully', '100', 0, '2018-03-02 04:39:30'),
(39, 'ZVR003', 'ZTN007', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR003 redeemed | LTYID-ZLTY004 successfully', '100', 0, '2018-03-02 04:40:41'),
(40, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, 0, '2018-03-02 05:04:35'),
(41, 'ZUR001', 'VQ8307', 'http://zoin.in/zoin/public/images/notification/redeem_code.png', 'Redeem code created successfully', NULL, 0, '2018-03-02 05:05:40'),
(42, 'ZVR003', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'MERID-ZVR003 logged out.', NULL, 0, '2018-03-02 05:06:15'),
(43, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/logout.png', 'Logout successful', NULL, 0, '2018-03-02 05:06:18'),
(44, 'ZUR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'Login successful', NULL, 0, '2018-03-02 05:07:19'),
(45, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/login.png', 'MERID-ZVR001 logged in.', NULL, 0, '2018-03-02 05:07:44'),
(46, 'ZVR001', 'ZTN008', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '100', 0, '2018-03-02 05:08:44'),
(47, 'ZVR001', 'ZTN009', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '500', 0, '2018-03-02 05:10:29'),
(48, 'ZVR001', 'ZTN010', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '100', 0, '2018-03-02 05:11:10'),
(49, 'ZVR001', 'ZTN011', 'http://zoin.in/zoin/public/images/notification/transaction.png', 'USRID-ZUR001 redeemed | LTYID-ZLTY001 successfully', '200', 0, '2018-03-02 05:11:48'),
(50, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR001 edited Tag successfully', NULL, 0, '2018-03-02 23:10:14'),
(51, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR001 edited Tag successfully', NULL, 0, '2018-03-02 23:14:54'),
(52, 'ZVR001', NULL, 'http://zoin.in/zoin/public/images/notification/edit_profile.png', 'MERID-ZVR001 edited Tag successfully', NULL, 0, '2018-03-02 23:15:06');

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
  `mer_mobile_number` varchar(12) DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `user_checkin` varchar(10) NOT NULL DEFAULT '0',
  `user_balance` double(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `redeem_code`
--

INSERT INTO `redeem_code` (`id`, `vendor_id`, `user_id`, `loyalty_id`, `redeem_code`, `mobile_number`, `mer_mobile_number`, `status`, `user_checkin`, `user_balance`) VALUES
(1, 'ZVR001', 'ZUR001', 'ZLTY001', 'AE9469', '9894571615', '9585537309', 0, '5', 0.00),
(2, 'ZVR002', 'ZUR002', 'ZLTY003', 'YK7370', '8248014315', '9566425554', 0, '2', 0.00),
(3, 'ZVR003', 'ZUR003', 'ZLTY004', 'BS2274', '8508784722', '9566309844', 0, '2', 0.00),
(4, 'ZVR003', 'ZUR001', 'ZLTY004', 'VQ8307', '9894571615', '9566309844', 0, '0', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tag_merchants`
--

CREATE TABLE `tag_merchants` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` varchar(25) NOT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tag_merchants`
--

INSERT INTO `tag_merchants` (`id`, `vendor_id`, `tag_id`) VALUES
(1, 'ZVR001', 1),
(3, 'ZVR002', 3),
(4, 'ZVR002', 4),
(5, 'ZVR003', 5),
(6, 'ZVR003', 6),
(8, 'ZVR001', 5),
(9, 'ZVR001', 7);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(15) UNSIGNED NOT NULL,
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
(1, 'ZTN001', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519983193.bmp', 500, NULL, '2018-03-02 04:03:13', 1),
(2, 'ZTN002', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519983305.bmp', 500, NULL, '2018-03-02 04:05:06', 1),
(3, 'ZTN003', 'ZVR002', 'ZUR002', 'ZOIN', 'Approved', 'ZLTY003', 1, 'http://zoin.in/zoin/public/images/1519983334.bmp', 100, NULL, '2018-03-02 04:05:34', 0),
(4, 'ZTN004', 'ZVR002', 'ZUR002', 'ZOIN', 'Approved', 'ZLTY003', 1, 'http://zoin.in/zoin/public/images/1519983626.bmp', 300, NULL, '2018-03-02 04:10:26', 0),
(5, 'ZTN005', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519983877.bmp', 10, NULL, '2018-03-02 04:14:37', 0),
(6, 'ZTN006', 'ZVR003', 'ZUR003', 'ZOIN', 'Approved', 'ZLTY004', 1, 'http://zoin.in/zoin/public/images/1519985370.bmp', 100, NULL, '2018-03-02 04:39:30', 0),
(7, 'ZTN007', 'ZVR003', 'ZUR003', 'ZOIN', 'Approved', 'ZLTY004', 1, 'http://zoin.in/zoin/public/images/1519985441.bmp', 100, NULL, '2018-03-02 04:40:41', 0),
(8, 'ZTN008', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519987124.bmp', 100, NULL, '2018-03-02 05:08:44', 0),
(9, 'ZTN009', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519987229.bmp', 500, NULL, '2018-03-02 05:10:29', 0),
(10, 'ZTN010', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519987269.bmp', 100, NULL, '2018-03-02 05:11:10', 0),
(11, 'ZTN011', 'ZVR001', 'ZUR001', 'ZOIN', 'Approved', 'ZLTY001', 1, 'http://zoin.in/zoin/public/images/1519987307.bmp', 200, NULL, '2018-03-02 05:11:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(9) UNSIGNED NOT NULL,
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
(1, 'ZUR001', 'user tamil', 'tamil@thegang.in', '9894571615', NULL, NULL, NULL, 0, NULL, NULL, 01, 'u', 1, 1),
(2, 'ZUR002', 'penny', 'karthick@thegang.in', '8248014315', NULL, NULL, NULL, 0, NULL, NULL, 01, 'u', 1, 0),
(3, 'ZUR003', 'pragdeesh', 'pragdesh@thegang.in', '8508784722', NULL, NULL, NULL, 0, NULL, NULL, 01, 'u', 1, 1);

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
(1, '1.0', '2018-02-17 09:31:51');

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
('ZVR001', 400),
('ZVR002', 500),
('ZUR001', 100),
('ZVR003', 500);

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
  MODIFY `address_id` int(12) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_types`
--
ALTER TABLE `business_types`
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loyalty`
--
ALTER TABLE `loyalty`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loyalty_balance`
--
ALTER TABLE `loyalty_balance`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `merchant_bank_details`
--
ALTER TABLE `merchant_bank_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `merchant_details`
--
ALTER TABLE `merchant_details`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `merchant_feature_details`
--
ALTER TABLE `merchant_feature_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `merchant_feature_images`
--
ALTER TABLE `merchant_feature_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `merchant_images`
--
ALTER TABLE `merchant_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `merchant_social_media`
--
ALTER TABLE `merchant_social_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `merchant_status`
--
ALTER TABLE `merchant_status`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `merchant_tags`
--
ALTER TABLE `merchant_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `redeem_code`
--
ALTER TABLE `redeem_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tag_merchants`
--
ALTER TABLE `tag_merchants`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_mobile_otp`
--
ALTER TABLE `user_mobile_otp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `version`
--
ALTER TABLE `version`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zoin_open_balance`
--
ALTER TABLE `zoin_open_balance`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
