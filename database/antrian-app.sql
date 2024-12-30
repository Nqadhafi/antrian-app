-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 10:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `antrian-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'File Siap Cetak', 'A', '2024-12-19 08:33:48', '2024-12-19 09:07:18'),
(2, 'Design', 'B', '2024-12-19 08:33:58', '2024-12-19 09:07:18'),
(3, 'Pengambilan', 'C', '2024-12-19 08:34:06', '2024-12-19 09:07:18'),
(4, 'CS', 'D', '2024-12-19 08:34:13', '2024-12-19 09:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_12_19_152820_create_categories_table', 2),
(6, '2024_12_19_155209_add_role_to_users_table', 3),
(7, '2024_12_19_160631_add_code_to_categories_table', 4),
(8, '0000_00_00_000000_create_websockets_statistics_entries_table', 5),
(9, '2024_12_30_151544_create_videos_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queues`
--

CREATE TABLE `queues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `is_called` tinyint(1) NOT NULL DEFAULT 0,
  `is_printed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `queues`
--

INSERT INTO `queues` (`id`, `number`, `category_id`, `is_called`, `is_printed`, `created_at`, `updated_at`) VALUES
(315, 'A-001', 1, 1, 0, '2024-12-29 23:38:09', '2024-12-30 06:46:44'),
(316, 'A-002', 1, 1, 0, '2024-12-29 23:40:45', '2024-12-30 08:02:28'),
(317, 'A-003', 1, 1, 0, '2024-12-29 23:41:06', '2024-12-30 08:10:12'),
(318, 'C-001', 3, 1, 0, '2024-12-29 23:42:27', '2024-12-30 08:57:37'),
(319, 'B-001', 2, 1, 0, '2024-12-29 23:43:37', '2024-12-30 08:10:08'),
(320, 'D-001', 4, 1, 0, '2024-12-30 06:45:17', '2024-12-30 08:58:47'),
(321, 'B-002', 2, 1, 0, '2024-12-30 06:52:27', '2024-12-30 08:12:59'),
(322, 'C-002', 3, 1, 0, '2024-12-30 07:00:23', '2024-12-30 08:58:44'),
(323, 'C-003', 3, 1, 0, '2024-12-30 07:03:48', '2024-12-30 09:07:37'),
(324, 'D-002', 4, 1, 0, '2024-12-30 07:04:52', '2024-12-30 08:59:13'),
(325, 'C-004', 3, 0, 0, '2024-12-30 07:11:31', '2024-12-30 07:11:31'),
(326, 'B-003', 2, 1, 0, '2024-12-30 07:15:10', '2024-12-30 08:58:10'),
(327, 'D-003', 4, 1, 0, '2024-12-30 07:15:32', '2024-12-30 09:05:37'),
(328, 'D-004', 4, 1, 0, '2024-12-30 07:15:54', '2024-12-30 09:07:39'),
(329, 'A-004', 1, 1, 0, '2024-12-30 07:17:54', '2024-12-30 08:12:56'),
(330, 'D-005', 4, 0, 0, '2024-12-30 07:18:34', '2024-12-30 07:18:34'),
(331, 'C-005', 3, 0, 0, '2024-12-30 07:20:20', '2024-12-30 07:20:20'),
(332, 'C-006', 3, 0, 0, '2024-12-30 07:21:56', '2024-12-30 07:21:56'),
(333, 'C-007', 3, 0, 0, '2024-12-30 07:22:32', '2024-12-30 07:22:32'),
(334, 'D-006', 4, 0, 0, '2024-12-30 07:23:47', '2024-12-30 07:23:47'),
(335, 'D-007', 4, 0, 0, '2024-12-30 07:24:43', '2024-12-30 07:24:43'),
(336, 'C-008', 3, 0, 0, '2024-12-30 07:24:48', '2024-12-30 07:24:48'),
(337, 'C-009', 3, 0, 0, '2024-12-30 07:30:25', '2024-12-30 07:30:25'),
(338, 'C-010', 3, 0, 0, '2024-12-30 07:33:18', '2024-12-30 07:33:18'),
(339, 'A-005', 1, 1, 0, '2024-12-30 07:33:37', '2024-12-30 08:13:26'),
(340, 'D-008', 4, 0, 0, '2024-12-30 07:35:00', '2024-12-30 07:35:00'),
(341, 'D-009', 4, 0, 0, '2024-12-30 07:36:18', '2024-12-30 07:36:18'),
(342, 'C-011', 3, 0, 0, '2024-12-30 07:36:21', '2024-12-30 07:36:21'),
(343, 'D-010', 4, 0, 0, '2024-12-30 07:38:12', '2024-12-30 07:38:12'),
(344, 'D-011', 4, 0, 0, '2024-12-30 07:38:16', '2024-12-30 07:38:16'),
(345, 'B-004', 2, 1, 0, '2024-12-30 07:38:41', '2024-12-30 08:58:28'),
(346, 'D-012', 4, 0, 0, '2024-12-30 07:40:07', '2024-12-30 07:40:07'),
(347, 'C-012', 3, 0, 0, '2024-12-30 07:43:41', '2024-12-30 07:43:41'),
(348, 'D-013', 4, 0, 0, '2024-12-30 07:45:15', '2024-12-30 07:45:15'),
(349, 'C-013', 3, 0, 0, '2024-12-30 07:47:02', '2024-12-30 07:47:02'),
(350, 'C-014', 3, 0, 0, '2024-12-30 07:48:27', '2024-12-30 07:48:27'),
(351, 'D-014', 4, 0, 0, '2024-12-30 07:49:23', '2024-12-30 07:49:23'),
(352, 'B-005', 2, 1, 0, '2024-12-30 07:50:24', '2024-12-30 08:58:40'),
(353, 'D-015', 4, 0, 0, '2024-12-30 07:51:42', '2024-12-30 07:51:42'),
(354, 'A-006', 1, 1, 0, '2024-12-30 07:54:05', '2024-12-30 08:57:57'),
(355, 'C-015', 3, 0, 0, '2024-12-30 08:02:20', '2024-12-30 08:02:20'),
(356, 'C-016', 3, 0, 0, '2024-12-30 09:05:21', '2024-12-30 09:05:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$10$7HClm5rpmdpn5Q4MlmEJ0u6I77aK8n0QLFl7tM1bNlSE6l6ZpdHwq', NULL, '2024-12-19 08:53:06', '2024-12-19 08:53:06', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `path`, `created_at`, `updated_at`) VALUES
(4, 'Hang Tag', 'videos/bI9WKxQCrmL9GSOWTIx6TzOKvv21LK-metadmlkZW9wbGF5YmFjayAoNykubXA0-.mp4', '2024-12-30 08:54:05', '2024-12-30 08:54:05');

-- --------------------------------------------------------

--
-- Table structure for table `websockets_statistics_entries`
--

CREATE TABLE `websockets_statistics_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_id` varchar(255) NOT NULL,
  `peak_connection_count` int(11) NOT NULL,
  `websocket_message_count` int(11) NOT NULL,
  `api_message_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `queues`
--
ALTER TABLE `queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queues`
--
ALTER TABLE `queues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=357;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
