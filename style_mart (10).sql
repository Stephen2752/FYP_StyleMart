-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 02:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `style_mart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(9) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`) VALUES
(3, 'stephen2005', '', '2005');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(9) NOT NULL,
  `user_id` int(9) NOT NULL,
  `product_id` int(9) NOT NULL,
  `quantity` int(9) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`, `size`) VALUES
(1, 8, 9, 2, '2025-06-03 13:29:23', ''),
(2, 8, 8, 5, '2025-06-03 13:35:28', ''),
(3, 8, 8, 1, '2025-06-03 13:38:04', ''),
(4, 8, 9, 1, '2025-06-03 13:38:59', ''),
(5, 8, 9, 1, '2025-06-03 13:40:27', ''),
(6, 8, 9, 1, '2025-06-03 13:40:51', ''),
(7, 8, 9, 1, '2025-06-03 13:40:55', ''),
(8, 8, 9, 1, '2025-06-03 13:41:04', ''),
(9, 8, 7, 1, '2025-06-03 13:41:14', ''),
(10, 8, 8, 2, '2025-06-03 13:42:33', ''),
(11, 8, 8, 2, '2025-06-03 13:42:37', ''),
(12, 8, 8, 2, '2025-06-03 13:42:42', ''),
(25, 12, 9, 1, '2025-06-08 09:33:44', 'S'),
(27, 12, 9, 2, '2025-06-08 09:34:10', 'L'),
(28, 12, 9, 1, '2025-06-08 09:34:30', 'XL'),
(31, 12, 10, 2, '2025-06-08 09:45:13', 'S'),
(32, 12, 8, 1, '2025-06-08 09:47:59', 'M'),
(35, 13, 7, 1, '2025-06-08 16:36:04', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `product_id` int(9) NOT NULL,
  `user_id` int(9) NOT NULL,
  `comment_text` text NOT NULL,
  `rate` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `product_id`, `user_id`, `comment_text`, `rate`, `created_at`) VALUES
(1, 8, 7, 'abc', 5, '2025-06-02 20:14:00'),
(2, 8, 10, 'gooooood', 3, '2025-06-02 20:15:07'),
(3, 18, 16, 'goood quality', 5, '2025-06-24 16:09:45');

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `complaint_id` int(9) NOT NULL,
  `user_id` int(9) NOT NULL,
  `product_id` int(9) NOT NULL,
  `seller_id` int(9) NOT NULL,
  `report_reason` varchar(255) NOT NULL,
  `complaint_text` text NOT NULL,
  `image_path_1` varchar(255) NOT NULL,
  `image_path_2` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `assigned_admin_id` int(9) DEFAULT NULL,
  `admin_response` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`complaint_id`, `user_id`, `product_id`, `seller_id`, `report_reason`, `complaint_text`, `image_path_1`, `image_path_2`, `status`, `assigned_admin_id`, `admin_response`, `created_at`, `updated_at`) VALUES
(1, 16, 9, 10, 'Fake Product', 'a123 ezy is fake', 'uploads/reports/685a5175dd18a_Picture1.jpg', 'uploads/reports/685a5175dd197_Screenshot (1).png', 'In Review', 3, NULL, '2025-06-24 15:19:17', '2025-06-24 15:20:13'),
(2, 16, 9, 10, 'Fake Product', 'a123 ezy is fake', 'uploads/reports/685a5179afc60_Picture1.jpg', 'uploads/reports/685a5179afc66_Screenshot (1).png', 'Pending', NULL, NULL, '2025-06-24 15:19:21', '2025-06-24 15:19:21'),
(3, 16, 18, 11, 'Fake Product', 'fake nike', 'uploads/reports/685a5247b7621_Screenshot 2024-07-23 111354.png', 'uploads/reports/685a5247b7663_Screenshot 2024-07-24 105602.png', 'Pending', NULL, NULL, '2025-06-24 15:22:47', '2025-06-24 15:22:47'),
(4, 16, 18, 11, 'Fake Product', 'fake nike', 'uploads/reports/685a5298182bb_Screenshot 2024-07-23 111354.png', 'uploads/reports/685a5298182c6_Screenshot 2024-07-24 105602.png', 'Pending', NULL, NULL, '2025-06-24 15:24:08', '2025-06-24 15:24:08'),
(5, 16, 17, 18, 'Fake Product', 'adidas fake', 'uploads/reports/685a52aacc464_Screenshot 2024-07-24 142119.png', 'uploads/reports/685a52aacc46b_Screenshot 2024-07-24 165911.png', 'Pending', NULL, NULL, '2025-06-24 15:24:26', '2025-06-24 15:24:26'),
(6, 17, 18, 11, 'Fake Product', 'fake nike', 'uploads/reports/685a5fde7de31_Screenshot 2024-07-23 111354.png', 'uploads/reports/685a5fde7de40_Screenshot 2024-07-23 111354.png', 'Resolved', 3, 'it product are be baned', '2025-06-24 16:20:46', '2025-06-24 16:23:20'),
(7, 17, 18, 11, 'Fake Product', 'fakeee nike', 'uploads/reports/685a630e26a7e_Screenshot 2024-07-23 111354.png', 'uploads/reports/685a630e26a83_Screenshot 2024-07-23 111354.png', 'Pending', NULL, NULL, '2025-06-24 16:34:22', '2025-06-24 16:34:22'),
(8, 17, 17, 18, 'Fake Product', 'abc', 'uploads/reports/685a63f7e659e_Screenshot 2024-07-23 111354.png', 'uploads/reports/685a63f7e65a2_Screenshot 2024-07-23 111354.png', 'Pending', NULL, NULL, '2025-06-24 16:38:15', '2025-06-24 16:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `favorite_id` int(9) NOT NULL,
  `user_id` int(9) NOT NULL,
  `product_id` int(9) NOT NULL,
  `favorited_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`favorite_id`, `user_id`, `product_id`, `favorited_at`) VALUES
