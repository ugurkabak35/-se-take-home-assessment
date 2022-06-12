-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 Haz 2022, 22:55:01
-- Sunucu sürümü: 10.4.24-MariaDB
-- PHP Sürümü: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Veritabanı: `ideasoft`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `basket_products`
--

CREATE TABLE `basket_products` (
  `id` int(11) NOT NULL,
  `active_basket_id` int(11) NOT NULL,
  `user_token` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL COMMENT 'products table',
  `category_id` int(11) NOT NULL COMMENT 'categories table',
  `price` double NOT NULL,
  `add_time` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `basket_products`
--

INSERT INTO `basket_products` (`id`, `active_basket_id`, `user_token`, `product_id`, `category_id`, `price`, `add_time`, `status`) VALUES
(1, 1, 'OL7c31YGKUgDtyv3', 1, 1, 8299, '2022-06-12 18:21:13', 1),
(2, 1, 'OL7c31YGKUgDtyv3', 1, 1, 8299, '2022-06-12 18:21:21', 1),
(3, 1, 'OL7c31YGKUgDtyv3', 1, 1, 8299, '2022-06-12 18:21:22', 1),
(4, 1, 'OL7c31YGKUgDtyv3', 1, 1, 8299, '2022-06-12 18:21:22', 1),
(20, 1, 'OL7c31YGKUgDtyv3', 1, 1, 8299, '2022-06-12 21:52:22', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `add_time` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `add_time`, `status`) VALUES
(1, 'Elektronik', '2022-06-09 16:15:06', 1),
(2, 'Giyim', '2022-06-09 16:15:06', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT 'categories table',
  `name` varchar(500) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` double NOT NULL,
  `image` varchar(255) NOT NULL,
  `add_time` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `stock`, `price`, `image`, `add_time`, `status`) VALUES
(1, 1, 'Huawei Matebook D15 AMD Ryzen 5 5500U 8GB 512GB SSD Windows 11 Home 15.6\" FHD Dizüstü Bilgisayar MBOOKD15R5', 10, 8299, 'https://via.placeholder.com/150', '2022-06-09 16:19:55', 1),
(2, 1, 'Apple iPad 9. Nesil 64GB 10.2 inç Wİ-Fİ Tablet - Gümüş Mk2l3tu/a - Apple Türkiye Garantili MK2L3TU/A', 10, 6099, 'https://via.placeholder.com/150', '2022-06-09 16:19:55', 1),
(3, 1, 'Monster Abra A5 V18.1.3 Intel Core I7 ', 7, 16449.06, 'https://via.placeholder.com/150', '2022-06-09 16:21:59', 1),
(4, 2, 'Mavi Pro Logo Baskılı Beyaz Tişört Loose Fit ', 12, 150, 'https://via.placeholder.com/150', '2022-06-09 16:21:59', 1),
(5, 2, 'Givenchy Erkek Siyah T-shirt', 3, 1500, 'https://via.placeholder.com/150', '2022-06-09 16:24:28', 1),
(6, 2, 'Versace T-shirt ', 15, 750, 'https://via.placeholder.com/150', '2022-06-09 16:24:28', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_token` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `revenue` double NOT NULL,
  `add_time` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `user_token`, `name`, `email`, `password`, `revenue`, `add_time`) VALUES
(1, 'OL7c31YGKUgDtyv3', 'Uğur Kabak', 'ugurkabak348@gmail.com', '3980e4a8437784304b35d88d61ac8a7c', 2715.56, '2022-06-09'),
(2, 'tmOtOfZzi1zYIqeu', 'Türker Jöntürk', '', '', 158.75, '2022-06-09'),
(3, 'VuTtDIy1NtGKOeez', 'Kaptan Devopuz', '', '', 1505.95, '2022-06-09'),
(4, 'VuTtDIy1NtGKOeez', 'İsa Sonuyumaz', '', '', 0, '2022-06-09');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_basket`
--

CREATE TABLE `user_basket` (
  `id` int(11) NOT NULL,
  `user_token` varchar(255) NOT NULL,
  `discount_total` double NOT NULL DEFAULT 0,
  `add_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '0-beklemede 1-aktif sipariş 2-silinmiş'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `user_basket`
--

INSERT INTO `user_basket` (`id`, `user_token`, `discount_total`, `add_time`, `update_time`, `delete_time`, `status`) VALUES
(1, 'OL7c31YGKUgDtyv3', 0, '2022-06-10 20:07:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `basket_products`
--
ALTER TABLE `basket_products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `user_basket`
--
ALTER TABLE `user_basket`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `basket_products`
--
ALTER TABLE `basket_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `user_basket`
--
ALTER TABLE `user_basket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
