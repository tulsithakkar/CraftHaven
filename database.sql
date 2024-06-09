-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 02:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crafthaven_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `artisans1`
--

CREATE TABLE `artisans1` (
  `ArtisanID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Bio` text NOT NULL,
  `ContactInfo` varchar(255) NOT NULL,
  `ProfilePicture` varchar(255) NOT NULL,
  `Artisan_Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artisans1`
--

INSERT INTO `artisans1` (`ArtisanID`, `Name`, `Bio`, `ContactInfo`, `ProfilePicture`, `Artisan_Password`) VALUES
(1, 'Rajesh Patel', 'Crafts intricate Indian jewelry ', 'rajesh12@gmail.com', 'Rajesh_profile.jpg', '$2y$10$y/evu/Lq8Yvl0BVtgmntY.PcgaxkAdJVKQWylAy5JK4gK6JUIQhau'),
(2, 'Priya Shah', 'Skilled in traditional pottery.', 'Priya@gmail.com', 'priya_profile.png', '$2y$10$2avEE2Ubb96md9xYBU3/l.OqDLLF3kCxb7Bx4kPqoZ0X32qz.BRrS'),
(4, 'Jay Patel', 'Skilled in traditiPatelonal Paintings.', 'jay12@gmail.com', 'Jay Patel.jpg', '$2y$10$J7G5ezeXNbFPVPoKSEj2ueTVuWB7dDkmdEQ2dyFtcID5rgLO7v2Ga');

-- --------------------------------------------------------

--
-- Table structure for table `cartdetails`
--

CREATE TABLE `cartdetails` (
  `CartDetailID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cartdetails`
--

INSERT INTO `cartdetails` (`CartDetailID`, `UserID`, `ProductID`, `Quantity`, `Price`) VALUES
(4, 2, 2, 1, 120.11);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails1`
--

CREATE TABLE `orderdetails1` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails1`
--

INSERT INTO `orderdetails1` (`OrderDetailID`, `OrderID`, `ProductID`, `Quantity`, `Price`) VALUES
(3, 1, 1, 2, 100.00),
(5, 3, 2, 2, 120.11),
(17, 13, 3, 1, 67.00),
(18, 14, 4, 2, 30.00),
(19, 15, 4, 1, 30.00),
(28, 22, 3, 1, 67.00),
(31, 23, 4, 1, 30.00),
(34, 24, 4, 2, 30.00),
(35, 25, 1, 1, 100.00),
(37, 25, 4, 2, 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `shippingAddress` varchar(300) NOT NULL,
  `shippingCity` varchar(100) NOT NULL,
  `shippingState` varchar(100) NOT NULL,
  `shippingZip` int(6) NOT NULL,
  `shippingCountry` varchar(100) NOT NULL,
  `shippingMethod` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `OrderDate`, `TotalAmount`, `shippingAddress`, `shippingCity`, `shippingState`, `shippingZip`, `shippingCountry`, `shippingMethod`) VALUES
(1, 1, '2024-06-07 12:04:22', 650.22, 'Tarapur', 'anand', 'Gujrat', 388180, 'IN', 'express'),
(2, 1, '2024-06-07 12:09:26', 110.00, 'abc', 'abc', 'abc', 380111, 'IN', 'express'),
(3, 1, '2024-06-07 12:13:11', 250.22, 'abc', 'abc', 'abc', 123456, 'IN', 'express'),
(4, 1, '2024-06-07 12:40:13', 130.11, 'Tarapur', 'anand', 'Gujrat', 12345, 'IN', 'express'),
(5, 1, '2024-06-07 12:57:53', 250.22, 'abc', 'abc', 'abc', 123455, 'IN', 'standard'),
(6, 1, '2024-06-07 13:12:02', 250.22, 'ann', 'anand', 'abc', 123456, 'IN', 'standard'),
(7, 1, '2024-06-07 13:16:53', 130.11, 'Tarapur', 'ana', 'Gujrat', 388180, 'IN', 'standard'),
(8, 1, '2024-06-07 13:21:32', 110.00, 'Tarapur', 'anand', 'Gujrat', 388180, 'IN', 'standard'),
(9, 1, '2024-06-07 13:23:21', 230.11, 'Tarapur', 'anand', 'Gujrat', 123456, 'IN', 'standard'),
(10, 1, '2024-06-07 13:59:51', 230.11, 'abc', 'abc', 'anc12', 123456, 'IN', 'standard'),
(12, 1, '2024-06-07 18:15:15', 110.00, 'tarapur', 'abc', 'abc', 123456, 'IN', 'standard'),
(13, 1, '2024-06-07 19:51:07', 77.00, 'Tulsi', 'abc', 'gu', 122222, 'IN', 'standard'),
(14, 1, '2024-06-07 19:57:52', 70.00, 'ann', 'aaa', 'Gujrat', 1234, 'IN', 'standard'),
(15, 1, '2024-06-08 09:57:30', 30.00, 'tarapur', 'abc', 'abc', 123456, 'India', 'standard'),
(16, 1, '2024-06-08 10:07:16', 30.00, 'Khetls', 'abc', 'abc', 123456, 'IN', 'standard'),
(17, 1, '2024-06-08 10:20:20', 144.00, 'yatra', 'abc', 'abc', 123456, 'IN', 'standard'),
(18, 1, '2024-06-08 10:26:53', 40.00, 'hetviiiiiiiiiiii', 'abc', 'abc', 123456, 'IN', 'standard'),
(19, 1, '2024-06-08 10:29:16', 40.00, 'Yashhhhhhhhhh', 'abc', 'abc', 123456, 'IN', 'standard'),
(20, 1, '2024-06-08 10:36:46', 40.00, 'sdhaval', 'abc', 'abc', 123456, 'IN', 'standard'),
(21, 1, '2024-06-08 10:38:15', 40.00, 'sdhaval...........', 'abc', 'abc', 123456, 'IN', 'standard'),
(22, 6, '2024-06-09 04:49:27', 637.33, 'ram Nagar', 'Aanad', 'Gujrat', 123456, 'IN', 'express'),
(23, 6, '2024-06-09 05:22:30', 260.11, 'tarapur', 'anand', 'Gujrat', 123456, 'IN', 'express'),
(24, 6, '2024-06-09 06:47:07', 390.11, 'tarapur', 'anand', 'Gujrat', 123456, 'IN', 'express'),
(25, 7, '2024-06-09 07:52:50', 410.22, 'tarapur', 'anand', 'Gujrat', 123456, 'IN', 'express');

-- --------------------------------------------------------

--
-- Table structure for table `products1`
--

CREATE TABLE `products1` (
  `ProductID` int(11) NOT NULL,
  `ArtisanID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Availability` tinyint(1) NOT NULL,
  `Images` text NOT NULL,
  `Category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products1`
--

INSERT INTO `products1` (`ProductID`, `ArtisanID`, `Title`, `Description`, `Price`, `Availability`, `Images`, `Category`) VALUES
(1, 1, 'Regal Redwood Elephant', 'The Regal Redwood Elephant is a handcrafted masterpiece carved from premium redwood.', 100.00, 1, 'Regal Redwood Elephant.jpg', 'Woodwork'),
(2, 1, 'Flower vase', 'This antique ceramic pottery flower vase, with its intricate design and timeless appeal.', 120.11, 1, 'Flower pottery.jpg', 'pottery'),
(3, 2, 'Krishna jewellery', 'traditional elegance meets contemporary flair, offering intricately designed printed jewellery', 67.00, 1, 'Kanha_jewellery.jpg', 'jewellery'),
(4, 2, 'Earrings', 'Handcrafted Elegance. Explore a world of individuality with our meticulously crafted one-of-a-kind earrings.', 30.00, 1, 'Earing_jewellery.jpg', 'jewelry');

-- --------------------------------------------------------

--
-- Table structure for table `users1`
--

CREATE TABLE `users1` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserType` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`UserID`, `Username`, `Email`, `Password`, `UserType`) VALUES
(1, 'Tulsi Thakkar', 'tulsi@gmail.com', '$2y$10$Ob.iFF00Db8G/XG4TtTyFet7EYhC004I8uVA8Qn6i9HUQhEfgrLnS', 'Buyer'),
(2, 'Hetvi Soni', 'hetvi@gmail.com', '$2y$10$2rfzMbAJ2HMLGLNP6JxU7uykVqXL60i/mFLIEYAcJsrFEvLReb8oC', 'Buyer'),
(3, 'Sejal Patel', 'sejal@gmail.com', '$2y$10$iN27Fnj2zqdLyB8ZTWFAQ.Wr1g7ItbExmFAcHJO7.4X8zuWCK6aOq', 'Buyer'),
(4, 'pinkal sheth', 'pinkal@gmail.com', '$2y$10$Oy51uOxQWQSEaeOXIGBML.Y2kmtyltXTQyEGrOa.wGpdmpBeAwyPW', 'Buyer'),
(5, 'Yash Patel', 'yash@gmail.com', '$2y$10$I08qI83bQCbEEceZNxDpYOlCi5nDuXyLlBZO/PHUU4kH9/mMu6lvO', 'Buyer'),
(6, 'john shah', 'john@gmail.com', '$2y$10$4OCwRFsD0tfedJ2buzTMvuH0zaw7de07pBavyjaGXYzmv6QkiSXG.', 'Buyer'),
(7, 'John patel', 'john.123@gmail.com', '$2y$10$a.o/b8L85zOETTOz.GUij.0gAsY0UeuactbtC1x5lp1VgRtkKXFdG', 'Buyer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artisans1`
--
ALTER TABLE `artisans1`
  ADD PRIMARY KEY (`ArtisanID`);

--
-- Indexes for table `cartdetails`
--
ALTER TABLE `cartdetails`
  ADD PRIMARY KEY (`CartDetailID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `orderdetails1`
--
ALTER TABLE `orderdetails1`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `products1`
--
ALTER TABLE `products1`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `ArtisanID` (`ArtisanID`);

--
-- Indexes for table `users1`
--
ALTER TABLE `users1`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artisans1`
--
ALTER TABLE `artisans1`
  MODIFY `ArtisanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cartdetails`
--
ALTER TABLE `cartdetails`
  MODIFY `CartDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `orderdetails1`
--
ALTER TABLE `orderdetails1`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products1`
--
ALTER TABLE `products1`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users1`
--
ALTER TABLE `users1`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartdetails`
--
ALTER TABLE `cartdetails`
  ADD CONSTRAINT `cartdetails_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users1` (`UserID`),
  ADD CONSTRAINT `cartdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products1` (`ProductID`);

--
-- Constraints for table `orderdetails1`
--
ALTER TABLE `orderdetails1`
  ADD CONSTRAINT `orderdetails1_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `orderdetails1_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products1` (`ProductID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users1` (`UserID`);

--
-- Constraints for table `products1`
--
ALTER TABLE `products1`
  ADD CONSTRAINT `products1_ibfk_1` FOREIGN KEY (`ArtisanID`) REFERENCES `artisans1` (`ArtisanID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
