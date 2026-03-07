-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2026 at 05:49 AM
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
-- Database: `desiaroma`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'Ayush Borade', 'boradeayush54@gmail.com', 'cbkjkzkjz', '2026-02-11 12:08:04'),
(2, 'Ayush Borade', 'boradeayush54@gmail.com', 'assddw', '2026-02-12 04:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'Pending',
  `payment_id` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `order_status`, `order_date`, `total_price`, `payment_status`, `payment_id`, `payment_method`) VALUES
(1, 1, 5997.00, 'Pending', '2026-02-18 05:12:00', 5997.00, 'Pending', NULL, NULL),
(2, 1, 5997.00, 'Pending', '2026-02-18 05:12:03', 5997.00, 'Pending', NULL, NULL),
(3, 1, 5997.00, 'Pending', '2026-02-18 05:12:24', 5997.00, 'Pending', NULL, NULL),
(4, 1, 5997.00, 'Pending', '2026-02-18 05:13:05', 5997.00, 'Pending', NULL, NULL),
(5, 1, 999.00, 'Pending', '2026-02-18 05:13:13', 999.00, 'Pending', NULL, NULL),
(6, 1, 999.00, 'Pending', '2026-02-18 05:15:33', 999.00, 'Pending', NULL, NULL),
(7, 1, 2499.00, 'Completed', '2026-02-18 05:30:11', 2499.00, 'Pending', NULL, NULL),
(8, 1, 5599.00, 'Pending', '2026-02-18 05:53:24', 5599.00, 'Pending', NULL, NULL),
(9, 1, 4399.00, 'Pending', '2026-02-18 06:36:21', 4399.00, 'Pending', NULL, NULL),
(10, 4, 2399.00, 'Pending', '2026-02-26 08:59:16', 2399.00, 'Pending', NULL, NULL),
(11, 4, 2199.00, 'Pending', '2026-02-26 09:09:43', 2199.00, 'Pending', NULL, NULL),
(12, 4, 2199.00, 'Pending', '2026-02-26 10:02:43', 2199.00, 'Pending', NULL, NULL),
(13, 4, 2000.00, 'Pending', '2026-02-26 11:32:28', 2000.00, 'Initiated', NULL, 'ONLINE'),
(14, 4, 2000.00, 'Pending', '2026-02-26 11:33:20', 2000.00, 'Initiated', NULL, 'ONLINE'),
(15, 4, 2000.00, 'Pending', '2026-02-26 11:36:44', 2000.00, 'Pending', NULL, 'COD'),
(16, 4, 1599.00, 'Pending', '2026-02-26 11:38:54', 1599.00, 'Pending', NULL, 'COD'),
(17, 4, 2199.00, 'Pending', '2026-02-26 11:44:31', 2199.00, 'Initiated', NULL, 'ONLINE'),
(18, 4, 4199.00, 'Pending', '2026-02-26 12:06:57', 4199.00, 'Initiated', NULL, 'ONLINE'),
(19, 4, 2399.00, 'Pending', '2026-02-27 03:54:44', 2399.00, 'Pending', NULL, 'COD'),
(20, 4, 4000.00, 'Pending', '2026-02-27 04:20:01', 4000.00, 'Pending', NULL, 'COD'),
(21, 4, 2000.00, 'Pending', '2026-02-27 04:22:55', 2000.00, 'Pending', NULL, 'COD'),
(22, 4, 2000.00, 'Pending', '2026-02-27 05:07:17', 2000.00, 'Pending', NULL, 'COD'),
(23, 4, 2000.00, 'Pending', '2026-02-27 05:07:31', 2000.00, 'Initiated', NULL, 'ONLINE'),
(24, 4, 2000.00, 'Pending', '2026-02-27 05:08:41', 2000.00, 'Initiated', NULL, 'ONLINE'),
(25, 4, 2000.00, 'Confirmed', '2026-02-27 05:11:14', 2000.00, 'Paid', 'TESTPAY123', 'Online'),
(26, 4, 2000.00, 'Pending', '2026-02-27 05:43:33', 2000.00, 'Initiated', NULL, 'ONLINE'),
(27, 4, 4000.00, 'Pending', '2026-02-27 05:47:40', 4000.00, 'Initiated', NULL, 'ONLINE'),
(28, 4, 4000.00, 'Pending', '2026-02-27 05:47:58', 4000.00, 'Initiated', NULL, 'ONLINE'),
(29, 4, 4000.00, 'Pending', '2026-02-27 05:51:21', 4000.00, 'Initiated', NULL, 'ONLINE'),
(30, 4, 6000.00, 'Pending', '2026-02-27 05:51:40', 6000.00, 'Initiated', NULL, 'ONLINE'),
(31, 4, 6000.00, 'Confirmed', '2026-02-27 05:54:27', 6000.00, 'Paid', NULL, 'UPI'),
(32, 1, 2000.00, 'Pending', '2026-03-06 04:21:59', 2000.00, 'Initiated', NULL, 'ONLINE'),
(33, 1, 4199.00, 'Pending', '2026-03-06 04:48:29', 4199.00, 'Pending', NULL, 'COD'),
(34, 1, 2000.00, 'Pending', '2026-03-06 04:51:51', 2000.00, 'Pending', NULL, 'COD'),
(35, 1, 2000.00, 'Pending', '2026-03-06 04:51:58', 2000.00, 'Initiated', NULL, 'ONLINE'),
(36, 1, 4000.00, 'Pending', '2026-03-06 05:11:52', 4000.00, 'Initiated', NULL, 'ONLINE'),
(37, 1, 2000.00, 'Pending', '2026-03-07 03:30:22', 2000.00, 'Initiated', NULL, 'ONLINE');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 4, 1, 2, 2499.00),
(2, 4, 4, 1, 999.00),
(3, 5, 4, 1, 999.00),
(4, 6, 4, 1, 999.00),
(5, 7, 1, 1, 2499.00),
(6, 8, 5, 1, 1599.00),
(7, 8, 11, 2, 2000.00),
(8, 9, 11, 1, 2000.00),
(9, 9, 12, 1, 2399.00),
(10, 10, 12, 1, 2399.00),
(11, 11, 10, 1, 2199.00),
(12, 12, 10, 1, 2199.00),
(13, 13, 11, 1, 2000.00),
(14, 14, 11, 1, 2000.00),
(15, 15, 11, 1, 2000.00),
(16, 16, 9, 1, 1599.00),
(17, 17, 10, 1, 2199.00),
(18, 18, 10, 1, 2199.00),
(19, 18, 11, 1, 2000.00),
(20, 19, 12, 1, 2399.00),
(21, 20, 11, 2, 2000.00),
(22, 21, 11, 1, 2000.00),
(23, 22, 11, 1, 2000.00),
(24, 23, 11, 1, 2000.00),
(25, 24, 11, 1, 2000.00),
(26, 25, 11, 1, 2000.00),
(27, 26, 11, 1, 2000.00),
(28, 27, 11, 2, 2000.00),
(29, 28, 11, 2, 2000.00),
(30, 29, 11, 2, 2000.00),
(31, 30, 11, 3, 2000.00),
(32, 31, 11, 3, 2000.00),
(33, 32, 11, 1, 2000.00),
(34, 33, 10, 1, 2199.00),
(35, 33, 11, 1, 2000.00),
(36, 34, 11, 1, 2000.00),
(37, 35, 11, 1, 2000.00),
(38, 36, 11, 2, 2000.00),
(39, 37, 11, 1, 2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'Royal Oud', 'Premium woody fragrance for men', 2499.00, 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=600&q=80', '2026-02-11 11:38:16'),
(4, 'Jean Paul Gaultier ', NULL, 999.00, 'images/Jean Paul Gaultier Le Male Le Parfum Eau de Parfum Intense _ Nordstrom.jpg', '2026-02-12 04:42:09'),
(5, 'Lebeni perfume', NULL, 1599.00, 'images/Lebeni perfume bottle design art (1).jpg', '2026-02-12 04:49:22'),
(7, 'LovelyBEE', NULL, 1999.00, 'images/Lebeni perfume bottle design art (2).jpg', '2026-02-12 04:51:53'),
(8, 'Midnight Mystique', NULL, 1299.00, 'images/Lebeni perfume bottle design art (3).jpg', '2026-02-12 17:24:38'),
(9, 'Fantice Blossom', NULL, 1599.00, 'images/lebeni perfume bottle design art (4).jpg', '2026-02-12 17:36:13'),
(10, 'Kristal Saflik', NULL, 2199.00, 'images/Lebeni perfume bottle design art.jpg', '2026-02-12 17:39:30'),
(11, 'Eternity Aqua', NULL, 2000.00, 'images/download.jpg', '2026-02-12 17:46:30'),
(12, 'Fairytale ', NULL, 2399.00, 'images/download (1).jpg', '2026-02-12 18:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `phone`, `address`, `pincode`) VALUES
(1, 'darpan', 'imposterrr@gmail.com', '$2y$10$6X7nAilwY242OkZ2MsvhWukjJ9BKKKxKpRct3jsIGAp9fjr4vE/yK', '2026-02-11 13:36:17', NULL, NULL, NULL),
(2, 'Lithesh', 'litheshborade999@gmail.com', '$2y$10$N.2JF8rH2KjYvhfe/JJWCeK6u7XSXVBoEFhHwGoFV3X1SItUTYtw.', '2026-02-12 18:08:46', NULL, NULL, NULL),
(3, 'LITHESH', 'lizzborade07@gmail.com', '$2y$10$t1wjR9HSwco619aWCi14juaQD9kCzeUTvxAaWNRfHRo/kkIbssfFe', '2026-02-17 13:06:19', NULL, NULL, NULL),
(4, 'LITHESH', 'lithesh11@gmail.com', '$2y$10$7BOyUVDWXQUUaewgJ5DTjuaTtqNB1kYBBivOSl5bi2vpkXMIRJgK.', '2026-02-26 08:58:48', '7039368108', 'KANCHAN NIWAS NR GANPATI MANDIR SITARAM NAGAR MARATHA SECTION 32 ULHASNAGAR 4', '421004');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
