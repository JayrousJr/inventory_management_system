-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2023 at 08:10 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'usersView', 'web', '2023-12-01 02:12:39', '2023-12-01 02:46:42', NULL),
(2, 'usersCreate', 'web', '2023-12-01 02:12:50', '2023-12-01 02:46:29', NULL),
(3, 'usersUpdate', 'web', '2023-12-01 02:12:58', '2023-12-01 02:46:17', NULL),
(4, 'usersDelete', 'web', '2023-12-01 02:13:05', '2023-12-01 02:46:04', NULL),
(5, 'usersRestore', 'web', '2023-12-01 02:13:14', '2023-12-01 02:45:49', NULL),
(6, 'ViewAny', 'web', '2023-12-01 02:47:31', '2023-12-01 02:47:31', NULL),
(7, 'saleCreate', 'web', '2023-12-01 02:57:16', '2023-12-01 03:01:29', '2023-12-01 03:01:29'),
(8, 'SaleDelete', 'web', '2023-12-01 02:57:41', '2023-12-01 03:01:20', '2023-12-01 03:01:20'),
(9, 'salesView', 'web', '2023-12-01 03:00:47', '2023-12-01 03:00:47', NULL),
(10, 'salesCreate', 'web', '2023-12-01 03:00:57', '2023-12-01 03:00:57', NULL),
(11, 'categoryView', 'web', '2023-12-01 03:05:08', '2023-12-01 03:05:08', NULL),
(12, 'categoryEdit', 'web', '2023-12-01 03:05:15', '2023-12-01 03:05:15', NULL),
(13, 'categoryDelete', 'web', '2023-12-01 03:05:27', '2023-12-01 03:05:27', NULL),
(14, 'categoryRestore', 'web', '2023-12-01 03:05:37', '2023-12-01 03:05:37', NULL),
(15, 'categoryCreate', 'web', '2023-12-01 03:08:34', '2023-12-01 03:08:34', NULL),
(16, 'debtView', 'web', '2023-12-01 03:12:57', '2023-12-01 03:12:57', NULL),
(17, 'debtCreate', 'web', '2023-12-01 03:13:09', '2023-12-01 03:13:09', NULL),
(18, 'debtUpdate', 'web', '2023-12-01 03:13:17', '2023-12-01 03:13:17', NULL),
(19, 'expenseEdit', 'web', '2023-12-01 08:32:25', '2023-12-01 08:32:25', NULL),
(20, 'expenseCreate', 'web', '2023-12-01 08:32:33', '2023-12-01 08:32:33', NULL),
(21, 'expenseDelete', 'web', '2023-12-01 08:32:42', '2023-12-01 08:32:42', NULL),
(22, 'expenseViewAny', 'web', '2023-12-01 08:33:22', '2023-12-01 08:33:22', NULL),
(23, 'debtViewAny', 'web', '2023-12-01 08:33:34', '2023-12-01 08:33:34', NULL),
(24, 'saleViewAny', 'web', '2023-12-01 08:33:45', '2023-12-01 08:33:45', NULL),
(25, 'userViewAny', 'web', '2023-12-01 08:33:58', '2023-12-01 08:33:58', NULL),
(26, 'categoryViewAny', 'web', '2023-12-01 08:34:09', '2023-12-01 08:34:09', NULL),
(27, 'expenseView', 'web', '2023-12-01 08:40:39', '2023-12-01 08:40:39', NULL),
(28, 'productView', 'web', '2023-12-01 08:47:40', '2023-12-01 08:47:40', NULL),
(29, 'productViewAny', 'web', '2023-12-01 08:47:47', '2023-12-01 08:47:47', NULL),
(30, 'expensetypeViewAny', 'web', '2023-12-01 08:51:41', '2023-12-01 08:51:41', NULL),
(31, 'expensetypeView', 'web', '2023-12-01 08:52:02', '2023-12-01 08:52:02', NULL),
(32, 'expensetypeCreate', 'web', '2023-12-01 08:53:04', '2023-12-01 08:53:04', NULL),
(33, 'expensetypeEdit', 'web', '2023-12-01 08:53:22', '2023-12-01 08:53:22', NULL),
(34, 'expensetypeDelete', 'web', '2023-12-01 08:53:37', '2023-12-01 08:53:37', NULL),
(35, 'shopViewAny', 'web', '2023-12-01 08:58:42', '2023-12-01 08:58:42', NULL),
(36, 'shopView', 'web', '2023-12-01 08:59:06', '2023-12-01 08:59:06', NULL),
(37, 'storeViewAny', 'web', '2023-12-01 09:01:44', '2023-12-01 09:01:44', NULL),
(38, 'storeView', 'web', '2023-12-01 09:02:12', '2023-12-01 09:02:12', NULL),
(39, 'storeCreate', 'web', '2023-12-01 09:02:29', '2023-12-01 09:02:29', NULL),
(40, 'storeDelete', 'web', '2023-12-01 09:03:23', '2023-12-01 09:03:23', NULL),
(41, 'purchaseViewAny', 'web', '2023-12-01 09:05:53', '2023-12-01 09:05:53', NULL),
(42, 'purchaseView', 'web', '2023-12-01 09:06:11', '2023-12-01 09:06:11', NULL),
(43, 'purchaseCreate', 'web', '2023-12-01 09:06:46', '2023-12-01 09:06:46', NULL),
(44, 'purchaseEdit', 'web', '2023-12-01 09:07:04', '2023-12-01 09:07:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