(2, 8, 9, '2025-06-03 12:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(9) DEFAULT NULL,
  `admin_id` int(9) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `user_id`, `admin_id`, `message`, `is_read`, `created_at`) VALUES
(1, 17, NULL, 'Your report has been successfully submitted and is awaiting admin review.', 1, '2025-06-24 16:20:46'),
(2, NULL, NULL, 'A new complaint has been submitted and needs your attention.', 1, '2025-06-24 16:20:46'),
(3, 17, NULL, 'Your report has been successfully submitted and is awaiting admin review.', 1, '2025-06-24 16:34:22'),
(4, NULL, NULL, 'A new complaint has been submitted and requires your attention.', 1, '2025-06-24 16:34:22'),
(5, 17, NULL, 'Your report has been successfully submitted and is awaiting admin review.', 1, '2025-06-24 16:38:15'),
(6, NULL, NULL, 'A new complaint has been submitted and requires your attention.', 0, '2025-06-24 16:38:15'),
(7, NULL, NULL, 'Your complaint (ID: ) has been processed. Admin Response: ', 0, '2025-06-24 16:44:38'),
(8, NULL, 3, 'You have successfully processed complaint ID: .', 1, '2025-06-24 16:44:38'),
(9, NULL, NULL, 'Your complaint (ID: ) has been processed. Admin Response: ', 0, '2025-06-24 16:44:54'),
(10, NULL, 3, 'You have successfully processed complaint ID: .', 1, '2025-06-24 16:44:54'),
(11, NULL, NULL, 'Your complaint (ID: ) has been processed. Admin Response: ', 0, '2025-06-24 16:44:54'),
(12, NULL, 3, 'You have successfully processed complaint ID: .', 1, '2025-06-24 16:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(9) NOT NULL,
  `user_id` int(9) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(100) NOT NULL,
  `rate` int(5) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `subcategory` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `user_id`, `product_name`, `category`, `price`, `description`, `status`, `rate`, `comment`, `created_at`, `subcategory`) VALUES
