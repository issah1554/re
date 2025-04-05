-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 11:09 PM
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
-- Database: `house_rental_latest`
--

-- --------------------------------------------------------

--
-- Table structure for table `apartments`
--

CREATE TABLE `apartments` (
  `id` int(11) NOT NULL,
  `number` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `apartment_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `granted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_items`
--

CREATE TABLE `asset_items` (
  `id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_by`, `created_at`) VALUES
(9, 'Single-Family Home', NULL, '2025-04-05 01:08:02'),
(10, 'Apartment', NULL, '2025-04-05 01:08:02'),
(11, 'Townhouse', NULL, '2025-04-05 01:08:02'),
(12, 'Condominium', NULL, '2025-04-05 01:08:02'),
(13, 'Duplex', NULL, '2025-04-05 01:08:02'),
(14, 'Mobile Home', NULL, '2025-04-05 01:08:02'),
(17, 'Tiny House', NULL, '2025-04-05 01:08:02'),
(18, 'wema', NULL, '2025-04-05 01:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(30) NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `category_id` int(30) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_no`, `category_id`, `description`, `price`) VALUES
(1, '123 Main St', 9, 'Beautiful single-family home with spacious backyard', 250000),
(2, '456 Elm St', 10, 'Modern apartment in downtown area', 1500),
(3, '789 Oak St', 11, 'Cozy townhouse with attached garage', 300000),
(4, '101 Pine St', 12, 'Luxurious condominium with panoramic views', 500000),
(5, '222 Maple St', 13, 'Well-maintained duplex with rental income potential', 200000),
(6, '333 Cherry St', 14, 'Comfortable mobile home in a quiet community', 75000),
(7, '444 Walnut St', 17, 'Charming tiny house with efficient design', 100000),
(8, '555 Cedar St', 9, 'Spacious single-family home with modern amenities', 275000),
(9, '666 Birch St', 10, 'Cozy apartment in a historic building', 1200),
(10, '777 Spruce St', 11, 'Townhouse with backyard patio perfect for entertaining', 320000),
(11, '888 Elmwood St', 12, 'Luxury condominium with 24/7 security and concierge service', 600000),
(12, '999 Pineapple St', 13, 'Duplex with updated kitchen and bathrooms', 220000),
(13, '111 Mango St', 14, 'Mobile home in a family-friendly community with swimming pool', 80000),
(14, '222 Banana St', 17, 'Tiny house with loft bedroom and solar panels', 95000),
(15, '333 Grape St', 9, 'Classic single-family home with original hardwood floors', 280000),
(16, '444 Lemon St', 10, 'Bright and airy apartment with city views', 1600),
(17, '555 Orange St', 11, 'Townhouse with spacious living area and attached garage', 330000),
(18, '666 Peach St', 12, 'Condominium in a high-rise building with amenities', 550000),
(19, '777 Plum St', 13, 'Duplex with separate entrances for each unit', 230000),
(20, '888 Watermelon St', 14, 'Mobile home with updated interior and large deck', 85000),
(21, '12', 10, ',rl23,e,23le2l3,e', 333),
(22, '123', 10, 'ABC', 1222);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `tenant_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `apartment_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `invoice`, `date_created`, `apartment_id`, `created_by`, `created_at`, `verified_by`, `verified_at`, `status`) VALUES
(1, 1, 1500, 'INV001', '2024-05-02 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(2, 2, 1200, 'INV002', '2024-05-04 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(3, 3, 1600, 'INV003', '2024-05-06 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(4, 4, 1800, 'INV004', '2024-05-08 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(5, 5, 1400, 'INV005', '2024-05-10 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(6, 6, 1700, 'INV006', '2024-05-12 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(7, 7, 1300, 'INV007', '2024-05-14 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(8, 8, 1900, 'INV008', '2024-05-16 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(9, 9, 1550, 'INV009', '2024-05-18 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(10, 10, 1250, 'INV010', '2024-05-20 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(11, 11, 1650, 'INV011', '2024-05-22 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(12, 12, 1350, 'INV012', '2024-05-24 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending'),
(13, 13, 1450, 'INV013', '2024-05-26 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'House Rental Management System', 'info@sample.comm', '+6948 8542 623', '1603344720_1602738120_pngtree-purple-hd-business-banner-image_5493.jpg', '&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-weight: 400; text-align: justify;&quot;&gt;&amp;nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&rsquo;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `house_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0= inactive',
  `date_in` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `firstname`, `middlename`, `lastname`, `email`, `contact`, `house_id`, `status`, `date_in`) VALUES
(1, 'Aarav', 'Kumar', 'Sharma', 'aarav.sharma@example.com', '+91-9876543210', 1, 1, '2024-05-01'),
(2, 'Aadhya', 'Singh', 'Patel', 'aadhya.patel@example.com', '+91-8765432109', 2, 1, '2024-05-03'),
(3, 'Advait', 'Gupta', 'Desai', 'advait.desai@example.com', '+91-7654321098', 3, 0, '2024-05-05'),
(4, 'Ananya', 'Shah', 'Joshi', 'ananya.joshi@example.com', '+91-6543210987', 4, 1, '2024-05-07'),
(5, 'Arnav', 'Patel', 'Khan', 'arnav.khan@example.com', '+91-5432109876', 5, 1, '2024-05-09'),
(6, 'Aryan', 'Mishra', 'Shah', 'aryan.shah@example.com', '+91-4321098765', 6, 1, '2024-05-11'),
(7, 'Avni', 'Verma', 'Singh', 'avni.singh@example.com', '+91-3210987654', 7, 1, '2024-05-13'),
(8, 'Ishaan', 'Kumar', 'Mehta', 'ishaan.mehta@example.com', '+91-2109876543', 8, 1, '2024-05-15'),
(9, 'Kabir', 'Malhotra', 'Gupta', 'kabir.gupta@example.com', '+91-1098765432', 9, 1, '2024-05-17'),
(10, 'Kiara', 'Sharma', 'Singh', 'kiara.singh@example.com', '+91-0987654321', 20, 1, '2024-05-19'),
(11, 'Reyaansh', 'Singh', 'Verma', 'reyaansh.verma@example.com', '+91-9876543210', 11, 0, '2024-05-21'),
(12, 'Rhea', 'Patel', 'Sharma', 'rhea.sharma@example.com', '+91-8765432109', 12, 0, '2024-05-23'),
(13, 'Rudra', 'Mishra', 'Kaur', 'rudra.kaur@example.com', '+91-7654321098', 13, 1, '2024-05-25'),
(14, 'Saisha', 'Desai', 'Kumar', 'saisha.kumar@example.com', '+91-6543210987', 14, 1, '2024-05-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2=Staff',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `first_name`, `last_name`, `phone`, `gender`, `last_login`, `created_by`, `created_at`, `avatar`) VALUES
(1, 'Administrator', 'superadmin@gmail.com', '$2y$10$GwejIp6cIgl9hLUGPbVwhOtCEi8Fg4i5DvdAMzFJEYBd9WQCHTX6W', 1, 'Super ', 'Admin', '', NULL, NULL, NULL, '2025-04-05 07:16:31', NULL),
(3, '', 'supermanager@gmail.com', '$2y$10$VdnfXELMrNIly5b/KcIcAeBRZjwlvNJnr5tk5jF4PpvScKKvGCc.u', 3, 'Super', 'Manager', '', NULL, NULL, NULL, '2025-04-05 07:16:31', NULL),
(4, '', 'supertenant@gmail.com', '$2y$10$gHGhjVentpDYc15xjJLb7.Be8Ysjluep..A7xflRP76WMTtlLZ3mS', 4, 'Super', 'Tenant', '', NULL, NULL, NULL, '2025-04-05 07:34:03', NULL),
(5, '', 'superowner@gmail.com', '$2y$10$OZLqIEItdi28oDxIhShrJuhL845Dqr5onLZD/sd/d1tTkDWDDxisy', 2, 'Super', 'Owner', '', NULL, NULL, NULL, '2025-04-05 07:36:27', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apartments`
--
ALTER TABLE `apartments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apartment_id` (`apartment_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `asset_items`
--
ALTER TABLE `asset_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categories_created_by` (`created_by`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apartments`
--
ALTER TABLE `apartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_items`
--
ALTER TABLE `asset_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apartments`
--
ALTER TABLE `apartments`
  ADD CONSTRAINT `apartments_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `apartments_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `apartments_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `apartments_ibfk_4` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`),
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `asset_items`
--
ALTER TABLE `asset_items`
  ADD CONSTRAINT `asset_items_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
