-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 15, 2025 at 02:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bot_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `examples`
--

CREATE TABLE `examples` (
  `id` int(11) NOT NULL,
  `intent_id` int(11) DEFAULT NULL,
  `example` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examples`
--

INSERT INTO `examples` (`id`, `intent_id`, `example`) VALUES
(31, 3, 'edwe'),
(32, 3, 'wewe'),
(33, 3, 'wew'),
(34, 3, 'wew'),
(35, 3, 'ewe'),
(36, 3, 'ewe'),
(37, 3, 'ewe'),
(38, 3, 'ee'),
(39, 3, 'wew'),
(40, 3, 'wqw'),
(61, 6, 'sw'),
(62, 6, 'we'),
(63, 6, 'ewe'),
(64, 6, 'ewe'),
(65, 6, 'sxsx'),
(66, 6, 'ssa'),
(67, 6, 'xsx'),
(68, 6, 'sasa'),
(69, 6, 'asaqq'),
(70, 6, 'qsqs'),
(281, 16, 'jerald'),
(282, 16, 'jerald'),
(283, 16, 'jerald'),
(284, 16, 'hi'),
(285, 16, 'hello'),
(286, 16, 'hi'),
(287, 16, 'hello'),
(288, 16, 'hi'),
(289, 16, 'hello how are you'),
(290, 16, 'hi hello'),
(291, 17, 'anyeong'),
(292, 17, 'anyeong'),
(293, 17, 'anyeong'),
(294, 17, 'anyeong'),
(295, 17, 'anyeong'),
(296, 17, 'anyeong'),
(297, 17, 'anyeong'),
(298, 17, 'anyeong'),
(299, 17, 'anyeong'),
(300, 17, 'anyeong');

-- --------------------------------------------------------

--
-- Table structure for table `intents`
--

CREATE TABLE `intents` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `office_in_charge` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `intents`
--

INSERT INTO `intents` (`id`, `name`, `office_in_charge`) VALUES
(3, 'sw11', 'admin'),
(6, 'qqqw', 'admin'),
(16, 'hiii', 'jerald'),
(17, 'init', 'jerald');

-- --------------------------------------------------------

--
-- Table structure for table `intent_logs`
--

CREATE TABLE `intent_logs` (
  `id` int(11) NOT NULL,
  `intent` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `intent_logs`
--

INSERT INTO `intent_logs` (`id`, `intent`, `message`, `timestamp`) VALUES
(63, 'hiii', 'Jerald', '2025-08-14 13:19:38'),
(64, 'init', 'anyeong', '2025-08-14 14:23:01'),
(65, 'hiii', 'jerald', '2025-08-14 14:23:13'),
(66, 'hiii', 'jerald', '2025-08-14 14:25:15'),
(67, 'init', 'anyeong', '2025-08-14 14:25:20'),
(68, 'init', 'anyeong', '2025-08-14 14:30:36'),
(69, 'hiii', '/hiii', '2025-08-14 14:32:51');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `id` int(11) NOT NULL,
  `rule_name` varchar(255) DEFAULT NULL,
  `intent` varchar(255) DEFAULT NULL,
  `utter_name` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`id`, `rule_name`, `intent`, `utter_name`, `created_by`) VALUES
(47, 'hii', 'hiii', '[\"utter_hii\"]', 'jerald'),
(49, 'weeee', 'init', '[\"utter_heheheh\"]', 'jerald');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(4, 'jerald', '$2y$10$MCvS/uxuefO36E4ZY.YmleW88K9uCcSh/W/MlATp9/SC5u5H2jqyq'),
(5, 'admin', '$2y$10$40bf83tceowowMm7VghDeuhnJazJXZWgCCaHnmC7tBVWkyski9LZ6'),
(6, 'romelyn', '$2y$10$FaqX.VqQ31GzG4X7eHgtguU.hLzUTvo6hzyvLfEUwu.tLI/.NajAa'),
(7, 'axellene', '$2y$10$cdC.ALe2ovSwaDAMAVlB1uXgbcu2xOZzVNGG.QVXHiKXIX.tikOy.'),
(8, 'zuchini', '$2y$10$VexDc9zLjPt0lFWXqP/65eOemNO8jvCYjakmXeg6g4zPfGZFV9LaG');

-- --------------------------------------------------------

--
-- Table structure for table `utters`
--

CREATE TABLE `utters` (
  `id` int(11) NOT NULL,
  `utter_name` varchar(255) DEFAULT NULL,
  `type` enum('text','image','button','card') DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utters`
--

INSERT INTO `utters` (`id`, `utter_name`, `type`, `content`, `created_by`) VALUES
(32, 'utter_jerald', 'button', '{\"text\":\"sure?\",\"buttons\":[{\"title\":\"yes\",\"payload\":\"\\/hiii\"}]}', 'jerald'),
(33, 'utter_hii', 'text', 'hiii', 'jerald'),
(34, 'utter_button', 'button', '{\"text\":\"ded\",\"buttons\":[{\"title\":\"dsdd\",\"payload\":\"\\/sw11\"}]}', 'admin'),
(35, 'utter_heheheh', 'card', '{\"title\":\"hello\",\"subtitle\":\"hi\",\"image_url\":\"https:\\/\\/scontent.fmnl33-3.fna.fbcdn.net\\/v\\/t39.30808-1\\/517116303_1437617094357691_1766914225845436015_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=105&ccb=1-7&_nc_sid=1d2534&_nc_eui2=AeFwHUdPUbPJdMkuAHGLdqC3101nl57JwM3XTWeXnsnAzcYB5fjIYRqish33bvlkjPDXUhY48LdugGYLrERIHuYB&_nc_ohc=x3ZSJEzKvNYQ7kNvwGhBQwK&_nc_oc=AdlHvbbYG-whp5hcqqW6TwHIxRoYlE2TO3K1lrugu7nIPYdzlJw_yrsbieFmKSVcgbw&_nc_zt=24&_nc_ht=scontent.fmnl33-3.fna&_nc_gid=sdgPef8L3s8KVG4AsGl-IA&oh=00_AfWnaKI7y6JRNX_tsRMDbfHcTECkBKuk0e2SgxcuysM-fQ&oe=68A3D87E\",\"buttons\":[{\"title\":\"wahaha\",\"payload\":\"\\/hiii\"}]}', 'jerald'),
(36, 'utter_img', 'image', '{\"text\":\"img\",\"image\":\"https:\\/\\/scontent.fmnl33-3.fna.fbcdn.net\\/v\\/t39.30808-1\\/517116303_1437617094357691_1766914225845436015_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=105&ccb=1-7&_nc_sid=1d2534&_nc_eui2=AeFwHUdPUbPJdMkuAHGLdqC3101nl57JwM3XTWeXnsnAzcYB5fjIYRqish33bvlkjPDXUhY48LdugGYLrERIHuYB&_nc_ohc=x3ZSJEzKvNYQ7kNvwGhBQwK&_nc_oc=AdlHvbbYG-whp5hcqqW6TwHIxRoYlE2TO3K1lrugu7nIPYdzlJw_yrsbieFmKSVcgbw&_nc_zt=24&_nc_ht=scontent.fmnl33-3.fna&_nc_gid=rbkz6s_ID7DKHDh2wKmI1g&oh=00_AfUwFOsdCHYukcD2XyXfWLmmYa-fulHVx7rr7eedBhC_QQ&oe=68A3D87E\"}', 'jerald');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `examples`
--
ALTER TABLE `examples`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intent_id` (`intent_id`);

--
-- Indexes for table `intents`
--
ALTER TABLE `intents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intent_logs`
--
ALTER TABLE `intent_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `utters`
--
ALTER TABLE `utters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `examples`
--
ALTER TABLE `examples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `intents`
--
ALTER TABLE `intents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `intent_logs`
--
ALTER TABLE `intent_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `utters`
--
ALTER TABLE `utters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `examples`
--
ALTER TABLE `examples`
  ADD CONSTRAINT `examples_ibfk_1` FOREIGN KEY (`intent_id`) REFERENCES `intents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
