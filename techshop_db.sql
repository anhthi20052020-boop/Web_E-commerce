-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 25, 2025 lúc 05:59 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `techshop_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `about_us`
--
create database techshop_db;
use techshop_db;
CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `about_us`
--

INSERT INTO `about_us` (`id`, `image_url`, `description`) VALUES
(1, '../uploads/about.jpg', 'ATAIT là điểm đến lý tưởng cho sản phẩm công nghệ chất lượng và dịch vụ kỹ thuật chuyên nghiệp. Chúng tôi cung cấp giải pháp nhanh chóng, hiệu quả cho mọi nhu cầu công nghệ của bạn.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$fm3lfgGcX7Og7sDy04aLZO/2ytWyeB4B9PWmHhP4kBU/pe9KSZ4lK', '2025-05-22 20:15:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `video_url`, `title`, `subtitle`, `description`, `product_id`) VALUES
(1, '../uploads/vid_home_1.mp4', 'Samsung', 'PC', 'Mạnh mẽ và hiện đại với công nghệ tiên tiến', 1),
(2, '../uploads/vid_home_2.mp4', 'Apple', 'iPhone 16', 'Trải nghiệm mới với iOS thế hệ tiếp theo', 2),
(3, '../uploads/vid_home_3.mp4', 'Samsung', 'Galaxy', 'Thiết kế đẹp, hiệu suất cao', 3),
(4, '../uploads/vid_home_4.mp4', 'Oppo', 'Zeno', 'Chụp ảnh cực đẹp, pin lâu', 4),
(5, '../uploads/vid_home_5.mp4', 'Apple', 'Mac', 'Làm việc chuyên nghiệp, thiết kế tinh tế', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Na Na', 'linhdantruong201@gmail.com', 'kingkhung\r\n', '2025-05-21 21:18:26'),
(2, 'Na Na', 'linhdantruong201@gmail.com', 'kingkhung\r\n', '2025-05-21 21:18:26'),
(3, 'Na Na', 'phamanthuy478@gmail.com', 'tôi muốn đổi sản phẩm khác', '2025-05-24 19:52:27'),
(4, 'Na Na', 'phamanthuy478@gmail.com', 'tôi muốn đổi sản phẩm khác', '2025-05-24 19:52:27'),
(5, 'An', 'linhdantruong201@gmail.com', 'tôi muốn đỏi sản phẩm khác', '2025-05-24 19:54:21'),
(6, 'An', 'linhdantruong201@gmail.com', 'tôi muốn đỏi sản phẩm khác', '2025-05-24 19:54:21'),
(7, 'user1', 'linhdantruong201@gmail.com', 'HIHU', '2025-05-24 22:41:48'),
(8, 'user1', 'linhdantruong201@gmail.com', 'HIHU', '2025-05-24 22:41:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `image_url`, `name`, `comment`) VALUES
(1, '../uploads/user.jpg', 'Nguyễn Văn A', 'Sản phẩm chất lượng, giao hàng nhanh!'),
(2, '../uploads/user.jpg', 'Trần Thị B', 'Dịch vụ hỗ trợ rất tận tình và nhanh chóng.'),
(3, '../uploads/user.jpg', 'Lê Văn C', 'Tôi rất hài lòng với trải nghiệm mua hàng tại đây.'),
(4, '../uploads/user.jpg', 'Dương Văn D', 'sốp 10đ khum có nhưng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) NOT NULL,
  `shipping_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `payment_method`, `shipping_address`, `notes`, `created_at`) VALUES
