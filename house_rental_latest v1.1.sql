-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 12:07 PM
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
  `tenant_id` int(11) DEFAULT NULL,
  `manager_assigned_by` int(11) DEFAULT NULL,
  `manager_assigned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartments`
--

INSERT INTO `apartments` (`id`, `number`, `description`, `price`, `category_id`, `owner_id`, `manager_id`, `tenant_id`, `manager_assigned_by`, `manager_assigned_at`) VALUES
(1, 'A101', 'Spacious 2-bedroom apartment with balcony', 1200.00, 10, 5, 11, 4, 9, '2025-04-06 09:06:31'),
(2, 'B202', 'Luxury 1-bedroom with city views', 1500.00, 10, 5, 3, 12, NULL, '2025-04-06 09:06:31'),
(3, 'C303', 'Cozy studio apartment', 850.00, 10, 5, 3, NULL, NULL, '2025-04-06 09:06:31'),
(4, 'TH101', 'Modern townhouse with garage', 1800.00, 11, 5, 3, 13, NULL, '2025-04-06 09:06:31'),
(5, 'SF201', 'Single-family home with yard', 2200.00, 9, 5, 3, 14, NULL, '2025-04-06 09:06:31'),
(6, 'DUP102', 'Duplex unit with separate entrance', 1600.00, 13, 5, 3, 16, NULL, '2025-04-06 09:06:31'),
(7, 'CON501', 'High-end condominium with amenities', 2500.00, 12, 5, 11, 4, NULL, '2025-04-06 09:06:31'),
(8, 'MH301', 'Mobile home in quiet community', 700.00, 14, 5, 3, 15, NULL, '2025-04-06 09:06:31'),
(9, 'TINY01', 'Efficient tiny house with loft', 900.00, 17, 5, 3, 12, NULL, '2025-04-06 09:06:31'),
(10, 'A102', 'Renovated 1-bedroom apartment', 1100.00, 10, 5, 3, 23, NULL, '2025-04-06 09:06:31');

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

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `apartment_id`, `created_by`, `created_at`, `granted_at`) VALUES
(1, 1, 3, '2025-04-06 08:06:07', '2025-01-15 10:00:00'),
(2, 1, 3, '2025-04-06 08:06:07', '2025-01-15 10:00:00'),
(3, 3, 3, '2025-04-06 08:06:07', '2025-02-20 11:30:00'),
(4, 5, 3, '2025-04-06 08:06:07', '2025-03-10 09:15:00'),
(5, 7, 3, '2025-04-06 08:06:07', '2025-01-05 14:45:00'),
(6, 9, 3, '2025-04-06 08:06:07', '2025-02-28 16:20:00');

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

--
-- Dumping data for table `asset_items`
--

INSERT INTO `asset_items` (`id`, `asset_id`, `item_name`, `quantity`, `description`) VALUES
(1, 1, 'Refrigerator', 1, 'Stainless steel, 20 cu. ft.'),
(2, 1, 'Microwave', 1, 'Over-the-range model'),
(3, 1, 'Dining table set', 1, '4 chairs included'),
(4, 2, 'Sofa', 1, '3-seater, dark brown'),
(5, 2, 'TV', 1, '55-inch smart TV'),
(6, 3, 'Bed frame', 1, 'Queen size'),
(7, 3, 'Mattress', 1, 'Memory foam, queen size'),
(8, 4, 'Washing machine', 1, 'Front-loading'),
(9, 4, 'Dryer', 1, 'Electric'),
(10, 5, 'Dishwasher', 1, 'Built-in'),
(11, 5, 'Oven', 1, 'Gas range with 4 burners'),
(12, 6, 'Coffee table', 1, 'Glass top'),
(13, 6, 'Lamp', 2, 'Table lamps');

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
(18, 'wema', NULL, '2025-04-05 01:08:02'),
(21, 'Studio Apartment', 1, '2025-04-06 08:06:06'),
(22, 'Loft', 1, '2025-04-06 08:06:06'),
(23, 'Villa', 1, '2025-04-06 08:06:06'),
(24, 'Bungalow', 1, '2025-04-06 08:06:06');

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
  `status` enum('pending','completed','failed','veried') DEFAULT 'pending',
  `payment_method` enum('credit_card','bank_transfer','cash') DEFAULT 'credit_card',
  `payment_details` text DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `invoice`, `date_created`, `apartment_id`, `created_by`, `created_at`, `verified_by`, `verified_at`, `status`, `payment_method`, `payment_details`, `from_date`, `to_date`) VALUES
