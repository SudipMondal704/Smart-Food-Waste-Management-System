-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 07:42 PM
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
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `msg` text NOT NULL,
  `user_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `msg`, `user_type`) VALUES
(1, 'dsf', 'fjj@gmail.com', '54', ''),
(2, 'dsf', 'fjj@gmail.com', '54', 'Visitor'),
(3, 'dsf', 'fjj@gmail.com', '54', 'Visitor'),
(4, 'dsf', 'dfg@gmail.om', '5454', 'Visitor'),
(5, 'Rayanatan', 'rayantan123@gmail.com', 'guhiuhuuihy', 'Donor'),
(6, 'Shuvankar ', 'Shuvankar@gmail.com', 'I want do donate food through your system. please guide me how to donate food .', 'Visitor'),
(7, 'Priyotosh Mondal', 'anirban@gmail.com', 'very good initiative', 'Donor'),
(8, 'Ananda Dhara foundation', 'ananda@gmail.com', 'Too much helpful website.', 'NGO'),
(9, 'ABC', 'abc@gmail.com', 'fhgfhf', 'Visitor'),
(10, 'Arindam Pal', 'arindam@gmail.com', 'nice', 'Visitor'),
(11, 'Disha banerjee', 'disha@gmail.com', 'helpful', 'Visitor'),
(12, 'alexa', 'alexa@gmail.com', 'Nice', 'Visitor'),
(13, 'Doremon', 'doremon@gmail.com', 'hi', 'Visitor');

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
(1, 1, 'Bikash Biswas', 'deb@gmail.com', '88558857', 'Very helpful system for our socity.', '2025-05-27 07:48:33'),
(2, 1, 'Bikash Biswas', 'deb@gmail.com', '88558857', 'good', '2025-06-22 12:59:36'),
(3, 9, 'Baban Chaterjee', 'babai@gmail.com', '7412474610', 'Very helpful system', '2025-06-22 21:34:04'),
(4, 16, 'Rayantan Das', 'rayantan123@gmail.com', '456984685', 'Excellent System', '2025-07-01 15:19:57');

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
  `unit` varchar(50) DEFAULT 'kg',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_ngo_id` int(11) DEFAULT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fooddetails`
--

INSERT INTO `fooddetails` (`fooddetails_id`, `user_id`, `donor_name`, `pickup_address`, `phone`, `alt_phone`, `food_name`, `food_type`, `food_category`, `quantity`, `unit`, `image`, `created_at`, `assigned_ngo_id`, `submission_id`, `status`) VALUES
(36, 9, 'Baban Chaterjee', 'Burdwan', '7412474610', '5485246400', 'Fried Rice', 'vegetarian', 'cooked-food', 50, 'kg', 'uploads/685870f7330f8_1750626551.jpg', '2025-06-22 21:09:11', 4, 17, 'pending'),
(37, 9, 'Baban Chaterjee', 'Burdwan', '7412474610', '5485246400', 'Chili Chiken', 'non-vegetarian', 'cooked-food', 30, 'kg', 'uploads/685870f733c99_1750626551.jpg', '2025-06-22 21:09:11', 4, 17, 'pending'),
(38, 9, 'Baban Chaterjee', 'Burdwan', '7412474610', '5485246400', 'Sweets', 'vegetarian', 'packed-food', 100, 'packets', 'uploads/685870f7345a9_1750626551.jpg', '2025-06-22 21:09:11', 4, 17, 'accepted'),
(39, 19, 'Nilanjana Pal', 'Bankura', '7501538486', '4568521352', 'Butter nun', 'vegetarian', 'cooked-food', 200, 'pices', 'uploads/686d5522eacc8_1751995682.jpg', '2025-07-08 17:28:02', 12, 18, 'accepted'),
(40, 19, 'Nilanjana Pal', 'Bankura', '7501538486', '4568521352', 'Paneer Curry', 'vegetarian', 'cooked-food', 60, 'kg', 'uploads/686d5522eb941_1751995682.jpg', '2025-07-08 17:28:02', 12, 18, 'accepted');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `reset_otp` varchar(6) DEFAULT NULL,
  `reset_otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngo`
--

INSERT INTO `ngo` (`ngo_id`, `ngo_name`, `address`, `email`, `phone`, `password`, `created_at`, `image`, `reset_otp`, `reset_otp_expiry`) VALUES
(1, 'Anandadhara Foundation', 'Kolkata', 'ananda@gmail.com', '645456545', '666', '2025-05-27 08:37:21', NULL, NULL, NULL),
(2, 'Durgapur Association', 'Durgapur', 'durgapur@gmail.com', '57575757', '5555', '2025-05-31 12:05:16', NULL, NULL, NULL),
(3, 'Plate of hope', 'Durgapur', 'hope@gmail.com', '741147741', '714', '2025-05-31 12:45:13', NULL, NULL, NULL),
(4, 'Burdwan Welfare Society', 'Burdwan', 'burdwan@gmail.com', '6767676767', '676', '2025-05-31 14:52:31', 'NGO_4_1750627378.jpg', '682597', '2025-06-18 20:06:46'),
(5, 'Annapurna Seva Trust', 'Burdwan', 'annapurna@gmail.com', '2828282828', '282', '2025-05-31 15:12:13', NULL, NULL, NULL),
(6, 'Asansol Foundation', 'Asansol', 'asansol@gmail.com', '754347812', '750125', '2025-06-02 18:42:42', NULL, NULL, NULL),
(7, 'Akshya Patra Foundation', 'Siuri', 'akshya@gmail.com', '0110011001', '520250', '2025-06-05 02:34:26', 'NGO_7_1751382724.jpg', NULL, NULL),
(8, 'Asobondhu Association', 'Galsi', 'sudip777@gmail.com', '852369741', 'sudip', '2025-06-17 16:22:59', '', NULL, NULL),
(12, 'SETU', 'Bankura', 'setu@gmail.com', '7501538488', '323232', '2025-07-08 17:20:13', 'NGO_12_1751996120.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `details` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `title`, `details`, `date`, `time`) VALUES
(1, 'New User Signup', 'Sutonu has signed up in your portal', '', ''),
(2, 'New User Signup', 'TEST1 has signed up in your portal', '', ''),
(3, 'New User Signup', 'TEST1 has signed up in your portal', '', ''),
(4, 'New User Signup', 'Test2 has signed up in your portal', '2025-06-19', '07:39:19'),
(5, 'New User Signup', 'Sudip has signed up in your portal', '2025-06-19', '07:58:42'),
(6, 'New User Signup', 'Rayantan has signed up in your portal', '2025-06-19', '07:59:11'),
(7, 'New Donation', 'Bikash Biswas has donated food', '2025-06-21', '07:25:07'),
(8, 'NGO Login', 'Anandadhara Foundation has logged into the portal', '2025-06-21', '13:41:08'),
(9, 'DONOR Login', ' has logged into the portal', '2025-06-21', '13:46:29'),
(10, 'DONOR Login', ' has logged into the portal', '2025-06-21', '13:47:44'),
(11, 'DONOR Login', ' has logged into the portal', '2025-06-21', '13:50:09'),
(12, 'DONOR Login', 'Rayantan Sharma has loggedin into the portal', '2025-06-21', '13:56:27'),
(13, 'DONOR Login', 'Ashok Ghosh has loggedin into the portal', '2025-06-22', '12:35:41'),
(14, 'DONOR Login', 'Baban Chaterjee has loggedin into the portal', '2025-06-22', '14:04:27'),
(15, 'New User Signup', 'Anirban Mondal has signed up in your portal', '2025-06-22', '14:07:29'),
(16, 'NGO Login', 'Plate of hope has logged into the portal', '2025-06-22', '14:08:40'),
(17, 'New NGO Signup', ' has signed up in your portal', '2025-06-22', '14:20:32'),
(18, 'New NGO Signup', 'Rahul Foundation has signed up in your portal', '2025-06-22', '14:26:11'),
(19, 'DONOR Login', 'Ashok Ghosh has loggedin into the portal', '2025-06-22', '14:34:33'),
(20, 'DONOR Login', 'Ashok Ghosh has loggedin into the portal', '2025-06-22', '14:40:57'),
(21, 'DONOR Login', 'Bikash Biswas has loggedin into the portal', '2025-06-22', '14:52:08'),
(22, 'DONOR Login', 'Bikash Biswas has loggedin into the portal', '2025-06-22', '14:57:43'),
(23, 'New User Signup', 'Rayantan Das has signed up in your portal', '2025-06-22', '15:03:47'),
(24, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-06-22', '15:04:58'),
(25, 'New Donation', 'Rayantan Das has donated food', '2025-06-22', '18:36:57'),
(26, 'NGO Login', 'Akshya Patra Foundation has logged into the portal', '2025-06-22', '15:12:56'),
(27, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-06-22', '15:15:03'),
(28, 'DONOR Login', 'Baban Chaterjee has loggedin into the portal', '2025-06-22', '22:35:42'),
(29, 'DONOR Login', 'Baban Chaterjee has loggedin into the portal', '2025-06-22', '23:03:28'),
(30, 'New Donation', 'Baban Chaterjee has donated food', '2025-06-23', '02:39:11'),
(31, 'NGO Login', 'Burdwan Welfare Society has logged into the portal', '2025-06-22', '23:20:27'),
(32, 'DONOR Login', 'Baban Chaterjee has loggedin into the portal', '2025-06-22', '23:28:17'),
(33, 'DONOR Login', 'Baban Chaterjee has loggedin into the portal', '2025-06-23', '06:04:12'),
(34, 'NGO Login', 'Burdwan Welfare Society has logged into the portal', '2025-06-23', '06:06:52'),
(35, 'DONOR Login', 'Baban Chaterjee has loggedin into the portal', '2025-06-23', '06:15:43'),
(36, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-06-24', '08:51:57'),
(37, 'DONOR Login', 'Anirban Mondal has loggedin into the portal', '2025-06-24', '14:44:21'),
(38, 'NGO Login', 'Anandadhara Foundation has logged into the portal', '2025-06-24', '14:47:29'),
(39, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-06-28', '19:05:00'),
(40, 'New User Signup', 'ASHIS DAS has signed up in your portal', '2025-06-28', '19:11:01'),
(41, 'DONOR Login', 'ASHIS DAS has loggedin into the portal', '2025-06-28', '19:11:35'),
(42, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-06-29', '17:46:57'),
(43, 'NGO Login', 'Akshya Patra Foundation has logged into the portal', '2025-07-01', '17:08:38'),
(44, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-07-01', '17:14:45'),
(45, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-07-03', '18:08:23'),
(46, 'NGO Login', 'Asobondhu Association has logged into the portal', '2025-07-03', '18:10:44'),
(47, 'DONOR Login', 'Rayantan Das has loggedin into the portal', '2025-07-07', '16:54:48'),
(48, 'New User Signup', 'Animash Jadav has signed up in your portal', '2025-07-08', '06:12:37'),
(49, 'DONOR Login', 'Animash Jadav has loggedin into the portal', '2025-07-08', '06:13:00'),
(50, 'New NGO Signup', 'SETU has signed up in your portal', '2025-07-08', '19:20:13'),
(51, 'NGO Login', 'SETU has logged into the portal', '2025-07-08', '19:20:56'),
(52, 'New User Signup', 'Rebati Pal has signed up in your portal', '2025-07-08', '19:23:41'),
(53, 'DONOR Login', 'Rebati Pal has loggedin into the portal', '2025-07-08', '19:24:31'),
(54, 'DONOR Login', 'Rebati Pal has loggedin into the portal', '2025-07-08', '19:25:03'),
(55, 'New Donation', 'Rebati Pal has donated food', '2025-07-08', '22:58:02'),
(56, 'NGO Login', 'SETU has logged into the portal', '2025-07-08', '19:34:28'),
(57, 'DONOR Login', 'Rebati Pal has loggedin into the portal', '2025-07-08', '19:35:48'),
(58, 'NGO Login', 'SETU has logged into the portal', '2025-07-08', '19:39:25'),
(59, 'DONOR Login', 'Rebati Pal has loggedin into the portal', '2025-07-08', '19:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `submission_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `donor_name` varchar(100) DEFAULT NULL,
  `pickup_address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `alt_phone` varchar(15) DEFAULT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`submission_id`, `user_id`, `donor_name`, `pickup_address`, `phone`, `alt_phone`, `submission_time`) VALUES
(1, 3, 'Ashok Ghosh', 'Kolkata', '111', '454', '2025-05-30 15:13:13'),
(2, 2, 'Ashis Banerjee', 'Durgapur', '12321123', '454', '2025-05-30 16:01:48'),
(3, 1, 'Bikash Hazra', 'Kolkata', '88558857', '4568521', '2025-05-30 17:08:44'),
(4, 1, 'Bikash Hazra', 'Siuri', '88558857', '4568521', '2025-05-30 17:22:22'),
(5, 1, 'Bikash Hazra', 'Kolkata', '88558857', '4568521', '2025-05-31 08:18:17'),
(6, 2, 'Ashis Banerjee', 'Durgapur', '12321123', '546', '2025-05-31 11:27:27'),
(7, 4, 'Rayantan Sharma', 'Burdwan', '2772277272', '272', '2025-05-31 15:00:23'),
(8, 4, 'Rayantan Sharma', 'Burdwan', '2772277272', '102', '2025-05-31 16:19:38'),
(9, 4, 'Rayantan Sharma', 'Burdwan', '2772277272', '145254', '2025-05-31 16:32:30'),
(10, 4, 'Rayantan Sharma', 'Burdwan', '2772277272', '6666666666', '2025-05-31 16:53:42'),
(11, 5, 'Bidisha Haldar', 'Asansol', '45662134', '45662134', '2025-06-02 18:37:21'),
(12, 9, 'Rayantan Das', 'Siuri', '7412474610', 'vjhghjg', '2025-06-17 10:39:25'),
(13, 1, 'Sudip Mondal', 'Galsi', '88558857', '2222222', '2025-06-17 16:12:29'),
(14, 1, 'Babu Mondal', 'kolkata', '88558857', '3838777047', '2025-06-17 18:25:27'),
(15, 1, 'Babu Mondal', 'Burdwan', '88558857', '3838777047', '2025-06-21 05:25:07'),
(16, 16, 'Debarghya Das', 'Siuri', '9476145237', '154534244', '2025-06-22 13:06:57'),
(17, 9, 'Baban Chaterjee', 'Burdwan', '7412474610', '5485246400', '2025-06-22 21:09:11'),
(18, 19, 'Nilanjana Pal', 'Bankura', '7501538486', '4568521352', '2025-07-08 17:28:02');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `address`, `email`, `phone`, `password`, `created_at`, `reset_otp`, `reset_otp_expiry`, `updated_at`, `image`) VALUES
(1, 'Bikash Biswas', 'Siuri', 'deb@gmail.com', '88558857', '888', '2025-05-27 07:46:06', '257563', '2025-06-18 20:08:04', '2025-06-18 17:53:04', NULL),
(2, 'Ashis Banerjee', 'Durgapur', 'ashisB@gmail.com', '12321123', '123', '2025-05-28 16:31:23', NULL, NULL, '2025-05-28 16:31:23', NULL),
(3, 'Ashok Ghosh', 'Kolkata', 'ashok@gmail.com', '111', '111', '2025-05-28 16:40:01', NULL, NULL, '2025-05-28 16:40:01', NULL),
(4, 'Rayantan Sharma', 'Asansol', 'sharma@gmail.com', '2772277272', '272', '2025-05-31 14:49:22', NULL, NULL, '2025-06-04 18:26:34', NULL),
(5, 'Bidisha Haldar', 'Asansol', 'bidisha@gmail.com', '45662134', '420420', '2025-06-02 06:51:56', NULL, NULL, '2025-06-02 06:51:56', NULL),
(6, 'Nillam Ghosh', 'Siuri', 'nilam@gmail.com', '103212301', '123456', '2025-06-04 08:03:33', NULL, NULL, '2025-06-04 08:03:33', 'about.png.jpg'),
(7, 'Debarghya banerjee', 'Burdwan', 'debarghyaD@gmail.com', '4568522505', '420651', '2025-06-04 10:49:03', NULL, NULL, '2025-06-04 10:49:03', 'myself.jpg'),
(8, 'Virat Chaterjee', 'Kolkata', 'virat18@gmail.com', '2011023018', '181818', '2025-06-04 10:59:36', NULL, NULL, '2025-06-04 10:59:36', 'about.png.jpg'),
(9, 'Baban Chaterjee', 'Burdwan', 'babai@gmail.com', '7412474610', '230320', '2025-06-04 11:16:19', NULL, NULL, '2025-06-22 20:38:16', 'Donor_9_1750624696.jpg'),
(10, 'Sudip Mondal', 'Galsi', 'sudipmondal704777@gmail.com', '7047778383', '87654321', '2025-06-18 18:40:17', NULL, NULL, '2025-06-18 19:18:54', '1750272017_sudip.jpg'),
(11, 'TEST1', 'kjhkjh', 'kjhkjh@gmail.com', '687654654', '321654987', '2025-06-19 05:38:07', NULL, NULL, '2025-06-19 05:38:07', ''),
(12, 'Test2', 'sdfjygsdfjg', 'jgfhjg@gmailc.om', '9876543212', '987654651', '2025-06-19 05:39:19', NULL, NULL, '2025-06-19 05:39:19', ''),
(13, 'Sudip', 'kjhkjh', 'shiudfhghk@gmail.com', '65465465464', '65465465464', '2025-06-19 05:58:42', NULL, NULL, '2025-06-19 05:58:42', ''),
(14, 'Rayantan', 'jhdbjh', 'jhjh@gmail.com', '68746', '65464654', '2025-06-19 05:59:11', NULL, NULL, '2025-06-19 05:59:11', ''),
(15, 'Anirban Mondal', 'Burdwan', 'anirban@gmail.com', '4040404040', '444444', '2025-06-22 12:07:29', NULL, NULL, '2025-06-22 12:07:29', '1750594049_Donor_13_1749498542.jpg'),
(16, 'Rayantan Das', 'Siuri', 'rayantan123@gmail.com', '9476145237', 'rayantan', '2025-06-22 13:03:47', NULL, NULL, '2025-06-22 13:03:47', '1750597427_About.jpg'),
(17, 'ASHIS DAS', 'SURI', 'rayantan2003@gmail.com', '7501538487', 'ashis1972', '2025-06-28 17:11:01', NULL, NULL, '2025-06-28 17:11:01', ''),
(18, 'Animesh Jadav', 'Patna', 'animesh1@gmail.com', '0880800880', '000000', '2025-07-08 04:12:37', NULL, NULL, '2025-07-08 04:14:35', 'Donor_18_1751948075.jpg'),
(19, 'Rebati Pal', 'Burdwan', 'rebati@gmail.com', '7501538486', '323232', '2025-07-08 17:23:41', NULL, NULL, '2025-07-08 17:37:09', 'Donor_19_1751996229.jpg');

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
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `idx_fooddetails_user_id` (`user_id`),
  ADD KEY `idx_fooddetails_status` (`status`),
  ADD KEY `idx_fooddetails_created_at` (`created_at`);

--
-- Indexes for table `ngo`
--
ALTER TABLE `ngo`
  ADD PRIMARY KEY (`ngo_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submission_id`);

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
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fooddetails`
--
ALTER TABLE `fooddetails`
  MODIFY `fooddetails_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `ngo`
--
ALTER TABLE `ngo`
  MODIFY `ngo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fooddetails_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`submission_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
