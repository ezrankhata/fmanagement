-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 11:02 AM
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
-- Database: `file_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `request_method` varchar(10) NOT NULL,
  `status_code` int(11) NOT NULL,
  `log_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `directories`
--

CREATE TABLE `directories` (
  `id` int(11) NOT NULL,
  `directory_name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(10) UNSIGNED NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `directory_id` int(10) UNSIGNED DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `file_name`, `original_name`, `file_type`, `file_size`, `upload_date`, `directory_id`, `thumbnail_path`, `created_at`, `updated_at`) VALUES
(4, '1734048350_ea80879103.png', 'final1.PNG', 'image/png', 80868, '2024-12-12 01:01:43', NULL, NULL, '2024-12-11 23:01:43', '2024-12-13 00:05:50'),
(5, '1733958777_204728a126.jpg', 'Malume 1.jpg', 'image/jpeg', 1795792, '2024-12-12 01:12:57', NULL, NULL, '2024-12-11 23:12:57', '2024-12-11 23:12:57'),
(6, '1733960515_bedcff6534.png', 'ITPM WBS.PNG', 'image/png', 79991, '2024-12-12 01:41:55', NULL, NULL, '2024-12-11 23:41:55', '2024-12-11 23:41:55'),
(7, '1733960615_09142df24c.png', 'RESSSSSRT.PNG', 'image/png', 138588, '2024-12-12 01:43:35', NULL, NULL, '2024-12-11 23:43:35', '2024-12-11 23:43:35'),
(8, '1733993025_5b7dac9bab.pdf', 'speech.pdf', 'application/pdf', 135883, '2024-12-12 10:43:46', NULL, NULL, '2024-12-12 08:43:46', '2024-12-12 08:43:46'),
(9, '1733994923_1f05d2edc1.pdf', 'SDA QUIZ.pdf', 'application/pdf', 218826, '2024-12-12 11:15:23', NULL, NULL, '2024-12-12 09:15:23', '2024-12-12 09:15:23'),
(10, '1733995339_2e0a045c13.png', 'Blank boarbbbbbbd.png', 'image/png', 63174, '2024-12-12 11:22:19', NULL, NULL, '2024-12-12 09:22:19', '2024-12-12 09:22:19'),
(11, '1733996196_21ef910bd1.png', 'randomforest.PNG', 'image/png', 13511, '2024-12-12 11:36:36', NULL, NULL, '2024-12-12 09:36:36', '2024-12-12 09:36:36'),
(12, '1733996964_7a86d1c07d.png', 'img.PNG', 'image/png', 40301, '2024-12-12 11:49:24', NULL, NULL, '2024-12-12 09:49:24', '2024-12-12 09:49:24'),
(13, '1733999379_ee62e85c68.png', 'imag.PNG', 'image/png', 59111, '2024-12-12 12:29:39', NULL, NULL, '2024-12-12 10:29:39', '2024-12-12 10:29:39'),
(14, '1734004567_0424b7b547.png', 'logistic.PNG', 'image/png', 12231, '2024-12-12 13:56:07', NULL, NULL, '2024-12-12 11:56:07', '2024-12-12 11:56:07'),
(15, '1734004925_09497c90f2.png', 'class.PNG', 'image/png', 40744, '2024-12-12 14:02:05', NULL, NULL, '2024-12-12 12:02:05', '2024-12-12 12:02:05'),
(16, '1734006057_d5e0ff88a3.png', 'RESSSSSRT.PNG', 'image/png', 138588, '2024-12-12 14:20:57', NULL, NULL, '2024-12-12 12:20:57', '2024-12-12 12:20:57'),
(17, '1734007145_933900b338.xlsx', 'vinjeru Quatation.xlsx', 'application/vnd.openxmlformats-officedocument.spre', 9362, '2024-12-12 14:39:06', NULL, NULL, '2024-12-12 12:39:06', '2024-12-12 12:39:06'),
(18, '1734007803_e525b719ca.docx', 'SPEED TRAP.docx', 'application/vnd.openxmlformats-officedocument.word', 100157, '2024-12-12 14:50:03', NULL, NULL, '2024-12-12 12:50:03', '2024-12-12 12:50:03'),
(19, '1734007826_de9aa5e12d.pptx', 'Lecture Notes 2 - SAD.pptx', 'application/vnd.openxmlformats-officedocument.pres', 939898, '2024-12-12 14:50:26', NULL, NULL, '2024-12-12 12:50:26', '2024-12-12 12:50:26'),
(20, '1734045506_50c7b62b83.pptx', 'Lecture Notes 1 - SDA.pptx', 'application/vnd.openxmlformats-officedocument.pres', 1163775, '2024-12-13 01:18:27', NULL, NULL, '2024-12-12 23:18:27', '2024-12-12 23:18:27'),
(21, '1734045717_1e15519897.png', 'AIDSS.PNG', 'image/png', 66317, '2024-12-13 01:21:57', NULL, NULL, '2024-12-12 23:21:57', '2024-12-12 23:21:57'),
(22, '1734046944_945fbb1040.png', 'final1.PNG', 'image/png', 80868, '2024-12-13 01:42:24', NULL, NULL, '2024-12-12 23:42:24', '2024-12-12 23:42:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `directories`
--
ALTER TABLE `directories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_name` (`file_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `directories`
--
ALTER TABLE `directories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `directories`
--
ALTER TABLE `directories`
  ADD CONSTRAINT `directories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `directories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
