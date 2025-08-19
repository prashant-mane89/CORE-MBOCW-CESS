-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 19, 2025 at 04:53 AM
-- Server version: 9.1.0
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `core_mbocw_cess`
--

-- --------------------------------------------------------

--
-- Table structure for table `bulk_projects_invoices_history`
--

DROP TABLE IF EXISTS `bulk_projects_invoices_history`;
CREATE TABLE IF NOT EXISTS `bulk_projects_invoices_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `effective_cess_amount` decimal(15,2) NOT NULL,
  `bulk_project_invoices_template_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cess_payment_mode` int NOT NULL COMMENT '1=Online ,2=Offline, 3=Exempted',
  `is_payment_verified` int NOT NULL COMMENT '1=verified, 2=pending, 3=rejected',
  `rejection_reason` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bulk_projects_invoices_history`
--

INSERT INTO `bulk_projects_invoices_history` (`id`, `effective_cess_amount`, `bulk_project_invoices_template_file`, `cess_payment_mode`, `is_payment_verified`, `rejection_reason`, `created_at`) VALUES
(1, 63320.40, 'bulk_upload_68a33a089216e.xlsx', 1, 1, '', '2025-08-18 20:04:48'),
(2, 63320.40, 'bulk_upload_68a33a79addde.xlsx', 1, 1, '', '2025-08-18 20:06:41'),
(3, 63320.40, 'bulk_upload_68a348ce9358b.xlsx', 1, 2, '', '2025-08-18 21:07:50'),
(4, 63320.40, 'bulk_upload_68a34a9bce063.xlsx', 1, 2, '', '2025-08-18 21:15:32'),
(5, 63320.40, 'bulk_upload_68a34ad064a7b.xlsx', 1, 2, '', '2025-08-18 21:16:24'),
(6, 63320.40, 'bulk_upload_68a34afb6a2aa.xlsx', 1, 2, '', '2025-08-18 21:17:07'),
(7, 63320.40, 'bulk_upload_68a34c133c842.xlsx', 1, 2, '', '2025-08-18 21:21:47'),
(8, 63320.40, 'bulk_upload_68a34d2340a05.xlsx', 1, 2, '', '2025-08-18 21:26:19'),
(9, 63320.40, 'bulk_upload_68a35059a9ae8.xlsx', 1, 2, '', '2025-08-18 21:40:02'),
(10, 63320.40, 'bulk_upload_68a3519c8292f.xlsx', 1, 2, '', '2025-08-18 21:45:24'),
(11, 63320.40, 'bulk_upload_68a352b5a0b32.xlsx', 1, 2, '', '2025-08-18 21:50:05'),
(12, 63320.40, 'bulk_upload_68a363193c63a.xlsx', 1, 1, '', '2025-08-18 23:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `cess_payment_history`
--

DROP TABLE IF EXISTS `cess_payment_history`;
CREATE TABLE IF NOT EXISTS `cess_payment_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bulk_invoice_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `workorder_id` int NOT NULL,
  `invoice_amount` decimal(15,2) DEFAULT NULL,
  `cess_amount` decimal(15,2) DEFAULT NULL,
  `gst_cess_amount` decimal(15,2) NOT NULL,
  `administrative_cost` decimal(15,2) DEFAULT NULL,
  `effective_cess_amount` decimal(15,2) NOT NULL,
  `employer_id` int NOT NULL,
  `cess_payment_mode` int NOT NULL COMMENT '1=Online ,2=Offline, 3=Exempted',
  `cess_receipt_file` text NOT NULL,
  `payment_status` text NOT NULL COMMENT 'payment gateway statuses',
  `is_payment_verified` int NOT NULL COMMENT '1=verified, 2=pending, 3=rejected',
  `invoice_upload_type` enum('bulk','single') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cess_payment_history`
--

INSERT INTO `cess_payment_history` (`id`, `bulk_invoice_id`, `project_id`, `workorder_id`, `invoice_amount`, `cess_amount`, `gst_cess_amount`, `administrative_cost`, `effective_cess_amount`, `employer_id`, `cess_payment_mode`, `cess_receipt_file`, `payment_status`, `is_payment_verified`, `invoice_upload_type`, `created_at`) VALUES
(1, 1, 1, 1, 500000.00, 5000.00, 5125.00, 51.25, 5073.75, 1, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(2, 1, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(3, 1, 3, 3, 2500000.00, 25000.00, 25625.00, 256.25, 25368.75, 3, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(4, 1, 4, 4, 700000.00, 7000.00, 7175.00, 71.75, 7103.25, 4, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(5, 1, 5, 5, 600000.00, 6000.00, 6150.00, 61.50, 6088.50, 5, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(6, 1, 6, 6, 400000.00, 4000.00, 4100.00, 41.00, 4059.00, 6, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(7, 1, 7, 7, 300000.00, 3000.00, 3075.00, 30.75, 3044.25, 7, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(8, 1, 8, 8, 150000.00, 1500.00, 1537.50, 15.38, 1522.13, 8, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(9, 1, 9, 9, 250000.00, 2500.00, 2562.50, 25.63, 2536.88, 9, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(10, 1, 3, 10, 800000.00, 8000.00, 8200.00, 82.00, 8118.00, 10, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:04:48'),
(11, 2, 1, 1, 500000.00, 5000.00, 5125.00, 51.25, 5073.75, 1, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:06:41'),
(12, 2, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:06:41'),
(13, 2, 5, 5, 600000.00, 6000.00, 6150.00, 61.50, 6088.50, 5, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:06:41'),
(14, 2, 7, 7, 300000.00, 3000.00, 3075.00, 30.75, 3044.25, 7, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:06:42'),
(15, 2, 8, 8, 150000.00, 1500.00, 1537.50, 15.38, 1522.13, 8, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:06:42'),
(16, 2, 9, 9, 250000.00, 2500.00, 2562.50, 25.63, 2536.88, 9, 1, '', 'Paid', 1, 'bulk', '2025-08-18 20:06:42'),
(17, 3, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:07:50'),
(18, 3, 8, 8, 150000.00, 1500.00, 1537.50, 15.38, 1522.13, 8, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:07:50'),
(19, 4, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:15:32'),
(20, 5, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:16:24'),
(21, 6, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:17:07'),
(22, 7, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:21:47'),
(23, 8, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:26:19'),
(24, 9, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:40:02'),
(25, 10, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:45:24'),
(26, 11, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Pending', 2, 'bulk', '2025-08-18 21:50:05'),
(27, 12, 2, 2, 40000.00, 400.00, 410.00, 4.10, 405.90, 2, 1, '', 'Paid', 1, 'bulk', '2025-08-18 23:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
CREATE TABLE IF NOT EXISTS `districts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state_id` bigint UNSIGNED NOT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `updated_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `state_id`, `active_status`, `created_by`, `created_datetime`, `updated_by`, `updated_datetime`) VALUES
(1, 'Ahmednagar', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(2, 'Akola', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(3, 'Amravati', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(4, 'Aurangabad', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(5, 'Beed', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(6, 'Bhandara', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(7, 'Buldhana', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(8, 'Chandrapur', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(9, 'Dhule', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(10, 'Gadchiroli', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(11, 'Gondia', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(12, 'Hingoli', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(13, 'Jalgaon', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(14, 'Jalna', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(15, 'Kolhapur', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(16, 'Latur', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(17, 'Mumbai City', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(18, 'Mumbai Suburban', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(19, 'Nagpur', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(20, 'Nanded', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(21, 'Nandurbar', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(22, 'Nashik', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(23, 'Osmanabad', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(24, 'Palghar', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(25, 'Parbhani', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(26, 'Pune', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(27, 'Raigad', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(28, 'Ratnagiri', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(29, 'Sangli', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(30, 'Satara', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(31, 'Sindhudurg', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(32, 'Solapur', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(33, 'Thane', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(34, 'Wardha', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(35, 'Washim', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41'),
(36, 'Yavatmal', 14, 1, NULL, '2025-07-24 17:19:41', NULL, '2025-07-24 17:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

DROP TABLE IF EXISTS `employers`;
CREATE TABLE IF NOT EXISTS `employers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employer_type` enum('Individual','Private Company','Government Organization') NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `phone` int NOT NULL,
  `pancard` varchar(10) NOT NULL,
  `pancard_path` text NOT NULL,
  `aadhaar` text NOT NULL,
  `aadhaar_path` text NOT NULL,
  `gstn` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`id`, `employer_type`, `name`, `email`, `phone`, `pancard`, `pancard_path`, `aadhaar`, `aadhaar_path`, `gstn`, `created_at`, `created_by`) VALUES
(1, '', 'Employer Name 1', 'employer1@gmail.com', 2147483647, '', '', '', '', '123456789', '2025-08-18 20:04:48', 1),
(2, 'Private Company', 'Employer Name 2', 'employer2@gmail.com', 1234568970, '', '', '', '', '555656565', '2025-08-18 20:04:48', 1),
(3, 'Private Company', 'Employer Name 3', 'employer3@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(4, 'Private Company', 'Employer Name 4', 'employer4@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(5, 'Private Company', 'Employer Name 5', 'employer5@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(6, 'Private Company', 'Employer Name 6', 'employer6@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(7, 'Private Company', 'Employer Name 7', 'employer7@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(8, 'Private Company', 'Employer Name 8', 'employer8@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(9, 'Private Company', 'Employer Name 9', 'employer9@gmail.com', 2147483647, '', '', '', '', '562315478', '2025-08-18 20:04:48', 1),
(10, '', 'Employer Name 10', 'employer10@gmail.com', 2147483647, '', '', '', '', '895647125', '2025-08-18 20:04:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `local_authorities`
--

DROP TABLE IF EXISTS `local_authorities`;
CREATE TABLE IF NOT EXISTS `local_authorities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'cafo - Chief Account Finance Officer',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `district_id` int NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `local_authorities`
--

INSERT INTO `local_authorities` (`id`, `type_id`, `user_id`, `name`, `district_id`, `contact_email`, `contact_phone`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'Brihanmumbai Municipal Corporation (BMC)', 0, NULL, NULL, 0, 0),
(2, 1, 0, 'Pune Municipal Corporation (PMC)', 0, NULL, NULL, 0, 0),
(3, 1, 0, 'Nagpur Municipal Corporation (NMC)', 0, NULL, NULL, 0, 0),
(4, 2, 0, 'Alibag Municipal Council', 0, NULL, NULL, 0, 0),
(5, 2, 0, 'Baramati Municipal Council', 0, NULL, NULL, 0, 0),
(6, 2, 0, 'Satara Municipal Council', 0, NULL, NULL, 0, 0),
(7, 3, 0, 'Shirdi Nagar Panchayat', 0, NULL, NULL, 0, 0),
(8, 3, 0, 'Mangaon Nagar Panchayat', 0, NULL, NULL, 0, 0),
(9, 3, 0, 'Uran Nagar Panchayat', 0, NULL, NULL, 0, 0),
(10, 4, 0, 'Raigad Zilla Parishad', 0, NULL, NULL, 0, 0),
(11, 4, 0, 'Ratnagiri Zilla Parishad', 0, NULL, NULL, 0, 0),
(12, 4, 0, 'Kolhapur Zilla Parishad', 0, NULL, NULL, 0, 0),
(13, 5, 0, 'Maharashtra Industrial Development Corporation (MIDC)', 0, NULL, NULL, 0, 0),
(14, 5, 0, 'City and Industrial Development Corporation (CIDCO)', 0, NULL, NULL, 0, 0),
(15, 5, 0, 'Maharashtra Housing and Area Development Authority (MHADA)', 0, NULL, NULL, 0, 0),
(16, 6, 0, 'Mumbai Metropolitan Region Development Authority (MMRDA)', 0, NULL, NULL, 0, 0),
(17, 6, 0, 'Pune Metropolitan Region Development Authority (PMRDA)', 0, NULL, NULL, 0, 0),
(18, 7, 0, 'Pune Cantonment Board', 0, NULL, NULL, 0, 0),
(19, 7, 0, 'Dehu Road Cantonment Board', 0, NULL, NULL, 0, 0),
(20, 7, 0, 'Khadki Cantonment Board', 0, NULL, NULL, 0, 0),
(21, 8, 0, 'Pune Town Planning Department', 0, NULL, NULL, 0, 0),
(22, 8, 0, 'Navi Mumbai ULB', 0, NULL, NULL, 0, 0),
(23, 8, 0, 'Thane ULB', 0, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `local_authority_types`
--

DROP TABLE IF EXISTS `local_authority_types`;
CREATE TABLE IF NOT EXISTS `local_authority_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `local_authority_types`
--

INSERT INTO `local_authority_types` (`id`, `name`, `description`, `is_active`) VALUES
(1, 'Municipal Corporations', 'For big cities like Mumbai, Pune, Nagpur.', 0),
(2, 'Municipal Councils / Nagar Parishads', 'For medium-sized towns or cities.', 0),
(3, 'Nagar Panchayats', 'For smaller towns transitioning from rural to urban.', 0),
(4, 'Gram Panchayats / Zilla Parishads', 'For villages and rural areas.', 0),
(5, 'Special Planning Authorities (SPAs)', 'Authorities appointed for specific regions.', 0),
(6, 'Metropolitan Region Development Authorities', 'Development authorities for metropolitan regions.', 0),
(7, 'Cantonment Boards', 'For military or defense areas.', 0),
(8, 'Town Planning Departments / ULBs', 'Urban Local Bodies and Town Planning departments.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `is_active`) VALUES
(1, 'Full access', 1),
(2, 'Verify/assess cess', 1),
(3, 'Submit cess collection', 1),
(4, 'Submit project info, pay cess', 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `project_description` text,
  `project_category_id` bigint UNSIGNED NOT NULL,
  `project_type_id` bigint UNSIGNED NOT NULL,
  `local_authority_id` int NOT NULL,
  `construction_cost` decimal(15,2) NOT NULL,
  `project_start_date` date DEFAULT NULL,
  `project_end_date` date DEFAULT NULL,
  `cess_amount` decimal(15,2) DEFAULT NULL,
  `state_id` bigint UNSIGNED DEFAULT NULL,
  `district_id` bigint UNSIGNED DEFAULT NULL,
  `taluka_id` bigint UNSIGNED DEFAULT NULL,
  `village_id` bigint UNSIGNED DEFAULT NULL,
  `pin_code` varchar(6) DEFAULT NULL,
  `project_address` text,
  `status` enum('Pending','Approved','Rejected','Completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Pending',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_description`, `project_category_id`, `project_type_id`, `local_authority_id`, `construction_cost`, `project_start_date`, `project_end_date`, `cess_amount`, `state_id`, `district_id`, `taluka_id`, `village_id`, `pin_code`, `project_address`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Sample Project Name 1', NULL, 3, 11, 13, 1000000.00, '2023-02-15', '2024-06-02', 5000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Completed', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(2, 'Sample Project Name 2', NULL, 2, 10, 6, 500000.00, '2020-08-24', '2024-07-30', 400.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(3, 'Sample Project Name 3', NULL, 3, 14, 7, 4500000.00, '2020-08-25', '2024-09-26', 25000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(4, 'Sample Project Name 4', NULL, 4, 12, 3, 900000.00, '2020-08-26', '2024-11-23', 7000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(5, 'Sample Project Name 5', NULL, 1, 17, 5, 1700000.00, '2020-08-27', '2025-01-20', 6000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(6, 'Sample Project Name 6', NULL, 3, 13, 8, 4500000.00, '2020-08-28', '2025-03-19', 4000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(7, 'Sample Project Name 7', NULL, 2, 17, 10, 600000.00, '2020-08-29', '2025-05-16', 3000.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Completed', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(8, 'Sample Project Name 8', NULL, 5, 15, 16, 550000.00, '2020-08-30', '2025-07-13', 1500.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48'),
(9, 'Sample Project Name 9', NULL, 2, 16, 14, 600000.00, '2020-08-31', '2025-09-09', 2500.00, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 1, NULL, '2025-08-18 20:04:48', '2025-08-18 20:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `project_categories`
--

DROP TABLE IF EXISTS `project_categories`;
CREATE TABLE IF NOT EXISTS `project_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_categories`
--

INSERT INTO `project_categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Urban Infrastructure', 'Urban residential and commercial construction', 1, '2025-08-06 06:36:31', '2025-08-06 06:36:31'),
(2, 'Public Works', 'Roads, bridges, government infrastructure', 1, '2025-08-06 06:36:31', '2025-08-06 06:36:31'),
(3, 'Industrial', 'Factories, power plants, logistics parks', 1, '2025-08-06 06:36:31', '2025-08-06 06:36:31'),
(4, 'Large Infrastructure', 'Airports, ports, expressways, rail', 1, '2025-08-06 06:36:31', '2025-08-06 06:36:31'),
(5, 'Other', 'Miscellaneous government schemes and smart projects', 1, '2025-08-06 06:36:31', '2025-08-06 06:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `project_documents`
--

DROP TABLE IF EXISTS `project_documents`;
CREATE TABLE IF NOT EXISTS `project_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `work_order` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sanctioned_plan` text NOT NULL,
  `estimate` text NOT NULL,
  `commencement_certificate` text NOT NULL,
  `dpr` text NOT NULL,
  `budget_approval` text NOT NULL,
  `contractor_bills` text NOT NULL COMMENT 'multiple bills file path in json format',
  `land_use_noc` text NOT NULL,
  `approval_letter` text NOT NULL,
  `contractor_agreement` text NOT NULL,
  `Contractual_work_orders` text NOT NULL COMMENT 'multiple paths in json',
  `financial_sanction_notes` text NOT NULL,
  `cess_payment_receipt` text NOT NULL,
  `completion_certificate` text NOT NULL,
  `mime_type` varchar(50) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_documents`
--

INSERT INTO `project_documents` (`id`, `project_id`, `work_order`, `sanctioned_plan`, `estimate`, `commencement_certificate`, `dpr`, `budget_approval`, `contractor_bills`, `land_use_noc`, `approval_letter`, `contractor_agreement`, `Contractual_work_orders`, `financial_sanction_notes`, `cess_payment_receipt`, `completion_certificate`, `mime_type`, `file_size`, `uploaded_at`, `is_verified`, `verified_by`, `verified_at`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '/uploads/projects/1/work_order_phase1.pdf', '', '', '', '', '', '', '', '', '', '', '', '', '', 'application/pdf', 145234, '2025-08-06 07:16:57', 1, NULL, NULL, 2, NULL, '2025-08-06 07:16:57', '2025-08-06 07:16:57'),
(2, 1, '/uploads/projects/1/approved_plan.pdf', '', '', '', '', '', '', '', '', '', '', '', '', '', 'application/pdf', 89324, '2025-08-06 07:16:57', 0, NULL, NULL, 2, NULL, '2025-08-06 07:16:57', '2025-08-06 07:16:57'),
(3, 2, '/uploads/projects/2/cess_receipt.pdf', '', '', '', '', '', '', '', '', '', '', '', '', '', 'application/pdf', 43784, '2025-08-06 07:16:57', 1, NULL, NULL, 5, NULL, '2025-08-06 07:16:57', '2025-08-06 07:16:57'),
(4, 2, '/uploads/projects/2/contractor_agreement_lt.pdf', '', '', '', '', '', '', '', '', '', '', '', '', '', 'application/pdf', 129384, '2025-08-06 07:16:57', 1, NULL, NULL, 5, NULL, '2025-08-06 07:16:57', '2025-08-06 07:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `project_types`
--

DROP TABLE IF EXISTS `project_types`;
CREATE TABLE IF NOT EXISTS `project_types` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `cess_trigger` text NOT NULL,
  `how_cess_is_paid` text NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_types`
--

INSERT INTO `project_types` (`id`, `category_id`, `name`, `description`, `cess_trigger`, `how_cess_is_paid`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Commercial Buildings', 'Includes malls, offices, IT parks', 'At approval / during execution', 'Builder/developer uploads documents, pays 1%', 1, '2025-08-06 06:37:24', '2025-08-06 07:04:12'),
(2, 1, 'Residential Housing', 'Apartments, townships', 'Before commencement or during', 'Self-assessment online', 1, '2025-08-06 06:37:24', '2025-08-06 07:04:31'),
(3, 1, 'Institutional Buildings', 'Schools, hospitals', 'Usually by contractor/government', 'Uploaded by employer or contractor', 1, '2025-08-06 06:37:24', '2025-08-06 07:04:48'),
(4, 1, 'Hospitality Projects', 'Hotels and resorts', 'Large hotels submit at license', 'Usually before construction starts', 1, '2025-08-06 06:37:24', '2025-08-06 07:05:03'),
(5, 2, 'Roads & Highways', 'Construction of highways, NH, SH', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(6, 2, 'Metro Infrastructure', 'Metro stations and tunnels', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(7, 2, 'Bridges & Flyovers', 'Overpasses and river bridges', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(8, 2, 'Water Supply Projects', 'Sewer lines, water pipelines', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(9, 3, 'Industrial Units', 'Factories, industrial sheds', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(10, 3, 'Power Plants', 'Thermal, solar, hydel', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(11, 3, 'Logistics Parks', 'Freight terminals, warehouses', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(12, 4, 'Expressways', 'Long-distance greenfield corridors', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(13, 4, 'Airport Terminals', 'New terminals or expansion', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(14, 4, 'Port Development', 'Docks, shipping yards', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(15, 5, 'Slum Rehabilitation', 'SRD or MHADA projects', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(16, 5, 'Smart City Projects', 'IT infra, smart lights', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24'),
(17, 5, 'Affordable Housing', 'PMAY or low-cost housing', '', '', 1, '2025-08-06 06:37:24', '2025-08-06 06:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `project_work_orders`
--

DROP TABLE IF EXISTS `project_work_orders`;
CREATE TABLE IF NOT EXISTS `project_work_orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `work_order_number` varchar(100) NOT NULL,
  `work_order_date` datetime NOT NULL,
  `work_order_amount` decimal(15,2) NOT NULL,
  `work_order_cess_amount` decimal(15,2) NOT NULL,
  `work_order_gst_cess_amount` decimal(15,2) NOT NULL,
  `work_order_administrative_cost` decimal(15,2) NOT NULL,
  `work_order_effective_cess_amount` decimal(15,2) NOT NULL,
  `work_order_approval_letter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `employer_id` int NOT NULL,
  `manager_id` int NOT NULL,
  `engineer_id` int NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_work_orders`
--

INSERT INTO `project_work_orders` (`id`, `project_id`, `work_order_number`, `work_order_date`, `work_order_amount`, `work_order_cess_amount`, `work_order_gst_cess_amount`, `work_order_administrative_cost`, `work_order_effective_cess_amount`, `work_order_approval_letter`, `employer_id`, `manager_id`, `engineer_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '565456', '2020-05-12 00:00:00', 1000000.00, 10000.00, 10250.00, 102.50, 10147.50, '', 1, 0, 0, 'Completed', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(2, 2, '232654', '2021-08-15 00:00:00', 500000.00, 5000.00, 5125.00, 51.25, 5073.75, '', 2, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(3, 3, '856923', '2019-04-28 00:00:00', 3000000.00, 30000.00, 30750.00, 307.50, 30442.50, '', 3, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(4, 4, '582946', '2020-05-12 00:00:00', 900000.00, 9000.00, 9225.00, 92.25, 9132.75, '', 4, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(5, 5, '456789', '2021-08-15 00:00:00', 1700000.00, 17000.00, 17425.00, 174.25, 17250.75, '', 5, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(6, 6, '261548', '2019-04-28 00:00:00', 400000.00, 4000.00, 4100.00, 41.00, 4059.00, '', 6, 0, 0, 'Completed', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(7, 7, '986325', '2020-05-12 00:00:00', 600000.00, 6000.00, 6150.00, 61.50, 6088.50, '', 7, 0, 0, 'Completed', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(8, 8, '458963', '2021-08-15 00:00:00', 550000.00, 5500.00, 5637.50, 56.37, 5581.12, '', 8, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(9, 9, '142756', '2019-04-28 00:00:00', 600000.00, 6000.00, 6150.00, 61.50, 6088.50, '', 9, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48'),
(10, 3, '582966', '2019-04-28 00:00:00', 1500000.00, 15000.00, 15375.00, 153.75, 15221.25, '', 10, 0, 0, 'Pending', 1, 0, '2025-08-18 14:34:48', '2025-08-18 20:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_transactions`
--

DROP TABLE IF EXISTS `razorpay_transactions`;
CREATE TABLE IF NOT EXISTS `razorpay_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL,
  `bulk_invoice_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'INR',
  `status` enum('created','paid','failed') NOT NULL DEFAULT 'created',
  `request_data` json DEFAULT NULL,
  `response_data` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `razorpay_transactions`
--

INSERT INTO `razorpay_transactions` (`id`, `order_id`, `payment_id`, `signature`, `user_id`, `bulk_invoice_id`, `amount`, `currency`, `status`, `request_data`, `response_data`, `created_at`) VALUES
(1, 'order_R6qt303ClfnIY2', NULL, NULL, 1, 6, 63320.40, 'INR', 'created', '0', NULL, '2025-08-18 15:47:08'),
(2, 'order_R6qxyWYCTBYlcZ', NULL, NULL, 1, 7, 63320.40, 'INR', 'created', '0', NULL, '2025-08-18 15:51:48'),
(3, 'order_R6r2lXxrnxoMKK', NULL, NULL, 1, 8, 63320.40, 'INR', 'created', '0', NULL, '2025-08-18 15:56:20'),
(4, 'order_R6rHFQOztCgkFC', NULL, NULL, 1, 9, 63320.40, 'INR', 'created', '0', NULL, '2025-08-18 16:10:03'),
(5, 'order_R6rRsV1332elzo', NULL, NULL, 1, 11, 63320.40, 'INR', 'created', '{\"notes\": {\"user_id\": 1, \"bulk_invoice_id\": 11}, \"amount\": 6332040, \"receipt\": \"bulk_invoice_11\", \"currency\": \"INR\"}', NULL, '2025-08-18 16:20:06'),
(6, 'order_R6sdlHWLc4YXXc', NULL, NULL, 1, 12, 63320.40, 'INR', 'created', '{\"notes\": {\"user_id\": 1, \"bulk_invoice_id\": 12}, \"amount\": 6332040, \"receipt\": \"bulk_invoice_12\", \"currency\": \"INR\"}', NULL, '2025-08-18 17:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Admin (Welfare Board)', 'Full access'),
(2, 'Assessing Officer', 'Verify/assess cess'),
(3, 'Local Authority', 'Submit cess collection'),
(4, 'Employers/Builders', 'Submit project info, pay cess'),
(5, 'Contractors (optional)', 'Linked with projects'),
(6, 'Manager', 'Project Workorder Manager'),
(7, 'Engineer', 'Project Workorder Engineer');

-- --------------------------------------------------------

--
-- Table structure for table `talukas`
--

DROP TABLE IF EXISTS `talukas`;
CREATE TABLE IF NOT EXISTS `talukas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `district_id` bigint UNSIGNED NOT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `updated_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talukas`
--

INSERT INTO `talukas` (`id`, `name`, `district_id`, `active_status`, `created_by`, `created_datetime`, `updated_by`, `updated_datetime`) VALUES
(1, 'Akole', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(2, 'Sangamner', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(3, 'Kopargaon', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(4, 'Shrirampur', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(5, 'Rahata', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(6, 'Rahuri', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(7, 'Parner', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(8, 'Pathardi', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(9, 'Nevasa', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(10, 'Shevgaon', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(11, 'Ahmednagar', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(12, 'Karjat', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(13, 'Shrigonda', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(14, 'Jamkhed', 1, 1, 1, '2025-08-10 12:50:30', 1, '2025-08-10 12:55:39'),
(15, 'Akola', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:46'),
(16, 'Akot', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:50'),
(17, 'Telhara', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:52'),
(18, 'Balapur', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:54'),
(19, 'Patur', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:55'),
(20, 'Murtizapur', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:57'),
(21, 'Barshitakli', 2, 1, 1, '2025-08-10 12:52:53', 1, '2025-08-10 12:55:59'),
(22, 'Alibag', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(23, 'Pen', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(24, 'Panvel', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(25, 'Karjat', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(26, 'Khalapur', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(27, 'Uran', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(28, 'Murud', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(29, 'Roha', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(30, 'Sudhagad', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(31, 'Mangaon', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(32, 'Tala', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(33, 'Shrivardhan', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(34, 'Mahad', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(35, 'Poladpur', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(36, 'Mhasla', 27, 1, 1, '2025-08-10 12:57:55', 1, '2025-08-10 12:57:55'),
(37, 'Pune City', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(38, 'Haveli', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(39, 'Pimpri-Chinchwad', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(40, 'Maval', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(41, 'Mulshi', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(42, 'Velhe', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(43, 'Bhor', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(44, 'Baramati', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(45, 'Indapur', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(46, 'Daund', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(47, 'Shirur', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(48, 'Khed', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(49, 'Junnar', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09'),
(50, 'Ambegaon', 26, 1, 1, '2025-08-10 12:59:09', 1, '2025-08-10 12:59:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `phone` int NOT NULL,
  `role` int NOT NULL,
  `local_authority_id` int NOT NULL,
  `gstn` varchar(20) NOT NULL,
  `pancard` varchar(10) NOT NULL,
  `aadhaar` varchar(12) NOT NULL,
  `is_active` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `local_authority_id`, `gstn`, `pancard`, `aadhaar`, `is_active`) VALUES
(1, 'Super Admin', 'superadmin@gmail.com', '17c4520f6cfd1ab53d8745e84681eb49', 0, 1, 0, '', '', '', 1),
(2, 'Engineer 1', 'engineer1@gmail.com', '17c4520f6cfd1ab53d8745e84681eb49', 1234568978, 7, 0, '123456789654', 'ADFTD5668J', '123456789123', 1),
(3, 'Engineer 2', 'engineer2@gmail.com', '17c4520f6cfd1ab53d8745e84681eb49', 1234567899, 7, 0, '4548787799798', 'TGHYUP5669', '456789203156', 1);

-- --------------------------------------------------------

--
-- Table structure for table `villages`
--

DROP TABLE IF EXISTS `villages`;
CREATE TABLE IF NOT EXISTS `villages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `taluka_id` bigint UNSIGNED NOT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `updated_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_villages_taluka_id` (`taluka_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `villages`
--

INSERT INTO `villages` (`id`, `name`, `taluka_id`, `active_status`, `created_by`, `created_datetime`, `updated_by`, `updated_datetime`) VALUES
(1, 'Uran', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(2, 'Kegaon', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(3, 'Bori', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(4, 'Duran', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(5, 'Dongri', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(6, 'Raanvad', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(7, 'Nagothane', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(8, 'Chirner', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(9, 'Gavan', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(10, 'Nhava', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(11, 'Sonari', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(12, 'Awre', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(13, 'Jashkhar', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(14, 'Vadhavan', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(15, 'Devi', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(16, 'Kadu', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(17, 'Kunda', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(18, 'Navghar', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(19, 'Dhasai', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(20, 'Chanje', 27, 1, 1, '2025-08-10 13:02:46', 1, '2025-08-10 13:02:46'),
(21, 'Shirur', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(22, 'Khandale', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(23, 'Nimgaon Mahalungi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(24, 'Nhavare', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(25, 'Wajegaon', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(26, 'Tardobawadi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(27, 'Vajra', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(28, 'Shirasgaon Deshmukh', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(29, 'Inamgaon', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(30, 'Kardilwadi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(31, 'Mhalunge', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(32, 'Kanhur Mesai', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(33, 'Karandi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(34, 'Shinde Wadi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(35, 'Phalake', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(36, 'Nimone', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(37, 'Shikrapur', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(38, 'Wadgaon Rasai', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(39, 'Saradwadi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(40, 'Ranjangaon', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22'),
(41, 'Parodi', 47, 1, 1, '2025-08-10 13:04:22', 1, '2025-08-10 13:04:22');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `villages`
--
ALTER TABLE `villages`
  ADD CONSTRAINT `fk_villages_taluka_id` FOREIGN KEY (`taluka_id`) REFERENCES `talukas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