(1, 1, 53980000.00, 'processing', 'cod', 'xxx,xxx,xxx,q10', '', '2025-05-23 16:13:54'),
(2, 1, 99999999.99, 'delivered', 'momo', 'p1, q1, thpcm', '', '2025-05-23 17:12:18'),
(3, 1, 105960000.00, 'shipped', 'credit_card', 'pmt street, district 1, hcmc', '', '2025-05-23 17:24:16'),
(4, 1, 20990000.00, 'pending', 'credit_card', 'q7,hcm', '', '2025-05-23 17:42:52'),
(5, 1, 63980000.00, 'pending', 'momo', 'hi,hi,hi', '', '2025-05-23 19:24:11'),
(6, 2, 50980000.00, 'shipped', 'cod', 'nguyen huu tho, tan phong, q7', '', '2025-05-24 12:45:47'),
(7, 1, 53980000.00, 'pending', 'bank_transfer', 'CHB NSBCJZ JSBCJ', '', '2025-05-24 15:40:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 1, '6', 'Asus ROG Zephyrus', 32990000.00, 1),
(2, 1, 'Xiaomi 14 Pro-20990000', 'Xiaomi 14 Pro', 20990000.00, 1),
(3, 2, 'iPad Air 2024-17990000', 'iPad Air 2024', 17990000.00, 2),
(4, 2, 'Canon EOS R6-39990000', 'Canon EOS R6', 39990000.00, 1),
(5, 2, 'Samsung Galaxy S24 Ultra-29990000', 'Samsung Galaxy S24 Ultra', 29990000.00, 1),
(6, 3, 'iPad Air 2024-17990000', 'iPad Air 2024', 17990000.00, 2),
(7, 3, 'Canon EOS R6-39990000', 'Canon EOS R6', 39990000.00, 1),
(8, 3, 'Samsung Galaxy S24 Ultra-29990000', 'Samsung Galaxy S24 Ultra', 29990000.00, 1),
(9, 4, 'Xiaomi 14 Pro-20990000', 'Xiaomi 14 Pro', 20990000.00, 1),
(10, 5, '10', 'iPad Air 2024', 17990000.00, 1),
(11, 5, 'MacBook Pro M3-45990000', 'MacBook Pro M3', 45990000.00, 1),
(12, 6, '2', 'Samsung Galaxy S24 Ultra', 29990000.00, 1),
(13, 6, 'Xiaomi 14 Pro-20990000', 'Xiaomi 14 Pro', 20990000.00, 1),
(14, 7, '6', 'Asus ROG Zephyrus', 32990000.00, 1),
(15, 7, 'Xiaomi 14 Pro-20990000', 'Xiaomi 14 Pro', 20990000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `likes_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `author`, `content`, `image`, `category`, `created_at`, `likes_count`) VALUES
(1, 'iPhone 15 Launches with Revolutionary Design', 'TechNews', 'Apple officially unveils iPhone 15 featuring titanium frame and first-ever USB-C port', '../uploads/about.jpg', 'hot-news', '2023-09-15 10:00:00', 0),
(2, '5 Battery Saving Tips for Your Smartphone', 'MobileExpert', 'Simple tricks to extend your phone battery life significantly', '../uploads/about.jpg', 'tips-and-trends', '2023-10-02 14:30:00', 0),
(3, 'Google Unveils Pixel 8 with Advanced AI Features', 'AndroidWorld', 'Pixel 8 series introduces groundbreaking AI capabilities including Magic Editor and enhanced Call Screen', '../uploads/about.jpg', 'hot-news', '2023-10-04 09:15:00', 0),
(4, 'How to Secure Your Online Accounts Effectively', 'CyberSecurity', 'Complete guide to setting up 2FA and managing passwords securely', '../uploads/about.jpg', 'tips-and-trends', '2023-09-20 16:45:00', 0),
(5, 'Samsung Introduces Galaxy S23 Ultra with 200MP Camera', 'TechInsider', 'The new flagship boasts unprecedented camera capabilities and S Pen integration', '../uploads/about.jpg', 'hot-news', '2023-02-01 11:20:00', 0),
(6, '10 Essential Keyboard Shortcuts for Productivity', 'PCPro', 'Master these time-saving shortcuts to work faster on Windows and Mac', '../uploads/about.jpg', 'tips-and-trends', '2023-08-15 13:10:00', 0),
(7, 'Microsoft Announces Windows 12 Coming Next Year', 'DigitalTrends', 'Next generation Windows promises AI integration and revamped interface', '../uploads/about.jpg', 'hot-news', '2023-10-10 08:30:00', 0),
(8, 'Best Free Antivirus Software for 2023', 'SecurityWatch', 'Top-rated free antivirus solutions to protect your devices', '../uploads/about.jpg', 'tips-and-trends', '2023-09-05 10:15:00', 0),
(9, 'Tesla Reveals New Cybertruck Production Model', 'FutureTech', 'Final production version features updated design and improved specs', '../uploads/about.jpg', 'hot-news', '2023-10-05 14:00:00', 0),
(10, 'How to Clean Your Laptop Safely', 'GadgetCare', 'Step-by-step guide to maintaining your laptop without causing damage', '../uploads/about.jpg', 'tips-and-trends', '2023-08-25 15:45:00', 0),
(11, 'Meta Launches Next-Gen VR Headset Quest 3', 'VirtualReality', 'New standalone VR device offers mixed reality capabilities', '../uploads/about.jpg', 'hot-news', '2023-09-27 12:30:00', 0),
(12, 'Essential Apps for Remote Workers in 2023', 'WorkFromHome', 'Must-have tools for productivity and communication while working remotely', '../uploads/about.jpg', 'tips-and-trends', '2023-09-10 11:20:00', 0),
(13, 'NVIDIA Announces RTX 5000 Series Graphics Cards', 'GameTech', 'Next-generation GPUs promise groundbreaking performance for gamers and creators', '../uploads/about.jpg', 'hot-news', '2023-10-12 10:00:00', 0),
(14, 'How to Speed Up Your Old Computer', 'TechSavvy', 'Proven methods to revive aging hardware and improve performance', '../uploads/about.jpg', 'tips-and-trends', '2023-08-30 14:15:00', 0),
(15, 'Amazon Unveils New Kindle Paperwhite Signature', 'eBookLover', 'Premium e-reader now features wireless charging and auto-adjusting light', '../uploads/about.jpg', 'hot-news', '2023-09-18 09:45:00', 0),
(16, 'Top 5 Programming Languages to Learn in 2024', 'CodeMaster', 'The most valuable coding skills for the coming year', '../uploads/about.jpg', 'tips-and-trends', '2023-10-08 16:30:00', 0),
(17, 'Sony Launches PlayStation 5 Slim Model', 'GameOn', 'More compact version of the popular console maintains full performance', '../uploads/about.jpg', 'hot-news', '2023-10-15 11:00:00', 0),
(18, 'How to Protect Your Privacy on Social Media', 'DigitalPrivacy', 'Critical settings to adjust for better online privacy protection', '../uploads/about.jpg', 'tips-and-trends', '2023-09-22 13:25:00', 0),
(19, 'Apple Announces M3 Chip with 3nm Architecture', 'MacWorld', '<p><strong>Next-generation</strong> silicon promises major performance and efficiency gains</p>\r\n', '../uploads/about.jpg', 'hot-news', '2023-10-18 10:45:00', 0),
(20, 'Beginner Guide to Smart Home Setup', 'HomeTech', 'Everything you need to know to start automating your home', '../uploads/about.jpg', 'tips-and-trends', '2023-09-28 15:00:00', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_hot` tinyint(1) DEFAULT 0,
  `is_bestseller` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `original_price`, `image`, `images`, `description`, `stock`, `category`, `brand`, `created_at`, `is_hot`, `is_bestseller`) VALUES
(1, 'iPhone 15 Plus', 34990000.00, 39990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', 'Điện thoại Iphone sang trọng, hiệu suất cao, dung lượng và pin lớn', 50, 'Smartphones', 'Apple', '2025-05-22 20:46:03', 1, 1),
(2, 'Samsung Galaxy S24 Ultra', 29990000.00, 33990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 50, 'Smartphones', 'Samsung', '2025-05-22 20:46:03', 1, 1),
(3, 'Xiaomi 14 Pro', 20990000.00, 23990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 50, 'Smartphones', 'Xiaomi', '2025-05-22 20:46:03', 0, 0),
(4, 'MacBook Pro M3', 45990000.00, 49990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 30, 'Laptops', 'Apple', '2025-05-22 20:46:03', 0, 0),
(5, 'Dell XPS 13', 35990000.00, 39990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 30, 'Laptops', 'Dell', '2025-05-22 20:46:03', 0, 0),
(6, 'Asus ROG Zephyrus', 32990000.00, 36990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 25, 'Laptops', 'Asus', '2025-05-22 20:46:03', 1, 0),
(7, 'HP Envy x360', 18990000.00, 21990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 25, 'Laptops', 'HP', '2025-05-22 20:46:03', 0, 0),
(8, 'Canon EOS R6', 39990000.00, 42990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 10, 'Cameras', 'Canon', '2025-05-22 20:46:03', 0, 0),
(9, 'Sony A7 III', 38990000.00, 41990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 10, 'Cameras', 'Sony', '2025-05-22 20:46:03', 0, 0),
(10, 'iPad Air 2024', 17990000.00, 19990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 40, 'Tablets', 'Apple', '2025-05-22 20:46:03', 1, 0),
(11, 'Samsung Galaxy Tab S9', 15990000.00, 18990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 40, 'Tablets', 'Samsung', '2025-05-22 20:46:03', 0, 0),
(12, 'Huawei MatePad Pro', 13990000.00, 16990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 35, 'Tablets', 'Huawei', '2025-05-22 20:46:03', 0, 1),
(13, 'Apple Watch Series 9', 11990000.00, 13990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 50, 'Smartwatch', 'Apple', '2025-05-22 20:46:03', 0, 1),
(14, 'Samsung Galaxy Watch 6', 8990000.00, 10990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 50, 'Smartwatch', 'Samsung', '2025-05-22 20:46:03', 0, 0),
(15, 'MSI Stealth 15M', 29990000.00, 34990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]\\', NULL, 20, 'Laptops', 'MSI', '2025-05-22 20:46:03', 0, 0),
(16, 'Lenovo Legion 5', 26990000.00, 29990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]\\', NULL, 25, 'Laptops', 'Lenovo', '2025-05-22 20:46:03', 0, 0),
(17, 'Oppo Reno 11', 10990000.00, 12990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 40, 'Smartphones', 'Oppo', '2025-05-22 20:46:03', 0, 0),
(18, 'Vivo X90', 14990000.00, 17990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 35, 'Smartphones', 'Vivo', '2025-05-22 20:46:03', 0, 1),
(19, 'Canon Powershot G7X', 18990000.00, 20990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 10, 'Cameras', 'Canon', '2025-05-22 20:46:03', 0, 0),
(20, 'Sony ZV-E10', 20990000.00, 23990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 10, 'Cameras', 'Sony', '2025-05-22 20:46:03', 0, 0),
(21, 'AirPods Pro 2', 5990000.00, 6990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 60, 'Accessories', 'Apple', '2025-05-22 20:46:03', 1, 0),
(22, 'Samsung Galaxy Buds2 Pro', 3990000.00, 4990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]\\', NULL, 60, 'Accessories', 'Samsung', '2025-05-22 20:46:03', 0, 1),
(23, 'Logitech MX Master 3S', 2690000.00, 2990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 40, 'Accessories', 'Logitech', '2025-05-22 20:46:03', 1, 0),
(24, 'Apple Magic Keyboard', 3890000.00, 4290000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]\\', NULL, 40, 'Accessories', 'Apple', '2025-05-22 20:46:03', 0, 0),
(25, 'Asus TUF Gaming F15', 23990000.00, 26990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 20, 'Laptops', 'Asus', '2025-05-22 20:46:03', 0, 0),
(26, 'HP Victus 16', 21990000.00, 24990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 20, 'Laptops', 'HP', '2025-05-22 20:46:03', 0, 0),
(27, 'Samsung Galaxy Z Flip5', 26990000.00, 29990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 30, 'Smartphones', 'Samsung', '2025-05-22 20:46:03', 0, 0),
(28, 'iPhone 14', 20990000.00, 22990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 35, 'Smartphones', 'Apple', '2025-05-22 20:46:03', 0, 0),
(29, 'Oppo A78', 6490000.00, 7990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 40, 'Smartphones', 'Oppo', '2025-05-22 20:46:03', 0, 0),
(30, 'Vivo Y100', 6990000.00, 8490000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 35, 'Smartphones', 'Vivo', '2025-05-22 20:46:03', 0, 0),
(31, 'Sony WH-1000XM5', 7990000.00, 8990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 20, 'Accessories', 'Sony', '2025-05-22 20:46:03', 0, 0),
(32, 'Canon EOS M50', 20990000.00, 23990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 15, 'Cameras', 'Canon', '2025-05-22 20:46:03', 0, 0),
(33, 'Apple Mac Mini M2', 15990000.00, 17990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 10, 'PCs', 'Apple', '2025-05-22 20:46:03', 0, 0),
(34, 'Dell Inspiron Desktop', 13990000.00, 15990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 10, 'PCs', 'Dell', '2025-05-22 20:46:03', 0, 0),
(35, 'Samsung Smart Monitor M8', 11990000.00, 13990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 20, 'Accessories', 'Samsung', '2025-05-22 20:46:03', 0, 0),
(36, 'Asus ZenBook 14X', 26990000.00, 29990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 20, 'Laptops', 'Asus', '2025-05-22 20:46:03', 0, 0),
(37, 'Apple iMac M3', 34990000.00, 37990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 8, 'PCs', 'Apple', '2025-05-22 20:46:03', 0, 0),
(38, 'Vivo Watch 3', 5990000.00, 6990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 15, 'Smartwatch', 'Vivo', '2025-05-22 20:46:03', 0, 0),
(39, 'Oppo Watch X', 6990000.00, 7990000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 15, 'Smartwatch', 'Oppo', '2025-05-22 20:46:03', 0, 0),
(40, 'Samsung SIM Nano Kit', 99000.00, 199000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', NULL, 100, 'Sim', 'Samsung', '2025-05-22 20:46:03', 0, 0),
(41, 'Iphone 14', 16990000.00, 24590000.00, '../uploads/img2.png', '[\"../uploads/img2.png\",\"../uploads/img1.png\",\"../uploads/img3.png\",\"../uploads/img4.png\"]', 'Đẹp, sang trọng', 20, 'Smartphones', 'Apple', '2025-05-23 11:53:45', 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cart` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `created_at`, `cart`) VALUES
(1, 'user1', 'linhdantruong201@gmail.com', '$2y$10$3iSyQrJA7alr25Kw7Q7BWOJLoV9Qn1jD3sIif3.lnaLSLIMpJAaOu', NULL, NULL, NULL, '2025-05-23 04:57:46', '[{\"id\":\"6\",\"name\":\"Asus ROG Zephyrus\",\"image\":\"http:\\/\\/localhost\\/52300173_52300157\\/uploads\\/img2.png\",\"star\":5,\"price\":32990000,\"quantity\":2},{\"id\":\"MacBook Pro M3-45990000\",\"name\":\"MacBook Pro M3\",\"image\":\"http:\\/\\/localhost\\/52300173_52300157\\/uploads\\/img2.png\",\"star\":5,\"price\":45990000,\"quantity\":1},{\"id\":\"Xiaomi 14 Pro-20990000\",\"name\":\"Xiaomi 14 Pro\",\"image\":\"http:\\/\\/localhost\\/52300173_52300157\\/uploads\\/img2.png\",\"star\":5,\"price\":20990000,\"quantity\":1}]'),
(2, 'user2', 'phamanthuy478@gmail.com', '$2y$10$Fpntm9aYv5/qtfHJajxbS.0KWkZfuc6CNq9TvlhAYg4BqJQ.SojJC', NULL, NULL, NULL, '2025-05-24 07:42:58', '[{\"id\":\"6\",\"name\":\"Asus ROG Zephyrus\",\"image\":\"http:\\/\\/localhost\\/52300173_52300157\\/uploads\\/img2.png\",\"star\":5,\"price\":32990000,\"quantity\":2},{\"id\":\"MacBook Pro M3-45990000\",\"name\":\"MacBook Pro M3\",\"image\":\"http:\\/\\/localhost\\/52300173_52300157\\/uploads\\/img2.png\",\"star\":5,\"price\":45990000,\"quantity\":1},{\"id\":\"Xiaomi 14 Pro-20990000\",\"name\":\"Xiaomi 14 Pro\",\"image\":\"http:\\/\\/localhost\\/52300173_52300157\\/uploads\\/img2.png\",\"star\":5,\"price\":20990000,\"quantity\":1}]');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
