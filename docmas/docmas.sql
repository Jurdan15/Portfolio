-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 04:12 PM
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
-- Database: `docmas`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `document` varchar(255) DEFAULT NULL,
  `author` varchar(50) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `borrower` varchar(50) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `college` varchar(30) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `scanner` varchar(50) NOT NULL,
  `borrow_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `document` varchar(255) DEFAULT NULL,
  `author` varchar(50) NOT NULL,
  `Book_Status` varchar(50) NOT NULL,
  `scanner` varchar(50) NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `document`, `author`, `Book_Status`, `scanner`, `timestamp`) VALUES
(229, 'QR Code Generator by TEC-IT', 'Mariisa', 'Available', 'shaika', '2025-05-27 14:04:54'),
(230, 'https://www.canva.com/', 'Jomar', 'Available', 'shaika', '2025-05-27 13:15:44'),
(231, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Wendel', 'Available', 'shaika', '2025-05-27 13:45:12'),
(232, 'http://en.m.wikipedia.org', 'siyak', 'Available', 'shaika', '2025-05-27 14:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(5) NOT NULL,
  `document` varchar(255) DEFAULT NULL,
  `author` varchar(50) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `borrow` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `college` varchar(50) NOT NULL,
  `scanner` varchar(50) NOT NULL,
  `tdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `document`, `author`, `student_id`, `borrow`, `contact_number`, `purpose`, `college`, `scanner`, `tdate`) VALUES
(181, 'QR Code Generator by TEC-IT', 'Mariisa', 'nanalaow', 'hahanam', 'jwkama', 'sheshh', 'CICS', 'shaika', '2025-05-21 18:47:10'),
(182, 'https://www.canva.com/', 'Shaika', '7282', 'jjjj', '7271', 'k', 'CICS', 'shaika', '2025-05-21 18:47:44'),
(183, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Wendel', 'msma', 'nMal', 'amM', 'mamama', 'CICS', 'shaika', '2025-05-21 18:48:50'),
(184, 'http://en.m.wikipedia.org', 'siyak', 'nananan', 'kajan', 'nananan', 'banana', 'CICS', 'shaika', '2025-05-27 14:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `returned_books`
--

CREATE TABLE `returned_books` (
  `id` int(5) NOT NULL,
  `document` varchar(50) NOT NULL,
  `scanner` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `history_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returned_books`
--

INSERT INTO `returned_books` (`id`, `document`, `scanner`, `timestamp`, `history_id`) VALUES
(49, 'https://www.canva.com/', 'shaika', '2025-05-22 03:48:20', 182),
(50, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'shaika', '2025-05-22 03:49:01', 183),
(51, 'QR Code Generator by TEC-IT', 'admin', '2025-05-27 23:01:39', 181),
(52, 'http://en.m.wikipedia.org', 'admin', '2025-05-27 23:04:24', 184);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `wholename` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `wholename`, `username`, `password`, `role`, `created_at`) VALUES
(94, ' shaika', 'shaika', '12345', 'admin', '2024-12-28 06:29:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`document`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bcode` (`document`);

--
-- Indexes for table `returned_books`
--
ALTER TABLE `returned_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_returned_history` (`history_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `returned_books`
--
ALTER TABLE `returned_books`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `returned_books`
--
ALTER TABLE `returned_books`
  ADD CONSTRAINT `fk_returned_history` FOREIGN KEY (`history_id`) REFERENCES `history` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
