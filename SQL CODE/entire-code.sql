CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0
) 


CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_status` varchar(50) DEFAULT NULL,
  `quantity_small` int(11) DEFAULT 0,
  `quantity_medium` int(11) DEFAULT 0,
  `quantity_large` int(11) DEFAULT 0,
  `photoFront` varchar(255) DEFAULT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `photo4` varchar(255) DEFAULT NULL,
  `product_category` enum('men','women') DEFAULT NULL
)


CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
)