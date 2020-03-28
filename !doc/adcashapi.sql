-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 28, 2020 at 12:18 PM
-- Server version: 8.0.15
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adcashapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `categoryname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `categoryname`) VALUES
(1, 'Convenience'),
(2, 'Shopping'),
(3, 'Specialty'),
(4, 'Unsought');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `productname` varchar(100) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `dateadded` datetime DEFAULT NULL,
  `ipadded` varchar(75) DEFAULT NULL,
  `browseradded` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `productname`, `categoryid`, `dateadded`, `ipadded`, `browseradded`) VALUES
(1, 'Newspapers', 1, '2020-03-28 04:19:27', '::1', 'PostmanRuntime/7.24.0'),
(2, 'Magazines', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(3, 'Medicines', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(4, 'Detergents', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(5, 'Dish washer', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(6, 'Toothpaste', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(7, 'Soft drinks', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(8, 'Tissue', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(9, 'Bread', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(10, 'Milk', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(11, 'Meat', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(12, 'Fruit', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(13, 'Fast food', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(14, 'Vegetables', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(15, 'Coffee', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(16, 'Tea', 1, '2020-03-28 05:23:29', '::1', 'PostmanRuntime/7.24.0'),
(17, 'Washer', 2, '2020-03-28 14:10:09', '::1', 'PostmanRuntime/7.24.0'),
(18, 'Dryer', 2, '2020-03-28 14:10:26', '::1', 'PostmanRuntime/7.24.0'),
(19, 'Friedge', 2, '2020-03-28 14:10:30', '::1', 'PostmanRuntime/7.24.0'),
(20, 'Shoes', 2, '2020-03-28 14:10:36', '::1', 'PostmanRuntime/7.24.0'),
(21, 'Shirts', 2, '2020-03-28 14:10:40', '::1', 'PostmanRuntime/7.24.0'),
(22, 'Dishes', 2, '2020-03-28 14:10:49', '::1', 'PostmanRuntime/7.24.0'),
(23, 'Barber', 3, '2020-03-28 14:12:39', '::1', 'PostmanRuntime/7.24.0'),
(24, 'Hair Stylist', 3, '2020-03-28 14:12:48', '::1', 'PostmanRuntime/7.24.0'),
(25, 'Nail Artist', 3, '2020-03-28 14:12:54', '::1', 'PostmanRuntime/7.24.0'),
(26, 'Vitamins', 3, '2020-03-28 14:12:59', '::1', 'PostmanRuntime/7.24.0'),
(27, 'Cosmetics', 3, '2020-03-28 14:17:21', '::1', 'PostmanRuntime/7.24.0'),
(28, 'Funeral Services', 4, '2020-03-28 14:15:30', '::1', 'PostmanRuntime/7.24.0'),
(29, 'Encyclopedias', 4, '2020-03-28 14:15:38', '::1', 'PostmanRuntime/7.24.0'),
(30, 'iPhone', 4, '2020-03-28 14:15:43', '::1', 'PostmanRuntime/7.24.0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_index` (`productname`,`categoryid`),
  ADD KEY `FK_categoryid` (`categoryid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
