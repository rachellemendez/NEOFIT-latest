ALTER TABLE `orders`
ADD COLUMN `user_id` int(11) NOT NULL AFTER `id`,
ADD COLUMN `product_id` int(11) NOT NULL AFTER `user_id`,
ADD KEY `user_id` (`user_id`),
ADD KEY `product_id` (`product_id`),
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;