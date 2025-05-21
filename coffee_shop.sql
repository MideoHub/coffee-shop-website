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

-- Sample data: users
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'admin', 'Admin@gmail.com', '$2y$10$FcmFN3TSb9ZPAhrzUJMoxOQBWaN3KV9RAnF/cOTLQfm11uNXpj6OG', '2025-05-21 00:47:42', 'admin'),
(2, 'mohamdd', 'asd@gmial.com', '$2y$10$FcmFN3TSb9ZPAhrzUJMoxOQBWaN3KV9RAnF/cOTLQfm11uNXpj6OG', '2025-05-21 02:34:55', 'user');

-- Sample data: products
INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `nutrition`) VALUES
(1, 'Caffè Americano', 'A rich, full-bodied coffee with a hint of caramel.', '../assets/images/caffe-americano.jpg', 2.10, 'Serving Size: 16 fl oz|Calories: 15|Calories from Fat: 0'),
(2, 'Caffè Misto', 'A one-to-one combination of fresh-brewed coffee and steamed milk.', '../assets/images/caffe-misto.jpg', 2.60, 'Serving Size: 16 fl oz|Calories: 110|Calories from Fat: 35'),
(3, 'Espresso', 'Rich and full-bodied with a hint of caramel.', '../assets/images/espresso.jpg', 2.79, 'Serving Size: 1.5 fl oz|Calories: 5|Calories from Fat: 0'),
(4, 'Espresso Macchiato', 'A rich, full-bodied espresso with a dash of steamed milk.', '../assets/images/espresso-macchiato.jpg', 2.05, 'Serving Size: 1.5 fl oz|Calories: 15|Calories from Fat: 5'),
(5, 'Dark Roast Coffee', 'A full-bodied coffee with bold, robust flavors.', '../assets/images/caffe-americano.jpg', 2.20, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
(6, 'Flat White', 'Smooth ristretto shots of espresso with velvety steamed milk.', '../assets/images/flat-white.jpg', 2.80, 'Serving Size: 12 fl oz|Calories: 170|Calories from Fat: 90'),
(7, 'Decaf Roast', 'A decaffeinated coffee with a smooth, rich flavor.', '../assets/images/caffe-misto.jpg', 2.25, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
(8, 'Cappuccino', 'A perfect balance of espresso, steamed milk, and foam.', '../assets/images/cappuccino.jpg', 2.59, 'Serving Size: 12 fl oz|Calories: 120|Calories from Fat: 50'),
(9, 'Blonde Roast', 'A light-bodied coffee with a sweet, citrusy flavor.', '../assets/images/caffe-americano.jpg', 2.59, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
(10, 'aaa', 'free', 'E:\\Xampp\\htdocs\\coffee-shop-website\\assets\\images\\p6.jpeg', 2.50, 'aaa');

-- Sample data: cart_items
INSERT INTO `cart_items` (`id`, `user_id`, `session_id`, `product_name`, `product_image`, `price`, `quantity`, `created_at`) VALUES
(1, NULL, 't2p7u8iqipbc6g9ime1p3p1b4i', 'Caffè Misto', '../assets/images/caffe-misto.jpg', 2.60, 1, '2025-05-21 00:29:52'),
(2, NULL, 't2p7u8iqipbc6g9ime1p3p1b4i', 'Caffè Americano', '../assets/images/caffe-americano.jpg', 2.10, 1, '2025-05-21 00:30:23'),
(3, 1, 't2p7u8iqipbc6g9ime1p3p1b4i', 'Caffè Misto', '../assets/images/caffe-misto.jpg', 2.60, 1, '2025-05-21 00:54:23'),
(4, 1, 't2p7u8iqipbc6g9ime1p3p1b4i', 'Caffè Misto', '../assets/images/p2.jpeg', 2.60, 1, '2025-05-21 00:54:36'),
(9, 2, 'gn94ivt3s3rge9u1v6704lqke6', 'Caffè Americano', '../assets/images/caffe-americano.jpg', 2.10, 1, '2025-05-21 03:04:29'),
(10, 2, 'gn94ivt3s3rge9u1v6704lqke6', 'aaa', 'E:\\Xampp\\htdocs\\coffee-shop-website\\assets\\images\\p6.jpeg', 2.50, 1, '2025-05-21 03:07:58'),
(11, 2, 'gn94ivt3s3rge9u1v6704lqke6', 'Dark Roast Coffee', '../assets/images/p5.jpeg', 2.20, 1, '2025-05-21 03:29:50');

-- Sample data: bookings
INSERT INTO `bookings` (`id`, `user_id`, `name`, `email`, `booking_date`, `booking_time`, `persons`, `created_at`) VALUES
(1, NULL, '‪mohmed hmdy‬‏ن', 'mohmedhmdy95@gmail.com', '2025-05-26', '20:47:00', 3, '2025-05-21 00:43:36'),
(2, NULL, '‪mohmed hmdy‬‏ن', 'mohmedhmdy95@gmail.com', '2025-05-26', '20:47:00', 3, '2025-05-21 00:43:58'),
(3, NULL, 'aaaaaaaaaaaa‬‏ن', 'mohmedhmdy95@gmail.com', '2025-05-24', '20:47:00', 1, '2025-05-21 00:44:52'),
(4, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-05-08', '08:48:00', 4, '2025-05-21 02:49:04'),
(5, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-05-14', '05:53:00', 3, '2025-05-21 02:51:54'),
(6, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-05-30', '05:53:00', 3, '2025-05-21 02:52:02'),
(7, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-05-09', '05:53:00', 3, '2025-05-21 02:53:22'),
(8, 2, 'mohamdd', 'mohmedhmdy95@gmail.com', '2025-06-18', '05:53:00', 3, '2025-05-21 02:53:31');
