-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 17, 2024 at 06:57 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `banking_app_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `receiver_email` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL,
  `type` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_email`, `receiver_email`, `amount`, `type`, `created_at`) VALUES
(1, 'cefypis@mailinator.com', NULL, 10000, 'deposit', '2024-07-17 12:02:23'),
(2, 'cefypis@mailinator.com', 'dynony@mailinator.com', 5000, 'transfer', '2024-07-17 12:03:16'),
(3, 'cefypis@mailinator.com', NULL, 1000, 'withdraw', '2024-07-17 12:03:33'),
(4, 'dynony@mailinator.com', 'cefypis@mailinator.com', 500, 'transfer', '2024-07-17 12:04:26'),
(5, 'dynony@mailinator.com', NULL, 100, 'deposit', '2024-07-17 12:28:29'),
(6, 'dynony@mailinator.com', NULL, 200, 'withdraw', '2024-07-17 12:28:34'),
(7, 'dynony@mailinator.com', 'cefypis@mailinator.com', 1000, 'transfer', '2024-07-17 12:28:50'),
(8, 'muguqid@mailinator.com', NULL, 500, 'deposit', '2024-07-17 12:42:58'),
(9, 'muguqid@mailinator.com', NULL, 100, 'withdraw', '2024-07-17 12:43:02'),
(10, 'muguqid@mailinator.com', 'dynony@mailinator.com', 100, 'transfer', '2024-07-17 12:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Axel Palmer', 'pubapihufe@mailinator.com', '$2y$10$v7rZfgkyTezNFV254rMAlO2qydT7AS9xMeD3sEBk4Al1tbcDdL8BG', 'Customer', '2024-07-17 11:36:10'),
(2, 'Nero Potter', 'cefypis@mailinator.com', '$2y$10$hdj/e06ZKQzjPXA6HlB8fOZJO7SXF32oInFgqz19ipGA/zfjfd.Tm', 'Customer', '2024-07-17 11:36:31'),
(3, 'Joshua Jacobs', 'dynony@mailinator.com', '$2y$10$f5fagVdPw8z0SWgDan8B9.bL2XY5D7EjwuIlITd2VCDdvawV852nm', 'Customer', '2024-07-17 12:02:42'),
(4, 'Nafis Ahmed', 'asd@asd.asd', '$2y$10$oJHK16zbovvd3GeHLbCupOVoQ1Pgd148POI28eath6ETNsqi7p2SW', 'Admin', '2024-07-17 12:05:39'),
(5, 'Clio Wong', 'xeci@mailinator.com', '$2y$10$qyIXycJF7b/HRP5R95yx/OG4EP2rCKL3lBNdTg5vPgRYHPVB6OZmG', 'Customer', '2024-07-17 12:14:38'),
(6, 'Carla Foster', 'zysutyse@mailinator.com', '$2y$10$bbdu9CGkaHpibOo0in6mROOmwmCD2hFntRmV2htgxjkVYavnkGHg6', 'Customer', '2024-07-17 12:14:48'),
(7, 'Rashad Sosa', 'micihor@mailinator.com', '$2y$10$LpI.4c7XO.nSL2viYG9ZuOmQxCchn68HNaQejoLK0sc3NJaTGNVCC', 'Customer', '2024-07-17 12:19:14'),
(8, 'Amir Patterson', 'kykevi@mailinator.com', '$2y$10$eHZX6isyfXszM7kwt6wv7OHA0C1fT0gZ8B9lFM9eoEx.1Ecm6gBsu', 'Customer', '2024-07-17 12:19:22'),
(9, 'Bruce Ball', 'tebekenosa@mailinator.com', '$2y$10$8AUquhYzOU0dyp/evxcr0erSAw4MK5H3O21zjaPkzmLwUwBzNQxMy', 'Customer', '2024-07-17 12:23:03'),
(10, 'Nicole Davidson', 'hynare@mailinator.com', '$2y$10$iTsFLP/GUsw/vF4iOs2AKuJzds8K8v2IQ7vw9Znl.fnHnZmJUEHwC', 'Customer', '2024-07-17 12:28:10'),
(11, 'Megan Park', 'pavedyzu@mailinator.com', '$2y$10$Sh.4t2//fKfCP9pDxwILlOoVaSrxEdnEBVjQcpCSjpY7G9NG23vr6', 'Customer', '2024-07-17 12:28:16'),
(12, 'Aimee Mann', 'gaxatujub@mailinator.com', '$2y$10$5zEMIzRVmO2YOSoNehGiAO8t81kIdyFyVjhLQvBxaGaFnfsgcEM2K', 'Customer', '2024-07-17 12:42:32'),
(13, 'Jerome Mccormick', 'muguqid@mailinator.com', '$2y$10$5cE7RZUXuY.v82Sp0RF0fOtMrsvkR98HjP6g7YG6kV/Qz04LuzzKq', 'Customer', '2024-07-17 12:42:37'),
(14, 'Dexter Sanchez', 'meco@mailinator.com', '$2y$10$ftNCgm.PBA6Es/Jz1zUwE.hLhq1rwueHzxARN34dzSmA9STAKeKNq', 'Customer', '2024-07-17 12:44:24'),
(15, 'Blair Mcdonald', 'zoqihamoc@mailinator.com', '$2y$10$2HO/odb3TodbRte5EvwDk.mxIXRkZKL8DXJbMkB4FgI5BDdlMVqJK', 'Customer', '2024-07-17 12:44:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
