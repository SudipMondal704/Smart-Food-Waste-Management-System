-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 05:44 PM
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
(1, 11, 'Mishra Das', 'mishra@gmail.com', '9999999999', 'Good', '2025-05-17 03:21:36');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fooddetails`
--

INSERT INTO `fooddetails` (`fooddetails_id`, `user_id`, `donor_name`, `pickup_address`, `phone`, `alt_phone`, `food_name`, `food_type`, `food_category`, `quantity`, `unit`, `image`, `created_at`) VALUES
(1, 1, 'Vikram Bose', 'Burdwan', '1111111111', '23232323', 'Rice', 'vegetarian', 'cooked-food', 10, 'kg', '?????Exif\0\0MM\0*\0\0\0\0;\0\0\0\0\0\0\0b\0\0\0\0\0?\0\0\0\0\0\0?\0\01\0\0\0\0\0\0\0r?i\0\0\0\0\0\0\0?\0\0\0\0\0\0\02\0\0\0\0\0\0\0z\0\0\0\0484847092019101\0Picsart\02025:05:15 07:32:38\0\0?\0\0\0\0\0\0\0???\0\0\0:\0\0\0̒\0\0\0\0\0\0\0\0\0\0\0\02025:05:15 07:32:38\0{\"remix_data\":[],\"remix_entry_point\":\"ch', '2025-05-18 06:58:50'),
(2, 1, 'Vikram Bose', 'Burdwan', '1111111111', '45685522', 'Rice', 'vegetarian', 'cooked-food', 10, 'kg', '?????Exif\0\0MM\0*\0\0\0\0;\0\0\0\0\0\0\0b\0\0\0\0\0?\0\0\0\0\0\0?\0\01\0\0\0\0\0\0\0r?i\0\0\0\0\0\0\0?\0\0\0\0\0\0\02\0\0\0\0\0\0\0z\0\0\0\0484847092019101\0Picsart\02025:05:15 07:32:38\0\0?\0\0\0\0\0\0\0???\0\0\0:\0\0\0̒\0\0\0\0\0\0\0\0\0\0\0\02025:05:15 07:32:38\0{\"remix_data\":[],\"remix_entry_point\":\"ch', '2025-05-18 06:59:41'),
(3, 1, 'Vikram Bose', 'kolkata', '1111111111', '45685522', 'Rice', 'vegetarian', 'cooked-food', 10, 'kg', 'uploads/6829873d011af_Cooked-food.jpg', '2025-05-18 07:07:41'),
(4, 1, 'Vikram Bose', 'Durgapur', '1111111111', '23232323', 'Fried-rice', 'vegetarian', 'cooked-food', 50, 'kg', 'uploads/68298ef5ac526_Cooked-food.jpg', '2025-05-18 07:40:37'),
(5, 1, 'Vikram Bose', 'Malda', '1111111111', '45685522', 'Cutlet', 'vegetarian', 'cooked-food', 50, 'pieces', 'uploads/682994b5ac476_Cooked-food.jpg', '2025-05-18 08:05:09'),
(6, 1, 'Vikram Bose', 'Howrah', '1111111111', '54852464', 'Rice', 'non-vegetarian', 'packed-food', 200, 'pices', 'uploads/6829a2d409abb_Cooked-food.jpg', '2025-05-18 09:05:24');

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
(1, '', 'Siuri', 'abc@gmail.com', '7533215964', '$2y$10$fAummSd4lA1oCAFR4vAFfuc0zHT2v8AR2zZqcGftOl5ZAYDYaQYTi', '2025-05-09 15:24:59'),
(2, 'xyz Organization', 'kolkata', 'xyz@gmail.com', '75614585231', '$2y$10$JEJOEFxuo4w5d89RukSDI./fPicKZh8ZhAjAEjT8dQMe5phG.F8Ry', '2025-05-09 15:24:59'),
(3, 'RD Society', 'Panagarh', 'RD@gmail.com', '741598745', '$2y$10$KubXNkd5zlW4WwDjPyQRt.YFVcE9UzDDTCKvXKSYhsaPj/8ppYMPy', '2025-05-09 15:24:59'),
(4, 'BCA Welfare Society', 'Burdwan', 'bca@gmail.com', '741236985', '$2y$10$.EmxV4luruvD1rTZF6zuYeWGs9iiVITwQXGONOw7jqmoipA2nV3O.', '2025-05-09 15:24:59');

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
  `gender` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `address`, `email`, `phone`, `password`, `gender`, `created_at`) VALUES
(1, 'Vikram Bose', 'Burdwan', 'bikram@gmail.com', '1111111111', '$2y$10$nV.fHoTCIyoDFShVC1e8EO7wA0ogCEhuSbfDCXDwpZpOgDtQ3LlTu', 'Male', '2025-05-09 08:40:39'),
(2, 'Arunima Saha', 'Kolkata', 'arunima@gmail.com', '6666666666', '$2y$10$b.tEi5mPhlOBscDq9bTaZONAr.DwkSTLQfvkLbjaQ3kqTzAj93lmy', 'Female', '2025-05-09 08:43:40'),
(3, 'Narendra Modi', 'Galsi', 'india@gmail.com', '0000000000', '$2y$10$sl0HivPuQslmWabERBj0x.VlOUaqEZwhHJB.ipnJFs8povOdcGyYm', 'Other', '2025-05-09 12:45:34'),
(4, 'Babon das', 'hhfy', 'bbb@gmail.com', '5554440078', '$2y$10$LGaFiPPVhLMScNhO/HUpt.3wvW3PW3JqE7jt6hT28RyHw7u/.y/rO', 'Male', '2025-05-11 05:41:53'),
(5, 'Baban Saha', 'Durgapur', 'baban@gmail.com', '2244660055', '$2y$10$jrDKXVpdP8Zvy4xznRbXdefg8uRIbUkrNHwUigcRLB8MPMKbfSLs6', 'Male', '2025-05-11 12:42:57'),
(6, 'Tanmoy Das', 'Delhi', 'tanmoy@gmail.com', '3332220000', '$2y$10$luLAIVW/iA3pHQ/qfeWFP.4czUazan.ZRob1ArZNQ8TSjvLDIA4.e', 'Male', '2025-05-12 13:15:30'),
(7, 'Dinesh Gupta', 'Pune', 'dinesh@gmail.com', '9955110064', '$2y$10$o/J2sPO7EDb3SHD/Ca8Z0eL6Yt98CW0ThE5zYIKCQn72ebaCLwPjy', 'Male', '2025-05-13 17:33:36'),
(8, 'Anuradha Pal', 'Jharkhand', 'pal@gmail.com', '4411441144', '$2y$10$6aNXdajO9wAN9bgBywYCNeSJKRrBZI/bohFv350Kab6.RVubyGBTa', 'Male', '2025-05-13 18:40:19'),
(9, 'aaa', 'aaa', 'aaa@gmail.com', '00110011', '$2y$10$YbSDdv4XvHcZ/WChwk3nSOKtpPZ4Y02nyCabRvUlExDZM2T.Acfda', 'Male', '2025-05-16 06:27:35'),
(10, 'Anjan ', 'Galsi', 'xxx@gmail.com', '77887788', '$2y$10$WfGzi9H4MKgMLk0p4DowTehPEo779mhsxWLB2EGnpD54JGWLFkpA6', 'Male', '2025-05-16 08:38:33'),
(11, 'Mishra Das', 'Baruipur', 'mishra@gmail.com', '9999999999', '$2y$10$2GS64wO1uLbIRkbgS7V.XuX9AjepFUPJMyXLax7JJnFeZuJ/XTUym', 'Female', '2025-05-16 20:17:31');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fooddetails`
--
ALTER TABLE `fooddetails`
  MODIFY `fooddetails_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
