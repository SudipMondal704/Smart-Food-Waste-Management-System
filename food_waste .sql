-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 11:47 AM
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
-- Database: `food_waste`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `admin_email`, `phone`, `password`, `created_at`) VALUES
(1, 'Rayantan Das', 'rayantan@gmail.com', '1111111111', 'rayantan', '2025-05-22 19:19:41'),
(2, 'Sudip Mondal', 'sudip@gmail.com', '2222222222', 'sudip', '2025-05-22 19:19:41'),
(3, 'Anjan Saha', 'anjan@gmail.com', '3333333333', 'anjan', '2025-05-22 19:19:41'),
(4, 'Rintu Ghosh', 'rintu@gmail.com', '4444444444', 'rintu', '2025-05-22 19:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `feedback_text` text NOT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `full_name`, `email`, `phone_number`, `feedback_text`, `feedback_date`) VALUES
(1, 1, 'Bikash Das', 'deb@gmail.com', '88558857', 'Very helpful system for our socity.', '2025-05-27 07:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `fooddetails`
--

CREATE TABLE `fooddetails` (
  `fooddetails_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `pickup_address` varchar(500) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `alt_phone` varchar(20) DEFAULT NULL,
  `food_name` varchar(255) NOT NULL,
  `food_type` varchar(100) DEFAULT NULL,
  `food_category` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_ngo_id` int(11) DEFAULT NULL,
  `assigned_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fooddetails`
--

INSERT INTO `fooddetails` (`fooddetails_id`, `user_id`, `donor_name`, `pickup_address`, `phone`, `alt_phone`, `food_name`, `food_type`, `food_category`, `quantity`, `unit`, `image`, `created_at`, `assigned_ngo_id`, `assigned_time`) VALUES
(1, 1, 'Bikash Das', 'Kolkata', '88558857', '35454', 'Fish Curry', 'Non-veg', 'cooked-food', 30, 'pcs', 'uploads/68357a4295e5c_Cooked-food.jpg', '2025-05-27 08:39:30', 1, NULL),
(2, 1, 'Bikash Das', 'Siuri', '88558857', '2456', 'pakoda', 'Vegetarian', 'cooked-food', 2, 'kg', 'uploads/6835a1b22a947_raw.jpg', '2025-05-27 11:27:46', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ngo`
--

CREATE TABLE `ngo` (
  `ngo_id` int(11) NOT NULL,
  `ngo_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngo`
--

INSERT INTO `ngo` (`ngo_id`, `ngo_name`, `address`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'Anandadhara Foundation', 'Kolkata', 'ananda@gmail.com', '645456545', '666', '2025-05-27 08:37:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_otp` varchar(255) DEFAULT NULL,
  `reset_otp_expiry` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `address`, `email`, `phone`, `password`, `created_at`, `reset_otp`, `reset_otp_expiry`, `updated_at`) VALUES
(1, 'Bikash Das', 'Siuri', 'deb@gmail.com', '88558857', '888', '2025-05-27 07:46:06', NULL, NULL, '2025-05-30 08:35:43'),
(2, 'Ashis Banerjee', 'Durgapur', 'ashisB@gmail.com', '12321123', '123', '2025-05-28 16:31:23', NULL, NULL, '2025-05-28 16:31:23'),
(3, 'Ashok Ghosh', 'Kolkata', 'ashok@gmail.com', '111', '111', '2025-05-28 16:40:01', NULL, NULL, '2025-05-28 16:40:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fooddetails`
--
ALTER TABLE `fooddetails`
  ADD PRIMARY KEY (`fooddetails_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `ngo`
--
ALTER TABLE `ngo`
  ADD PRIMARY KEY (`ngo_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fooddetails`
--
ALTER TABLE `fooddetails`
  MODIFY `fooddetails_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ngo`
--
ALTER TABLE `ngo`
  MODIFY `ngo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fooddetails`
--
ALTER TABLE `fooddetails`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
