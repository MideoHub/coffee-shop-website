-- Create database
CREATE DATABASE IF NOT EXISTS coffee_shop;
USE coffee_shop;

-- Table: users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(10) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: products
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `nutrition` varchar(255) NOT NULL,
  `category` enum('Menu','Products') NOT NULL DEFAULT 'Products',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: cart_items
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: bookings
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `persons` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for `users`
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'admin', 'Admin@gmail.com', '$2y$10$FcmFN3TSb9ZPAhrzUJMoxOQBWaN3KV9RAnF/cOTLQfm11uNXpj6OG', '2025-05-21 00:47:42', 'admin'),
(2, 'mohamdd', 'asd@gmail.com', '$2y$10$FcmFN3TSb9ZPAhrzUJMoxOQBWaN3KV9RAnF/cOTLQfm11uNXpj6OG', '2025-05-21 02:34:55', 'user'),
(3, 'mmm', 'as@gmail.com', '$2y$10$FcmFN3TSb9ZPAhrzUJMoxOQBWaN3KV9RAnF/cOTLQfm11uNXpj6OG', '2025-05-21 20:06:25', 'user'),
(4, 'a7med', 'a@gmail.com', '$2y$10$2rkEM0uDws3fPbiPXvBVEu46PSn2GmsEfVW9iQaWxi/CN0fv4gCYO', '2025-05-22 00:38:51', 'user'),
(5, 'marwan', 'm@gmail.com', '$2y$10$Cx0AdOqun4H0e9FkOs7T8e5y.lD7JVdo3Mb1sIYKOZfd.mXUJfPtW', '2025-05-22 09:16:16', 'user'),
(6, 'eng aya ', 'aed@gmail.com', '$2y$10$kVjyhfoWWgTTpXZNP7Ps8.dEBeDfwhqXsoWP17XEHPC2xnUop5R8C', '2025-05-22 10:37:01', 'user');

-- Dumping data for `products`
INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `nutrition`, `category`) VALUES
(2, 'Caffè Misto', 'A one-to-one combination of fresh-brewed coffee and steamed milk.', '../assets/images/caffe-misto.jpg', 2.60, 'Serving Size: 16 fl oz|Calories: 110|Calories from Fat: 35', 'Products'),
(3, 'Espresso', 'Rich and full-bodied with a hint of caramel.', '../assets/images/espresso.jpg', 2.79, 'Serving Size: 1.5 fl oz|Calories: 5|Calories from Fat: 0', 'Products'),
(4, 'Espresso Macchiato', 'A rich, full-bodied espresso with a dash of steamed milk.', '../assets/images/espresso-macchiato.jpg', 2.05, 'Serving Size: 1.5 fl oz|Calories: 15|Calories from Fat: 5', 'Products'),
(5, 'Dark Roast Coffee', 'A full-bodied coffee with bold, robust flavors.', '../assets/images/caffe-americano.jpg', 2.20, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0', 'Products'),
(6, 'Flat White', 'Smooth ristretto shots of espresso with velvety steamed milk.', '../assets/images/flat-white.jpg', 2.80, 'Serving Size: 12 fl oz|Calories: 170|Calories from Fat: 90', 'Products'),
(7, 'Decaf Roast', 'A decaffeinated coffee with a smooth, rich flavor.', '../assets/images/caffe-misto.jpg', 2.25, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0', 'Products'),
(8, 'Cappuccino', 'A perfect balance of espresso, steamed milk, and foam.', '../assets/images/cappuccino.jpg', 2.59, 'Serving Size: 12 fl oz|Calories: 120|Calories from Fat: 50', 'Products'),
(15, 'Free ice cream', 'ice by ice', '../assets/images/main.jpeg', 1.00, '300CA', 'Products');

-- Dumping data for `cart_items`
INSERT INTO `cart_items` (`id`, `user_id`, `session_id`, `product_name`, `product_image`, `price`, `quantity`, `created_at`) VALUES
(10, NULL, 'ui8f67k3gvc47sitqcimrhruvo', 'Caffè Misto', '../assets/images/p2.jpeg', 2.60, 1, '2025-05-21 20:01:07'),
(11, NULL, 'ui8f67k3gvc47sitqcimrhruvo', 'Caffè Misto', '../assets/images/caffe-misto.jpg', 2.60, 1, '2025-05-21 20:06:00'),
(12, 3, 'ui8f67k3gvc47sitqcimrhruvo', 'Caffè Misto', '../assets/images/caffe-misto.jpg', 2.60, 1, '2025-05-21 20:15:02'),
(20, 2, 'dio9isi0ee73atj3ibqjmrfv56', 'Espresso', '../assets/images/espresso.jpg', 2.79, 1, '2025-05-22 10:38:13'),
(21, 2, 'dio9isi0ee73atj3ibqjmrfv56', 'Espresso', '../assets/images/p3.jpeg', 2.79, 1, '2025-05-22 10:38:18');

-- Dumping data for `bookings`
INSERT INTO `bookings` (`id`, `user_id`, `name`, `email`, `booking_date`, `booking_time`, `persons`, `created_at`) VALUES
(15, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-05-31', '01:25:00', 3, '2025-05-21 06:25:51'),
(16, 2, 'admin', 'mohmedhmdy95@gmail.com', '2025-05-31', '09:34:00', 2, '2025-05-21 06:31:37'),
(17, 2, 'admin', 'mohmedhmdy95@gmail.com', '2025-05-31', '09:34:00', 2, '2025-05-21 06:31:37'),
(18, 2, 'admin', 'mohmedhmdy95@gmail.com', '2025-05-28', '11:34:00', 2, '2025-05-21 06:34:12'),
(19, 2, 'admin', 'mohmedhmdy95@gmail.com', '2025-05-28', '11:34:00', 2, '2025-05-21 06:34:12'),
(20, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-05-31', '01:36:00', 2, '2025-05-21 06:36:09'),
(27, 4, 'ccc', 'asd@gmail.com', '2025-05-30', '08:00:00', 3, '2025-05-22 01:01:00'),
(28, 4, 'zz', 'mohmedhmdy95@gmail.com', '2025-05-31', '08:03:00', 3, '2025-05-22 01:03:29'),
(29, 4, 'zz', 'mohmedhmdy95@gmail.com', '2025-05-31', '08:03:00', 3, '2025-05-22 01:03:29'),
(30, 4, 'ccc', 'mohmedhmdy95@gmail.com', '2025-05-24', '08:06:00', 2, '2025-05-22 01:06:16'),
(31, 4, 'ccc', 'mohmedhmdy95@gmail.com', '2025-05-24', '08:06:00', 2, '2025-05-22 01:06:16'),
(32, 4, 'admin', 'mohmedhmdy95@gmail.com', '2025-05-24', '08:10:00', 3, '2025-05-22 01:10:21'),
(33, 4, 'admin', 'mohmedhmdy95@gmail.com', '2025-05-24', '08:10:00', 3, '2025-05-22 01:10:21'),
(34, 2, 'marwan', 'mohmedhmdy95@gmail.com', '2025-05-31', '16:14:00', 3, '2025-05-22 09:15:02'),
(35, 2, 'marwan', 'mohmedhmdy95@gmail.com', '2025-05-31', '16:14:00', 3, '2025-05-22 09:15:02'),
(36, 2, 'mawaran', 'm@gmai.com', '2025-05-31', '16:42:00', 4, '2025-05-22 10:40:44'),
(37, 2, 'mawaran', 'm@gmai.com', '2025-05-31', '16:42:00', 4, '2025-05-22 10:40:45');
