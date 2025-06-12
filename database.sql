-- DROP & CREATE jika sudah ada
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `cart`;

CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100),
  `price` INT,
  `image` VARCHAR(255),
  `description` TEXT,
  `bestseller` BOOLEAN DEFAULT FALSE
);

CREATE TABLE `cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT,
  `quantity` INT,
  FOREIGN KEY (`product_id`) REFERENCES products(`id`) ON DELETE CASCADE
);

-- INSERT sample data
INSERT INTO `products` (`name`, `price`, `image`, `description`, `bestseller`) VALUES
('Samsung Galaxy S23', 12500000, 'images/product-1.png', 'Samsung Galaxy S23 dengan layar AMOLED 6.1 inci dan kamera 50MP.', 1),
('Xiaomi Redmi Note 12', 2999000, 'images/product-2.png', 'Xiaomi dengan layar AMOLED dan baterai 5000mAh.', 0),
('iPhone 14', 15999000, 'images/product-3.png', 'iPhone 14 dengan chip A15 Bionic dan kamera ganda 12MP.', 1),
('Realme C55', 2299000, 'images/product-4.png', 'Realme C55 dengan RAM 6GB dan ROM 128GB.', 0);
