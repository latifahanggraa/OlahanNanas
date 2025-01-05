-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Agu 2024 pada 04.53
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjualan`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertProduct` (IN `p_image` TEXT, IN `p_image2` TEXT, IN `p_image3` TEXT, IN `p_title` VARCHAR(50), IN `p_descriptions` VARCHAR(255), IN `p_price` INT, IN `p_categoryId` INT, IN `p_stok` INT)   BEGIN
    INSERT INTO product (image, image2, image3, title, descriptions, price, categoryId, stok)
    VALUES (p_image, p_image2, p_image3, p_title, p_descriptions, p_price, p_categoryId, p_stok);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(12) NOT NULL,
  `roles` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `roles`) VALUES
(1, 'admin', 'admin@gmail.com', '1234', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog`
--

CREATE TABLE `blog` (
  `blogId` int(11) NOT NULL,
  `message` text NOT NULL,
  `time_blog` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `blog`
--

INSERT INTO `blog` (`blogId`, `message`, `time_blog`, `customerId`) VALUES
(1, 'Very Nice Product of Pineapple, made in Purbalingga\r\n\r\n', '2024-08-27 15:49:01', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'makanan'),
(2, 'minuman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chekout`
--

CREATE TABLE `chekout` (
  `order_Id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chekout`
--

INSERT INTO `chekout` (`order_Id`, `product_name`, `price`, `quantity`) VALUES
(28, 'Nanas Madu Cup Besar', 15000, 1),
(29, 'Pie Nanas', 20000, 1),
(30, 'Pie Nanas', 20000, 1),
(31, 'Nanas Madu Cup Besar', 15000, 1),
(32, 'D\'Sruput', 5000, 1);

--
-- Trigger `chekout`
--
DELIMITER $$
CREATE TRIGGER `after_checkout_insert` AFTER INSERT ON `chekout` FOR EACH ROW BEGIN
    -- Mengurangi stok produk berdasarkan product_name yang di-checkout
    UPDATE product 
    SET stok = stok - NEW.quantity
    WHERE title = NEW.product_name;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `comment_blog`
--

CREATE TABLE `comment_blog` (
  `id_comment` int(11) NOT NULL,
  `comment` text NOT NULL,
  `customer_Id` int(11) NOT NULL,
  `blog_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(12) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `address` varchar(256) NOT NULL,
  `image_user` text DEFAULT 'user.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `phone`, `address`, `image_user`) VALUES
(1, 'ahmed', 'ahmed@gmail.com', '1234', '01245459112', 'city nassir', 'user.jpg'),
(6, 'Handi yudha', 'katsudharmawan@gmail.com', 'yudha11', '08956346930', 'JALAN KARANGSUCI', 'user.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `quantity` int(1) NOT NULL DEFAULT 1,
  `customerId` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_manager`
--

CREATE TABLE `order_manager` (
  `order_Id` int(11) NOT NULL,
  `name_customer` varchar(20) NOT NULL,
  `phone_customer` varchar(11) NOT NULL,
  `address_customer` varchar(20) NOT NULL,
  `sumTotal` int(11) NOT NULL,
  `payment_method` enum('pay cash','PayPal','credit card') NOT NULL DEFAULT 'pay cash',
  `order_status` enum('Reviewing','Ready','Delivery','Delivered On') NOT NULL DEFAULT 'Reviewing',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_manager`
--

INSERT INTO `order_manager` (`order_Id`, `name_customer`, `phone_customer`, `address_customer`, `sumTotal`, `payment_method`, `order_status`, `order_date`) VALUES
(28, 'Handi yudha', '08956346930', 'JALAN KARANGSUCI', 15000, 'pay cash', 'Delivered On', '2024-08-28 02:07:34'),
(29, 'Handi yudha', '08956346930', 'JALAN KARANGSUCI', 20000, '', 'Reviewing', '2024-08-28 08:24:18'),
(30, 'Handi yudha', '08956346930', 'JALAN KARANGSUCI', 20000, 'pay cash', 'Reviewing', '2024-08-28 08:32:40'),
(31, 'Handi yudha', '08956346930', 'JALAN KARANGSUCI', 15000, 'pay cash', 'Reviewing', '2024-08-29 02:17:21'),
(32, 'Handi yudha', '08956346930', 'JALAN KARANGSUCI', 5000, 'pay cash', 'Reviewing', '2024-08-29 02:19:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `image2` text NOT NULL,
  `image3` text NOT NULL,
  `title` varchar(50) NOT NULL,
  `descriptions` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`id`, `image`, `image2`, `image3`, `title`, `descriptions`, `price`, `categoryId`, `stok`) VALUES
(1, 'product_1.png', 'product_1.png', 'product_1.png\r\n', 'Nanas Madu Cup Besar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 15000, 2, 99),
(2, 'product_2.png', 'product_2.png', 'product_2.png', 'Pie Nanas', 'Pie nanas ini dibuat menggunakan buah nanas segar dari kebun sendiri, menawarkan rasa yang enak dan lezat. Terbuat dari bahan-bahan pilihan, setiap potong pie menjanjikan cita rasa yang otentik dan berkualitas.', 20000, 1, 77),
(3, 'product_3.png', 'product_3.png', 'product_3.png', 'D\'Sruput', 'Minuman sari buah nanas madu yang diambil langsung dari buah nanas segar berkualitas tinggi dari kebun sendiri. Ini adalah minuman rendah gula tanpa tambahan pemanis buatan, kaya manfaat untuk kesehatan tubuh.', 5000, 2, 29),
(5, 'product_4.png', 'product_4.png', 'product_4.png', 'Nanas Madu Cup Kecil', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 7000, 2, 50);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_product_details`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_product_details` (
`categoryName` varchar(25)
,`id` int(11)
,`title` varchar(50)
,`descriptions` varchar(255)
,`price` int(11)
,`image` text
,`stok` int(11)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `view_product_details`
--
DROP TABLE IF EXISTS `view_product_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_product_details`  AS SELECT `category`.`name` AS `categoryName`, `product`.`id` AS `id`, `product`.`title` AS `title`, `product`.`descriptions` AS `descriptions`, `product`.`price` AS `price`, `product`.`image` AS `image`, `product`.`stok` AS `stok` FROM (`category` join `product` on(`product`.`categoryId` = `category`.`id`)) ORDER BY `product`.`id` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blogId`),
  ADD KEY `customerId` (`customerId`);

--
-- Indeks untuk tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `chekout`
--
ALTER TABLE `chekout`
  ADD KEY `order_Id` (`order_Id`);

--
-- Indeks untuk tabel `comment_blog`
--
ALTER TABLE `comment_blog`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `blog_Id` (`blog_Id`),
  ADD KEY `customer_Id` (`customer_Id`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customerId` (`customerId`),
  ADD KEY `productId` (`productId`);

--
-- Indeks untuk tabel `order_manager`
--
ALTER TABLE `order_manager`
  ADD PRIMARY KEY (`order_Id`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `idx_title` (`title`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `blog`
--
ALTER TABLE `blog`
  MODIFY `blogId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `comment_blog`
--
ALTER TABLE `comment_blog`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `order_manager`
--
ALTER TABLE `order_manager`
  MODIFY `order_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chekout`
--
ALTER TABLE `chekout`
  ADD CONSTRAINT `chekout_ibfk_1` FOREIGN KEY (`order_Id`) REFERENCES `order_manager` (`order_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `comment_blog`
--
ALTER TABLE `comment_blog`
  ADD CONSTRAINT `comment_blog_ibfk_1` FOREIGN KEY (`customer_Id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_blog_ibfk_2` FOREIGN KEY (`blog_Id`) REFERENCES `blog` (`blogId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
