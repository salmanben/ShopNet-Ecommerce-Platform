CREATE DATABASE shopnet;
USE shopnet;

CREATE TABLE `admin` (
  `id` varchar(30) PRIMARY KEY,
  `first_name` varchar(30),
  `last_name` varchar(30),
  `email` varchar(50) UNIQUE,
  `password` varchar(250),
  `image` varchar(30),
  `status` boolean DEFAULT 1,
  `date` datetime DEFAULT current_timestamp()
);

CREATE TABLE `buyer` (
  `id` varchar(30) PRIMARY KEY,
  `username` varchar(30),
  `email` varchar(50) UNIQUE,
  `password` varchar(250),
  `status` boolean DEFAULT 1,
  `date` datetime DEFAULT current_timestamp()
);

CREATE TABLE `seller` (
  `id` varchar(30) PRIMARY KEY,
  `username` varchar(30),
  `email` varchar(50) UNIQUE,
  `password` varchar(250),
  `image` varchar(30),
  `banner` varchar(30),
  `paypal_email` varchar(50),
  `status` boolean DEFAULT 1,
  `date` datetime DEFAULT current_timestamp()
);

CREATE TABLE `category` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(30) UNIQUE,
  `icon` varchar(30) UNIQUE,
  `status` boolean DEFAULT 1,
  `date` datetime DEFAULT current_timestamp()
);

CREATE TABLE `product` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `image` varchar(30),
  `title` varchar(30),
  `long_description` text,
  `short_description` VARCHAR(150),
  `price` float,
  `count` int(11),
  `status` boolean DEFAULT 1,
  `category_id` int(11),
  `seller_id` varchar(30),
  `date` datetime DEFAULT current_timestamp(),
  CONSTRAINT fk_product_seller FOREIGN KEY (`seller_id`) REFERENCES `seller`(`id`),
  CONSTRAINT fk_product_category FOREIGN KEY (`category_id`) REFERENCES `category`(`id`)
);

CREATE TABLE `orders` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` varchar(255) UNIQUE,
  `buyer_id` varchar(30),
  `amount` float,
  `subtotal` float,
  `coupon` text,
  `shipping_method` text,
  `address_info` text,
  `status` enum('Pending','Dropped off', 'Completed') default 'Pending',
  `date` datetime DEFAULT current_timestamp(),
  CONSTRAINT fk_order_buyer FOREIGN KEY (`buyer_id`) REFERENCES `buyer`(`id`)
);

CREATE TABLE `order_products` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `product_id` int(11),
  `unit_price` float,
  `order_id` int(11),
  `variants` varchar(250),
  `qty` int(11),
  `status` enum('Pending','Dropped off') default 'Pending',
  `date` datetime DEFAULT current_timestamp(),
  CONSTRAINT fk_order_products_product FOREIGN KEY (`product_id`) REFERENCES `product`(`id`),
  CONSTRAINT fk_order_products_orders FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
);

CREATE TABLE `seller_followers` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `buyer_id` varchar(30),
  `seller_id` varchar(30),
  `date` datetime DEFAULT current_timestamp(),
  CONSTRAINT fk_seller_followers_buyer FOREIGN KEY (`buyer_id`) REFERENCES `buyer`(`id`),
  CONSTRAINT fk_seller_followers_seller FOREIGN KEY (`seller_id`) REFERENCES `seller`(`id`)
);

CREATE TABLE `cart` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `product_id` int(11),
  `buyer_id` varchar(30),
  `date` datetime DEFAULT current_timestamp(),
  CONSTRAINT fk_cart_product FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE,
  CONSTRAINT fk_cart_buyer FOREIGN KEY (`buyer_id`) REFERENCES `buyer`(`id`)
);

CREATE TABLE `messaging` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `buyer_id` varchar(30),
  `seller_id` varchar(30),
  `send_id` varchar(30),
  `message` text,
  `date` datetime DEFAULT current_timestamp(),
  CONSTRAINT fk_messaging_buyer FOREIGN KEY (`buyer_id`) REFERENCES `buyer`(`id`),
  CONSTRAINT fk_messaging_seller FOREIGN KEY (`seller_id`) REFERENCES `seller`(`id`)
);


CREATE TABLE `coupon` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(30) UNIQUE,
  `quantity` INT(11),
  `max_use` INT(11),
  `status` boolean DEFAULT 1,
  `cost` DOUBLE,
  `type` ENUM('amount', 'percentage'),
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `shipping_method` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(30) UNIQUE,
  `cost` DOUBLE,
  `min_cost` DOUBLE DEFAULT NULL,
  `type` ENUM('flat_cost', 'min_cost'),
  `status` boolean DEFAULT 1,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `product_variants` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(30),
  `product_id` int(11),
  `status` boolean DEFAULT 1,
  CONSTRAINT fk_variants_product FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `product_variant_items` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(30),
  `variant_id` int(11),
  `price` double,
  `is_default` boolean,
  `status` boolean DEFAULT 1,
  CONSTRAINT fk_variants_items FOREIGN KEY (`variant_id`) REFERENCES `product_variants`(`id`) ON DELETE CASCADE,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `contact` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(50),
  `email` varchar(100),
  `message` text,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `withdraw_method` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `method_name` VARCHAR(50),
  `charges` DOUBLE,
  `min_amount` DOUBLE,
  `max_amount` DOUBLE,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `withdraw_request` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(30),
  `amount` DOUBLE,
  `charges` DOUBLE,
  `status` ENUM('Pending', 'Decline', 'Paid') DEFAULT 'pending',
  `seller_id` VARCHAR(30),
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`seller_id`) REFERENCES `seller`(`id`)
);

CREATE TABLE `paypal` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `client_id` text,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `email_settings` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255),
  `password` varchar(255),
  `from_name` varchar(255),
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `email`, `password`, `image`)
VALUES (1, 'SALMAN', 'BEN OMAR', 'benomarsalman112@gmail.com', '$2y$10$OQGKEGzaVqVJDe0V2iW9kO3pFxTPfm6E989ZEy25.pfS.CoMFAojm', 'admin.png');
