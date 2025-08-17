-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 11:57 PM
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
-- Database: `inquiry`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatsessions`
--

CREATE TABLE `chatsessions` (
  `session_id` int(11) NOT NULL,
  `session_desc` varchar(255) NOT NULL,
  `session_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatsessions`
--

INSERT INTO `chatsessions` (`session_id`, `session_desc`, `session_time`) VALUES
(1, 'User has started the chat', '2025-05-02 04:45:55'),
(2, 'User has left the chat', '2025-05-02 04:46:08'),
(3, 'User has started the chat', '2025-05-02 04:53:32'),
(4, 'User has left the chat', '2025-05-02 04:53:44'),
(5, 'User has started the chat', '2025-05-03 09:22:52'),
(6, 'User has started the chat', '2025-05-04 02:45:17'),
(7, 'User has started the chat', '2025-05-04 21:18:10'),
(8, 'User has started the chat', '2025-05-04 21:24:13'),
(9, 'User has started the chat', '2025-05-04 21:29:48'),
(10, 'User has started the chat', '2025-05-04 21:31:08'),
(11, 'User has started the chat', '2025-05-04 21:34:09'),
(12, 'User has started the chat', '2025-05-04 21:53:23'),
(13, 'User has left the chat', '2025-05-04 22:00:21'),
(14, 'User has started the chat', '2025-05-18 00:02:23'),
(15, 'User has started the chat', '2025-05-18 00:21:41'),
(16, 'User has left the chat', '2025-05-18 00:21:51'),
(17, 'User has started the chat', '2025-05-18 00:22:55'),
(18, 'User has started the chat', '2025-05-18 00:26:40'),
(19, 'User has started the chat', '2025-05-18 00:30:22'),
(20, 'User has started the chat', '2025-05-18 00:39:31'),
(21, 'User has started the chat', '2025-05-18 00:50:11'),
(22, 'User has started the chat', '2025-05-18 00:52:19'),
(23, 'User has started the chat', '2025-05-18 00:59:35'),
(24, 'User has started the chat', '2025-05-18 01:01:23'),
(25, 'User has started the chat', '2025-05-18 01:07:32'),
(26, 'User has started the chat', '2025-05-18 01:07:52'),
(27, 'User has started the chat', '2025-05-18 01:10:11'),
(28, 'User has started the chat', '2025-05-18 01:12:43'),
(29, 'User has started the chat', '2025-05-21 19:23:14'),
(30, 'User has left the chat', '2025-05-21 19:23:30'),
(31, 'User has started the chat', '2025-05-21 19:27:43'),
(32, 'User has started the chat', '2025-05-21 19:27:51'),
(33, 'User has started the chat', '2025-05-23 14:33:03'),
(34, 'User has started the chat', '2025-05-24 14:49:18'),
(35, 'User has started the chat', '2025-05-24 14:49:26'),
(36, 'User has started the chat', '2025-05-24 14:50:13'),
(37, 'User has started the chat', '2025-05-24 14:50:21'),
(38, 'User has started the chat', '2025-05-24 14:54:24'),
(39, 'User has started the chat', '2025-05-24 14:59:14'),
(40, 'User has started the chat', '2025-05-24 15:00:59'),
(41, 'User has started the chat', '2025-05-24 15:01:07'),
(42, 'User has started the chat', '2025-05-24 15:06:52'),
(43, 'User has started the chat', '2025-05-24 15:24:14'),
(44, 'User has started the chat', '2025-05-24 15:24:33'),
(45, 'User has started the chat', '2025-05-24 15:27:28'),
(46, 'User has started the chat', '2025-05-24 15:29:10'),
(47, 'User has started the chat', '2025-05-24 15:29:16'),
(48, 'User has started the chat', '2025-05-24 15:31:24'),
(49, 'User has started the chat', '2025-05-24 15:31:29'),
(50, 'User has left the chat', '2025-05-24 15:32:07'),
(51, 'User has started the chat', '2025-05-25 00:37:21'),
(52, 'User has started the chat', '2025-05-25 00:44:13'),
(53, 'User has started the chat', '2025-05-25 00:47:28'),
(54, 'User has started the chat', '2025-05-25 00:47:51'),
(55, 'User has started the chat', '2025-05-25 00:48:02'),
(56, 'User has started the chat', '2025-05-25 21:35:37'),
(57, 'User has started the chat', '2025-05-25 21:35:48'),
(58, 'User has left the chat', '2025-05-25 21:36:13'),
(59, 'User has started the chat', '2025-05-25 21:39:15'),
(60, 'User has started the chat', '2025-05-25 21:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `timely_response` varchar(255) NOT NULL,
  `fully_answered` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `session_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `rating`, `timely_response`, `fully_answered`, `comments`, `timestamp`, `session_id`) VALUES
