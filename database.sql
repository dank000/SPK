-- Buat ulang database dan tabel
DROP DATABASE IF EXISTS spk;
CREATE DATABASE spk;
USE spk;

-- Tabel users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Tabel products (HP)
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  harga INT,
  ram INT,
  kamera INT,
  baterai INT,
  image VARCHAR(255)
);

INSERT INTO products (nama, harga, ram, kamera, baterai, image) VALUES
('Samsung Galaxy S23', 12500000, 8, 64, 3900, 'images/samsung-galaxy-s23.png'),
('Xiaomi Redmi Note 12', 2999000, 8, 108, 5000, 'images/xiaomi-redmi-note-12.png'),
('iPhone 14', 15999000, 4, 12, 3279, 'images/iphone-14.png'),
('Realme C55', 2299000, 8, 64, 5000, 'images/realme-c55.png'),
('Vivo V25 Pro', 7999000, 8, 64, 4830, 'images/vivo-v25-pro.png'),
('Oppo A78', 2999000, 8, 50, 5000, 'images/oppo-a78.png'),
('Asus Zenfone 9', 9999000, 8, 50, 4300, 'images/asus-zenfone-9.png'),
('Google Pixel 7a', 5999000, 8, 64, 4385, 'images/google-pixel-7a.png'),
('Sony Xperia 10 IV', 4999000, 6, 12, 5000, 'images/sony-xperia-10-iv.png'),
('Motorola Moto G Power', 3499000, 4, 50, 5000, 'images/motorola-moto-g-power.png'),
('Nokia G50', 2999000, 4, 48, 5000, 'images/nokia-g50.png'),
('Huawei P50 Pro', 12999000, 8, 50, 4360, 'images/huawei-p50-pro.png'),
('LG Velvet', 5999000, 6, 48, 4300, 'images/lg-velvet.png'),
('OnePlus Nord 2', 4999000, 8, 50, 4500, 'images/oneplus-nord-2.png'),
('HTC U20 5G', 3999000, 8, 48, 5000, 'images/htc-u20-5g.png');

-- Tabel cart
CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  quantity INT,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
