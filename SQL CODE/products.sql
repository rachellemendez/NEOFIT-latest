CREATE TABLE `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(255) DEFAULT NULL,
  `product_price` DECIMAL(10,2) DEFAULT NULL,
  `product_status` VARCHAR(50) DEFAULT NULL,
  `quantity_small` INT(11) DEFAULT 0,
  `quantity_medium` INT(11) DEFAULT 0,
  `quantity_large` INT(11) DEFAULT 0,
  `photoFront` VARCHAR(255) DEFAULT NULL,
  `photo1` VARCHAR(255) DEFAULT NULL,
  `photo2` VARCHAR(255) DEFAULT NULL,
  `photo3` VARCHAR(255) DEFAULT NULL,
  `photo4` VARCHAR(255) DEFAULT NULL,
  `box_id` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
