-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2023 at 12:07 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ordersystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `items_list`
--

CREATE TABLE `items_list` (
  `item_id` int(10) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `item_price` double(5,2) NOT NULL,
  `item_type` enum('Beverage','Meal','Snack','Add-on') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items_list`
--

INSERT INTO `items_list` (`item_id`, `item_name`, `description`, `item_price`, `item_type`) VALUES
(1, 'Coke', 'Cold and refreshing softdrink', 20.00, 'Beverage'),
(2, 'Tapsilog', 'Sample description. Yummy!', 30.00, 'Meal'),
(3, 'Pancit', 'Pampahaba ng buhay! ', 25.00, 'Meal'),
(4, 'Fries', 'YUUMMMMM', 15.00, 'Add-on'),
(5, 'Muncher', 'MUNCHIIIIIRRRRR SUPREMACY!', 22.00, 'Snack');

-- --------------------------------------------------------

--
-- Table structure for table `orders_list`
--

CREATE TABLE `orders_list` (
  `order_id` int(10) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `order_data` text NOT NULL,
  `total_price` double(10,2) NOT NULL,
  `status` enum('Pending','Ready','Completed') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_list`
--

INSERT INTO `orders_list` (`order_id`, `order_date`, `order_data`, `total_price`, `status`) VALUES
(1, '2023-06-28 07:35:15', '3x Coke, 3x Tapsilog', 150.00, 'Completed'),
(4, '2023-07-04 05:56:49', '2x Coke', 40.00, 'Completed'),
(5, '2023-07-04 10:32:30', '2x Tapsilog', 60.00, 'Completed'),
(6, '2023-07-04 10:34:46', '2x Pancit, 3x Tapsilog, 3x Fries, 3x Coke', 245.00, 'Completed'),
(9, '2023-07-04 11:27:30', '6x Tapsilog', 180.00, 'Completed'),
(10, '2023-07-04 11:37:08', '5x Coke', 100.00, 'Completed'),
(11, '2023-07-04 11:51:51', '1x Coke', 20.00, 'Completed'),
(15, '2023-07-04 13:23:08', '5x Coke, 2x Tapsilog', 160.00, 'Completed'),
(16, '2023-07-04 13:24:28', '5x Coke, 5x Tapsilog, 4x Pancit, 9x Fries', 485.00, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `orders_statistics`
--

CREATE TABLE `orders_statistics` (
  `order_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `quantity` int(3) NOT NULL,
  `date_ordered` date NOT NULL DEFAULT current_timestamp(),
  `earnings` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_statistics`
--

INSERT INTO `orders_statistics` (`order_id`, `item_id`, `item_name`, `quantity`, `date_ordered`, `earnings`) VALUES
(1, 1, 'Coke', 3, '2023-05-01', 60.00),
(2, 2, 'Tapsilog', 3, '2023-06-01', 90.00),
(3, 2, 'Tapsilog', 1, '2023-06-24', 30.00),
(4, 1, 'Coke', 10, '2023-06-26', 200.00),
(5, 5, 'Muncher', 1, '2023-06-28', 22.00),
(6, 1, 'Coke', 2, '2023-07-04', 40.00),
(7, 2, 'Tapsilog', 2, '2023-07-04', 60.00),
(8, 3, 'Pancit', 2, '2023-07-04', 50.00),
(9, 2, 'Tapsilog', 3, '2023-07-04', 90.00),
(10, 4, 'Fries', 3, '2023-07-04', 45.00),
(11, 1, 'Coke', 3, '2023-07-04', 60.00),
(12, 1, 'Coke', 3, '2023-07-04', 60.00),
(13, 2, 'Tapsilog', 5, '2023-07-04', 150.00),
(14, 2, 'Tapsilog', 6, '2023-07-04', 180.00),
(15, 1, 'Coke', 5, '2023-07-04', 100.00),
(16, 1, 'Coke', 1, '2023-07-04', 20.00),
(17, 2, 'Tapsilog', 5, '2023-07-04', 150.00),
(18, 2, 'Tapsilog', 1, '2023-07-04', 30.00),
(19, 1, 'Coke', 2, '2023-07-04', 40.00),
(20, 1, 'Coke', 2, '2023-07-04', 40.00),
(21, 2, 'Tapsilog', 2, '2023-07-04', 60.00),
(22, 1, 'Coke', 5, '2023-07-04', 100.00),
(23, 2, 'Tapsilog', 2, '2023-07-04', 60.00),
(24, 1, 'Coke', 5, '2023-07-04', 100.00),
(25, 2, 'Tapsilog', 5, '2023-07-04', 150.00),
(26, 3, 'Pancit', 4, '2023-07-04', 100.00),
(27, 4, 'Fries', 9, '2023-07-04', 135.00);

-- --------------------------------------------------------

--
-- Table structure for table `temp_order`
--

CREATE TABLE `temp_order` (
  `temp_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `quantity` int(3) NOT NULL,
  `price` double(5,2) NOT NULL,
  `date_ordered` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_list`
--

CREATE TABLE `users_list` (
  `user_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` enum('Admin','Front Desk','Kitchen Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_list`
--

INSERT INTO `users_list` (`user_id`, `username`, `password`, `usertype`) VALUES
(1, 'admin', 'admin', 'Admin'),
(2, 'staff', 'staff', 'Front Desk'),
(5, 'kitchen', 'kitchen', 'Kitchen Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items_list`
--
ALTER TABLE `items_list`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders_list`
--
ALTER TABLE `orders_list`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `orders_statistics`
--
ALTER TABLE `orders_statistics`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `temp_order`
--
ALTER TABLE `temp_order`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indexes for table `users_list`
--
ALTER TABLE `users_list`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items_list`
--
ALTER TABLE `items_list`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders_list`
--
ALTER TABLE `orders_list`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders_statistics`
--
ALTER TABLE `orders_statistics`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `temp_order`
--
ALTER TABLE `temp_order`
  MODIFY `temp_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_list`
--
ALTER TABLE `users_list`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
