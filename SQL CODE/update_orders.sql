ALTER TABLE `orders`
ADD COLUMN `user_id` int(11) NOT NULL AFTER `id`,
ADD COLUMN `product_id` int(11) NOT NULL AFTER `user_id`,
ADD KEY `user_id` (`user_id`),
ADD KEY `product_id` (`product_id`),
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

-- Update orders table status enum
ALTER TABLE `orders` 
MODIFY COLUMN `status` enum('To Pack','Packed','In Transit','Delivered','Cancelled','Returned') NOT NULL DEFAULT 'To Pack';

-- Update existing status values
UPDATE `orders` SET `status` = 'To Pack' WHERE `status` = 'Pending';
UPDATE `orders` SET `status` = 'Packed' WHERE `status` = 'Processing';
UPDATE `orders` SET `status` = 'In Transit' WHERE `status` = 'Shipped';