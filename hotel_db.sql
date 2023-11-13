-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2023 lúc 07:10 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hotel_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
('EQYJaB96HcaTtxag6J7d', 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
('FnOiHeIe9iNiaY45qTf5', 'admin1', '356a192b7913b04c54574d18c28d46e6395428ab');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `user_id` varchar(20) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `rooms` int(1) NOT NULL,
  `check_in` varchar(10) NOT NULL,
  `check_out` varchar(10) NOT NULL,
  `adults` int(1) NOT NULL,
  `childs` int(1) NOT NULL,
  `room_type` int(11) NOT NULL,
  `status` enum('pending','success','paying','paid') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`user_id`, `booking_id`, `name`, `email`, `number`, `rooms`, `check_in`, `check_out`, `adults`, `childs`, `room_type`, `status`) VALUES
('B0YqfmIMKMfbZExNTvcn', 'BH6Pil9bahUT49KjCYsj', 'Hoang Dinh Manh', 'manhkuma@gmail.com', '1234567890', 5, '2023-11-17', '2023-11-18', 4, 3, 10, 'paid'),
('B0YqfmIMKMfbZExNTvcn', 'Dfofe5Y31RNDpRVtdR7a', 'Hoang Dinh Manh', 'manhkuma@gmail.com', '1234567890', 1, '2023-11-14', '2023-11-15', 1, 0, 0, 'paid');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `number`, `message`) VALUES
('gdxGTn0zQ5NRYL8ZRDhx', 'Hoang Dinh Manh', 'manhkuma@gmail.com', '1231231231', 'ạdhjasdhajksdhaksdh'),
('p9lbpqvRY1z1aFWOK295', 'Hoang Dinh Manh', 'manhkuma@gmail.com', '231231231', 'ádklasdkasda');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `booking_id`, `amount`, `payment_date`, `payment_method`) VALUES
('bPd63z0ea2DADYJwzjvh', 'B0YqfmIMKMfbZExNTvcn', 'BH6Pil9bahUT49KjCYsj', 1775.00, '2023-11-11', 'atm-card'),
('mvxIKI97b4WUubAmbte0', 'B0YqfmIMKMfbZExNTvcn', 'Dfofe5Y31RNDpRVtdR7a', 130.00, '2023-11-12', 'cash');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `review` varchar(1000) NOT NULL,
  `image_url` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `name`, `review`, `image_url`) VALUES
('acGitgznXO0pypAD1uU1', 'B0YqfmIMKMfbZExNTvcn', 'Tín - Thái Bình', 'Tôi thật sự ấn tượng với sự trang trí và không gian của khách sạn. Mọi thứ đều đẹp đẽ và sang trọng.\r\n                  Tôi sẽ khuyên bạn bè của tôi đến đây.', 'pic-6.png'),
('O0Wu02b24ZtIH4L7qUBk', 'B0YqfmIMKMfbZExNTvcn', 'Mạnh - Hà Nội 2', 'Không gian phòng ốc thoải mái và rất sạch sẽ. Tôi rất thích bãi biển riêng và dịch vụ thú vị tại hồ\r\n                  bơi.', 'pic-1.png'),
('z5sNWjc1OGO0ckSt9X8h', 'B0YqfmIMKMfbZExNTvcn', 'Mạnh - Bắc Ninh', 'Tôi đã có một kỳ nghỉ tuyệt vời ở khách sạn này. Nhân viên thân thiện và thực đơn đa dạng làm cho tôi\r\n                  cảm thấy hài lòng.', 'pic-2.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `image_url`) VALUES
(0, 'Single Room', 80.00, 'single.jpeg'),
(1, 'Double Room', 150.00, 'double.jpeg'),
(2, 'Twin Room', 160.00, 'twin.jpeg'),
(3, 'Family Room', 200.00, 'family.jpeg'),
(4, 'Suite', 400.00, 'suite.jpeg'),
(5, 'Sea View Room', 200.00, 'sea-view.jpeg'),
(6, 'Garden View Room', 150.00, 'garden-view.jpeg'),
(7, 'Executive Room', 250.00, 'executive.jpeg'),
(8, 'High-floor Room', 200.00, 'high-floor.jpeg'),
(9, 'Connecting Room', 200.00, 'connecting.jpeg'),
(10, 'Special Room', 300.00, 'special.jpeg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `password`) VALUES
('vdppRtdA1byafMbeJQpz', 'manhkuma@gmail.com', 'Hoang Dinh', 'Manh', '356a192b7913b04c54574d18c28d46e6395428ab');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