(1, 3, 'Yes', 'Yes', 'none', '2025-05-02 04:28:32', NULL),
(2, 2, 'No', 'No', 'none', '2025-05-02 04:46:08', NULL),
(3, 5, 'Yes', 'Somewhat', 'None', '2025-05-02 04:53:44', 3),
(4, 4, 'Yes', 'Somewhat', 'grateful', '2025-05-04 22:00:21', 12),
(5, 1, 'No', 'No', 'eewewee', '2025-05-18 00:21:51', 15),
(6, 4, 'Somewhat', 'No', 'cc', '2025-05-21 19:23:30', 29),
(7, 3, 'Yes', 'Yes', 'yesss', '2025-05-24 15:32:07', 49),
(8, 2, 'Yes', 'Somewhat', 'idk', '2025-05-25 21:36:13', 57);

-- --------------------------------------------------------

--
-- Table structure for table `intents`
--

CREATE TABLE `intents` (
  `id` int(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `intents`
--

INSERT INTO `intents` (`id`, `keyword`, `timestamp`) VALUES
(1, 'Admission', '2025-04-27 19:05:07'),
(2, 'Scholarship', '2025-04-27 19:05:43'),
(3, 'department', '2025-04-27 19:50:31'),
(7, 'enrollment', '2025-05-18 09:13:25'),
(12, 'HEHE', '2025-05-24 15:40:28'),
(13, 'HAHA', '2025-05-24 15:40:36'),
(14, 'HELLO', '2025-05-24 15:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(255) NOT NULL,
  `session_id` int(11) NOT NULL,
  `input` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `session_id`, `input`, `timestamp`) VALUES
(75, 23, 'loe', '2025-05-18 00:59:42'),
(76, 24, 'hehehehe', '2025-05-18 01:01:28'),
(77, 25, 'enrollement', '2025-05-18 01:07:43'),
(78, 26, 'enrollment', '2025-05-18 01:07:58'),
(79, 26, 'old', '2025-05-18 01:09:34'),
(80, 26, 'okay', '2025-05-18 01:09:55'),
(81, 26, 'wtf', '2025-05-18 01:10:04'),
(82, 27, 'who is axeleene', '2025-05-18 01:10:20'),
(83, 27, 'who is jerald', '2025-05-18 01:10:33'),
(84, 28, 'helloo', '2025-05-18 01:12:49'),
(85, 28, 'hello', '2025-05-18 01:12:57'),
(86, 33, 'hello', '2025-05-24 14:33:11'),
(87, 33, 'scholarship', '2025-05-24 14:37:40'),
(88, 49, 'hello', '2025-05-24 15:31:36'),
(89, 51, 'hello', '2025-05-25 00:37:28'),
(90, 52, 'admission', '2025-05-25 00:44:24'),
(91, 55, 'how to drop out', '2025-05-25 00:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `resp_desc` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `log_id`, `resp_desc`, `timestamp`) VALUES
(1, 90, 'Great!', '2025-05-24 17:44:27'),
(2, 91, 'PROCEDURES FOR DROPPING OUT \nStudents may be allowed to drop from a course only before the conduct of the mid term examination.\nRequirements:\nIf you\'re planning to drop out, here’s the additional document you’ll need to prepare:\n•A Drop-out Form — you can', '2025-05-24 17:48:27');

-- --------------------------------------------------------

--
-- Table structure for table `response_rating`
--

CREATE TABLE `response_rating` (
  `id` int(11) NOT NULL,
  `response_id` int(11) DEFAULT NULL,
  `response_description` text DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `response_rating`
--

INSERT INTO `response_rating` (`id`, `response_id`, `response_description`, `rating`, `session_id`, `timestamp`, `log_id`) VALUES
(1, 1, 'Is this scholarship a university based or a private scholarship?', 1, 19, '2025-05-18 00:30:42', NULL),
(2, 1, 'Hello! How can I assist you?', 1, 22, '2025-05-18 00:53:16', NULL),
(3, 3, 'Please refrain from using offensive words even if you are talking to a bot', 1, 22, '2025-05-18 00:53:18', NULL),
(4, 1, 'Please refrain from using offensive words even if you are talking to a bot', 1, 23, '2025-05-18 00:59:47', 75),
(5, 1, 'Please refrain from using offensive words even if you are talking to a bot', 0, 24, '2025-05-18 01:01:34', 76),
(6, 6, 'Is this scholarship a university based or a private scholarship?', 1, 33, '2025-05-24 14:37:50', 87);

-- --------------------------------------------------------

--
-- Table structure for table `userlogs`
--

CREATE TABLE `userlogs` (
  `id` int(11) NOT NULL,
  `log_desc` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlogs`
--

INSERT INTO `userlogs` (`id`, `log_desc`, `timestamp`) VALUES
(1, 'Admin has logged in', '2025-05-04 03:12:29'),
(2, 'Admin has logged in', '2025-05-04 09:20:17'),
(3, 'Admin has logged in', '2025-05-04 09:23:44'),
(4, 'Admin has logged in', '2025-05-04 09:25:06'),
(5, 'Admin has logged in', '2025-05-04 09:25:49'),
(6, 'Admin has logged in', '2025-05-04 09:29:34'),
(7, 'Admin has logged in', '2025-05-04 09:39:16'),
(8, 'Admin has logged in', '2025-05-04 09:44:01'),
(9, 'Admin has logged in', '2025-05-04 09:46:04'),
(10, 'Admin has logged in', '2025-05-04 09:47:52'),
(11, 'Admin has logged in', '2025-05-04 09:49:43'),
(12, 'Admin has logged in', '2025-05-04 09:53:52'),
(13, 'Admin has logged in', '2025-05-04 09:59:00'),
(14, 'Admin has logged in', '2025-05-04 10:00:02'),
(15, 'Admin has logged in', '2025-05-04 10:00:43'),
(16, 'Admin has logged in', '2025-05-04 10:01:38'),
(17, 'Admin has logged in', '2025-05-04 10:02:07'),
(18, 'Admin has logged in', '2025-05-04 10:06:31'),
(19, 'Admin has logged in', '2025-05-04 10:09:32'),
(20, 'Admin has logged in', '2025-05-04 10:10:41'),
(21, 'Admin has logged in', '2025-05-04 10:23:48'),
(22, 'Admin has logged in', '2025-05-04 10:27:22'),
(23, 'Admin has logged in', '2025-05-04 10:29:04'),
(24, 'Admin has logged in', '2025-05-04 10:39:19'),
(25, 'Admin has logged in', '2025-05-04 10:40:47'),
(26, 'Admin has logged in', '2025-05-04 10:53:49'),
(27, 'Admin has logged in', '2025-05-04 10:59:49'),
(28, 'Admin has logged in', '2025-05-04 11:05:30'),
(29, 'Admin has logged in', '2025-05-04 11:10:30'),
(30, 'Admin has logged in', '2025-05-04 11:14:20'),
(31, 'Admin has logged in', '2025-05-04 11:14:56'),
(32, 'Admin has logged in', '2025-05-04 11:15:32'),
(33, 'Admin has logged in', '2025-05-04 11:16:30'),
(34, 'Admin has logged in', '2025-05-04 11:19:07'),
(35, 'Admin has logged in', '2025-05-04 11:19:44'),
(36, 'Admin has logged in', '2025-05-04 11:19:59'),
(37, 'Admin has logged in', '2025-05-04 11:22:30'),
(38, 'Admin has logged in', '2025-05-04 11:23:07'),
(39, 'Admin has logged in', '2025-05-04 11:23:10'),
(40, 'Admin has logged in', '2025-05-05 05:35:44'),
(41, 'Admin has logged in', '2025-05-05 05:49:21'),
(42, 'Admin has logged in', '2025-05-05 06:02:01'),
(43, 'Admin has logged in', '2025-05-08 06:09:54'),
(44, 'Admin has logged in', '2025-05-08 06:22:54'),
(45, 'Admin has logged in', '2025-05-18 01:40:16'),
(46, 'Admin has logged in', '2025-05-18 01:43:51'),
(47, 'Admin has logged in', '2025-05-18 01:56:57'),
(48, 'Admin has logged in', '2025-05-18 02:06:17'),
(49, 'Admin has logged in', '2025-05-18 02:08:33'),
(50, 'Admin has logged in', '2025-05-18 02:10:43'),
(51, 'Admin has logged in', '2025-05-18 02:12:53'),
(52, 'Admin has logged in', '2025-05-18 02:14:24'),
(53, 'Admin has logged in', '2025-05-18 02:17:09'),
(54, 'Admin has logged in', '2025-05-18 02:19:15'),
(55, 'Admin has logged in', '2025-05-18 02:20:56'),
(56, 'Admin has logged in', '2025-05-19 08:38:12'),
(57, 'Admin has logged in', '2025-05-19 08:38:49'),
(58, 'Admin has logged in', '2025-05-19 08:38:55'),
(59, 'Admin has logged in', '2025-05-19 08:38:59'),
(60, 'Admin has logged in', '2025-05-19 08:39:02'),
(61, 'Admin has logged in', '2025-05-19 08:42:23'),
(62, 'Admin has logged in', '2025-05-19 08:43:42'),
(63, 'Admin has logged in', '2025-05-19 08:56:02'),
(64, 'Admin has logged in', '2025-05-19 09:00:59'),
(65, 'Admin has logged in', '2025-05-19 09:03:14'),
(66, 'Admin has logged in', '2025-05-19 09:04:29'),
(67, 'Admin has logged in', '2025-05-19 09:05:51'),
(68, 'Admin has logged in', '2025-05-19 09:15:37'),
(69, 'Admin has logged out', '2025-05-19 09:15:57'),
(70, 'Admin has logged out', '2025-05-19 09:16:02'),
(71, 'Admin has logged in', '2025-05-19 09:24:30'),
(72, 'Admin has logged out', '2025-05-19 09:43:35'),
(73, 'Admin has logged in', '2025-05-19 13:26:38'),
(74, 'Admin has logged in', '2025-05-19 13:27:18'),
(75, 'Admin has logged in', '2025-05-19 13:28:17'),
(76, 'Admin has logged in', '2025-05-19 13:34:12'),
(77, 'Admin has logged in', '2025-05-19 14:10:53'),
(78, 'Admin has logged in', '2025-05-19 14:16:49'),
(79, 'Admin has logged in', '2025-05-20 10:03:05'),
(80, 'Admin has logged in', '2025-05-20 10:32:20'),
(81, 'Admin has logged in', '2025-05-20 10:36:26'),
(82, 'Admin has logged in', '2025-05-20 10:37:11'),
(83, 'Admin has logged in', '2025-05-21 11:58:23'),
(84, 'Admin has logged in', '2025-05-21 12:01:33'),
(85, 'Admin has logged in', '2025-05-21 12:02:42'),
(86, 'Admin has logged in', '2025-05-21 12:06:04'),
(87, 'Admin has logged in', '2025-05-21 12:07:00'),
(88, 'Admin has logged in', '2025-05-21 12:15:49'),
(89, 'Admin has logged in', '2025-05-21 12:18:37'),
(90, 'Admin has logged in', '2025-05-21 12:21:08'),
(91, 'Admin has logged in', '2025-05-24 07:16:33'),
(92, 'Admin has logged in', '2025-05-24 07:24:55'),
(93, 'Admin has logged in', '2025-05-24 07:30:02'),
(94, 'Admin has logged in', '2025-05-24 07:30:43'),
(95, 'Admin has logged in', '2025-05-24 07:38:25'),
(96, 'Admin has logged in', '2025-05-24 08:33:14'),
(97, 'Admin has logged in', '2025-05-24 08:38:43'),
(98, 'Admin has logged in', '2025-05-24 08:39:44'),
(99, 'Admin has logged in', '2025-05-24 08:42:17'),
(100, 'Admin has logged in', '2025-05-24 08:46:54'),
(101, 'Admin has logged in', '2025-05-24 08:51:14'),
(102, 'Admin has logged in', '2025-05-24 08:56:46'),
(103, 'Admin has logged in', '2025-05-24 09:01:30'),
(104, 'Admin has logged in', '2025-05-24 09:03:37'),
(105, 'Admin has logged in', '2025-05-24 09:13:01'),
(106, 'Admin has logged in', '2025-05-24 09:14:52'),
(107, 'Admin has logged in', '2025-05-24 10:04:10'),
(108, 'Admin has logged in', '2025-05-24 16:21:52'),
(109, 'Admin has logged in', '2025-05-24 16:23:00'),
(110, 'Admin has logged in', '2025-05-24 16:32:47'),
(111, 'Admin has logged in', '2025-05-24 16:48:20'),
(112, 'Admin has logged in', '2025-05-24 16:49:42'),
(113, 'Admin has logged in', '2025-05-24 16:52:18'),
(114, 'Admin has logged in', '2025-05-24 16:54:35'),
(115, 'Admin has logged in', '2025-05-24 17:05:21'),
(116, 'Admin has logged in', '2025-05-24 17:07:00'),
(117, 'Admin has logged in', '2025-05-24 17:09:12'),
(118, 'Admin has logged in', '2025-05-24 17:11:27'),
(119, 'Admin has logged in', '2025-05-24 17:16:06'),
(120, 'Admin has logged in', '2025-05-24 17:45:19'),
(121, 'Admin has logged out', '2025-05-25 09:41:50'),
(122, 'Admin has logged out', '2025-05-25 14:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT 'Admin',
  `password` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `timestamp`) VALUES
(1, 'Admin', '12345', '2025-05-04 03:12:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatsessions`
--
ALTER TABLE `chatsessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `fk_feedback_session` (`session_id`);

--
-- Indexes for table `intents`
--
ALTER TABLE `intents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_logs_session` (`session_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_id` (`log_id`);

--
-- Indexes for table `response_rating`
--
ALTER TABLE `response_rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_response_rating_session` (`session_id`),
  ADD KEY `fk_response_rating_log` (`log_id`);

--
-- Indexes for table `userlogs`
--
ALTER TABLE `userlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatsessions`
--
ALTER TABLE `chatsessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `intents`
--
ALTER TABLE `intents`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `response_rating`
--
ALTER TABLE `response_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `userlogs`
--
ALTER TABLE `userlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_session` FOREIGN KEY (`session_id`) REFERENCES `chatsessions` (`session_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_session` FOREIGN KEY (`session_id`) REFERENCES `chatsessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `fk_log_id` FOREIGN KEY (`log_id`) REFERENCES `logs` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `response_rating`
--
ALTER TABLE `response_rating`
  ADD CONSTRAINT `fk_response_rating_log` FOREIGN KEY (`log_id`) REFERENCES `logs` (`log_id`),
  ADD CONSTRAINT `fk_response_rating_session` FOREIGN KEY (`session_id`) REFERENCES `chatsessions` (`session_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
