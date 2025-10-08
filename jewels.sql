-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 08:19 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jewels`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'tv@gmail.com\r\n', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `bill_payment`
--

CREATE TABLE `bill_payment` (
  `bill_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `billing_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_payment`
--

INSERT INTO `bill_payment` (`bill_id`, `user_id`, `total_amount`, `payment_method`, `payment_status`, `billing_date`, `order_id`) VALUES
(1, 1, '14236.00', 'UPI', 'completed', '2025-09-04 17:15:25', 5),
(2, 1, '142536.00', 'COD', 'pending', '2025-09-04 17:21:22', 6),
(3, 1, '12350.00', 'COD', 'pending', '2025-09-04 17:23:24', 7);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(1, 1, 13, 1, '2025-09-05 11:25:55'),
(2, 1, 12, 1, '2025-09-05 11:48:56');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cid` int(11) NOT NULL,
  `cnm` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cid`, `cnm`) VALUES
(2, 'Diamond'),
(5, 'Gold'),
(7, 'Gemstone');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'twinkle', 't@gmail.com', 'feedback', 'good!', '2025-08-10 11:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'COD',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `name`, `email`, `phone`, `address`, `total_amount`, `status`, `payment_method`, `created_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, '12345.00', 'pending', 'UPI', '2025-09-03 13:32:35'),
(2, 1, 'shubham', 'sp@gmail.com', '09879495806', 'baroda', '45896.00', 'pending', 'UPI', '2025-09-03 13:40:24'),
(3, 1, 'shubham', 'sp@gmail.com', '09879495806', 'junagadh', '142536.00', 'pending', 'Card', '2025-09-03 13:49:57'),
(4, 1, 'shubham', 'sp@gmail.com', '09879495806', 'Block no - 9', '12345.00', 'pending', 'COD', '2025-09-04 11:41:12'),
(5, 1, 'shubham', 'sp@gmail.com', '09879495806', 'Block no - 9', '14236.00', 'pending', 'UPI', '2025-09-04 11:45:25'),
(6, 1, 'shubham', 'sp@gmail.com', '09879495806', 'Block no - 9', '142536.00', 'pending', 'COD', '2025-09-04 11:51:22'),
(7, 1, 'shubham', 'sp@gmail.com', '09879495806', 'Block no - 9', '12350.00', 'pending', 'COD', '2025-09-04 11:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`) VALUES
(7, 5, 12, 'gd nacklace', 1, '14236.00', '14236.00'),
(9, 7, 13, 'gd ring', 1, '12350.00', '12350.00');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int(11) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `pname`, `cid`, `sid`, `price`, `qty`, `description`, `image`, `created_at`) VALUES
(12, 'Dhriti Gold Necklace', 5, 5, '33820.00', 1, 'The Dhriti Gold Necklace is a captivating and exquisite piece that combines intricate design with timeless elegance.\r\nLength : 43.18cm (17 inches)Width : 4.4mm\r\n\r\nHeight : 46 mm\r\nWeight\r\nGross : 3.200 g\r\nPurity18 KT', '1757656674_gdnec1.jpg', '2025-08-25 13:23:02'),
(13, 'Meher Gold Ring', 5, 6, '56671.00', 1, 'Set in 22 KT Yellow Gold(4.280 g)\r\nGOLD:\r\nWidth : 4 mm\r\n\r\nHeight : 1.5 mm\r\nGross : 4.280 g\r\nPurity:22 KT', '1757656663_gdring.jpg', '2025-08-25 13:23:30'),
(14, 'Gold Bangle', 5, 10, '96021.00', 1, 'Set in 22 KT Yellow Gold(7.730 g)\r\n22Kt gold is naturally soft and delicate. Mishandling may\r\nWidth : 1.75 mm\r\nHeight : 1.73 mm\r\nWeight\r\nGross : 7.730 g\r\nPurity:22 KT', '1757656654_gdban1.jpg', '2025-09-10 04:19:26'),
(15, ' Classic Gold Earrings', 5, 11, '10555.00', 1, 'Introducing the  Classic  Gold Earrings! These stunning earrings are a must-have for any young fashionista.\r\nWidth : 1.40 mm\r\nHeight : 9.7 mm\r\nWeight\r\nGross : 1.060 g\r\nPurity:14 KT', '1757656635_gderr1.jpg', '2025-09-10 04:22:39'),
(16, 'Serene Station diamond Necklace', 2, 18, '80409.00', 1, 'Set in 18 KT Rose Gold(4.280 g) with diamonds (0.169Ct,FG-SI)\r\nGOLD:\r\nLength : 43.18cm (17 inches)\r\nHeight : 6.05 mm\r\nWeight\r\nGross : 4.566 g\r\nPurity:18 KT\r\nDiamond:\r\nType\r\nFG-SI\r\nSetting : Micro Pave\r\nTotal No. : 16\r\nTotal Weight:0.169Ct', '1757656623_ddnec1.jpg', '2025-09-10 04:55:47'),
(17, 'Whiskers Love Diamond Ring', 2, 16, '24702.00', 1, 'Set in 14 KT Rose Gold(1.160 g) with diamonds (0.080Ct,FG-SI)\r\nGOLD:\r\nWidth : 10.35 mm\r\nHeight : 2.3 mm\r\nWeight\r\nGross : 1.176 g\r\nPurity;14 KT\r\nDiamond:\r\nSetting : Plate Prong\r\nTotal No. : 16\r\nTotal Weight\r\n0.080Ct', '1757656612_ddring1.jpg', '2025-09-10 05:07:36'),
(18, 'Eisha Glamorous Diamond Bangle', 2, 15, '45000.00', 1, 'Add a touch of glamour to your look with the Eisha Glamorous Diamond Bangle by CaratLane. This exquisite bangle is crafted with premium quality materials and is adorned with stunning diamonds that sparkle with every movement. \r\nGold:\r\nWidth : 8.00mm\r\nHeight : 3.4 mm\r\nWeight\r\nGross : 20.080 g\r\nPurity:18 KT\r\nDiamond:\r\nSetting : Prong\r\nTotal No. : 104\r\nTotal Weight\r\n1.650Ct', '1757656597_ddbang1.jpg', '2025-09-10 05:15:00'),
(19, 'Ascending Diamond Hoop Earrings', 2, 19, '189516.00', 1, 'Set in 18 KT White Gold(5.250 g) with diamonds (0.750 ct ,FG-SI)\r\nDiamond:\r\nSetting : Channel\r\nTotal No. : 72\r\nTotal Weight\r\n0.750Ct', '1757656579_dderr1.jpg', '2025-09-10 05:26:22'),
(20, 'Brice Gemstone Ring', 7, 13, '82480.00', 1, 'Set in 18 KT White Gold(2.680 g) with diamonds (0.166Ct ,GH-SI)\r\nDiamond:\r\nType: GH-SI\r\nSetting : Prong\r\nTotal No. : 6\r\nTotal Weight\r\n0.166Ct\r\nGemstone:\r\nType: Sapphire (8.00 x 6.00 mm)\r\nTotal No.1', '1757657050_gmring1.jpg', '2025-09-12 06:04:10'),
(21, 'Rosa Gemstone Necklace', 7, 12, '62681.00', 1, 'Set in 14 KT Rose Gold(3.830 g) with diamonds (0.169Ct ,FG-SI)\r\nGOLD:\r\n\r\n\r\nLength : 43.18cm (17 inches)\r\n\r\nHeight : 22.25 mm\r\n\r\nWeight\r\nGross : 3.900 g\r\nPurity:\r\n14 KT\r\nDiamond:\r\nType:\r\nFG-SI\r\nSetting : Micro Prong\r\n\r\nTotal No. : 21\r\n\r\nTotal Weight:\r\n0.169 ct\r\n\r\nGemstone:\r\n\r\nType:\r\nSynthetic Ruby (2.20 x 1.20 mm)\r\n\r\nTotal No.\r\n6', '1757658128_gmsnec1.jpg', '2025-09-12 06:22:08'),
(22, 'Flamboyant Gemstone bangle', 7, 15, '188182.00', 1, 'The Flamboyant Gemstone bangle by caratLane is a true work of art. This exquisite bracelet is crafted with 18KT rose gold and features an intricate design that is both stylish and elegant.', '1757658512_gmsban1.jpg', '2025-09-12 06:28:32'),
(23, 'Azure Gemstone  Earrings', 7, 14, '19011.00', 1, 'Set in 14 KT Yellow Gold(2.030 g)\r\nGemstone:\r\n\r\nType\r\nSynthetic Ruby (7 mm)\r\n\r\nTotal No.\r\n2', '1757658750_gmserr1.jpg', '2025-09-12 06:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `sid` int(11) NOT NULL,
  `snm` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`sid`, `snm`, `cid`) VALUES
(5, ' Gold necklace', 5),
(6, ' Gold ring', 5),
(10, 'Gold bengals', 5),
(11, 'Gold earrings', 5),
(12, ' Gemstone necklace', 7),
(13, 'Gemstone rings', 7),
(14, 'Gemstone earrings', 7),
(15, 'Gemstone bangles', 7),
(16, 'Diamond rings', 2),
(17, 'Diamond bangles', 2),
(18, 'Diamond necklace', 2),
(19, 'Diamond earrings', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'twinkle', 'tv@gmail.com', '$2y$10$L3N4iuxkc7pzNAbQTcgT4ufSEccGl/O5.6v8jLw8t2S44Wk0i8FnW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_payment`
--
ALTER TABLE `bill_payment`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `cid` (`cid`),
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `cid` (`cid`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bill_payment`
--
ALTER TABLE `bill_payment`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_payment`
--
ALTER TABLE `bill_payment`
  ADD CONSTRAINT `bill_payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bill_payment_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`pid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`pid`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `category` (`cid`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `subcategory` (`sid`) ON DELETE CASCADE;

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `category` (`cid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
