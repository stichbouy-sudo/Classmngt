-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 30, 2025 at 05:39 PM
-- Server version: 5.7.34
-- PHP Version: 8.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT '',
  `score` decimal(6,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `student_id`, `subject_id`, `title`, `score`, `created_at`) VALUES
(1, 2, 2, 'Laboratory 1', 88.00, '2025-11-29 15:40:14'),
(2, 6, 2, 'Laboratory 1', 96.00, '2025-11-29 15:49:23'),
(3, 6, 2, 'Quiz 1', 48.00, '2025-11-29 15:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `status` enum('present','absent') NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `subject_id`, `status`, `date`, `created_at`) VALUES
(1, 1, 1, 'present', '2025-11-29', '2025-11-29 15:37:52'),
(2, 2, 2, 'present', '2025-11-29', '2025-11-29 15:37:58'),
(3, 6, 2, 'present', '2025-11-29', '2025-11-29 15:52:17'),
(4, 4, 2, 'present', '2025-11-29', '2025-11-29 15:52:18'),
(5, 5, 2, 'present', '2025-11-29', '2025-11-29 15:52:20'),
(6, 3, 2, 'present', '2025-11-29', '2025-11-29 15:52:20'),
(7, 3, 2, 'absent', '2025-11-30', '2025-11-30 16:37:25'),
(8, 5, 2, 'present', '2025-11-30', '2025-11-30 16:37:26'),
(9, 2, 2, 'present', '2025-11-30', '2025-11-30 16:37:27'),
(10, 4, 2, 'present', '2025-11-30', '2025-11-30 16:37:27'),
(11, 6, 2, 'present', '2025-11-30', '2025-11-30 16:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `prelim` decimal(5,2) DEFAULT '0.00',
  `midterm` decimal(5,2) DEFAULT '0.00',
  `finals` decimal(5,2) DEFAULT '0.00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `prelim`, `midterm`, `finals`, `updated_at`) VALUES
(1, 1, 1, 0.00, 0.00, 0.00, '2025-11-29 15:23:40'),
(2, 2, 2, 0.00, 0.00, 0.00, '2025-11-29 15:37:29'),
(3, 3, 2, 0.00, 0.00, 0.00, '2025-11-29 15:41:44'),
(4, 4, 2, 0.00, 0.00, 0.00, '2025-11-29 15:41:44'),
(5, 5, 2, 0.00, 0.00, 0.00, '2025-11-29 15:41:44'),
(6, 6, 2, 1.10, 1.10, 0.00, '2025-11-29 15:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `course` varchar(100) DEFAULT '',
  `year_level` varchar(50) DEFAULT '',
  `avatar` varchar(255) DEFAULT 'assets/img/default-avatar.png',
  `archived` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `subject_id`, `lastname`, `firstname`, `course`, `year_level`, `avatar`, `archived`, `created_at`) VALUES
(1, 1, 'Doe', 'John', 'BSIT', '4th', 'uploads/1764430548_69249cc366a68.jpg', 0, '2025-11-29 15:23:40'),
(2, 2, 'Doe', 'John', 'BSIT', '4th', 'uploads/1764430663_69249cc366a68.jpg', 0, '2025-11-29 15:37:29'),
(3, 2, 'Agustin', 'Mark', 'BSIT', '4th', 'uploads/1764430978_Capture-1024x576.png', 1, '2025-11-29 15:41:44'),
(4, 2, 'Matthias', 'Bryan', 'BSIT', '4th', 'uploads/1764431000_CIDOO_V68_VIA_bf78b370-690a-4847-bac6-d8df07f730e4_1024x1024.jpg', 0, '2025-11-29 15:41:44'),
(5, 2, 'Dela Cruz', 'Hanna Mae', 'BSIT', '4TH', 'uploads/1764430933_1761724392_14727.jpg', 1, '2025-11-29 15:41:44'),
(6, 2, 'Morales', 'Oalden Lamper', 'BSIT', '4th', 'uploads/1764431344_1761724386_14726.jpg', 0, '2025-11-29 15:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT '',
  `schedule` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `faculty_id`, `name`, `code`, `schedule`, `created_at`) VALUES
(1, 5, 'Web Systems and Technologies', 'WS101', 'M,T,TH,F - 5:00-7:40PM', '2025-11-29 15:23:18'),
(2, 5, 'Human Computer Interactions', 'HCI-101', 'M,W,F - 5:00-7:40PM', '2025-11-29 15:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT '',
  `role` enum('admin','faculty') NOT NULL DEFAULT 'faculty',
  `department` varchar(255) DEFAULT '',
  `avatar` varchar(255) DEFAULT 'assets/img/default-avatar.png',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `role`, `department`, `avatar`, `created_at`) VALUES
(1, 'admin', '$2a$12$8MLXcgg39Trnh00oJFfIVuOliHxXYvT13eWC89Nc/Mjvttzou1M1C', 'Administrator', 'admin', 'IT Department', 'assets/img/classflow-favicon.svg', '2025-11-29 15:08:04'),
(8, 'johnD', '$2y$10$7dYOTJi8Ib7l/fjfdVhg9Or.HkTxLvsS9DBYfjZPvAB9NL6YMWFoy', 'John Doe', 'faculty', 'SEAIT', 'assets/img/default-avatar.png', '2025-11-30 15:41:55'),
(5, 'oaldenm', '$2y$10$uUuX1GQ3g2qI9qOHjceLbeN2oeLPIFEuw294AS04tiTyCNQ.N23i6', 'Oalden Morales', 'faculty', 'SEAIT', '../uploads/1764429755_1761724386_14726.jpg', '2025-11-29 15:19:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`);

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
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
