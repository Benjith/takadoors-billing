-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 16, 2022 at 04:04 PM
-- Server version: 8.0.30-0ubuntu0.20.04.2
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taka`
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
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_08_13_102540_add_phone_to_users_table', 2),
(6, '2022_08_13_111715_create_roles_table', 3),
(7, '2022_08_13_112823_add_role_id_to_users_table', 4),
(8, '2022_08_13_160646_create_raw_materials_table', 5),
(9, '2022_08_15_053254_create_stocks_table', 6),
(10, '2022_08_15_095630_create_orders_table', 7),
(11, '2022_08_16_090932_add_status_to_stocks_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `length` double(10,2) NOT NULL,
  `width` double(10,2) NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `design` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frame` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thickness` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1-produced,2-finished,3-dispatched,4-billing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `code`, `length`, `width`, `quantity`, `design`, `frame`, `remarks`, `thickness`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'ORDER1', 8.00, 500.00, '6', 'PV1', '9', 'good', '6', 1, '2022-08-16 00:44:01', '2022-08-16 00:44:01'),
(2, 1, 'ORDER1', 8.00, 500.00, '1', 'PV1', '9', 'good', '6', 1, '2022-08-16 00:44:44', '2022-08-16 00:44:44');

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

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '7b3eaf8ccb4f8e34dd132993b9277064223954f70d3ebe96b11d0546a7952679', '[\"*\"]', NULL, '2022-08-13 04:35:58', '2022-08-13 04:35:58'),
(2, 'App\\Models\\User', 1, 'auth_token', '7fdb5947613814e5774e9b0301661c4e81506ee77b2d58a2c63992c8d8903a01', '[\"*\"]', NULL, '2022-08-13 04:41:15', '2022-08-13 04:41:15'),
(3, 'App\\Models\\User', 1, 'auth_token', '3d4b97b42caf64538f285a43031821e1e3e2d704eceb6f73c04f25e65461543a', '[\"*\"]', '2022-08-13 04:50:35', '2022-08-13 04:47:35', '2022-08-13 04:50:35'),
(4, 'App\\Models\\User', 3, 'auth_token', '7479523325a0ebc3b4260b66ba4e52088ce2810d339cc468d505f5d2f050861f', '[\"*\"]', NULL, '2022-08-13 05:10:17', '2022-08-13 05:10:17'),
(5, 'App\\Models\\User', 4, 'auth_token', '44065af2ec590fbe5697a8f3330c3628d84f83e8cdf478d266fedb40d94d661a', '[\"*\"]', NULL, '2022-08-13 05:16:40', '2022-08-13 05:16:40'),
(6, 'App\\Models\\User', 1, 'auth_token', '0e1c51d42740ce1b37c2e84a7ab6a8a1e17c0626f30ed67d9a397c10d790b79b', '[\"*\"]', NULL, '2022-08-13 05:21:18', '2022-08-13 05:21:18'),
(7, 'App\\Models\\User', 1, 'auth_token', '69b2093d48ce778baa39524ba0c8c6d1952f7352bb30bd1a4efb3eec4001440d', '[\"*\"]', NULL, '2022-08-13 05:23:23', '2022-08-13 05:23:23'),
(8, 'App\\Models\\User', 1, 'auth_token', 'b22e4144c0a4c34dac03274fa70e2a33d47413da60609fcd827f4cdd0165ebf1', '[\"*\"]', '2022-08-16 03:20:51', '2022-08-13 10:54:25', '2022-08-16 03:20:51'),
(9, 'App\\Models\\User', 1, 'auth_token', '39c8f1f57fe0d08668bdad79d951dd2dc92ea30f5a7c994655a3a8b2de385fdc', '[\"*\"]', '2022-08-16 03:21:33', '2022-08-15 00:18:22', '2022-08-16 03:21:33'),
(10, 'App\\Models\\User', 2, 'auth_token', '536a658cf7d45ad8987d26e3695aa9d84a53697d21e57c5d0c4016bcd4de6154', '[\"*\"]', '2022-08-15 00:58:30', '2022-08-15 00:41:05', '2022-08-15 00:58:30'),
(11, 'App\\Models\\User', 1, 'auth_token', '1c5b72b1757bc5751a74c5f4eed7359a3e11f8abb94e18957c95233c835358a9', '[\"*\"]', '2022-08-16 03:32:53', '2022-08-15 07:53:11', '2022-08-16 03:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'teek', '2022-08-13 11:01:03', '2022-08-13 11:01:03'),
(3, 'mahagani', '2022-08-16 03:20:52', '2022-08-16 03:20:52');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL),
(2, 'Production', NULL, NULL),
(3, 'Marketing', NULL, NULL),
(4, 'Finishing', NULL, NULL),
(5, 'Dispatch', NULL, NULL),
(6, 'Billing', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `design` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `height` double(10,2) NOT NULL,
  `width` double(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1-active,2-damaged,3-inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `material_id`, `design`, `height`, `width`, `quantity`, `created_at`, `updated_at`, `status`) VALUES
(1, 2, 'triangle', 100.00, 36.00, 4, '2022-08-15 00:23:00', '2022-08-15 00:23:00', 1),
(3, 2, 'PV1 GHY', 100.70, 100.00, 7, '2022-08-15 00:23:35', '2022-08-15 00:23:35', 1),
(4, 2, 'PV1 J', 0.00, 600.00, 0, '2022-08-15 00:23:51', '2022-08-16 00:44:01', 1),
(5, 2, 'PV11', 10.00, 750.00, 8, '2022-08-15 00:39:47', '2022-08-16 00:44:45', 1),
(6, 2, 'rectangle', 10.00, 36.50, 9, '2022-08-15 00:59:18', '2022-08-15 00:59:18', 1),
(7, 2, 'rectangle', 10.00, 36.50, 9, '2022-08-15 01:01:42', '2022-08-15 01:01:42', 1),
(8, 2, 'circle', 10.00, 36.99, 9, '2022-08-15 01:38:40', '2022-08-15 01:38:40', 1),
(10, 2, 'circle', 10.00, 36.99, 9, '2022-08-15 01:44:48', '2022-08-15 01:44:48', 1),
(11, 2, 'circle', 10.00, 36.99, 9, '2022-08-15 08:11:09', '2022-08-15 08:11:09', 1),
(12, 3, 'circle', 10.00, 36.99, 9, '2022-08-16 03:21:24', '2022-08-16 03:21:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `firstname`, `lastname`, `phone`, `role_id`) VALUES
(1, 'admin@gmail.com', NULL, '$2y$10$OonB7uGaSuIoy0qigOvrL.gTxtL53VPpt/8X/GwVALwr/Y/0gtqF6', NULL, '2022-08-13 10:54:24', '2022-08-13 10:54:24', 'admin', 'kk', '9988776655', 1),
(2, 'production@gmail.com', NULL, '$2y$10$A.FT.qn6IozGLfei9puPnuSs3RmeweSnf.mMHsQ/8I76ac7Xpy9mi', NULL, '2022-08-15 00:41:04', '2022-08-15 00:41:04', 'production', 'kk', '9988776655', 2);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

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
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_material_id_foreign` (`material_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `raw_materials` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
