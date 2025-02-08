-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 02:48 PM
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
-- Database: `project_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_categories`
--

CREATE TABLE `tb_categories` (
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_categories`
--

INSERT INTO `tb_categories` (`category_id`, `description`, `created_at`, `category_name`) VALUES
(1, NULL, '2025-01-22 09:03:25', 'ชุดเดรสสั้น'),
(2, NULL, '2025-01-22 09:03:37', 'ชุดเดรสยาว'),
(3, NULL, '2025-01-22 09:03:44', 'ชุดจั๊มสั้น'),
(5, NULL, '2025-01-22 09:04:33', 'ชุดจั๊มยาว');

-- --------------------------------------------------------

--
-- Table structure for table `tb_orderdetails`
--

CREATE TABLE `tb_orderdetails` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_orders`
--

CREATE TABLE `tb_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `tracking` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_orders`
--

INSERT INTO `tb_orders` (`order_id`, `user_id`, `order_date`, `status`, `tracking`, `total_amount`) VALUES
(1, 3, '2025-02-06 16:51:34', 'pending', NULL, 870.00),
(2, 3, '2025-02-06 16:57:11', 'pending', NULL, 870.00),
(3, 3, '2025-02-06 16:57:12', 'pending', NULL, 870.00),
(4, 3, '2025-02-06 16:57:13', 'pending', NULL, 870.00),
(5, 3, '2025-02-06 16:57:13', 'pending', NULL, 870.00),
(6, 3, '2025-02-06 16:59:59', 'pending', NULL, 580.00);

-- --------------------------------------------------------

--
-- Table structure for table `tb_payments`
--

CREATE TABLE `tb_payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `payment_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_products`
--

CREATE TABLE `tb_products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `product_pic` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_products`
--

INSERT INTO `tb_products` (`product_id`, `name`, `price`, `description`, `product_pic`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'เดรสแขนบัวลายดอกกุหลาบ 3 สี', 290.00, 'Texture: Chiffon fabric with full lining\r\nSize: M\r\nBust: 35”\r\nWaist: 27”\r\nHips: 37”\r\nLength: 33.5”\r\n\r\nSize: L\r\nBust: 37”\r\nWaist: 30”\r\nHips: 40”\r\nLength: 34”\r\n\r\nSize: XL\r\nBust: 40”\r\nWaist: 33”\r\nHips: 42”\r\nLength: 35”', '436310449_747138704197473_7033009692385520984_n.jpg', 1, '2025-02-05 16:45:21', '2025-02-05 16:45:21');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `firstname`, `lastname`, `gender`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin1234', 'ssirawittham@gmail.com', NULL, 'กรุงเทพมหานคร', 'Admin', 'Amon', 'other', 'admin', '2025-01-22 08:38:03', '2025-02-05 14:56:37'),
(2, 'neno', 'neno2001', 'ninothana@gmail.com', '0963849701', 'กรุงเพทมหานคร นะ', 'Thana', 'Kanlayaprasit', 'other', 'user', '2025-01-22 09:56:02', '2025-02-05 14:56:37'),
(3, 'Fai', 'ff123', 'faii@gmail.com', '0855675890', 'BK แมนชั่น', 'ใยฝ้าย', 'ใบเขียว', 'other', 'user', '2025-02-03 10:13:38', '2025-02-05 14:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `tb_variants`
--

CREATE TABLE `tb_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `variant_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_variants`
--

INSERT INTO `tb_variants` (`variant_id`, `product_id`, `color`, `size`, `stock_quantity`, `variant_pic`) VALUES
(6, 1, 'แดง', 'M', 5, '436356395_747138724197471_6019114367478175434_n.jpg'),
(7, 1, 'ขาว', 'M', 5, '436356395_747138724197471_6019114367478175434_n.jpg'),
(8, 1, 'ดำ', 'M', 5, 'S__63823874.jpg'),
(9, 1, 'แดง', 'L', 5, '436310449_747138704197473_7033009692385520984_n.jpg'),
(10, 1, 'ขาว', 'L', 5, '436356395_747138724197471_6019114367478175434_n.jpg'),
(11, 1, 'ดำ', 'L', 5, 'S__63823874.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_categories`
--
ALTER TABLE `tb_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tb_orderdetails`
--
ALTER TABLE `tb_orderdetails`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `tracking_number` (`tracking`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `tb_payments`
--
ALTER TABLE `tb_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payments_ibfk_1` (`order_id`);

--
-- Indexes for table `tb_products`
--
ALTER TABLE `tb_products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tb_variants`
--
ALTER TABLE `tb_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_categories`
--
ALTER TABLE `tb_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_orderdetails`
--
ALTER TABLE `tb_orderdetails`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_payments`
--
ALTER TABLE `tb_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_products`
--
ALTER TABLE `tb_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_variants`
--
ALTER TABLE `tb_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_orderdetails`
--
ALTER TABLE `tb_orderdetails`
  ADD CONSTRAINT `fk_variant_id` FOREIGN KEY (`variant_id`) REFERENCES `tb_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tb_orders` (`order_id`),
  ADD CONSTRAINT `tb_orderdetails_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `tb_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`user_id`);

--
-- Constraints for table `tb_payments`
--
ALTER TABLE `tb_payments`
  ADD CONSTRAINT `tb_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tb_orders` (`order_id`);

--
-- Constraints for table `tb_products`
--
ALTER TABLE `tb_products`
  ADD CONSTRAINT `tb_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tb_categories` (`category_id`);

--
-- Constraints for table `tb_variants`
--
ALTER TABLE `tb_variants`
  ADD CONSTRAINT `tb_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tb_products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
