-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 02:10 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `order_number` varchar(10) NOT NULL,
  `nama_customer` varchar(255) DEFAULT NULL,
  `order_type` varchar(50) DEFAULT NULL,
  `subtotal` int(20) DEFAULT NULL,
  `tax` int(20) DEFAULT NULL,
  `total` int(20) DEFAULT NULL,
  `order_date` date NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `order_number`, `nama_customer`, `order_type`, `subtotal`, `tax`, `total`, `order_date`, `deleted_at`) VALUES
(15, 'ORD0001', 'dadaa', 'dine in', 15000, 1500, 16500, '2025-01-04', NULL),
(16, 'ORD0002', 'dadaa', 'dine in', 15000, 1500, 16500, '2025-01-04', NULL),
(17, 'ORD0003', 'yoga', 'take away', 46000, 4600, 50600, '2025-01-04', NULL),
(18, 'ORD0004', 'asfa', 'dine in', 30000, 3000, 33000, '2025-01-04', NULL),
(19, 'ORD0005', 'Lucky', 'dine in', 27500, 2750, 30250, '2025-01-05', NULL),
(20, 'ORD0006', 'Louie', 'take away', 21000, 2100, 23100, '2025-01-05', NULL),
(21, 'ORD0007', 'Lily', 'take away', 15000, 1500, 16500, '2025-01-05', NULL),
(22, 'ORD0008', 'Sasa', 'take away', 38000, 3800, 41800, '2025-01-05', NULL),
(23, 'ORD0009', 'Abil', 'delivery', 42000, 4200, 46200, '2025-01-05', NULL),
(25, 'ORD0010', 'Qori', 'take away', 25000, 2500, 27500, '2025-01-05', NULL),
(26, 'ORD0011', 'Doni', 'take away', 43000, 4300, 47300, '2025-01-05', '2025-01-05 19:40:06'),
(27, 'ORD0012', 'Asfanissa', 'take away', 22500, 2250, 24750, '2025-01-05', '2025-01-05 19:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cafe`
--

CREATE TABLE `tbl_cafe` (
  `id_menu` int(100) NOT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `harga` int(100) NOT NULL,
  `gambar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_cafe`
--

INSERT INTO `tbl_cafe` (`id_menu`, `nama_menu`, `kategori`, `harga`, `gambar`) VALUES
(27, 'Americanno', 'minuman', 10000, 'americano.jpg'),
(29, 'Croissant', 'makanan', 15000, 'pexels-elkady-3892469.jpg'),
(30, 'Green Tea', 'minuman', 12500, 'greentea.jpg'),
(31, 'Cocktail', 'minuman', 20000, 'koktail.jpg'),
(32, 'Chocolate Cupcake', 'makanan', 17500, 'Chocolate cupcake.jpg'),
(33, 'Fruite Cupcake', 'makanan', 18000, 'Fruite cupcake.jpg'),
(34, 'ChocoChip Cookies', 'makanan', 10000, 'copkie.jpg'),
(35, 'Donat', 'makanan', 6000, 'donat.jpg'),
(36, 'Cinnamon Roll', 'makanan', 13000, 'cinnamon roll.jpg'),
(37, 'Strawberry Juice', 'minuman', 17000, 'strawberry juice.jpg'),
(38, 'Orange Juice', 'minuman', 8000, 'orange juice.jpg'),
(39, 'Chocolate Milkshake', 'minuman', 15000, 'coklat milkshae.jpg'),
(40, 'Air Mineral', 'minuman', 5000, 'air mineral.jpg'),
(41, 'Macaroon', 'makanan', 12000, 'macaron.jpg'),
(42, 'Pancake', 'makanan', 8000, 'pexels-hissetmehurriyeti-47135946-17165552.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Indexes for table `tbl_cafe`
--
ALTER TABLE `tbl_cafe`
  ADD PRIMARY KEY (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_cafe`
--
ALTER TABLE `tbl_cafe`
  MODIFY `id_menu` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
