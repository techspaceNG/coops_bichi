-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 02:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coops_bichi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('superadmin','admin') NOT NULL,
  `is_locked` tinyint(1) DEFAULT 0,
  `failed_attempts` int(11) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `password_changed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `email`, `role`, `is_locked`, `failed_attempts`, `last_login`, `password_changed_at`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', '$2y$10$hXkAnPbsAo34clkqb6KX5eu4AlJwn3JAejb3AYg0jDRV.J.qYhJ/i', 'Super Administrator', 'superadmin@fcetbichi.edu.ng', 'superadmin', 0, 0, NULL, NULL, '2025-04-03 10:52:59', '2025-05-18 14:47:08'),
(2, 'admin', '$2y$10$8wDFnMqGUhvnsQvwYf1H6.4tq8f0N1/LAXE8a4VfTE4ce59ac3t3y', 'Administrator', 'admin@example.com', 'admin', 0, 0, NULL, NULL, '2025-04-03 14:23:26', '2025-05-07 12:59:18'),
(3, 'testadmin', '$2y$10$pDPSvd.OOFjh77wyKRMrfuY7oAKu3n8/ilHq5So7iaImaFkaG9aYS', 'test admin', 'testadmin@gmail.com', 'admin', 0, 0, NULL, NULL, '2025-04-06 09:15:05', '2025-04-06 10:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
  `category` enum('general','important','event') NOT NULL DEFAULT 'general',
  `publish_date` datetime DEFAULT current_timestamp(),
  `expire_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type` enum('admin','member') NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_type` varchar(50) DEFAULT 'general',
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `user_type`, `action`, `action_type`, `details`, `ip_address`, `timestamp`) VALUES
(1, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-04-03 14:23:50'),
(2, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-04-03 21:05:53'),
(3, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-03 22:31:15'),
(4, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-04 12:56:32'),
(5, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-04-04 13:06:37'),
(6, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 13:10:57'),
(7, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:13:04'),
(8, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:15:42'),
(9, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:22:27'),
(10, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:22:41'),
(11, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:24:11'),
(12, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:25:11'),
(13, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:28:08'),
(14, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:31:26'),
(15, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:31:29'),
(16, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 13:33:08'),
(17, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:33:08'),
(18, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-04-04 13:33:38'),
(19, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 13:34:49'),
(20, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:34:49'),
(21, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:37:30'),
(22, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:39:20'),
(23, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:40:14'),
(24, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:40:18'),
(25, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:44:19'),
(26, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:44:56'),
(27, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:46:05'),
(28, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:47:44'),
(29, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:47:48'),
(30, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:47:55'),
(31, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:48:15'),
(32, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:48:19'),
(33, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:48:36'),
(34, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:49:33'),
(35, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:50:09'),
(36, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:50:16'),
(37, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:50:36'),
(38, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:50:43'),
(39, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:51:17'),
(40, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:51:59'),
(41, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:52:31'),
(42, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:52:49'),
(43, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:54:02'),
(44, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:54:08'),
(45, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 13:54:31'),
(46, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:54:31'),
(47, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:55:21'),
(48, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:56:04'),
(49, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:56:08'),
(50, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:42'),
(51, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:45'),
(52, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:47'),
(53, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:47'),
(54, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:48'),
(55, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:48'),
(56, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:49'),
(57, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:49'),
(58, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:49'),
(59, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:50'),
(60, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:50'),
(61, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:50'),
(62, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:50'),
(63, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:50'),
(64, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:51'),
(65, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:51'),
(66, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:51'),
(67, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:51'),
(68, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:52'),
(69, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:57:58'),
(70, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 13:58:57'),
(71, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 13:58:57'),
(72, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:01:13'),
(73, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:02:13'),
(74, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:02:15'),
(75, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:02:18'),
(76, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:02:47'),
(77, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:06:11'),
(78, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:08:35'),
(79, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:09:53'),
(80, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:12:37'),
(81, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:12:41'),
(82, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:12:46'),
(83, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:12:53'),
(84, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:12:54'),
(85, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:13:28'),
(86, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:13:53'),
(87, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:13:57'),
(88, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:16:12'),
(89, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:20:21'),
(90, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:20:36'),
(91, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:21:15'),
(92, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:21:20'),
(93, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:21:49'),
(94, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:22:29'),
(95, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:22:58'),
(96, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:23:53'),
(97, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:23:57'),
(98, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:24:13'),
(99, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:25:29'),
(100, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:25:32'),
(101, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:34:10'),
(102, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:35:10'),
(103, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:46:17'),
(104, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 14:48:42'),
(105, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:06:32'),
(106, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:20'),
(107, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:38'),
(108, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:39'),
(109, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:40'),
(110, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:49'),
(111, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:54'),
(112, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:11:56'),
(113, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:12:02'),
(114, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:12:04'),
(115, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:12:05'),
(116, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:12:16'),
(117, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:13:36'),
(118, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:14:19'),
(119, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:14:42'),
(120, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:15:51'),
(121, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:16:01'),
(122, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:16:06'),
(123, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:16:21'),
(124, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:16:27'),
(125, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:17:09'),
(126, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:17:19'),
(127, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:15'),
(128, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:26'),
(129, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:27'),
(130, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:32'),
(131, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:36'),
(132, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:41'),
(133, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:43'),
(134, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:44'),
(135, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:45'),
(136, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:45'),
(137, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:45'),
(138, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:47'),
(139, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:47'),
(140, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:49'),
(141, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:49'),
(142, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:50'),
(143, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:51'),
(144, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:52'),
(145, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:52'),
(146, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:52'),
(147, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:18:52'),
(148, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 18:19:43'),
(149, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:19:43'),
(150, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:19:48'),
(151, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:21:43'),
(152, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:21:52'),
(153, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:22:01'),
(154, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:22:22'),
(155, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:24:40'),
(156, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:25:35'),
(157, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:25:51'),
(158, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:25:58'),
(159, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:25:58'),
(160, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:26:09'),
(161, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:27:07'),
(162, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:27:16'),
(163, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:27:37'),
(164, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:27:43'),
(165, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:27:49'),
(166, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 18:29:04'),
(167, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:29:04'),
(168, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:29:08'),
(169, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:29:30'),
(170, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:31:05'),
(171, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:33:51'),
(172, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:34:44'),
(173, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:34:57'),
(174, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:35:14'),
(175, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:37:31'),
(176, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:37:41'),
(177, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:37:51'),
(178, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:37:57'),
(179, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:37:58'),
(180, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:37:58'),
(181, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:38:14'),
(182, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:38:18'),
(183, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:12'),
(184, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:20'),
(185, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:26'),
(186, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:42'),
(187, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:46'),
(188, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:48'),
(189, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:49'),
(190, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:39:51'),
(191, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:40:07'),
(192, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:40:13'),
(193, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:40:24'),
(194, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:40:26'),
(195, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:41:08'),
(196, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:41:28'),
(197, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:43:29'),
(198, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:43:30'),
(199, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:20'),
(200, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:24'),
(201, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:28'),
(202, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:30'),
(203, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:32'),
(204, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:36'),
(205, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:44:39'),
(206, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:05'),
(207, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:14'),
(208, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:17'),
(209, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:23'),
(210, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:26'),
(211, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:37'),
(212, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:45:40'),
(213, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:09'),
(214, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:09'),
(215, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:22'),
(216, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:23'),
(217, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:24'),
(218, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:25'),
(219, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:26'),
(220, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:28'),
(221, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:46:32'),
(222, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:47:10'),
(223, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:47:31'),
(224, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:47:34'),
(225, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:47:36'),
(226, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:47:59'),
(227, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:50:16'),
(228, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:50:19'),
(229, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:50:22'),
(230, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:50:27'),
(231, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:50:48'),
(232, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 18:51:37'),
(233, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:51:37'),
(234, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 18:51:58'),
(235, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:51:58'),
(236, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 18:54:29'),
(237, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 18:54:29'),
(238, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 19:02:18'),
(239, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 19:03:44'),
(240, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:21:17'),
(241, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:00'),
(242, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:04'),
(243, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:20'),
(244, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:31'),
(245, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:36'),
(246, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:38'),
(247, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:22:42'),
(248, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 19:23:57'),
(249, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:23:57'),
(250, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:07'),
(251, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:09'),
(252, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:10'),
(253, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:12'),
(254, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:17'),
(255, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:28'),
(256, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:31'),
(257, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:33'),
(258, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:34'),
(259, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:40'),
(260, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:47'),
(261, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:51'),
(262, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:24:56'),
(263, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:02'),
(264, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:21'),
(265, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:23'),
(266, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:25'),
(267, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:44'),
(268, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:46'),
(269, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:32:47'),
(270, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:41:49'),
(271, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:41:51'),
(272, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:42:04'),
(273, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:42:14'),
(274, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:42:42'),
(275, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:42:47'),
(276, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:44:44'),
(277, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:45:49'),
(278, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:38'),
(279, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:40'),
(280, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:41'),
(281, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:43'),
(282, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:45'),
(283, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:46'),
(284, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:46:48'),
(285, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:48:00'),
(286, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:48:10'),
(287, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:48:37'),
(288, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:49:17'),
(289, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:49:23'),
(290, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:50:15'),
(291, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:50:30'),
(292, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:50:32'),
(293, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:51:01'),
(294, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:51:07'),
(295, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:51:11'),
(296, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:51:15'),
(297, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:52:02'),
(298, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:52:08'),
(299, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:52:13'),
(300, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-04 19:52:16'),
(301, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-04 19:52:20'),
(302, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:54:18'),
(303, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:54:27'),
(304, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:54:36'),
(305, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:54:45'),
(306, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:54:59'),
(307, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:55:05'),
(308, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:55:45'),
(309, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 19:59:07'),
(310, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:01:47'),
(311, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:01:50'),
(312, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:04:56'),
(313, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:05:07'),
(314, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:06:57'),
(315, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:08:26'),
(316, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:12:03'),
(317, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:18:34'),
(318, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:18:38'),
(319, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:19:05'),
(320, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:19:08'),
(321, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:19:11'),
(322, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:38:15'),
(323, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:38:16'),
(324, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:38:19'),
(325, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:38:22'),
(326, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:38:27'),
(327, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:38:34'),
(328, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:49:00'),
(329, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-04 20:52:50'),
(330, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 20:55:54'),
(331, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:55:55'),
(332, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:29'),
(333, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:34'),
(334, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:35'),
(335, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:37'),
(336, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:40'),
(337, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:42'),
(338, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:53'),
(339, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:56:59'),
(340, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:57:09'),
(341, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:57:53'),
(342, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:58:10'),
(343, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:58:13'),
(344, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:58:21'),
(345, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:58:25'),
(346, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:58:25'),
(347, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:59:15'),
(348, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:59:20'),
(349, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 20:59:24'),
(350, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:00:07'),
(351, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:00:28'),
(352, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:00:48'),
(353, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:00:49'),
(354, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:00:53'),
(355, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:01:23'),
(356, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-04 21:01:26'),
(357, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:02:36'),
(358, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:02:51'),
(359, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:02:53'),
(360, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:00'),
(361, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:07'),
(362, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:20'),
(363, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:27'),
(364, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:32'),
(365, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:38'),
(366, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:03:40'),
(367, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:04:27'),
(368, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:04:36'),
(369, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:11:17'),
(370, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:11:19'),
(371, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:11:21'),
(372, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-04 21:11:24'),
(373, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:11:42'),
(374, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-04 21:11:45'),
(375, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:12:31'),
(376, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:12:31'),
(377, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:18:21'),
(378, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:18:29'),
(379, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:20:09'),
(380, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:20:15'),
(381, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:22:48'),
(382, 1, 'admin', 'Added deduction of ₦5,000.00 for member with COOPS No. COOPS/04/002', 'general', '{\"type\":\"savings\",\"member_id\":1}', '::1', '2025-04-04 21:22:49'),
(383, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:22:49'),
(384, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:23:18'),
(385, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:23:22'),
(386, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:25:43'),
(387, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:25:57'),
(388, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:19'),
(389, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:20'),
(390, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:30'),
(391, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:34'),
(392, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:46'),
(393, 1, 'admin', 'Added deduction of ₦2,000.00 for member with COOPS No. COOPS/04/002', 'general', '{\"type\":\"savings\",\"member_id\":1}', '::1', '2025-04-04 21:26:46'),
(394, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:46'),
(395, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:53'),
(396, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:26:56'),
(397, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-04 21:27:18'),
(398, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 21:27:49'),
(399, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:27:49'),
(400, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:27:54'),
(401, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:28:05'),
(402, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:30:09'),
(403, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:30:21'),
(404, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:34:07'),
(405, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:34:13'),
(406, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:34:18'),
(407, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:37:37'),
(408, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:37:39'),
(409, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:03'),
(410, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:05'),
(411, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:07'),
(412, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:08'),
(413, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:10'),
(414, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:14'),
(415, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:21'),
(416, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:29'),
(417, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:38:33'),
(418, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:40:31'),
(419, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:42:00'),
(420, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:42:02'),
(421, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-04 21:42:07'),
(422, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:43:02'),
(423, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:43:11'),
(424, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:43:12'),
(425, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:53:26'),
(426, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:54:29'),
(427, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:54:32'),
(428, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:55:01'),
(429, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:55:02'),
(430, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:55:12'),
(431, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:55:16'),
(432, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:56:14'),
(433, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:56:19'),
(434, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:56:37'),
(435, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:56:46'),
(436, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:56:58'),
(437, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:57:03'),
(438, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 21:57:09'),
(439, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:01:12'),
(440, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:01:36'),
(441, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:10:07'),
(442, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:15:36');
INSERT INTO `audit_logs` (`id`, `user_id`, `user_type`, `action`, `action_type`, `details`, `ip_address`, `timestamp`) VALUES
(443, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:15:39'),
(444, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:16:12'),
(445, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:16:39'),
(446, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:17:13'),
(447, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:17:27'),
(448, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:18:31'),
(449, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:18:33'),
(450, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:18:37'),
(451, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:18:39'),
(452, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:18:41'),
(453, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:59:05'),
(454, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:59:11'),
(455, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 22:59:15'),
(456, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:03:22'),
(457, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:03:25'),
(458, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:34'),
(459, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:39'),
(460, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:40'),
(461, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:42'),
(462, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:43'),
(463, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:45'),
(464, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:48'),
(465, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:51'),
(466, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:52'),
(467, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:53'),
(468, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:10:54'),
(469, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:31'),
(470, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:34'),
(471, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:37'),
(472, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:38'),
(473, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:40'),
(474, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:50'),
(475, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:54'),
(476, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:11:59'),
(477, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:13:00'),
(478, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:17:12'),
(479, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:20:18'),
(480, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:21:02'),
(481, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:22:20'),
(482, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:23:18'),
(483, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:23:24'),
(484, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:23:33'),
(485, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:29:05'),
(486, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:29:07'),
(487, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:32:06'),
(488, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:32:06'),
(489, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:32:16'),
(490, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:32:53'),
(491, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:32:55'),
(492, 1, 'admin', 'Approved loan application #1', 'general', '{\"type\":\"loan\",\"loan_id\":1,\"member_id\":1,\"amount\":\"100000.00\",\"loan_record_id\":1}', '::1', '2025-04-04 23:32:55'),
(493, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:35:07'),
(494, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:35:07'),
(495, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:35:11'),
(496, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:35:34'),
(497, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:35:36'),
(498, 1, 'admin', 'Approved loan application #1', 'general', '{\"type\":\"loan\",\"loan_id\":1,\"member_id\":1,\"amount\":100000,\"loan_record_id\":2}', '::1', '2025-04-04 23:35:37'),
(499, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:35:37'),
(500, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:36:21'),
(501, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-04 23:36:32'),
(502, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-04 23:37:30'),
(503, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:31'),
(504, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:35'),
(505, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:40'),
(506, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:41'),
(507, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:45'),
(508, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:51'),
(509, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:37:51'),
(510, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:05'),
(511, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:07'),
(512, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:08'),
(513, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:12'),
(514, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:12'),
(515, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:17'),
(516, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:17'),
(517, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:23'),
(518, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:31'),
(519, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:36'),
(520, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:38:42'),
(521, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:39:15'),
(522, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:39:18'),
(523, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:39:40'),
(524, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:39:42'),
(525, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:39:47'),
(526, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:40:01'),
(527, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:40:07'),
(528, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:40:12'),
(529, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:40:14'),
(530, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:40:27'),
(531, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:44:19'),
(532, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:44:43'),
(533, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:45:12'),
(534, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:45:18'),
(535, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:49:44'),
(536, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:50:37'),
(537, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:51:09'),
(538, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:51:16'),
(539, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:51:34'),
(540, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:51:41'),
(541, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:51:56'),
(542, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:52:16'),
(543, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:55:25'),
(544, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:55:33'),
(545, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:55:36'),
(546, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-04 23:57:03'),
(547, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 00:01:03'),
(548, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 00:01:09'),
(549, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:16:02'),
(550, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:16:09'),
(551, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:16:14'),
(552, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:16:18'),
(553, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:16:21'),
(554, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:16:27'),
(555, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:24:45'),
(556, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:24:53'),
(557, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:24:57'),
(558, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:25:02'),
(559, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:25:07'),
(560, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:25:17'),
(561, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:25:20'),
(562, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:25:23'),
(563, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:26:24'),
(564, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:27:14'),
(565, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-05 06:27:16'),
(566, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:27:27'),
(567, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:30:41'),
(568, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:31:03'),
(569, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-05 06:31:05'),
(570, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:37:45'),
(571, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:37:55'),
(572, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:37:58'),
(573, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:38:18'),
(574, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:39:09'),
(575, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:39:10'),
(576, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:53:28'),
(577, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:53:29'),
(578, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:53:30'),
(579, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:53:46'),
(580, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:53:47'),
(581, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:54:44'),
(582, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:55:02'),
(583, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:55:12'),
(584, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:55:24'),
(585, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:55:34'),
(586, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:56:25'),
(587, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:56:31'),
(588, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:56:37'),
(589, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:56:54'),
(590, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:56:57'),
(591, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:57:10'),
(592, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 06:57:12'),
(593, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:08:46'),
(594, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:08:51'),
(595, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:09:05'),
(596, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:09:59'),
(597, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:11:45'),
(598, 1, 'admin', 'Recorded loan repayment of ₦10,000.00 for Augustine Ada Okewu', 'general', '{\"type\":\"loan_repayment\",\"loan_id\":2,\"amount\":10000,\"member_id\":1,\"new_balance\":95000}', '::1', '2025-04-05 07:11:46'),
(599, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:11:49'),
(600, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:11:59'),
(601, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:12:07'),
(602, 1, 'admin', 'Recorded loan repayment of ₦10,000.00 for Augustine Ada Okewu', 'general', '{\"type\":\"loan_repayment\",\"loan_id\":2,\"amount\":10000,\"member_id\":1,\"new_balance\":85000}', '::1', '2025-04-05 07:12:08'),
(603, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:12:17'),
(604, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:13:53'),
(605, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:13:59'),
(606, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:14:08'),
(607, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:14:10'),
(608, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:14:45'),
(609, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:14:47'),
(610, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:15:10'),
(611, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:15:19'),
(612, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:15:22'),
(613, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:15:24'),
(614, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 07:15:45'),
(615, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 07:25:01'),
(616, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:25:01'),
(617, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:25:11'),
(618, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:25:18'),
(619, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:25:22'),
(620, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:25:28'),
(621, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:27:19'),
(622, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:27:27'),
(623, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:15'),
(624, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:20'),
(625, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:22'),
(626, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:23'),
(627, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:24'),
(628, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:37'),
(629, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:38'),
(630, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:39'),
(631, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:40'),
(632, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:36:40'),
(633, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 07:37:11'),
(634, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:37:11'),
(635, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:37:16'),
(636, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:37:20'),
(637, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:38:33'),
(638, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:38:54'),
(639, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:38:56'),
(640, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:40:02'),
(641, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:40:06'),
(642, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:40:26'),
(643, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:40:29'),
(644, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:40:31'),
(645, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:41:23'),
(646, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:41:29'),
(647, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:29'),
(648, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:32'),
(649, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:33'),
(650, 1, 'admin', 'Created 3 sample notifications for testing', 'general', '{\"type\":\"system\"}', '::1', '2025-04-05 07:43:34'),
(651, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:38'),
(652, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:39'),
(653, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:41'),
(654, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:43'),
(655, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:47'),
(656, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:50'),
(657, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:52'),
(658, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:53'),
(659, 1, 'admin', 'Created 3 sample notifications for testing', 'general', '{\"type\":\"system\"}', '::1', '2025-04-05 07:43:53'),
(660, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:43:58'),
(661, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:44:02'),
(662, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:44:04'),
(663, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:44:08'),
(664, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:44:09'),
(665, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:44:55'),
(666, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:45:02'),
(667, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:45:05'),
(668, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:45:06'),
(669, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:45:15'),
(670, 1, 'admin', 'Created 3 sample notifications for testing', 'general', '{\"type\":\"system\"}', '::1', '2025-04-05 07:45:15'),
(671, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:45:58'),
(672, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:45:58'),
(673, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:46:17'),
(674, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:46:19'),
(675, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:46:26'),
(676, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:46:38'),
(677, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:47:19'),
(678, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:47:23'),
(679, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:47:26'),
(680, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:47:33'),
(681, 1, 'admin', 'Created 3 sample notifications for testing', 'general', '{\"type\":\"system\"}', '::1', '2025-04-05 07:47:33'),
(682, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:47:37'),
(683, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:47:40'),
(684, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:48:40'),
(685, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:48:47'),
(686, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:48:49'),
(687, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:49:10'),
(688, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:49:46'),
(689, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:49:48'),
(690, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:49:51'),
(691, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:41'),
(692, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:44'),
(693, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:48'),
(694, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:49'),
(695, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:50'),
(696, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:51'),
(697, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:52'),
(698, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:50:55'),
(699, 1, 'admin', 'Created 3 sample notifications for testing', 'general', '{\"type\":\"system\"}', '::1', '2025-04-05 07:50:56'),
(700, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:51:00'),
(701, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:51:28'),
(702, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:51:31'),
(703, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:51:32'),
(704, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:35'),
(705, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:37'),
(706, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:38'),
(707, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:39'),
(708, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:42'),
(709, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:44'),
(710, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:47'),
(711, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:50'),
(712, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:51'),
(713, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:52'),
(714, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:53'),
(715, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:52:54'),
(716, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:53:18'),
(717, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:53:21'),
(718, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:53:22'),
(719, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:53:23'),
(720, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:54:03'),
(721, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:56:02'),
(722, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:58:46'),
(723, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:58:50'),
(724, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 07:59:13'),
(725, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:01:23'),
(726, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:01:26'),
(727, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:01:28'),
(728, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:01:38'),
(729, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:01:52'),
(730, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:01:59'),
(731, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:06:17'),
(732, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:06:28'),
(733, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:06:39'),
(734, 1, 'admin', 'Created 1 notifications (0 failed)', 'general', '{\"type\":\"notification\",\"target_type\":\"specific_member\"}', '::1', '2025-04-05 08:06:39'),
(735, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:06:41'),
(736, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:06:44'),
(737, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:07:15'),
(738, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:07:32'),
(739, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:08:16'),
(740, 1, 'admin', 'Created 1 notifications (0 failed)', 'general', '{\"type\":\"notification\",\"target_type\":\"all_members\"}', '::1', '2025-04-05 08:08:16'),
(741, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:08:22'),
(742, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:08:26'),
(743, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 08:09:30'),
(744, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 08:09:59'),
(745, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 09:49:03'),
(746, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 10:35:24'),
(747, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:35:24'),
(748, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:35:47'),
(749, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:35:57'),
(750, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:36:13'),
(751, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:36:16'),
(752, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:37:11'),
(753, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:41:36'),
(754, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:41:38'),
(755, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-05 10:41:40'),
(756, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:41:46'),
(757, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:41:53'),
(758, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:41:56'),
(759, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:42:00'),
(760, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:42:09'),
(761, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:42:11'),
(762, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 10:42:18'),
(763, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 11:09:09'),
(764, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 11:14:46'),
(765, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:14:46'),
(766, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:15:18'),
(767, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:15:28'),
(768, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:15:36'),
(769, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:15:46'),
(770, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:16:00'),
(771, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:16:34'),
(772, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:16:41'),
(773, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:16:55'),
(774, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:17:07'),
(775, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:17:36'),
(776, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:18:04'),
(777, 1, 'admin', 'Added deduction of ₦10,000.00 for member with COOPS No. COOPS/04/002', 'general', '{\"type\":\"savings\",\"member_id\":1}', '::1', '2025-04-05 11:18:04'),
(778, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:18:04'),
(779, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:22:03'),
(780, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:22:03'),
(781, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:22:14'),
(782, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 11:22:14'),
(783, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 17:24:16'),
(784, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 17:27:06'),
(785, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 17:31:33'),
(786, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 17:31:38'),
(787, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 17:31:41'),
(788, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 17:31:47'),
(789, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 17:37:25'),
(790, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 18:27:18'),
(791, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:27:18'),
(792, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:27:26'),
(793, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:31:43'),
(794, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:36:47'),
(795, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:36:48'),
(796, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:37:02'),
(797, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:39:36'),
(798, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:39:41'),
(799, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:39:58'),
(800, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:40:03'),
(801, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 18:55:15'),
(802, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:02:41'),
(803, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:02:42'),
(804, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:05:55'),
(805, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:05:58'),
(806, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:06:09'),
(807, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:07:55'),
(808, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:07:58'),
(809, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '127.0.0.1', '2025-04-05 19:07:59'),
(810, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:08:18'),
(811, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:08:21'),
(812, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:08:26'),
(813, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:08:32'),
(814, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:08:37'),
(815, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:08:41'),
(816, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:09:08'),
(817, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:11:06'),
(818, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:11:07'),
(819, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:11:28'),
(820, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:11:59'),
(821, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:11:59'),
(822, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:12:10'),
(823, 1, 'admin', 'Approved household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\"}', '::1', '2025-04-05 19:12:11'),
(824, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:13:05'),
(825, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:13:05'),
(826, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:13:25'),
(827, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:13:31'),
(828, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:13:31'),
(829, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:13:45'),
(830, 1, 'admin', 'Approved household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\"}', '::1', '2025-04-05 19:13:45'),
(831, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:14:21'),
(832, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:14:35'),
(833, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:24'),
(834, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:26'),
(835, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:27'),
(836, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:40'),
(837, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:44'),
(838, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:49'),
(839, 1, 'admin', 'Approved household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\"}', '::1', '2025-04-05 19:15:49'),
(840, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:15:49'),
(841, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:16:06'),
(842, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:16:11'),
(843, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:16:11'),
(844, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:24:37'),
(845, 1, 'admin', 'Approved household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\"}', '::1', '2025-04-05 19:24:37'),
(846, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 19:24:37'),
(847, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 19:25:01'),
(848, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 19:50:40'),
(849, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 20:04:25'),
(850, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:04:25'),
(851, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:04:56'),
(852, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:11:49'),
(853, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:12:31'),
(854, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:12:45'),
(855, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:13:05'),
(856, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:14:05'),
(857, 1, 'admin', 'Recorded loan repayment of ₦30,000.00 for Augustine Ada Okewu', 'general', '{\"type\":\"loan_repayment\",\"loan_id\":2,\"amount\":30000,\"member_id\":1,\"new_balance\":55000}', '::1', '2025-04-05 20:14:05'),
(858, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:14:07'),
(859, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-05 20:14:45'),
(860, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-05 20:15:28'),
(861, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:15:28'),
(862, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-05 20:15:34'),
(863, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:51:52'),
(864, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:51:56'),
(865, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:52:02'),
(866, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:52:04'),
(867, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:52:25'),
(868, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:52:29'),
(869, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:52:37'),
(870, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:52:37'),
(871, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:56:42');
INSERT INTO `audit_logs` (`id`, `user_id`, `user_type`, `action`, `action_type`, `details`, `ip_address`, `timestamp`) VALUES
(872, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:56:54'),
(873, 1, 'admin', 'Declined household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\",\"reason\":\"vv\"}', '::1', '2025-04-06 08:56:54'),
(874, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:56:54'),
(875, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:57:23'),
(876, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:57:27'),
(877, 1, 'admin', 'Approved household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\"}', '::1', '2025-04-06 08:57:27'),
(878, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:57:27'),
(879, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:57:31'),
(880, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:57:37'),
(881, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-06 08:59:42'),
(882, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:59:43'),
(883, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:59:57'),
(884, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 08:59:58'),
(885, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:00:03'),
(886, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:00:05'),
(887, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:00:52'),
(888, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:01:03'),
(889, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:01:12'),
(890, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:01:15'),
(891, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:01:25'),
(892, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:01:27'),
(893, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:01:54'),
(894, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:02:03'),
(895, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:04:31'),
(896, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:04:52'),
(897, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:04:56'),
(898, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:04:59'),
(899, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:05:18'),
(900, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:07:37'),
(901, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:08:43'),
(902, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:11:18'),
(903, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:11:18'),
(904, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:11:24'),
(905, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:11:34'),
(906, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:11:44'),
(907, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:11:51'),
(908, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:12:21'),
(909, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:15:05'),
(910, 1, 'admin', 'Created new admin account for testadmin', 'general', '{\"type\":\"admin\"}', '::1', '2025-04-06 09:15:05'),
(911, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:15:12'),
(912, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:15:29'),
(913, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:15:37'),
(914, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:15:48'),
(915, 1, 'admin', 'locked administrator account for testadmin', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:15:48'),
(916, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:16:23'),
(917, 1, 'admin', 'Updated administrator account for testadmin', 'general', '{\"type\":\"admin\"}', '::1', '2025-04-06 09:16:23'),
(918, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:14'),
(919, 1, 'admin', 'Updated administrator account for testadmin', 'general', '{\"type\":\"admin\"}', '::1', '2025-04-06 09:17:14'),
(920, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:21'),
(921, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:26'),
(922, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:31'),
(923, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:42'),
(924, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:57'),
(925, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:17:58'),
(926, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:18:02'),
(927, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:18:08'),
(928, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:18:14'),
(929, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:18:17'),
(930, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:19:09'),
(931, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:19:13'),
(932, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:19:22'),
(933, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:19:26'),
(934, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:19:27'),
(935, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:19:28'),
(936, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:33:40'),
(937, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:33:41'),
(938, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:33:44'),
(939, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:33:46'),
(940, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 09:52:41'),
(941, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:03:01'),
(942, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:03:03'),
(943, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:04:28'),
(944, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:04:53'),
(945, 1, 'admin', 'Updated 7 general settings', 'general', '{\"type\":\"settings\"}', '::1', '2025-04-06 10:04:55'),
(946, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:05:02'),
(947, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:05:04'),
(948, 1, 'admin', 'Updated 7 general settings', 'general', '{\"type\":\"settings\"}', '::1', '2025-04-06 10:05:04'),
(949, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:05:44'),
(950, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:07:08'),
(951, 1, 'admin', 'Updated 7 general settings', 'general', '{\"type\":\"settings\"}', '::1', '2025-04-06 10:07:08'),
(952, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:07:50'),
(953, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:07:54'),
(954, 1, 'admin', 'Updated 7 general settings', 'general', '{\"type\":\"settings\"}', '::1', '2025-04-06 10:07:54'),
(955, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:08:34'),
(956, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:08:39'),
(957, 1, 'admin', 'Updated 7 general settings', 'general', '{\"type\":\"settings\"}', '::1', '2025-04-06 10:08:39'),
(958, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:08:43'),
(959, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:09:02'),
(960, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:19:53'),
(961, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:27:46'),
(962, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:27:52'),
(963, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:28:59'),
(964, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:29:01'),
(965, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:29:02'),
(966, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:30:25'),
(967, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:30:32'),
(968, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:30:33'),
(969, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:31:39'),
(970, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:32:02'),
(971, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:32:18'),
(972, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:32:33'),
(973, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:33:47'),
(974, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:33:49'),
(975, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:43:57'),
(976, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:44:02'),
(977, 1, 'admin', 'Deleted database backup: backup_2025-04-06_11-32-33.sql', 'general', '{\"type\":\"system\"}', '::1', '2025-04-06 10:44:03'),
(978, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:44:08'),
(979, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:44:11'),
(980, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:44:13'),
(981, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:44:21'),
(982, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:46:11'),
(983, 1, 'admin', 'Deleted database backup: backup_2025-04-06_11-27-46.sql', 'general', '{\"type\":\"system\"}', '::1', '2025-04-06 10:46:11'),
(984, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:46:57'),
(985, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:47:04'),
(986, 1, 'admin', 'Deleted database backup: backup_2025-04-06_11-32-18.sql', 'general', '{\"type\":\"system\"}', '::1', '2025-04-06 10:47:04'),
(987, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:48:18'),
(988, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:54:44'),
(989, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:55:22'),
(990, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:55:25'),
(991, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:55:31'),
(992, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:55:55'),
(993, 1, 'admin', 'Deleted database backup: backup_2025-04-06_11-32-02.sql', 'general', '{\"type\":\"system\"}', '::1', '2025-04-06 10:55:55'),
(994, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:02'),
(995, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:05'),
(996, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:16'),
(997, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:43'),
(998, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:47'),
(999, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:50'),
(1000, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:56:53'),
(1001, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:57:05'),
(1002, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:57:17'),
(1003, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-06 10:57:52'),
(1004, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-06 11:01:15'),
(1005, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-06 13:52:24'),
(1006, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 10:21:57'),
(1007, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-07 10:28:47'),
(1008, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:28:48'),
(1009, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-07 10:29:29'),
(1010, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:29:29'),
(1011, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:30:36'),
(1012, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:30:43'),
(1013, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:11'),
(1014, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:16'),
(1015, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:18'),
(1016, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:29'),
(1017, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:35'),
(1018, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:41'),
(1019, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:31:47'),
(1020, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:32:09'),
(1021, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:32:22'),
(1022, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:32:46'),
(1023, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:32:46'),
(1024, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:32:56'),
(1025, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:00'),
(1026, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:06'),
(1027, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:20'),
(1028, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:20'),
(1029, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:28'),
(1030, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:30'),
(1031, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:33:56'),
(1032, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:34:31'),
(1033, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:34:53'),
(1034, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:35:13'),
(1035, 1, 'admin', 'Approved household purchase application #1', 'general', '{\"action_type\":\"household\",\"purchase_id\":1,\"member_id\":1,\"member_name\":\"Augustine Ada Okewu\",\"amount\":\"500000.00\",\"description\":\"Television\"}', '::1', '2025-04-07 10:35:13'),
(1036, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:35:13'),
(1037, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:35:39'),
(1038, 1, 'admin', 'Accessed superadmin area', 'general', '{\"type\":\"security\"}', '::1', '2025-04-07 10:35:42'),
(1039, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 10:36:14'),
(1040, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 10:42:46'),
(1041, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 11:19:49'),
(1042, 2, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/003\"}', '::1', '2025-04-07 11:20:05'),
(1043, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 11:36:08'),
(1044, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-07 12:09:50'),
(1045, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 13:37:40'),
(1046, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-07 13:41:07'),
(1047, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 15:33:07'),
(1048, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 16:15:49'),
(1049, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-07 16:18:34'),
(1050, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-04-07 16:19:15'),
(1051, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 05:46:25'),
(1052, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 06:50:05'),
(1053, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 06:54:20'),
(1054, 1, 'admin', 'Updated monthly savings deduction for Ada okewu (COOPS/04/003) to ₦10,000.00', 'general', '{\"type\":\"savings\",\"member_id\":\"2\"}', '::1', '2025-04-08 07:29:05'),
(1055, 1, 'admin', 'Updated monthly savings deduction for Ada okewu (COOPS/04/003) to ₦10,000.00', 'general', '{\"type\":\"savings\",\"member_id\":\"2\"}', '::1', '2025-04-08 07:34:48'),
(1056, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 10:15:17'),
(1057, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 10:15:46'),
(1058, 1, 'admin', 'Created new admin account for testsadmin', 'general', '{\"type\":\"admin\"}', '::1', '2025-04-08 10:17:04'),
(1059, 1, 'admin', 'Deleted administrator account for testsadmin', 'general', '{\"type\":\"security\"}', '::1', '2025-04-08 10:17:14'),
(1060, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 10:31:20'),
(1061, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 11:31:43'),
(1062, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 11:35:44'),
(1063, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 13:52:51'),
(1064, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 16:50:38'),
(1065, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 17:39:46'),
(1066, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 17:40:58'),
(1067, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 18:20:58'),
(1068, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 18:21:52'),
(1069, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 18:27:05'),
(1070, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 18:33:48'),
(1071, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 18:38:36'),
(1072, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 19:32:13'),
(1073, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 19:34:04'),
(1074, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-08 19:48:12'),
(1075, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-08 19:49:21'),
(1076, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-09 09:30:01'),
(1077, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-09 10:11:04'),
(1078, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-09 11:05:03'),
(1079, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-09 11:11:42'),
(1080, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-09 11:16:11'),
(1081, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-09 11:17:52'),
(1082, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-09 12:24:01'),
(1083, 3, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/004\"}', '::1', '2025-04-10 12:46:32'),
(1084, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-10 12:54:29'),
(1085, 1, 'admin', 'Added new member: Augustine Ada Ok (COOPS/04/006)', 'general', '{\"type\":\"member\"}', '::1', '2025-04-10 14:17:07'),
(1086, 1, 'admin', 'Member dsadsadasdsa (COOPS/04/005) deactivated', 'general', '{\"type\":\"member\"}', '::1', '2025-04-10 14:20:18'),
(1087, 1, 'admin', 'Member dsadsadasdsa (COOPS/04/005) activated', 'general', '{\"type\":\"member\"}', '::1', '2025-04-10 14:20:29'),
(1088, 1, 'admin', 'Member John Doe (COOPS/04/008) activated', 'general', '{\"type\":\"member\"}', '::1', '2025-04-10 14:25:18'),
(1089, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-10 15:28:42'),
(1090, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-10 15:29:16'),
(1091, 1, 'admin', 'Updated monthly savings deduction for John Doe (COOPS/04/008) to ₦10,000.00', 'general', '{\"type\":\"savings\",\"member_id\":\"6\"}', '::1', '2025-04-10 15:34:44'),
(1092, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-10 16:15:55'),
(1093, 1, 'admin', 'Created missing savings record for member #7 with balance of ₦2,500.00', 'general', '{\"type\":\"savings\"}', '::1', '2025-04-10 16:44:32'),
(1094, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-10 20:10:42'),
(1095, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-11 05:24:41'),
(1096, 1, 'admin', 'Recorded payment of 85,000.00 for household purchase #HP000004', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":1}', '::1', '2025-04-11 07:03:50'),
(1097, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-11 07:05:29'),
(1098, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-11 07:05:53'),
(1099, 1, 'admin', 'Added savings deduction of ₦20,000.00 for member Augustine Ada Okewu (COOPS/04/002)', 'general', '{\"type\":\"savings\",\"member_id\":1}', '::1', '2025-04-11 11:31:03'),
(1100, 1, 'admin', 'Added savings deduction of ₦30,000.00 for member Jane Smith (COOPS/04/007)', 'general', '{\"type\":\"savings\",\"member_id\":7}', '::1', '2025-04-11 12:15:00'),
(1101, 1, 'admin', 'Member asdasdasdas (COOPS/04/004) activated', 'general', '{\"type\":\"member\"}', '::1', '2025-04-11 12:19:45'),
(1102, 1, 'admin', 'Member Augustine Ada Ok (COOPS/04/006) activated', 'general', '{\"type\":\"member\"}', '::1', '2025-04-11 12:20:05'),
(1103, 1, 'admin', 'Updated monthly savings deduction for Jane Smith (COOPS/04/007) to ₦5,000.00', 'general', '{\"type\":\"savings\",\"member_id\":\"7\"}', '::1', '2025-04-11 12:23:32'),
(1104, 1, 'admin', 'Created database trigger', 'database', '{\"trigger\":\"after_loan_repayment_insert\",\"type\":\"loan_repayment\"}', 'unknown', '2025-04-11 12:40:16'),
(1105, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-14 10:47:46'),
(1106, 1, 'admin', 'Recorded payment of 87,500.00 for household purchase #HP000004', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":2}', '::1', '2025-04-14 11:46:08'),
(1107, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-16 14:24:42'),
(1108, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 10:21:40'),
(1109, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 10:22:13'),
(1110, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 10:25:18'),
(1111, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 10:26:25'),
(1112, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":3}', '::1', '2025-04-17 11:31:19'),
(1113, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":4}', '::1', '2025-04-17 11:32:18'),
(1114, 1, 'admin', 'Recorded payment of 10,000.00 for household purchase #HP000004', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":5}', '::1', '2025-04-17 11:34:42'),
(1115, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":6}', '::1', '2025-04-17 12:05:20'),
(1116, 1, 'admin', 'Recorded payment of 2,000.00 for household purchase #HP000004', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":7}', '::1', '2025-04-17 12:15:47'),
(1117, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":8}', '::1', '2025-04-17 12:16:11'),
(1118, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 14:09:55'),
(1119, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 14:10:18'),
(1120, 1, 'admin', 'Recorded payment of 10,000.00 for household purchase #HP000004', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":9}', '::1', '2025-04-17 14:11:35'),
(1121, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":10}', '::1', '2025-04-17 14:15:46'),
(1122, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":11}', '::1', '2025-04-17 14:16:26'),
(1123, 1, 'admin', 'Recorded payment of 10,000.00 for household purchase #HP000004', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":12}', '::1', '2025-04-17 14:18:36'),
(1124, 1, 'admin', 'Recorded bulk payment of 5,000.00 for member COOPS/04/002 (purchase #HP000004)', 'general', '{\"type\":\"household_payment\",\"purchase_id\":4,\"payment_id\":13}', '::1', '2025-04-17 14:20:22'),
(1125, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 14:21:06'),
(1126, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 14:21:30'),
(1127, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 14:42:41'),
(1128, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 14:48:41'),
(1129, 1, 'admin', 'Recorded share deduction of 100 units for member Augustine Ada Okewu (COOPS/04/002)', 'general', '{\"type\":\"share_deduction\",\"member_id\":1,\"units\":100,\"amount\":200000}', '::1', '2025-04-17 18:53:06'),
(1130, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 18:53:47'),
(1131, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-17 19:04:33'),
(1132, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 19:05:26'),
(1133, 1, 'admin', 'Member Aliyu ahmad (COOPS/04/009) activated', 'general', '{\"type\":\"member\"}', '::1', '2025-04-17 19:06:12'),
(1134, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-17 19:06:30'),
(1135, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 19:55:33'),
(1136, 1, 'admin', 'Added savings deduction of ₦100,000.00 for member Aliyu ahmad (COOPS/04/009)', 'general', '{\"type\":\"savings\",\"member_id\":8}', '::1', '2025-04-17 20:03:37'),
(1137, 1, 'admin', 'Recorded share deduction of 10 units for member Aliyu ahmad (COOPS/04/009)', 'general', '{\"type\":\"share_deduction\",\"member_id\":8,\"units\":10,\"amount\":20000}', '::1', '2025-04-17 20:04:02'),
(1138, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-17 20:07:11'),
(1139, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-17 20:08:14'),
(1140, 1, 'admin', 'decline_application', 'loan', '{\"action\":\"decline_application\",\"application_id\":\"2\",\"member_id\":8,\"loan_amount\":\"100000.00\",\"reason\":\"reapply\"}', '::1', '2025-04-18 16:08:35'),
(1141, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-18 15:27:16'),
(1142, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-18 15:28:04'),
(1143, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-18 15:30:03'),
(1144, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-18 15:30:15'),
(1145, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-19 11:34:52'),
(1146, 1, 'admin', 'decline_application', 'loan', '{\"action\":\"decline_application\",\"application_id\":\"2\",\"member_id\":8,\"loan_amount\":\"100000.00\",\"reason\":\"dddd\"}', '::1', '2025-04-19 18:50:17'),
(1147, 1, 'admin', 'decline_application', 'loan', '{\"action\":\"decline_application\",\"application_id\":\"2\",\"member_id\":8,\"loan_amount\":\"100000.00\",\"reason\":\"df\"}', '::1', '2025-04-19 18:52:07'),
(1148, 1, 'admin', 'decline_application', 'loan', '{\"action\":\"decline_application\",\"application_id\":\"2\",\"member_id\":8,\"loan_amount\":\"100000.00\",\"reason\":\"d\"}', '::1', '2025-04-23 10:58:25'),
(1149, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-23 10:28:57'),
(1150, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-23 10:33:07'),
(1151, 1, 'admin', 'decline_application', 'loan', '{\"action\":\"decline_application\",\"application_id\":\"2\",\"member_id\":8,\"loan_amount\":\"100000.00\",\"reason\":\"nn\"}', '::1', '2025-04-23 11:42:23'),
(1152, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-23 10:48:46'),
(1153, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-23 10:50:15'),
(1154, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-23 11:04:16'),
(1155, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-23 11:07:09'),
(1156, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-23 11:29:08'),
(1157, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-23 11:30:27'),
(1158, 1, 'admin', 'Declined household application #5 for Aliyu ahmad', 'general', '{\"type\":\"household\",\"application_id\":5}', '::1', '2025-04-23 11:34:35'),
(1159, 1, 'admin', 'Declined household application #5 for Aliyu ahmad', 'general', '{\"type\":\"household\",\"application_id\":5}', '::1', '2025-04-23 12:10:51'),
(1160, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":3,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-23 13:52:11'),
(1161, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":4,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-23 13:58:08'),
(1162, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-24 11:07:49'),
(1163, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-24 11:08:14'),
(1164, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-24 11:13:19'),
(1165, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-24 15:24:07'),
(1166, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":5,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-24 17:22:06'),
(1167, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":6,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-24 17:23:37'),
(1168, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":7,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-24 17:36:26'),
(1169, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":8,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-24 17:46:02'),
(1170, 1, 'admin', 'decline_application', 'loan', '{\"action\":\"decline_application\",\"application_id\":\"3\",\"member_id\":8,\"loan_amount\":\"200000.00\",\"reason\":\"fgfg\"}', '::1', '2025-04-24 17:46:42'),
(1171, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"3\",\"loan_id\":9,\"member_id\":8,\"loan_amount\":\"200000.00\"}', '::1', '2025-04-24 17:47:17'),
(1172, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-25 09:59:12'),
(1173, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-25 10:40:28'),
(1174, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-25 10:41:28'),
(1175, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"4\",\"loan_id\":10,\"member_id\":8,\"loan_amount\":200000,\"admin_charges\":10000,\"total_repayment\":210000}', '::1', '2025-04-25 11:42:01'),
(1176, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-26 10:30:22'),
(1177, 2, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/003\"}', '::1', '2025-04-26 10:40:39'),
(1178, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-26 10:41:35'),
(1179, 1, 'admin', 'approve_application', 'loan', '{\"action\":\"approve_application\",\"application_id\":\"5\",\"loan_id\":11,\"member_id\":2,\"loan_amount\":500000,\"admin_charges\":25000,\"total_repayment\":525000}', '::1', '2025-04-26 11:42:01'),
(1180, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-26 13:05:01'),
(1181, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-26 13:14:44'),
(1182, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-27 14:02:05'),
(1183, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-28 13:34:40'),
(1184, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-28 13:35:51'),
(1185, 2, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/003\"}', '::1', '2025-04-28 14:11:03'),
(1186, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-28 14:12:33'),
(1187, 1, 'admin', 'Approved household application #6 for Ada okewu', 'general', '{\"type\":\"household\",\"application_id\":6}', '::1', '2025-04-28 14:12:55'),
(1188, 1, 'admin', 'Approved household application #6 for Ada okewu', 'general', '{\"type\":\"household\",\"application_id\":6}', '::1', '2025-04-28 14:16:30'),
(1189, 1, 'admin', 'Recorded payment of 20,000.00 for household purchase #HP000006', 'general', '{\"type\":\"household_payment\",\"purchase_id\":6,\"payment_id\":14}', '::1', '2025-04-29 09:38:45'),
(1190, 2, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/003\"}', '::1', '2025-04-29 10:12:43'),
(1191, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-29 10:14:47'),
(1192, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-04-29 10:17:09'),
(1193, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-29 10:56:17'),
(1194, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-29 12:35:58'),
(1195, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-30 11:16:09'),
(1196, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-04-30 13:32:10'),
(1197, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-04-30 13:32:41'),
(1198, 1, 'admin', 'Added savings deduction of ₦10,000.00 for member Ada okewu (COOPS/04/003)', 'general', '{\"type\":\"savings\",\"member_id\":2}', '::1', '2025-04-30 13:34:27'),
(1199, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-02 11:23:56'),
(1200, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-05 10:46:49'),
(1201, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-05 11:43:41'),
(1202, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-06 11:26:35'),
(1203, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-06 15:23:09'),
(1204, 8, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/009\"}', '::1', '2025-05-06 15:25:23'),
(1205, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-06 15:25:46'),
(1206, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-06 16:30:31'),
(1207, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-07 11:24:47'),
(1208, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-07 11:26:45'),
(1209, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-07 12:59:18'),
(1210, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-07 13:09:06'),
(1211, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-07 13:09:21'),
(1212, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-07 13:19:13'),
(1213, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-07 13:19:43'),
(1214, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-07 14:31:07'),
(1215, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-12 10:45:15'),
(1216, 2, 'admin', 'member_updated', 'member', '[]', '::1', '2025-05-13 11:20:01'),
(1217, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-13 10:36:44'),
(1218, 2, 'admin', 'member_updated', 'member', '[]', '::1', '2025-05-13 11:41:26'),
(1219, 2, 'admin', 'member_locked', 'member', '[]', '::1', '2025-05-13 11:41:38'),
(1220, 2, 'admin', 'member_unlocked', 'member', '[]', '::1', '2025-05-13 11:41:43'),
(1221, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-13 13:16:29'),
(1222, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-13 13:16:48'),
(1223, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-13 14:22:11'),
(1224, 2, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/003\"}', '::1', '2025-05-13 14:25:01'),
(1225, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-13 14:25:20'),
(1226, 1, 'admin', 'Added savings deduction of ₦10,000.00 for member Ada okewu  (COOPS/04/003)', 'general', '{\"type\":\"savings\",\"member_id\":2}', '::1', '2025-05-13 14:25:45'),
(1227, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-13 14:26:19'),
(1228, 2, 'admin', 'share_purchase', 'purchase', '{\"units\":1000,\"amount\":2000,\"member_name\":\"Ada okewu \"}', '::1', '2025-05-13 17:04:03'),
(1229, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-15 10:33:09'),
(1230, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-16 11:12:24'),
(1231, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 14:08:22'),
(1232, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 14:11:39'),
(1233, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-18 14:11:53'),
(1234, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 14:21:41'),
(1235, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-18 14:21:56'),
(1236, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-18 14:36:57'),
(1237, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-18 14:43:52'),
(1238, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 14:45:55'),
(1239, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-18 14:47:08'),
(1240, 1, 'admin', 'Member Ahmad RUpee (COOPS/04/011) activated', 'general', '{\"type\":\"member\"}', '::1', '2025-05-18 14:47:37'),
(1241, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 14:47:54'),
(1242, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 14:48:52'),
(1243, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-18 14:57:53'),
(1244, 9, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/011\"}', '::1', '2025-05-18 14:58:26'),
(1245, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 15:01:02'),
(1246, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-18 15:19:21'),
(1247, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-18 15:20:54'),
(1248, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-19 11:56:42'),
(1249, 9, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/011\"}', '::1', '2025-05-19 12:23:02'),
(1250, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-19 12:23:22'),
(1251, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-19 12:23:56'),
(1252, 1, 'admin', 'Added savings deduction of ₦1,000,000.00 for member Augustine Ada Okewu (COOPS/04/002)', 'general', '{\"type\":\"savings\",\"member_id\":1}', '::1', '2025-05-19 12:24:26'),
(1253, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-19 12:25:00'),
(1254, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-19 12:25:19'),
(1255, 2, 'admin', 'Admin login', 'general', '{\"username\":\"admin\"}', '::1', '2025-05-19 12:57:43'),
(1256, 1, 'member', 'Member login', 'general', '{\"coop_no\":\"COOPS\\/04\\/002\"}', '::1', '2025-05-19 12:58:06'),
(1257, 1, 'admin', 'Admin login', 'general', '{\"username\":\"superadmin\"}', '::1', '2025-05-19 13:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_uploads`
--

CREATE TABLE `bulk_uploads` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `upload_type` enum('savings','loans','household','members') NOT NULL,
  `status` enum('processing','completed','failed') NOT NULL DEFAULT 'processing',
  `total_records` int(11) DEFAULT 0,
  `processed_records` int(11) DEFAULT 0,
  `successful_records` int(11) DEFAULT 0,
  `failed_records` int(11) DEFAULT 0,
  `skipped_records` int(11) DEFAULT 0,
  `error_details` text DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_upload_logs`
--

CREATE TABLE `bulk_upload_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `upload_type` enum('savings','loan','household') NOT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `records_processed` int(11) DEFAULT 0,
  `records_failed` int(11) DEFAULT 0,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulk_upload_logs`
--

INSERT INTO `bulk_upload_logs` (`id`, `admin_id`, `filename`, `upload_type`, `status`, `records_processed`, `records_failed`, `details`, `created_at`, `completed_at`) VALUES
(1, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 0, 7, '{\"failures\":[\"Member ID: 1, Amount: 10000\",\"Member ID: 2, Amount: 10000\",\"Member ID: 3, Amount: 10000\",\"Member ID: 4, Amount: 10000\",\"Member ID: 5, Amount: 10000\",\"Member ID: 7, Amount: 10000\",\"Member ID: 6, Amount: 10000\"]}', '2025-04-11 11:35:45', NULL),
(2, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 0, 7, '{\"failures\":[\"Member ID: 1, Amount: 10000\",\"Member ID: 2, Amount: 10000\",\"Member ID: 3, Amount: 10000\",\"Member ID: 4, Amount: 10000\",\"Member ID: 5, Amount: 10000\",\"Member ID: 7, Amount: 10000\",\"Member ID: 6, Amount: 10000\"]}', '2025-04-11 11:36:11', NULL),
(3, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 0, 7, '{\"failures\":[\"Member ID: 1, Amount: 10000\",\"Member ID: 2, Amount: 10000\",\"Member ID: 3, Amount: 10000\",\"Member ID: 4, Amount: 10000\",\"Member ID: 5, Amount: 10000\",\"Member ID: 7, Amount: 10000\",\"Member ID: 6, Amount: 10000\"]}', '2025-04-11 11:37:01', NULL),
(4, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 7, 0, '{\"failures\":[],\"completed_at\":\"2025-04-11 12:46:48\"}', '2025-04-11 11:46:46', '2025-04-11 12:46:48'),
(5, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 7, 0, '{\"failures\":[],\"completed_at\":\"2025-04-11 12:48:38\"}', '2025-04-11 12:48:36', '2025-04-11 12:48:38'),
(6, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 7, 0, '{\"failures\":[],\"completed_at\":\"2025-04-11 12:49:35\"}', '2025-04-11 12:49:34', '2025-04-11 12:49:35'),
(7, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 7, 0, '{\"failures\":[],\"completed_at\":\"2025-04-11 12:56:50\"}', '2025-04-11 12:56:49', '2025-04-11 12:56:50'),
(8, 1, 'savings_deductions_template(3).csv', 'savings', 'completed', 7, 0, '{\"failures\":[],\"completed_at\":\"2025-04-11 13:04:34\"}', '2025-04-11 13:04:32', '2025-04-11 13:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_by` int(11) DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administration', 'Administrative department', '2025-04-03 10:52:59', '2025-04-03 10:52:59'),
(2, 'Finance', 'Created from bulk import', '2025-04-10 14:23:32', '2025-04-10 14:23:32');

-- --------------------------------------------------------

--
-- Table structure for table `email_log`
--

CREATE TABLE `email_log` (
  `id` int(11) NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `error_message` text DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(50) DEFAULT 'general',
  `display_order` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `household_applications`
--

CREATE TABLE `household_applications` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `coop_no` varchar(20) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_category` varchar(50) DEFAULT 'General',
  `household_amount` decimal(12,2) NOT NULL,
  `ip_figure` decimal(12,2) NOT NULL,
  `purchase_duration` int(10) UNSIGNED DEFAULT 12,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `comment` text DEFAULT NULL,
  `vendor_details` text DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `account_type` enum('Savings','Current') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_applications`
--

INSERT INTO `household_applications` (`id`, `member_id`, `fullname`, `coop_no`, `item_name`, `item_category`, `household_amount`, `ip_figure`, `purchase_duration`, `status`, `comment`, `vendor_details`, `approval_date`, `approved_by`, `created_at`, `updated_at`, `bank_name`, `account_number`, `account_name`, `account_type`) VALUES
(4, 1, 'Augustine Ada Okewu', 'COOPS/04/002', 'Television', 'General', 500000.00, 87500.00, 6, 'approved', NULL, NULL, NULL, NULL, '2025-04-08 18:35:06', '2025-04-08 18:36:07', 'Access Bank', '0693988472', 'Ada oo', 'Savings'),
(6, 2, 'Ada okewu', 'COOPS/04/003', 'Television', 'General', 200000.00, 17500.00, 12, 'approved', '', NULL, '2025-04-28 15:16:30', 1, '2025-04-28 14:12:06', '2025-04-28 15:16:30', 'Diamond Bank', '0893988472', 'Alitu asssass te', 'Savings'),
(7, 9, 'Ahmad RUpee', 'COOPS/04/011', 'Television', 'General', 100000.00, 8750.00, 12, 'pending', NULL, NULL, NULL, NULL, '2025-05-18 15:00:38', '2025-05-18 15:00:38', 'First Bank', '0693088002', 'Ahmad adakawa', 'Savings');

--
-- Triggers `household_applications`
--
DELIMITER $$
CREATE TRIGGER `after_household_application_approval` AFTER UPDATE ON `household_applications` FOR EACH ROW BEGIN
            DECLARE repayment_period INT;
            DECLARE total_repayment DECIMAL(12,2);
            
            IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
                -- Calculate repayment period and total repayment
                SET repayment_period = CEILING(NEW.household_amount / NEW.ip_figure);
                SET total_repayment = NEW.household_amount * 1.05;
                
                -- Create household purchase entry
                INSERT INTO household_purchases (
                    member_id, description, amount, ip_figure, total_repayment,
                    balance, interest_rate, status, approval_date, approved_by,
                    repayment_period, start_date, end_date, created_at, updated_at
                ) VALUES (
                    NEW.member_id, NEW.item_name, NEW.household_amount, NEW.ip_figure, total_repayment,
                    total_repayment, 5.0, 'approved', NOW(), NEW.approved_by,
                    repayment_period, CURDATE(), DATE_ADD(CURDATE(), INTERVAL repayment_period MONTH),
                    NEW.created_at, NOW()
                );
                
                -- Update member's household balance
                UPDATE members
                SET household_balance = (
                    SELECT COALESCE(SUM(balance), 0)
                    FROM household_purchases
                    WHERE member_id = NEW.member_id
                    AND status IN ('approved', 'pending')
                ),
                updated_at = NOW()
                WHERE id = NEW.member_id;
                
                -- Add transaction record
                INSERT INTO transaction_history (
                    member_id, transaction_type, amount, description, created_at
                ) VALUES (
                    NEW.member_id, 'household', NEW.household_amount, 
                    CONCAT('Household Purchase Application #', NEW.id, ' Approved'),
                    NOW()
                );
            END IF;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `household_purchases`
--

CREATE TABLE `household_purchases` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `ip_figure` decimal(12,2) NOT NULL,
  `total_repayment` decimal(12,2) NOT NULL,
  `balance` decimal(12,2) NOT NULL,
  `payment_schedule` text DEFAULT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `status` enum('pending','approved','declined','completed') NOT NULL DEFAULT 'pending',
  `approval_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `repayment_period` int(11) NOT NULL,
  `term` int(11) DEFAULT 3,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `items` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_purchases`
--

INSERT INTO `household_purchases` (`id`, `reference_number`, `member_id`, `description`, `amount`, `ip_figure`, `total_repayment`, `balance`, `payment_schedule`, `interest_rate`, `status`, `approval_date`, `approved_by`, `repayment_period`, `term`, `start_date`, `end_date`, `completed_at`, `created_at`, `updated_at`, `items`) VALUES
(4, 'HP000004', 1, 'Television', 500000.00, 87500.00, 525000.00, 285500.00, NULL, 5.00, 'approved', '2025-04-08 18:36:07', NULL, 6, 3, '2025-04-08', '2025-10-08', NULL, '2025-04-08 18:35:06', '2025-04-17 14:20:22', NULL),
(6, 'HP000006', 2, 'Television', 200000.00, 17500.00, 210000.00, 190000.00, NULL, 5.00, 'approved', '2025-04-28 14:16:30', 1, 12, 3, '2025-04-28', '2026-04-28', NULL, '2025-04-28 14:12:06', '2025-04-29 09:38:44', NULL);

--
-- Triggers `household_purchases`
--
DELIMITER $$
CREATE TRIGGER `after_household_update` AFTER UPDATE ON `household_purchases` FOR EACH ROW BEGIN
            -- Update the household balance in the members table
            UPDATE members 
            SET household_balance = (
                SELECT COALESCE(SUM(balance), 0)
                FROM household_purchases 
                WHERE member_id = NEW.member_id 
                AND status IN ('approved', 'pending')
            ),
            updated_at = NOW()
            WHERE id = NEW.member_id;
        END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_household_insert` BEFORE INSERT ON `household_purchases` FOR EACH ROW BEGIN
                IF NEW.reference_number IS NULL OR NEW.reference_number = '' THEN
                    SET NEW.reference_number = CONCAT('HP', LPAD((SELECT IFNULL(MAX(id), 0) + 1 FROM household_purchases), 6, '0'));
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `household_repayments`
--

CREATE TABLE `household_repayments` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `receipt_number` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household_repayments`
--

INSERT INTO `household_repayments` (`id`, `purchase_id`, `amount`, `payment_date`, `processed_by`, `notes`, `receipt_number`, `created_at`) VALUES
(1, 4, 85000.00, '2025-04-11', 1, '', '', '2025-04-11 08:03:50'),
(2, 4, 87500.00, '2025-04-14', 1, '', '', '2025-04-14 12:46:07'),
(3, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 12:31:18'),
(4, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 12:32:17'),
(5, 4, 10000.00, '2025-04-17', 1, '', '', '2025-04-17 12:34:42'),
(6, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 13:05:19'),
(7, 4, 2000.00, '2025-04-17', 1, '', '', '2025-04-17 13:15:47'),
(8, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 13:16:11'),
(9, 4, 10000.00, '2025-04-17', 1, '', '', '2025-04-17 15:11:35'),
(10, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 15:15:46'),
(11, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 15:16:26'),
(12, 4, 10000.00, '2025-04-17', 1, '', '', '2025-04-17 15:18:35'),
(13, 4, 5000.00, '2025-04-17', 1, 'Monthly deduction', NULL, '2025-04-17 15:20:22'),
(14, 6, 20000.00, '2025-04-29', 1, '', '', '2025-04-29 10:38:43');

--
-- Triggers `household_repayments`
--
DELIMITER $$
CREATE TRIGGER `after_household_repayment_insert` AFTER INSERT ON `household_repayments` FOR EACH ROW BEGIN
            -- Update the household balance
            UPDATE household_purchases 
            SET balance = balance - NEW.amount,
                updated_at = NOW()
            WHERE id = NEW.purchase_id;
            
            -- If this makes the balance zero or negative, mark as completed
            UPDATE household_purchases 
            SET status = 'completed',
                updated_at = NOW()
            WHERE id = NEW.purchase_id AND balance <= 0;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `loan_amount` decimal(12,2) NOT NULL,
  `ip_figure` decimal(12,2) NOT NULL,
  `loan_duration` int(11) DEFAULT 0 COMMENT 'Loan duration in months',
  `total_repayment` decimal(12,2) NOT NULL,
  `balance` decimal(12,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined','completed') NOT NULL DEFAULT 'pending',
  `approval_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `repayment_period` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `member_id`, `loan_amount`, `ip_figure`, `loan_duration`, `total_repayment`, `balance`, `interest_rate`, `purpose`, `status`, `approval_date`, `approved_by`, `repayment_period`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(2, 1, 100000.00, 5000.00, 12, 105000.00, 5000.00, 5.00, NULL, 'completed', '2025-04-05 00:35:37', 1, 12, '2025-04-05', '2026-04-05', '2025-04-05 00:35:37', '2025-05-05 11:22:17'),
(10, 8, 200000.00, 10000.00, 12, 210000.00, 190000.00, 5.00, 'medical', 'approved', '2025-04-25 11:42:01', 1, 12, '2025-04-25', '2026-04-25', '2025-04-25 11:42:01', '2025-05-05 11:22:17'),
(11, 2, 500000.00, 25000.00, 12, 525000.00, 525000.00, 5.00, 'home_improvement', 'approved', '2025-04-26 11:42:01', 1, 24, '2025-04-26', '2027-04-26', '2025-04-26 11:42:01', '2025-05-05 11:22:17');

--
-- Triggers `loans`
--
DELIMITER $$
CREATE TRIGGER `after_loan_insert` AFTER INSERT ON `loans` FOR EACH ROW BEGIN
            -- Update the loan balance in the members table
            UPDATE members 
            SET loan_balance = (
                SELECT COALESCE(SUM(balance), 0)
                FROM loans 
                WHERE member_id = NEW.member_id 
                AND status IN ('approved', 'pending')
            ),
            updated_at = NOW()
            WHERE id = NEW.member_id;
        END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_loan_status_update` AFTER UPDATE ON `loans` FOR EACH ROW BEGIN
            -- If the loan status has changed to completed
            IF NEW.status = 'completed' AND (OLD.status != 'completed' OR OLD.status IS NULL) THEN
                -- Find the corresponding loan application and update its status
                UPDATE loan_applications 
                SET status = 'completed', 
                    updated_at = NOW(),
                    comment = CONCAT(IFNULL(comment, ''), ' - Loan completed at ', NOW())
                WHERE member_id = NEW.member_id 
                AND loan_amount = NEW.loan_amount
                AND status != 'completed';
            END IF;
        END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_loan_update` AFTER UPDATE ON `loans` FOR EACH ROW BEGIN
            -- Update the loan balance in the members table
            UPDATE members 
            SET loan_balance = (
                SELECT COALESCE(SUM(balance), 0)
                FROM loans 
                WHERE member_id = NEW.member_id 
                AND status IN ('approved', 'pending')
            ),
            updated_at = NOW()
            WHERE id = NEW.member_id;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `coop_no` varchar(20) NOT NULL,
  `loan_amount` decimal(12,2) NOT NULL,
  `ip_figure` decimal(12,2) NOT NULL,
  `loan_duration` int(10) UNSIGNED DEFAULT 12,
  `purpose` varchar(100) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `account_type` enum('Savings','Current') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `member_id`, `fullname`, `coop_no`, `loan_amount`, `ip_figure`, `loan_duration`, `purpose`, `additional_info`, `status`, `comment`, `created_at`, `updated_at`, `bank_name`, `account_number`, `account_name`, `account_type`) VALUES
(1, 1, 'Augustine Ada Okewu', 'COOPS/04/002', 100000.00, 5000.00, 12, NULL, NULL, '', ' - Auto-synced with completed loan on 2025-04-19 17:45:10 - Auto-synced with completed loan on 2025-04-19 17:45:50', '2025-04-04 11:05:39', '2025-04-19 17:45:50', NULL, NULL, NULL, NULL),
(2, 8, 'Aliyu ahmad', 'COOPS/04/009', 100000.00, 8750.00, 12, 'medical', '', 'rejected', 'nn', '2025-04-17 19:49:34', '2025-04-23 11:42:22', 'Jaiz Bank', '0693088472', 'Aliyu ktk', 'Savings'),
(3, 8, 'Aliyu ahmad', 'COOPS/04/009', 200000.00, 17500.00, 12, 'education', '', 'rejected', 'Approved by admin', '2025-04-23 10:49:29', '2025-04-25 10:39:19', 'Citibank', '0693082472', 'Ada oo', 'Savings'),
(4, 8, 'Aliyu ahmad', 'COOPS/04/009', 200000.00, 17500.00, 12, 'medical', '', 'approved', 'Approved by admin', '2025-04-25 10:41:11', '2025-04-25 11:42:01', 'FCMB', '0613988472', 'Aliyu ktk000', 'Savings'),
(5, 2, 'Ada okewu', 'COOPS/04/003', 500000.00, 21875.00, 24, 'home_improvement', '', 'approved', 'Approved by admin', '2025-04-26 10:41:18', '2025-04-26 11:42:01', 'Citibank', '1693988472', 'Alitu asssass', 'Savings'),
(6, 9, 'Ahmad RUpee', 'COOPS/04/011', 100000.00, 8750.00, 12, 'education', '', 'pending', NULL, '2025-05-18 14:59:49', '2025-05-18 14:59:49', 'First Bank', '0613988002', 'Ahmed Adakawa', 'Savings');

-- --------------------------------------------------------

--
-- Table structure for table `loan_repayments`
--

CREATE TABLE `loan_repayments` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_repayments`
--

INSERT INTO `loan_repayments` (`id`, `loan_id`, `amount`, `payment_date`, `processed_by`, `created_at`) VALUES
(1, 2, 10000.00, '2025-04-05', 1, '2025-04-05 08:11:45'),
(2, 2, 10000.00, '2025-04-05', 1, '2025-04-05 08:12:08'),
(3, 2, 30000.00, '2025-04-05', 1, '2025-04-05 21:14:05'),
(4, 2, 5000.00, '2025-04-11', 1, '2025-04-11 12:10:34'),
(5, 2, 5000.00, '2025-04-11', 1, '2025-04-11 13:24:28'),
(9, 2, 5000.00, '2025-04-14', 1, '2025-04-14 12:40:07'),
(10, 2, 5000.00, '2025-04-14', 1, '2025-04-14 12:44:39'),
(11, 2, 5000.00, '2025-04-14', 1, '2025-04-16 15:42:23'),
(12, 2, 20000.00, '2025-04-17', 1, '2025-04-17 11:22:43'),
(13, 2, 5000.00, '2025-04-14', 1, '2025-04-17 11:23:25'),
(14, 10, 20000.00, '2025-04-29', 1, '2025-04-29 10:39:30');

--
-- Triggers `loan_repayments`
--
DELIMITER $$
CREATE TRIGGER `after_loan_repayment_insert` AFTER INSERT ON `loan_repayments` FOR EACH ROW BEGIN
        -- Update the loan balance
        UPDATE loans 
        SET balance = balance - NEW.amount,
            updated_at = NOW()
        WHERE id = NEW.loan_id;
        
        -- If this makes the balance zero or negative, mark as completed
        UPDATE loans 
        SET status = 'completed',
            updated_at = NOW()
        WHERE id = NEW.loan_id AND balance <= 0;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `coop_no` varchar(20) NOT NULL,
  `ti_number` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `savings_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `loan_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `household_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `is_locked` tinyint(1) DEFAULT 0,
  `failed_attempts` int(11) DEFAULT 0,
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_number` varchar(50) DEFAULT NULL COMMENT 'Bank account number',
  `bank_name` varchar(100) DEFAULT NULL COMMENT 'Bank name',
  `account_name` varchar(100) DEFAULT NULL COMMENT 'Bank account name',
  `bank_branch` varchar(100) DEFAULT NULL COMMENT 'Bank branch',
  `bvn` varchar(50) DEFAULT NULL COMMENT 'Bank Verification Number',
  `shares_balance` decimal(12,2) DEFAULT 0.00 COMMENT 'Current shares value'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `coop_no`, `ti_number`, `password`, `name`, `email`, `phone`, `address`, `department_id`, `profile_image`, `savings_balance`, `loan_balance`, `household_balance`, `is_active`, `is_locked`, `failed_attempts`, `email_verified`, `verification_token`, `last_login`, `created_at`, `updated_at`, `account_number`, `bank_name`, `account_name`, `bank_branch`, `bvn`, `shares_balance`) VALUES
(1, 'COOPS/04/002', 'TI2233235', '$2y$10$JXXFt82/XSqGUQD7sh6JoOVJOSqgcBmNkPSzfhwzwPcVz7bovsHaC', 'Augustine Ada Okewu', 'okewu.official@gmail.com', '08162501592', NULL, 1, NULL, 1137000.00, 0.00, 285500.00, 1, 0, 0, 0, NULL, '2025-05-19 13:58:06', '2025-04-03 22:19:12', '2025-05-19 12:58:06', '0693988472', 'Access Bank', NULL, NULL, NULL, 200000.00),
(2, 'COOPS/04/003', 'TI2233232', '$2y$10$k6fBoO6TibF5jHQmmEi9L.INf.HKpniq93jBmpdPOvPzGvwidTVLy', 'Ada okewu ', 'adaok12@gmail.com', '09162501593', NULL, 1, NULL, 100000.00, 525000.00, 190000.00, 1, 0, 0, 0, NULL, '2025-05-13 15:25:01', '2025-04-07 11:18:25', '2025-05-13 14:25:45', '1693988472', 'Citibank', 'Alitu asssass', NULL, NULL, 0.00),
(3, 'COOPS/04/004', 'TI2233221', '$2y$10$STSDiPOftGZHDmtB48ZzQ.gjkhB39en1IUvpg/QycVw3Wq9Ic1Qea', 'asdasdasdas', 'xakef41459@evluence.com', '08162501543', '', NULL, NULL, 80000.00, 0.00, 0.00, 1, 0, 0, 0, NULL, '2025-04-10 13:46:32', '2025-04-09 09:39:36', '2025-04-11 12:19:44', NULL, NULL, NULL, NULL, NULL, 0.00),
(4, 'COOPS/04/005', 'TI2233224', '$2y$10$9TOIjdzdZF9ap/AKNsLx9OgFKEt4EXLpJAAKxcwLhEhtT7qSaaW52', 'dsadsadasdsa', 'testadmins@gmail.com', '08162501509', NULL, 1, NULL, 80000.00, 0.00, 0.00, 1, 0, 0, 0, NULL, NULL, '2025-04-09 10:12:52', '2025-04-11 12:04:33', NULL, NULL, NULL, NULL, NULL, 0.00),
(5, 'COOPS/04/006', 'TI2233565', '$2y$10$XuVpiQO1mbNEVaoI195hX.MgJpX.IOCzX2anUgSyt.TlsdhUz57SW', 'Augustine Ada Ok', 'techspace5744@gmail.com', '08062501592', 'SQ13 FCET BICHI KANO STATE\r\nSQ13 FCET BICHI KANO STATE', 1, NULL, 80000.00, 20000.00, 0.00, 1, 0, 0, 0, NULL, NULL, '2025-04-10 15:17:06', '2025-04-28 15:06:20', '0693988478', 'FirstBank', 'Alitu asssa', NULL, NULL, 200000.00),
(6, 'COOPS/04/008', 'TI64454585', '$2y$10$lezCZE.CnhG/YvdrzGf9WunzfZsmF7.TvGCbpT7Zi0xguLlTRRkge', 'John Doe', 'john@example.com', '8012345678', '123 Main St, Bichi', 1, NULL, 85000.00, 0.00, 0.00, 1, 0, 0, 0, NULL, NULL, '2025-04-10 15:23:31', '2025-05-13 11:41:43', '123456789', 'First Bank', NULL, NULL, NULL, 0.00),
(7, 'COOPS/04/007', 'TI87654321', '$2y$10$pymJrCr6s6V.ptZ1Chs7dutlDC/472BEZuiFEk0LPTRDtQkCakjQe', 'Jane Smith', 'jane@example.com', '8098765433', '456 Park Ave, Bichi', NULL, NULL, 80000.00, 0.00, 0.00, 1, 0, 0, 0, NULL, NULL, '2025-04-10 15:23:32', '2025-04-11 12:23:32', '9876543210', 'Zenith Bank', NULL, NULL, NULL, 0.00),
(8, 'COOPS/04/009', 'TI2243221', '$2y$10$CKM26TjLWVaJ2n4GCE10xeUTsY.K4DlkuQhWKr7cIv7XGICmcv.Oe', 'Aliyu ahmad', 'aliyu@gmail.com', '08132501592', NULL, 1, NULL, 100000.00, 190000.00, 0.00, 1, 0, 0, 0, NULL, '2025-05-06 16:25:23', '2025-04-17 19:04:06', '2025-05-06 15:25:23', '0693088472', 'Jaiz Bank', 'Aliyu ktk', NULL, NULL, 20000.00),
(9, 'COOPS/04/011', 'TI11221122', '$2y$10$cidnoGgtUIIz3aHGxo1oH.7bL1RXFpWwx75GSIV/GYX4NDnZlfUzS', 'Ahmad RUpee', 'Ahmad@evluence.com', '08062501234', NULL, 1, NULL, 0.00, 0.00, 0.00, 1, 0, 0, 0, NULL, '2025-05-19 13:23:02', '2025-05-18 14:45:27', '2025-05-19 12:23:02', '0613988002', 'First Bank', 'Ahmed Adakawa', NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type` enum('admin','member') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `user_type`, `title`, `message`, `type`, `is_read`, `read_at`, `link`, `created_at`) VALUES
(3, 1, 'member', 'Loan Repayment Recorded', 'A loan repayment of ₦10,000.00 has been recorded. Your new balance is ₦85,000.00.', 'info', 1, '2025-04-05 09:19:32', '/member/loans', '2025-04-05 08:12:08'),
(16, 1, 'admin', 'Welcome to Notifications', 'This is a test notification to verify the notification system is working correctly.', 'info', 1, '2025-04-08 11:20:18', '/superadmin/dashboard', '2025-04-05 08:50:56'),
(17, 1, 'admin', 'System Update Available', 'A new system update is available for installation. Please review and apply at your earliest convenience.', 'warning', 1, '2025-04-08 11:20:16', '/superadmin/system-settings', '2025-04-05 08:50:56'),
(27, 8, 'member', 'Loan Application Declined', 'Your loan application for ₦100,000.00 has been declined. Reason: df', '', 1, '2025-04-23 10:49:50', NULL, '2025-04-19 18:52:07'),
(28, 8, 'member', 'Loan Application Declined', 'Your loan application for ₦100,000.00 has been declined. Reason: d', '', 1, '2025-04-23 10:49:50', NULL, '2025-04-23 10:58:25'),
(29, 8, 'member', 'Loan Application Declined', 'Your loan application for ₦100,000.00 has been declined. Reason: nn', '', 1, '2025-04-23 10:49:50', NULL, '2025-04-23 11:42:24'),
(30, 8, 'member', 'Loan Application Approved', 'Your loan application for ₦200,000.00 has been approved. Monthly payment: ₦17,500.00 for 12 months.', '', 0, NULL, NULL, '2025-04-24 17:36:26'),
(31, 8, 'member', 'Loan Application Approved', 'Your loan application for ₦200,000.00 has been approved. Monthly payment: ₦17,500.00 for 12 months.', '', 0, NULL, NULL, '2025-04-24 17:46:02'),
(32, 8, 'member', 'Loan Application Declined', 'Your loan application for ₦200,000.00 has been declined. Reason: fgfg', '', 0, NULL, NULL, '2025-04-24 17:46:42'),
(33, 8, 'member', 'Loan Application Approved', 'Your loan application for ₦200,000.00 has been approved. Monthly payment: ₦17,500.00 for 12 months.', '', 0, NULL, NULL, '2025-04-24 17:47:17'),
(34, 8, 'member', 'Loan Application Approved', 'Your loan application for ₦200,000.00 has been approved. Monthly payment: ₦17,500.00 for 12 months.', '', 0, NULL, NULL, '2025-04-25 11:42:02'),
(35, 2, 'member', 'Loan Application Approved', 'Your loan application for ₦500,000.00 has been approved. Monthly payment: ₦21,875.00 for 24 months.', '', 0, NULL, NULL, '2025-04-26 11:42:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `user_type` enum('admin','member') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `monthly_deduction` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cumulative_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `last_deduction_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`id`, `member_id`, `monthly_deduction`, `cumulative_amount`, `last_deduction_date`, `created_at`, `updated_at`) VALUES
(1, 1, 5000.00, 1137000.00, '2025-05-19', '2025-04-04 22:22:49', '2025-05-19 12:24:25'),
(2, 2, 10000.00, 100000.00, '2025-05-13', '2025-04-07 11:18:25', '2025-05-13 14:25:45'),
(3, 3, 0.00, 80000.00, '2025-04-11', '2025-04-09 09:39:36', '2025-04-11 12:04:33'),
(4, 4, 0.00, 80000.00, '2025-04-11', '2025-04-09 10:12:52', '2025-04-11 12:04:33'),
(5, 6, 10000.00, 85000.00, '2025-04-11', '2025-04-10 15:49:51', '2025-04-11 12:04:34'),
(8, 7, 5000.00, 80000.00, '2025-04-11', '2025-04-10 17:44:32', '2025-04-11 13:23:32'),
(9, 5, 10000.00, 80000.00, '2025-04-11', '2025-04-11 12:46:47', '2025-04-11 12:04:33'),
(10, 8, 0.00, 100000.00, '2025-04-17', '2025-04-17 19:04:07', '2025-04-17 20:03:37'),
(11, 9, 0.00, 0.00, NULL, '2025-05-18 14:45:27', '2025-05-18 14:45:27');

--
-- Triggers `savings`
--
DELIMITER $$
CREATE TRIGGER `after_savings_update` AFTER UPDATE ON `savings` FOR EACH ROW BEGIN
            -- Update the savings balance in the members table
            UPDATE members 
            SET savings_balance = NEW.cumulative_amount,
                updated_at = NOW()
            WHERE id = NEW.member_id;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `savings_transactions`
--

CREATE TABLE `savings_transactions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_type` enum('deposit','withdrawal','interest','adjustment') NOT NULL,
  `deduction_date` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings_transactions`
--

INSERT INTO `savings_transactions` (`id`, `member_id`, `amount`, `transaction_type`, `deduction_date`, `description`, `processed_by`, `created_at`) VALUES
(1, 1, 5000.00, 'deposit', '2025-04-04', '', 1, '2025-04-04 22:22:49'),
(2, 1, 2000.00, 'deposit', '2025-04-05', '', 1, '2025-04-04 22:26:46'),
(3, 1, 10000.00, 'deposit', '2025-04-05', '', 1, '2025-04-05 12:18:04'),
(5, 1, 20000.00, 'deposit', '2025-04-11', 'Monthly Deduction', 1, '2025-04-11 12:31:02'),
(6, 1, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:46'),
(7, 2, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:47'),
(8, 3, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:47'),
(9, 4, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:47'),
(10, 5, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:47'),
(11, 7, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:47'),
(12, 6, 10000.00, 'deposit', '2025-04-11', 'Savings Deduction', 1, '2025-04-11 12:46:47'),
(13, 1, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:36'),
(14, 2, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:36'),
(15, 3, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:37'),
(16, 4, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:38'),
(17, 5, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:38'),
(18, 7, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:38'),
(19, 6, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:48:38'),
(20, 1, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:34'),
(21, 2, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:34'),
(22, 3, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:34'),
(23, 4, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:34'),
(24, 5, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:35'),
(25, 7, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:35'),
(26, 6, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:49:35'),
(27, 1, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:49'),
(28, 2, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:49'),
(29, 3, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:50'),
(30, 4, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:50'),
(31, 5, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:50'),
(32, 7, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:50'),
(33, 6, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 12:56:50'),
(34, 1, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:33'),
(35, 2, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:33'),
(36, 3, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:33'),
(37, 4, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:33'),
(38, 5, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:33'),
(39, 7, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:34'),
(40, 6, 10000.00, 'deposit', '2025-04-11', 'Bulk Savings Deduction', 1, '2025-04-11 13:04:34'),
(41, 7, 30000.00, 'deposit', '2025-04-11', 'Monthly Deduction', 1, '2025-04-11 13:15:00'),
(42, 8, 100000.00, 'deposit', '2025-04-17', 'Monthly Deduction', 1, '2025-04-17 21:03:37'),
(43, 2, 10000.00, 'deposit', '2025-04-30', 'Monthly Deduction', 1, '2025-04-30 14:34:27'),
(44, 2, 10000.00, 'deposit', '2025-05-13', 'Monthly Deduction', 1, '2025-05-13 15:25:45'),
(45, 1, 1000000.00, 'deposit', '2025-05-19', 'Monthly Deduction', 1, '2025-05-19 13:24:25');

--
-- Triggers `savings_transactions`
--
DELIMITER $$
CREATE TRIGGER `after_savings_transaction_insert` AFTER INSERT ON `savings_transactions` FOR EACH ROW BEGIN
            DECLARE current_amount DECIMAL(12,2);
            
            -- Get current cumulative amount
            SELECT COALESCE(cumulative_amount, 0) INTO current_amount
            FROM savings WHERE member_id = NEW.member_id;
            
            -- If no savings record exists, create one
            IF current_amount IS NULL THEN
                INSERT INTO savings (member_id, monthly_deduction, cumulative_amount, last_deduction_date, created_at, updated_at)
                VALUES (NEW.member_id, 0, 0, NOW(), NOW(), NOW());
                
                SET current_amount = 0;
            END IF;
            
            -- Update the savings amount based on transaction type
            IF NEW.transaction_type = 'deposit' OR NEW.transaction_type = 'interest' THEN
                UPDATE savings 
                SET cumulative_amount = cumulative_amount + NEW.amount,
                    last_deduction_date = NEW.deduction_date,
                    updated_at = NOW()
                WHERE member_id = NEW.member_id;
            ELSEIF NEW.transaction_type = 'withdrawal' THEN
                UPDATE savings 
                SET cumulative_amount = GREATEST(0, cumulative_amount - NEW.amount),
                    updated_at = NOW()
                WHERE member_id = NEW.member_id;
            ELSEIF NEW.transaction_type = 'adjustment' THEN
                UPDATE savings 
                SET cumulative_amount = GREATEST(0, cumulative_amount + NEW.amount),
                    updated_at = NOW()
                WHERE member_id = NEW.member_id;
            END IF;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE `shares` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `share_type` enum('ordinary','preferred') DEFAULT 'ordinary',
  `units` int(10) UNSIGNED NOT NULL,
  `unit_value` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED GENERATED ALWAYS AS (`units`) STORED,
  `unit_price` decimal(10,2) GENERATED ALWAYS AS (`unit_value`) STORED,
  `total_value` decimal(10,2) GENERATED ALWAYS AS (`units` * `unit_value`) STORED,
  `purchase_date` date DEFAULT curdate(),
  `status` enum('active','sold','forfeited') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sale_date` date DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shares`
--

INSERT INTO `shares` (`id`, `member_id`, `share_type`, `units`, `unit_value`, `purchase_date`, `status`, `created_at`, `updated_at`, `sale_date`, `sale_price`) VALUES
(4, 2, 'ordinary', 1000, 2000.00, '2025-05-13', 'active', '2025-05-13 15:04:03', '2025-05-13 15:04:03', NULL, NULL),
(5, 1, 'ordinary', 200, 1000.00, '2025-05-18', 'active', '2025-05-18 14:31:57', '2025-05-18 14:31:57', NULL, NULL),
(6, 5, 'ordinary', 200, 1000.00, '2025-05-18', 'active', '2025-05-18 14:31:57', '2025-05-18 14:31:57', NULL, NULL),
(7, 8, 'ordinary', 20, 1000.00, '2025-05-18', 'active', '2025-05-18 14:31:57', '2025-05-18 14:31:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `share_transactions`
--

CREATE TABLE `share_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `share_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_type` enum('purchase','sale','transfer') NOT NULL,
  `units` int(10) UNSIGNED NOT NULL,
  `unit_value` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED GENERATED ALWAYS AS (`units`) STORED,
  `unit_price` decimal(10,2) GENERATED ALWAYS AS (`unit_value`) STORED,
  `total_amount` decimal(10,2) NOT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `transaction_date` date DEFAULT curdate(),
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `share_transactions`
--

INSERT INTO `share_transactions` (`id`, `share_id`, `member_id`, `transaction_type`, `units`, `unit_value`, `total_amount`, `processed_by`, `notes`, `transaction_date`, `description`, `created_at`, `updated_at`) VALUES
(4, 0, 2, 'purchase', 1000, 2000.00, 2000000.00, 2, '', '2025-05-13', NULL, '2025-05-13 15:04:03', '2025-05-13 15:04:03'),
(5, 5, 1, '', 200, 1000.00, 200000.00, 1, 'Initial share balance conversion', '2025-05-18', NULL, '2025-05-18 14:31:57', '2025-05-18 13:31:57'),
(6, 6, 5, '', 200, 1000.00, 200000.00, 1, 'Initial share balance conversion', '2025-05-18', NULL, '2025-05-18 14:31:57', '2025-05-18 13:31:57'),
(7, 7, 8, '', 20, 1000.00, 20000.00, 1, 'Initial share balance conversion', '2025-05-18', NULL, '2025-05-18 14:31:57', '2025-05-18 13:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `value`, `updated_by`, `updated_at`, `description`) VALUES
('contact_email', 'admin@example.com', NULL, '2025-04-06 10:04:54', NULL),
('contact_phone', '', NULL, '2025-04-06 10:04:54', NULL),
('currency_symbol', '₦', NULL, '2025-04-06 10:04:55', NULL),
('db_version', '1.2', NULL, '2025-04-11 05:42:19', 'Database schema version'),
('fiscal_year_start', '2025-01-01', NULL, '2025-04-06 10:04:55', NULL),
('physical_address', '', NULL, '2025-04-06 10:04:55', NULL),
('site_name', 'FCET Bichi Staff Multipurpose Cooperative Society', NULL, '2025-04-06 10:04:54', NULL),
('site_short_name', 'FCET Bichi Cooperative', NULL, '2025-04-06 10:04:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `transaction_type` enum('savings','loan','household') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`id`, `member_id`, `transaction_type`, `amount`, `description`, `created_at`) VALUES
(1, 1, 'loan', 10000.00, 'Loan repayment - Manual deduction', '2025-04-05 08:11:45'),
(2, 1, 'loan', 10000.00, 'Loan repayment - Manual deduction', '2025-04-05 08:12:08'),
(3, 1, 'loan', 30000.00, 'Loan repayment - Manual deduction', '2025-04-05 21:14:05'),
(4, 1, 'household', 500000.00, 'Household Purchase Application #1 Approved', '2025-04-08 14:00:15'),
(5, 1, 'household', 500000.00, 'Household Purchase Application #2 Approved', '2025-04-08 17:40:24'),
(6, 1, 'household', 500000.00, 'Household Purchase Application #3 Approved', '2025-04-08 18:16:43'),
(7, 1, 'household', 500000.00, 'Household Purchase Application #4 Approved', '2025-04-08 18:36:07'),
(8, 1, 'loan', 5000.00, 'Loan repayment - Manual deduction', '2025-04-11 12:10:34'),
(9, 1, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(10, 2, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(11, 3, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(12, 4, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(13, 5, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(14, 7, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(15, 6, 'savings', 10000.00, 'Savings Deduction (by admin)', '2025-04-11 12:46:47'),
(16, 1, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:36'),
(17, 2, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:36'),
(18, 3, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:37'),
(19, 4, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:38'),
(20, 5, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:38'),
(21, 7, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:38'),
(22, 6, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:48:38'),
(23, 1, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:34'),
(24, 2, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:34'),
(25, 3, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:34'),
(26, 4, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:34'),
(27, 5, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:35'),
(28, 7, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:35'),
(29, 6, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:49:35'),
(30, 1, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:49'),
(31, 2, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:49'),
(32, 3, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:50'),
(33, 4, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:50'),
(34, 5, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:50'),
(35, 7, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:50'),
(36, 6, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 12:56:50'),
(37, 1, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:33'),
(38, 2, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:33'),
(39, 3, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:33'),
(40, 4, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:33'),
(41, 5, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:34'),
(42, 7, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:34'),
(43, 6, 'savings', 10000.00, 'Savings Deduction - Bulk Upload', '2025-04-11 13:04:34'),
(44, 1, 'loan', 5000.00, 'Loan repayment - Manual deduction', '2025-04-11 13:24:28'),
(45, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-11 13:26:30'),
(46, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-11 13:27:21'),
(47, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-11 13:35:35'),
(48, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-11 13:42:01'),
(49, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-14 11:49:00'),
(50, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-14 12:40:07'),
(51, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-14 12:44:39'),
(52, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-16 15:42:23'),
(53, 1, 'loan', 20000.00, 'Loan repayment - Manual deduction', '2025-04-17 11:22:43'),
(54, 1, 'loan', 5000.00, 'Loan repayment - Bulk deduction', '2025-04-17 11:23:25'),
(55, 2, 'household', 200000.00, 'Household Purchase Application #6 Approved', '2025-04-28 14:12:55'),
(56, 2, 'household', 200000.00, 'Household Purchase Application #6 Approved', '2025-04-28 14:16:30'),
(57, 8, 'loan', 20000.00, 'Loan repayment - Manual deduction', '2025-04-29 10:39:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_uploads`
--
ALTER TABLE `bulk_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `bulk_upload_logs`
--
ALTER TABLE `bulk_upload_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `read_by` (`read_by`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_log`
--
ALTER TABLE `email_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `household_applications`
--
ALTER TABLE `household_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `household_purchases`
--
ALTER TABLE `household_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `household_repayments`
--
ALTER TABLE `household_repayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_loan_duration` (`loan_duration`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coop_no` (`coop_no`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `savings_transactions`
--
ALTER TABLE `savings_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `shares`
--
ALTER TABLE `shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `share_transactions`
--
ALTER TABLE `share_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `share_id` (`share_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1258;

--
-- AUTO_INCREMENT for table `bulk_uploads`
--
ALTER TABLE `bulk_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_upload_logs`
--
ALTER TABLE `bulk_upload_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_log`
--
ALTER TABLE `email_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `household_applications`
--
ALTER TABLE `household_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `household_purchases`
--
ALTER TABLE `household_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `household_repayments`
--
ALTER TABLE `household_repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `savings_transactions`
--
ALTER TABLE `savings_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `share_transactions`
--
ALTER TABLE `share_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bulk_uploads`
--
ALTER TABLE `bulk_uploads`
  ADD CONSTRAINT `bulk_uploads_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`read_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `household_repayments`
--
ALTER TABLE `household_repayments`
  ADD CONSTRAINT `household_repayments_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `household_purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `household_repayments_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD CONSTRAINT `loan_applications_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  ADD CONSTRAINT `loan_repayments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_repayments_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `savings_transactions`
--
ALTER TABLE `savings_transactions`
  ADD CONSTRAINT `savings_transactions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `savings_transactions_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD CONSTRAINT `system_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
