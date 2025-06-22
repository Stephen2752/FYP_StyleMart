-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2025 at 01:32 PM
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
) ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `product_id`, `user_id`, `comment_text`, `rate`, `created_at`) VALUES
(1, 8, 7, 'abc', 5, '2025-06-02 20:14:00'),
(2, 8, 10, 'gooooood', 3, '2025-06-02 20:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `complaint_id` int(9) NOT NULL,
  `user_id` int(9) NOT NULL,
  `complaint_text` text NOT NULL,
  `admin_id` int(9) DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `stock_quantity` int(9) NOT NULL,
  `sales_count` int(9) NOT NULL,
  `rate` int(5) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `subcategory` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `user_id`, `product_name`, `category`, `price`, `description`, `status`, `stock_quantity`, `sales_count`, `rate`, `comment`, `created_at`, `subcategory`) VALUES
(7, 7, 'Cropped cotton-jersey T-shirt', 'Women - Clothes', 75.99, 'abcdefghijklmnop', 'Available', 68, 0, NULL, NULL, '2025-06-02 12:39:07', NULL),
(8, 10, 'Teza wool maxi dress', 'Women - Clothes', 50.00, 'The Row\'s minimalist approach to design ensures pieces like this \'Teza\' dress can easily be dressed up or down, making it so versatile. It\'s cut from black wool in a streamlined silhouette and has subtle pleats at the front for volume.', 'Available', 30, 0, NULL, NULL, '2025-06-02 13:09:28', NULL),
(9, 10, 'EZY Ultra Stretch Jeans', 'Men - Pants', 88.99, 'EZY Ultra Stretch Jeans', 'Available', 30, 0, NULL, NULL, '2025-06-02 20:24:09', NULL),
(10, 11, 'wc123abc', 'Men - Shoes', 12.00, 'avswcshvdfjwvefjk', 'Available', 10, 0, NULL, NULL, '2025-06-03 14:56:43', NULL);

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
(15, 10, 'uploads/683e9cab3a589_Screenshot 2025-06-03 145615.png', '2025-06-03 14:56:43');

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
(24, 9, 'S', 10, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(25, 9, 'M', 0, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(26, 9, 'L', 15, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(27, 9, 'XL', 5, '2025-06-02 20:24:09', '2025-06-02 20:24:09'),
(66, 10, 'S', 8, '2025-06-18 18:07:49', '2025-06-18 18:07:49'),
(67, 10, 'M', 2, '2025-06-18 18:07:49', '2025-06-18 18:07:49'),
(68, 10, 'XL', 0, '2025-06-18 18:07:49', '2025-06-18 18:07:49');

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
  `product_id` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `qrcode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `contact_info`, `phone_number`, `qrcode`) VALUES
(1, 'Stephen14233', '', '$2y$10$3qMyqmLpIBNyhJ343zFJGOTahm3Is59tvu3NicXjtzxgoX/LDWwo.', 'csvb nmyjthgvdc', NULL, NULL),
(2, 'stephen1', '', '$2y$10$hyS3MCjgt.0ZRmT4XVf62us96MKAmxbHgLADw.eheosn5cdQtKgCa', 's dvfgnmhgfvdc', NULL, NULL),
(3, 'Stephen13', 'stephen1313@gmail.com', '$2y$10$ecSbPF/TBJMOO/LdhgYGz.DblJc84FSpGXbJV5yo0dhk.oqyJmySS', 'jalan abu 1234', NULL, NULL),
(4, 'step121212', 'step121212@gmail.com', '$2y$10$SJGi9siPMXcZUx5vZl7B7.iBEUssfJ0DPsxdDOeLTtyx5lA3M4ypW', NULL, NULL, NULL),
(5, 'step1212', 'step1212@gmail.com', '$2y$10$oHoFPNypxloOd.yknG7tee0INOVRt6R08cBamz1TXNyelLVOtVf5.', NULL, NULL, NULL),
(6, 'stephen99', 'stephen99@gmail.com', '$2y$10$DTCLhMO9vaMDuDXGPU4aY.qYyd7YVJRl3VGehkvlA0QqUNuaw35Lu', NULL, NULL, NULL),
(7, 'w123', 'w123@gamil.com', '$2y$10$uIyNYya.KVPbV604Ka8s4eSN4B4SWUU7rhTgGzCMqvKD1HK7k/MLq', NULL, '0123456789', 'uploads/683c35b4aff49_1677675701695.jpg'),
(8, 's123', 's123@gamil.com', '$2y$10$nGoGzCnaBXH/VyueRif4Rue/x5PlG3a5a78eERWv88Wk8sEhLYP/G', NULL, NULL, NULL),
(9, 'ww123', 'ww123@gamil.com', '$2y$10$KBTna7qrZIoC50D3ZF6cbuHFUAavkwEjYVBBuEKVDp.qPi6FppCiO', NULL, NULL, NULL),
(10, 'a123', '', '$2y$10$LXfBTXdibYVPxNLAPOP34OTgYLQchXCyI0tcJZVNDVpwK2DGQkHNe', NULL, '0127911341', 'uploads/683d31c221229_Screenshot 2025-06-02 130805.png'),
(11, 'wc123', '', '$2y$10$IH43ibAu3xy9vf16K5vgYuRIBjD9Xkx3AeKNq6mEFue5I6FolFaU6', NULL, '012123142', 'uploads/683e9c42541aa_Screenshot (1).png'),
(12, 'j123', '', '$2y$10$3Aq9f7X42h6yIFFwVXC.wOLTojxJwcn2yQhU8TGmsXVydpYwZhWry', NULL, NULL, NULL),
(13, 'b123', '', '$2y$10$SHp47YAk5MzK1EKhiW3SMe6WuxhYLlzJebVFciPZSGCN2Gkn1raTa', NULL, '0127911342', 'uploads/684f97a24c76f_ChatGPT Image 2025年4月17日 17_20_37.png'),
(16, 'ssdz123', '', '$2y$10$QhLtLfWSiSO1lxGwLBEb6e/ZLwSGP5JLTqdsivxPXX7mthEyr.5.6', NULL, '0127911348', 'uploads/684fd3012bf3d_Screenshot 2025-06-16 122043.png'),
(17, 'ss123', '', '$2y$10$Fwgd7q8pKnL.1hLsPcdE7O3c4ZQ./YvY23Q40N4dBFQ7A0zzAnc5u', NULL, NULL, NULL);

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
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `cart_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `complaint_id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favorite_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `transaction_item`
--
ALTER TABLE `transaction_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  ADD CONSTRAINT `complaint_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `complaint_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