(1, 1, 1500, 'INV001', '2024-05-02 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(2, 2, 1200, 'INV002', '2024-05-04 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(3, 3, 1600, 'INV003', '2024-05-06 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(4, 4, 1800, 'INV004', '2024-05-08 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(5, 5, 1400, 'INV005', '2024-05-10 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(6, 6, 1700, 'INV006', '2024-05-12 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(7, 7, 1300, 'INV007', '2024-05-14 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(8, 8, 1900, 'INV008', '2024-05-16 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(9, 9, 1550, 'INV009', '2024-05-18 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(10, 10, 1250, 'INV010', '2024-05-20 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(11, 11, 1650, 'INV011', '2024-05-22 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(12, 12, 1350, 'INV012', '2024-05-24 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(13, 13, 1450, 'INV013', '2024-05-26 00:00:00', 0, 0, '2025-04-05 01:29:43', NULL, '2025-04-05 01:29:43', 'pending', 'credit_card', NULL, NULL, NULL),
(15, 15, 1200, 'INV2025-001', '2025-01-01 00:00:00', 1, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(16, 15, 1200, 'INV2025-002', '2025-02-01 00:00:00', 1, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(17, 15, 1200, 'INV2025-003', '2025-03-01 00:00:00', 1, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(18, 16, 850, 'INV2025-004', '2025-02-15 00:00:00', 3, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(19, 16, 850, 'INV2025-005', '2025-03-15 00:00:00', 3, 3, '2025-04-06 08:06:07', NULL, '2025-04-06 08:06:07', 'pending', 'credit_card', NULL, NULL, NULL),
(20, 17, 2200, 'INV2025-006', '2025-01-20 00:00:00', 5, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(21, 17, 2200, 'INV2025-007', '2025-02-20 00:00:00', 5, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(22, 17, 2200, 'INV2025-008', '2025-03-20 00:00:00', 5, 3, '2025-04-06 08:06:07', NULL, '2025-04-06 08:06:07', 'pending', 'credit_card', NULL, NULL, NULL),
(23, 18, 2500, 'INV2025-009', '2025-03-01 00:00:00', 7, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(24, 19, 900, 'INV2025-010', '2025-02-10 00:00:00', 9, 3, '2025-04-06 08:06:07', 1, '2025-04-06 08:06:07', 'completed', 'credit_card', NULL, NULL, NULL),
(25, 19, 900, 'INV2025-011', '2025-03-10 00:00:00', 9, 3, '2025-04-06 08:06:07', NULL, '2025-04-06 08:06:07', 'pending', 'credit_card', NULL, NULL, NULL),
(26, 20, 1500, 'INV2025-012', '2025-02-20 00:00:00', 2, 3, '2025-04-06 08:06:07', NULL, '2025-04-06 08:06:07', 'failed', 'credit_card', NULL, NULL, NULL);

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
(10, 'Kiara', 'Sharma', 'Singh', 'kiara.singh@example.com', '+91-0987654321', 20, 0, '2024-05-19'),
(11, 'Reyaansh', 'Singh', 'Verma', 'reyaansh.verma@example.com', '+91-9876543210', 11, 0, '2024-05-21'),
(12, 'Rhea', 'Patel', 'Sharma', 'rhea.sharma@example.com', '+91-8765432109', 12, 0, '2024-05-23'),
(13, 'Rudra', 'Mishra', 'Kaur', 'rudra.kaur@example.com', '+91-7654321098', 13, 1, '2024-05-25'),
(14, 'Saisha', 'Desai', 'Kumar', 'saisha.kumar@example.com', '+91-6543210987', 14, 1, '2024-05-27'),
(15, 'Michael', 'James', 'Johnson', 'michael.johnson@example.com', '+1-555-123-4567', 1, 1, '2025-01-10'),
(16, 'Sarah', 'Elizabeth', 'Williams', 'sarah.williams@example.com', '+1-555-234-5678', 3, 1, '2025-02-15'),
(17, 'David', 'Robert', 'Brown', 'david.brown@example.com', '+1-555-345-6789', 5, 1, '2025-01-20'),
(18, 'Jennifer', 'Marie', 'Davis', 'jennifer.davis@example.com', '+1-555-456-7890', 7, 1, '2025-03-01'),
(19, 'Christopher', 'Lee', 'Miller', 'christopher.miller@example.com', '+1-555-567-8901', 9, 1, '2025-02-10'),
(20, 'Jessica', 'Ann', 'Wilson', 'jessica.wilson@example.com', '+1-555-678-9012', 2, 0, '2025-01-15'),
(21, 'Matthew', 'Thomas', 'Moore', 'matthew.moore@example.com', '+1-555-789-0123', 4, 0, '2025-02-05'),
(22, 'Amanda', 'Grace', 'Taylor', 'amanda.taylor@example.com', '+1-555-890-1234', 6, 1, '2025-03-15'),
(23, 'Daniel', 'Paul', 'Anderson', 'daniel.anderson@example.com', '+1-555-901-2345', 8, 1, '2025-01-25'),
(24, 'Emily', 'Rose', 'Thomas', 'emily.thomas@example.com', '+1-555-012-3456', 10, 1, '2025-02-20'),
(25, '', '', '', '', '', 22, 1, '0000-00-00');

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
(5, '', 'superowner@gmail.com', '$2y$10$OZLqIEItdi28oDxIhShrJuhL845Dqr5onLZD/sd/d1tTkDWDDxisy', 2, 'Super', 'Owner', '', NULL, NULL, NULL, '2025-04-05 07:36:27', NULL),
(8, 'Property Owner 1', 'owner1@example.com', '$2y$10$OZLqIEItdi28oDxIhShrJuhL845Dqr5onLZD/sd/d1tTkDWDDxisy', 2, 'John', 'Smith', '+1-555-111-2222', 'male', NULL, 1, '2025-04-06 08:06:07', NULL),
(9, 'Property Owner 2', 'owner2@example.com', '$2y$10$OZLqIEItdi28oDxIhShrJuhL845Dqr5onLZD/sd/d1tTkDWDDxisy', 2, 'Lisa', 'Johnson', '+1-555-222-3333', 'female', NULL, 1, '2025-04-06 08:06:07', NULL),
(10, 'Property Manager 1', 'manager1@example.com', '$2y$10$VdnfXELMrNIly5b/KcIcAeBRZjwlvNJnr5tk5jF4PpvScKKvGCc.u', 3, 'Robert', 'Williams', '+1-555-333-4444', 'male', NULL, 1, '2025-04-06 08:06:07', NULL),
(11, 'Property Manager 2', 'manager2@example.com', '$2y$10$VdnfXELMrNIly5b/KcIcAeBRZjwlvNJnr5tk5jF4PpvScKKvGCc.u', 3, 'Emily', 'Brown', '+1-555-444-5555', 'female', NULL, 1, '2025-04-06 08:06:07', NULL),
(12, 'Tenant User 1', 'tenant1@example.com', '$2y$10$gHGhjVentpDYc15xjJLb7.Be8Ysjluep..A7xflRP76WMTtlLZ3mS', 4, 'Michael', 'Davis', '+1-555-555-6666', 'male', NULL, 1, '2025-04-06 08:06:07', NULL),
(13, 'Tenant User 2', 'tenant2@example.com', '$2y$10$gHGhjVentpDYc15xjJLb7.Be8Ysjluep..A7xflRP76WMTtlLZ3mS', 4, 'Sarah', 'Miller', '+1-555-666-7777', 'female', NULL, 1, '2025-04-06 08:06:07', NULL),
(14, '', 'ueueue@gmail.com', '$2y$10$CNG0eq4ndbi25sYlpLEukOKCI4Ocp.0dWekGd9xYJNMTtlz38Gf02', 4, 'ajaja', 'sjsjsjs', '0722889977', '', NULL, NULL, '2025-04-07 04:33:03', 'uploads/avatars/tenant_1744000383.jpeg'),
(15, '', 'a@a.a', '$2y$10$4SJZdLbznGzWGFcVlHOTjOzPapXHVaGJVRb1MacaPfQtScSZcOd.a', 4, 'tyu', 'weew', '0928822674', '', NULL, NULL, '2025-04-07 04:53:57', 'uploads/avatars/tenant_1744001637.jpeg'),
(16, '', 'test@gmail.com', '$2y$10$5jEXcu5ibVn4t3fiFbjKSurfwO/bp2LADYYHhu8ItUzZbYwkCTp9m', 4, 'test', 'tenant', '0322112233', '', NULL, NULL, '2025-04-07 05:40:19', 'uploads/avatars/tenant_1744004419.jpeg'),
(17, '', 'wani@gmail.c', '$2y$10$rZEwaSkKkdVHJP.Bnxjax.7Zg7LOLv8mM1Fu6.YNVVzyMsfZp4sxS', 4, 'one ', 'wani', '', NULL, NULL, NULL, '2025-04-07 05:43:27', NULL),
(18, '', 'q', '$2y$10$nEDtfW2NLY8rsjC5OnpipOP0SCBHWH79Xjpspa3COAQMCjXX9V0C6', 2, 'q', 'q', '', NULL, NULL, NULL, '2025-04-07 05:44:34', NULL),
(19, '', 'asdfg', '$2y$10$bSW8wa5w9I7ROYyAIVgTBeMdgc3BHLn2yxGV5DOEyQ2fpNz6Z.FzK', 3, 'qwertyu', 'sdfghjk', '', NULL, NULL, NULL, '2025-04-07 05:52:23', NULL),
(20, '', 'aaa', '$2y$10$L2n7ioF6iFnj4I0QpM6geObTZg42cR5EiYAqGZn.G5L1YBM9wuCJK', 2, 'sqq', 'a', '', NULL, NULL, NULL, '2025-04-07 05:58:23', NULL),
(22, '', 'dfghvjbhkjnl', '$2y$10$7QR7dzJnCvXVmg2lnD48EOLrcdCrpzt7Cu8WcejqyD.nQdSY7xmxG', 4, 'rtcgvbhj', 'xtcgvbhjn', '', NULL, NULL, NULL, '2025-04-07 06:01:51', NULL),
(23, '', 'ertyui@gmail.com', '$2y$10$A2GDLBlTuf3jPnczaYZZx.nOK3zf62BwkKLB2sv/u37QpyR5rrnNi', 4, 'g', 'w', '77883738373', '', NULL, NULL, '2025-04-07 06:06:40', NULL);

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
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `fk_manager_assigned_by` (`manager_assigned_by`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `asset_items`
--
ALTER TABLE `asset_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  ADD CONSTRAINT `apartments_ibfk_4` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_manager_assigned_by` FOREIGN KEY (`manager_assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
