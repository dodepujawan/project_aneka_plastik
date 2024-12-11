-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 27, 2024 at 01:00 AM
-- Server version: 8.0.20
-- PHP Version: 8.0.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ajax_laravel_10_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `roles`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'programmer mantap jiwa', 'dwebpro@gmail.com', NULL, '$2y$10$8MSYtT6A4E0dSDEwmp/SMeDJxqrhgsqTtikcJtmQ1o9YFzK39S016', 'programmer', NULL, '2024-07-30 17:50:43', '2024-08-21 18:41:18'),
(2, 'master admin', 'master_admin@gmail.com', NULL, '$2y$10$CcM5C7sq6CZvZHzcfBuDR.lisaDrnNM5BqR.Be49mSBIvZdH1H3V6', 'admin', NULL, '2024-07-30 17:54:43', '2024-08-20 18:45:07'),
(5, 'jon jones manause', 'jonjj@gmail.com', NULL, '$2y$10$tA/tXpxL9i7BDNQnRq6KOuu1aSOC.6Pk3FdQSSoh20BHdoHz37a2u', 'Guest', NULL, '2024-07-30 18:12:21', '2024-08-20 18:17:18'),
(7, 'maul pride', 'maul@gmail.com', NULL, '$2y$10$6TRWQkx3gXreg/i2XlRnE.CAJNjl3OO1oDeuKeD2.1zRgg1o4PtRW', 'Guest', NULL, '2024-07-30 18:16:30', '2024-08-21 18:29:50'),
(8, 'johan lalago', 'johan@gmail.com', NULL, '$2y$10$Tfqo5JdzvwCLldyjajdS0uHqsGL1xvsF2suRZGJWdpQp6HLHwgf9C', 'Guest', NULL, '2024-08-05 19:40:02', '2024-08-21 18:37:04'),
(9, 'guga food war', 'baculi@gmail.co.id', NULL, '$2y$10$DL0xAZipiZIZmMpO/yuZGeD0CX68Px4BYT/TPDxMoOgEIf50D/erW', 'Guest', NULL, '2024-08-05 19:40:19', '2024-08-21 18:41:06'),
(11, 'kadek sunitiderto', 'komangsuniti@gmail.com', NULL, '$2y$10$p4By79dIctr3CZOtZvxx7e2DNLCWWMzpn6NdSrzR0gwsPf8epYPpa', 'Guest', NULL, '2023-08-05 19:40:58', '2024-08-20 18:44:49'),
(12, 'johansen beni', 'johan123@gmail.com', NULL, '$2y$10$XY4QMRogZOf/f59Sp3eAoeTp9znJR3DCkxhQNVdPPwgSHdxWnz6mC', 'Guest', NULL, '2024-08-05 19:41:13', '2024-08-20 18:19:16'),
(14, 'babalu jackson', 'jackson@gmail.com', NULL, '$2y$10$oW6SpsuIuPZdbqsnEt2DguoZPSYJJ79VgsJljbfp9E/pPbkp6Y4FC', 'guest', NULL, '2024-08-21 18:42:00', '2024-08-21 18:42:00'),
(15, 'johansen ingemar', 'johanwar@gmail.com', NULL, '$2y$10$8ydhOF3zZWy8OAtXn6OGku7URn41mRRU4aDS/LqnGz2NJhK7j3z7i', 'guest', NULL, '2024-08-21 18:42:23', '2024-08-21 18:42:23');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