(7, 7, 'Cropped cotton-jersey T-shirt', 'Women - Clothes', 75.99, 'abcdefghijklmnop', 'Available', NULL, NULL, '2025-06-02 12:39:07', NULL),
(8, 10, 'Teza wool maxi dress', 'Women - Clothes', 50.00, 'The Row\'s minimalist approach to design ensures pieces like this \'Teza\' dress can easily be dressed up or down, making it so versatile. It\'s cut from black wool in a streamlined silhouette and has subtle pleats at the front for volume.', 'Available', NULL, NULL, '2025-06-02 13:09:28', NULL),
(9, 10, 'EZY Ultra Stretch Jeans', 'Men - Pants', 88.99, 'EZY Ultra Stretch Jeans', 'Available', NULL, NULL, '2025-06-02 20:24:09', NULL),
(10, 11, 'wc123abc', 'Men - Shoes', 12.00, 'avswcshvdfjwvefjk', 'Available', NULL, NULL, '2025-06-03 14:56:43', NULL),
(13, 18, 'nike x nba chicago bulls jersey \'derrick rose 1\' dn2131-657', 'Men - Clothes', 900.00, 'bulls', 'Available', NULL, NULL, '2025-06-22 19:55:31', NULL),
(14, 18, 'nike x nba chicago bulls jersey \'derrick rose 1\' dn2131-657', 'Men - Clothes', 900.00, 'bulls', 'Available', NULL, NULL, '2025-06-22 19:55:34', NULL),
(15, 18, 'lebron', 'Men - Clothes', 900.00, 'lakers', 'Available', NULL, NULL, '2025-06-22 19:57:53', NULL),
(16, 18, 'lebron', 'Men - Clothes', 900.00, 'lakers', 'Available', NULL, NULL, '2025-06-22 19:57:56', NULL),
(17, 18, 'Adidas Originals', 'Men - Shoes', 469.00, 'Samba OG leather ', 'Available', NULL, NULL, '2025-06-22 20:14:05', NULL),
(18, 11, 'NIKE', 'Men - Shoes, Women - Shoes', 368.00, 'Court Vision Low Shoes', 'Available', NULL, NULL, '2025-06-22 23:43:43', NULL),
(20, 11, 'fake', 'Men - Clothes', 11.00, 'qwerg', 'Available', NULL, NULL, '2025-06-23 00:33:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`image_id`, `product_id`, `image_path`, `created_at`) VALUES
(8, 7, 'uploads/683d2aeb58aaa_Screenshot 2025-06-02 115028.png', '2025-06-02 12:39:07'),
(9, 7, 'uploads/683d2aeb5916e_Screenshot 2025-06-02 122739.png', '2025-06-02 12:39:07'),
(10, 7, 'uploads/683d2e2b36e7a_Screenshot 2025-06-02 125234.png', '2025-06-02 12:52:59'),
(11, 8, 'uploads/683d320840446_Screenshot 2025-06-02 130250.png', '2025-06-02 13:09:28'),
(12, 8, 'uploads/683d320840dc0_Screenshot 2025-06-02 130303.png', '2025-06-02 13:09:28'),
(13, 8, 'uploads/683d323671391_Screenshot 2025-06-02 130311.png', '2025-06-02 13:10:14'),
(14, 9, 'uploads/683d97e979cbd_Screenshot 2025-06-02 202311.png', '2025-06-02 20:24:09'),
(15, 10, 'uploads/683e9cab3a589_Screenshot 2025-06-03 145615.png', '2025-06-03 14:56:43'),
(20, 13, 'uploads/6857ef33bcd59_s-l1200.jpg', '2025-06-22 19:55:31'),
(21, 14, 'uploads/6857ef361d72e_s-l1200.jpg', '2025-06-22 19:55:34'),
(22, 15, 'uploads/6857efc15858d_lbj 23.png', '2025-06-22 19:57:53'),
(23, 16, 'uploads/6857efc457767_lbj 23.png', '2025-06-22 19:57:56'),
(24, 17, 'uploads/6857f38dcf360_Screenshot 2025-06-22 201155.png', '2025-06-22 20:14:05'),
(25, 18, 'uploads/685824af0efd0_Screenshot 2025-06-22 234112.png', '2025-06-22 23:43:43'),
(27, 20, 'uploads/68583056e8be1_Picture1.jpg', '2025-06-23 00:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_stock`
--

CREATE TABLE `product_stock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_stock`
--

INSERT INTO `product_stock` (`stock_id`, `product_id`, `size`, `quantity`, `created_at`, `updated_at`) VALUES
(17, 8, 'S', 10, '2025-06-02 13:10:14', '2025-06-02 13:10:14'),
(18, 8, 'M', 10, '2025-06-02 13:10:14', '2025-06-02 13:10:14'),
(19, 8, 'L', 10, '2025-06-02 13:10:14', '2025-06-02 13:10:14'),
(20, 7, 'S', 22, '2025-06-02 19:38:13', '2025-06-02 19:38:13'),
(21, 7, 'M', 10, '2025-06-02 19:38:13', '2025-06-02 19:38:13'),
(22, 7, 'L', 21, '2025-06-02 19:38:13', '2025-06-02 19:38:13'),
(23, 7, 'XL', 15, '2025-06-02 19:38:13', '2025-06-02 19:38:13'),
(24, 9, 'S', 8, '2025-06-02 20:24:09', '2025-06-22 21:33:26'),
(25, 9, 'M', 0, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(26, 9, 'L', 15, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(27, 9, 'XL', 5, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(66, 10, 'S', 5, '2025-06-18 18:07:49', '2025-06-24 07:49:23'),
(67, 10, 'M', 0, '2025-06-18 18:07:49', '2025-06-22 20:36:17'),
(68, 10, 'XL', 0, '2025-06-18 18:07:49', '2025-06-18 18:07:49'),
(69, 13, 'S', 100, '2025-06-22 19:55:31', '2025-06-22 19:55:31'),
(70, 14, 'S', 100, '2025-06-22 19:55:34', '2025-06-22 19:55:34'),
(71, 15, 'S', 100, '2025-06-22 19:57:53', '2025-06-22 19:57:53'),
(72, 16, 'S', 98, '2025-06-22 19:57:56', '2025-06-22 23:37:47'),
(73, 17, 'US7.5', 1, '2025-06-22 20:14:05', '2025-06-22 21:33:18'),
(74, 18, 'US7.5', 9, '2025-06-22 23:43:43', '2025-06-22 23:43:43'),
(75, 18, 'US7', 9, '2025-06-22 23:43:43', '2025-06-22 23:43:43'),
(88, 20, 'S', 1, '2025-06-23 00:46:16', '2025-06-23 00:46:49');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(9) NOT NULL,
  `buyer_id` int(9) NOT NULL,
  `seller_id` int(9) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `receipt` varchar(255) DEFAULT NULL,
  `confirmed_by` int(9) DEFAULT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `product_id` int(9) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `buyer_id`, `seller_id`, `payment_status`, `total_amount`, `receipt`, `confirmed_by`, `transaction_date`, `status`, `product_id`, `shipping_address`) VALUES
(71, 18, 11, 'Verified', 12.00, 'uploads/receipts/1750593144_Screenshot (1).png', NULL, '2025-06-22 19:52:24', 'received', 10, NULL),
(72, 18, 11, 'Payment Failed', 12.00, 'uploads/receipts/1750593174_Screenshot (1).png', NULL, '2025-06-22 19:52:54', 'canceled', 10, NULL),
(73, 18, 18, 'Verified', 469.00, 'uploads/receipts/1750595728_Screenshot 2025-06-16 122043.png', NULL, '2025-06-22 20:35:28', 'received', 17, 'the arc cyberjaya block d 17-03'),
(74, 18, 10, 'Verified', 88.99, 'uploads/receipts/1750598347_Picture1.jpg', NULL, '2025-06-22 21:19:07', 'pending', 9, 'the arc cyberjaya block c 23-03'),
(75, 18, 11, 'Verified', 12.00, 'uploads/receipts/1750598484_Screenshot 2024-07-31 144220.png', NULL, '2025-06-22 21:21:24', 'pending', 10, 'the arc cyberjaya block c 23-03'),
(76, 16, 18, 'Verified', 900.00, 'uploads/receipts/1750606631_Screenshot 2024-07-24 165911.png', NULL, '2025-06-22 23:37:11', 'received', 16, 'the arc cyberjaya block d 17-03'),
(77, 16, 11, 'Verified', 15.00, 'uploads/receipts/1750607930_Screenshot 2024-07-24 142119.png', NULL, '2025-06-22 23:58:50', 'received', 19, 'the arc cyberjaya block d 17-03'),
(78, 16, 11, 'Verified', 15.00, 'uploads/receipts/1750609108_Screenshot 2024-07-24 165911.png', NULL, '2025-06-23 00:18:28', 'pending', 19, 'the arc cyberjaya block d 17-03'),
(79, 16, 11, 'Verified', 15.00, 'uploads/receipts/1750609757_Screenshot 2024-07-31 144220.png', NULL, '2025-06-23 00:29:17', 'pending', 19, 'the arc cyberjaya block d 17-03'),
(80, 16, 11, 'Verified', 11.00, 'uploads/receipts/1750610035_Picture1.jpg', NULL, '2025-06-23 00:33:55', 'pending', 20, 'the arc cyberjaya block d 17-03'),
(81, 16, 11, 'Verified', 11.00, 'uploads/receipts/1750610431_Screenshot 2024-07-24 110906.png', NULL, '2025-06-23 00:40:31', 'pending', 20, 'the arc cyberjaya block d 17-03'),
(82, 16, 11, 'Payment Failed', 11.00, 'uploads/receipts/1750610539_Screenshot 2024-07-24 142119.png', NULL, '2025-06-23 00:42:19', 'canceled', 20, 'the arc cyberjaya block d 17-03'),
(83, 16, 11, 'Payment Failed', 11.00, 'uploads/receipts/1750610646_Screenshot 2024-07-24 110906.png', NULL, '2025-06-23 00:44:06', 'canceled', 20, 'the arc cyberjaya block d 17-03'),
(84, 16, 11, 'Payment Failed', 11.00, 'uploads/receipts/1750610801_Screenshot 2024-07-31 144220.png', NULL, '2025-06-23 00:46:41', 'canceled', 20, 'the arc cyberjaya block d 17-03'),
(85, 16, 11, 'Paid', 12.00, 'uploads/receipts/1750722563_Screenshot 2024-07-24 110906.png', NULL, '2025-06-24 07:49:23', 'pending', 10, 'the arc cyberjaya block d 17-03');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_item`
--

CREATE TABLE `transaction_item` (
  `item_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_item`
--

INSERT INTO `transaction_item` (`item_id`, `transaction_id`, `product_id`, `size`, `quantity`, `price`) VALUES
(9, 71, 10, 'M', 1, 12.00),
(10, 72, 10, 'M', 1, 12.00),
(11, 73, 17, 'US7.5', 1, 469.00),
(12, 74, 9, 'S', 1, 88.99),
(13, 75, 10, 'S', 1, 12.00),
(14, 76, 16, 'S', 1, 900.00),
(18, 80, 20, 'S', 1, 11.00),
(19, 81, 20, 'S', 1, 11.00),
(20, 82, 20, 'S', 1, 11.00),
(21, 83, 20, 'S', 1, 11.00),
(22, 84, 20, 'S', 1, 11.00),
(23, 85, 10, 'S', 1, 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(9) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `qrcode` varchar(255) DEFAULT NULL,
  `status` enum('active','banned') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `contact_info`, `phone_number`, `qrcode`, `status`) VALUES
(1, 'Stephen14233', '', '$2y$10$3qMyqmLpIBNyhJ343zFJGOTahm3Is59tvu3NicXjtzxgoX/LDWwo.', 'csvb nmyjthgvdc', NULL, NULL, 'banned'),
(2, 'stephen1', '', '$2y$10$hyS3MCjgt.0ZRmT4XVf62us96MKAmxbHgLADw.eheosn5cdQtKgCa', 's dvfgnmhgfvdc', NULL, NULL, 'banned'),
(3, 'Stephen13', 'stephen1313@gmail.com', '$2y$10$ecSbPF/TBJMOO/LdhgYGz.DblJc84FSpGXbJV5yo0dhk.oqyJmySS', 'jalan abu 1234', NULL, NULL, 'active'),
(4, 'step121212', 'step121212@gmail.com', '$2y$10$SJGi9siPMXcZUx5vZl7B7.iBEUssfJ0DPsxdDOeLTtyx5lA3M4ypW', NULL, NULL, NULL, 'active'),
(5, 'step1212', 'step1212@gmail.com', '$2y$10$oHoFPNypxloOd.yknG7tee0INOVRt6R08cBamz1TXNyelLVOtVf5.', NULL, NULL, NULL, 'active'),
(6, 'stephen99', 'stephen99@gmail.com', '$2y$10$DTCLhMO9vaMDuDXGPU4aY.qYyd7YVJRl3VGehkvlA0QqUNuaw35Lu', NULL, NULL, NULL, 'active'),
(7, 'w123', 'w123@gamil.com', '$2y$10$uIyNYya.KVPbV604Ka8s4eSN4B4SWUU7rhTgGzCMqvKD1HK7k/MLq', NULL, '0123456789', 'uploads/683c35b4aff49_1677675701695.jpg', 'active'),
(8, 's123', 's123@gamil.com', '$2y$10$nGoGzCnaBXH/VyueRif4Rue/x5PlG3a5a78eERWv88Wk8sEhLYP/G', NULL, NULL, NULL, 'active'),
(9, 'ww123', 'ww123@gamil.com', '$2y$10$KBTna7qrZIoC50D3ZF6cbuHFUAavkwEjYVBBuEKVDp.qPi6FppCiO', NULL, NULL, NULL, 'active'),
(10, 'a123', '', '$2y$10$LXfBTXdibYVPxNLAPOP34OTgYLQchXCyI0tcJZVNDVpwK2DGQkHNe', NULL, '0127911341', 'uploads/683d31c221229_Screenshot 2025-06-02 130805.png', 'banned'),
(11, 'wc123', '', '$2y$10$IH43ibAu3xy9vf16K5vgYuRIBjD9Xkx3AeKNq6mEFue5I6FolFaU6', NULL, '012123142', 'uploads/683e9c42541aa_Screenshot (1).png', 'active'),
(12, 'j123', '', '$2y$10$3Aq9f7X42h6yIFFwVXC.wOLTojxJwcn2yQhU8TGmsXVydpYwZhWry', NULL, NULL, NULL, 'active'),
(13, 'b123', '', '$2y$10$SHp47YAk5MzK1EKhiW3SMe6WuxhYLlzJebVFciPZSGCN2Gkn1raTa', NULL, '0127911342', 'uploads/684f97a24c76f_ChatGPT Image 2025年4月17日 17_20_37.png', 'active'),
(16, 'ssdz123', 'ssdz123@gmail.comaaa', '$2y$10$QhLtLfWSiSO1lxGwLBEb6e/ZLwSGP5JLTqdsivxPXX7mthEyr.5.6', NULL, '0127911348', 'uploads/684fd3012bf3d_Screenshot 2025-06-16 122043.png', 'active'),
(17, 'ss123', '', '$2y$10$Fwgd7q8pKnL.1hLsPcdE7O3c4ZQ./YvY23Q40N4dBFQ7A0zzAnc5u', NULL, NULL, NULL, 'active'),
(18, 'ngwenghin', 'ngwenghin123@gamil.com', '$2y$10$JznvSHaVf9EVEwFN0onI8efd1lfWmMt9vuQerhyOTdQk4qH0.i1te', NULL, '1111', 'uploads/6857ef0fa2765_Screenshot (2).png', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_address`
--

INSERT INTO `user_address` (`address_id`, `user_id`, `address`, `created_at`) VALUES
(1, 18, 'the arc cyberjaya block d 17-03', '2025-06-22 20:24:56'),
(2, 18, 'the arc cyberjaya block c 23-03', '2025-06-22 20:26:04'),
(3, 16, 'the arc cyberjaya block d 17-03', '2025-06-22 23:36:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `assigned_admin_id` (`assigned_admin_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `confirmed_by` (`confirmed_by`);

--
-- Indexes for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `complaint_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favorite_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `transaction_item`
--
ALTER TABLE `transaction_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `complaint`
--
ALTER TABLE `complaint`
  ADD CONSTRAINT `complaint_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_ibfk_4` FOREIGN KEY (`assigned_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL;

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `product_image_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD CONSTRAINT `product_stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `transaction_ibfk_4` FOREIGN KEY (`confirmed_by`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD CONSTRAINT `transaction_item_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
