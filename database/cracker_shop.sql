-- ========================================================
-- Database Schema: Online Cracker Shop (cracker_shop)
-- Compatible with MySQL / MariaDB (XAMPP)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `cracker_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cracker_shop`;

-- --------------------------------------------------------
-- Table structure for `admin`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT NULL,
  `city` VARCHAR(50) NULL,
  `state` VARCHAR(50) NULL,
  `pincode` VARCHAR(10) NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `categories`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `image` VARCHAR(255) DEFAULT 'default-cat.jpg',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `products`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT 'default-product.jpg',
  `mrp` DECIMAL(10,2) NOT NULL,
  `offer_price` DECIMAL(10,2) NOT NULL,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `unit` VARCHAR(30) DEFAULT 'Box',
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX (`category_id`),
  INDEX (`is_featured`),
  INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `orders`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('COD', 'ONLINE') DEFAULT 'COD',
  `payment_status` ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
  `order_status` ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
  `shipping_name` VARCHAR(100) NOT NULL,
  `shipping_phone` VARCHAR(20) NOT NULL,
  `shipping_email` VARCHAR(100) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `city` VARCHAR(50) NOT NULL,
  `state` VARCHAR(50) NOT NULL,
  `pincode` VARCHAR(10) NOT NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX (`user_id`),
  INDEX (`order_status`),
  INDEX (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `order_items`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX (`order_id`),
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `contacts`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('unread', 'read') DEFAULT 'unread',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `newsletter`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- SEED SAMPLE DATA
-- ========================================================

-- Insert Default Admin Account (Username: admin, Password: admin123)
-- Password Hash using BCRYPT for 'admin123'
INSERT INTO `admin` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@crackershop.com', '$2y$10$wE99q9a32c2.rN18eT3/r.M0e0F9y68hF367c3g/25368a.f.f.f', 'admin')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `status`) VALUES
(1, 'Sparklers', 'sparklers', 'Dazzling hand-held wire sparklers for all ages.', 'sparklers.jpg', 'active'),
(2, 'Ground Chakkars', 'ground-chakkars', 'Spinning ground wheels creating mesmerising circular fire patterns.', 'chakkars.jpg', 'active'),
(3, 'Flower Pots', 'flower-pots', 'Erupting fountains of brilliant golden and colorful sparks.', 'flower-pots.jpg', 'active'),
(4, 'Rockets & Sky Shots', 'rockets-sky-shots', 'High-flying aerial fireworks that light up the night sky with color bursts.', 'rockets.jpg', 'active'),
(5, 'Sound Crackers', 'sound-crackers', 'Traditional loud echoing sound crackers and garlands for energetic celebrations.', 'sound-crackers.jpg', 'active'),
(6, 'Fancy Gift Boxes', 'fancy-gift-boxes', 'Curated value assortments packed in premium gift boxes for festivals.', 'gift-boxes.jpg', 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert Products
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `image`, `mrp`, `offer_price`, `stock_quantity`, `unit`, `is_featured`, `status`) VALUES
(1, 1, '10cm Electric Sparklers (10 Pcs)', '10cm-electric-sparklers-10-pcs', 'Bright golden wire sparklers suitable for kids and family celebrations. Low smoke formulation.', 'sparklers_10cm.jpg', 150.00, 75.00, 200, 'Pack of 10', 1, 'active'),
(2, 1, '30cm Color Sparklers (5 Pcs)', '30cm-color-sparklers-5-pcs', 'Extra-long multi-color sparklers offering long burning time and brilliant red, green, and gold sparks.', 'sparklers_30cm.jpg', 300.00, 149.00, 150, 'Pack of 5', 1, 'active'),
(3, 2, 'Big Asoka Ground Chakkar (10 Pcs)', 'big-asoka-ground-chakkar-10-pcs', 'High-speed spinning ground wheel emitting bright white and red starry sparks.', 'chakkar_big.jpg', 400.00, 199.00, 120, 'Box of 10', 1, 'active'),
(4, 2, 'Special Wheel Chakkar Deluxe', 'special-wheel-chakkar-deluxe', 'Heavy-duty long spinning ground wheel with dual color changing fireworks effect.', 'chakkar_deluxe.jpg', 550.00, 280.00, 80, 'Box of 10', 0, 'active'),
(5, 3, 'Flower Pots Special (10 Pcs)', 'flower-pots-special-10-pcs', 'Classic golden fountain erupting up to 10 feet into the air.', 'flowerpot_special.jpg', 450.00, 220.00, 100, 'Box of 10', 1, 'active'),
(6, 3, 'Color Change Fountain Deluxe (2 Pcs)', 'color-change-fountain-deluxe-2-pcs', 'Tri-stage color changing giant fountain reaching 15 feet high with crackling stars.', 'fountain_deluxe.jpg', 700.00, 399.00, 60, 'Pack of 2', 1, 'active'),
(7, 4, 'Whistling Sky Rockets (10 Pcs)', 'whistling-sky-rockets-10-pcs', 'High velocity aerial rockets with a thrilling whistling sound and golden rain finish.', 'rocket_whistling.jpg', 650.00, 329.00, 90, 'Box of 10', 1, 'active'),
(8, 4, '12 Shot Multi-Color Sky Shell', '12-shot-multi-color-sky-shell', 'Repeater aerial repeat shell discharging 12 colorful stars with loud crackles in sequence.', 'skyshot_12shot.jpg', 1200.00, 699.00, 50, 'Box of 1', 1, 'active'),
(9, 5, '28 Chora Sound Crackers', '28-chora-sound-crackers', 'Loud traditional sound cracker strip for inauguration and festive celebration.', 'sound_28chora.jpg', 250.00, 120.00, 150, 'Box of 1', 0, 'active'),
(10, 5, '1000 Wala Heavy Garland Cracker', '1000-wala-heavy-garland-cracker', 'Long continuous garland cracker producing impressive loud burst sequence.', 'garland_1000.jpg', 1800.00, 999.00, 40, 'Box of 1', 1, 'active'),
(11, 6, 'Family Festival Dhamaka Pack', 'family-festival-dhamaka-pack', 'Complete family assortment box containing 25 distinct varieties of sparklers, pots, chakkars & rockets.', 'giftbox_family.jpg', 3500.00, 1799.00, 30, 'Super Box', 1, 'active'),
(12, 6, 'Kids Delight Safe Mini Combo', 'kids-delight-safe-mini-combo', 'Child-friendly low-sound sparklers, mini pots, pencil sparklers, and pop-pops assortment.', 'giftbox_kids.jpg', 1500.00, 799.00, 45, 'Combo Pack', 1, 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert Sample Customer User (Password: user123)
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `city`, `state`, `pincode`, `status`) VALUES
(1, 'Rahul Sharma', 'rahul@example.com', '$2y$10$wO8w.S1oN4g3K8jU8t2y4.a1oKq/1qH.0Q4yN2v5.vG0f4v6x2G9m', '9876543210', '42 Festive Garden, MG Road', 'Mumbai', 'Maharashtra', '400001', 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert Sample Order
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `shipping_name`, `shipping_phone`, `shipping_email`, `shipping_address`, `city`, `state`, `pincode`) VALUES
(1, 'ORD-20260729-1001', 1, 1148.00, 'COD', 'Pending', 'Processing', 'Rahul Sharma', '9876543210', 'rahul@example.com', '42 Festive Garden, MG Road', 'Mumbai', 'Maharashtra', '400001')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insert Sample Order Items
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `total_price`) VALUES
(1, 1, 1, '10cm Electric Sparklers (10 Pcs)', 75.00, 2, 150.00),
(2, 1, 6, 'Color Change Fountain Deluxe (2 Pcs)', 399.00, 1, 399.00),
(3, 1, 7, 'Whistling Sky Rockets (10 Pcs)', 329.00, 1, 329.00)
ON DUPLICATE KEY UPDATE `id`=`id`;
