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
  `photo4` varchar(255) DEFAULT NULL
)