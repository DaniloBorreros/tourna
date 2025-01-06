-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 04:37 PM
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
-- Database: `sports`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `name`) VALUES
(1, 'renzolouismontejo@gmail.com', 'admin', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `code`, `name`) VALUES
(1, 'BSBM', 'Bachelor of Science in Business Management'),
(2, 'BSIT', 'Bachelor of Science in Information Technology'),
(3, 'BSPsych', 'Bachelor of Science in Psychology'),
(5, 'BSCrim', 'Bachelor of Science in Criminology');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `dateuploaded` date NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `dateuploaded`, `content`, `image`) VALUES
(1, 'Sample Title 1', '2024-04-15', 'Sample Content 1', '661cbc4662c42.jpg'),
(2, 'Sample News1', '2024-10-30', 'This is a sample news1', '67210269b7e1a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE `place` (
  `id` int(11) NOT NULL,
  `place` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `place`
--

INSERT INTO `place` (`id`, `place`) VALUES
(1, 'School Gymnasium'),
(2, 'Auditorium'),
(4, 'CL1');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `sport` int(11) NOT NULL,
  `team1` int(11) NOT NULL,
  `team2` int(11) DEFAULT NULL,
  `schedule` datetime NOT NULL,
  `place` varchar(255) NOT NULL,
  `winner` int(11) DEFAULT NULL,
  `loser` int(11) DEFAULT NULL,
  `round` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `sport`, `team1`, `team2`, `schedule`, `place`, `winner`, `loser`, `round`) VALUES
(1, 2, 2, 8, '2024-11-26 08:00:00', 'School Gymnasium', 8, 2, 1),
(2, 2, 4, 9, '2024-11-26 09:00:00', 'School Gymnasium', 9, 4, 1),
(3, 2, 3, 5, '2024-11-26 10:00:00', 'School Gymnasium', 5, 3, 1),
(4, 2, 6, 7, '2024-11-26 11:00:00', 'School Gymnasium', 7, 6, 1),
(5, 2, 6, 4, '2024-11-29 08:00:00', 'School Gymnasium', 4, 6, 2),
(6, 2, 3, 2, '2024-11-29 09:00:00', 'School Gymnasium', 2, 3, 2),
(7, 2, 5, 8, '2024-11-29 10:00:00', 'School Gymnasium', 8, 5, 2),
(8, 2, 7, 9, '2024-11-29 11:00:00', 'School Gymnasium', 9, 7, 2),
(9, 2, 5, 7, '2024-11-30 08:00:00', 'School Gymnasium', NULL, NULL, 3),
(10, 2, 4, 2, '2024-11-30 09:00:00', 'School Gymnasium', NULL, NULL, 3),
(11, 2, 9, 8, '2024-11-30 10:00:00', 'School Gymnasium', NULL, NULL, 3),
(12, 1, 1, 12, '2024-11-30 11:00:00', 'School Gymnasium', NULL, NULL, 1),
(13, 1, 10, 11, '2024-11-30 12:00:00', 'School Gymnasium', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sport`
--

CREATE TABLE `sport` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `elimination` int(1) NOT NULL DEFAULT 1,
  `round` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sport`
--

INSERT INTO `sport` (`id`, `name`, `elimination`, `round`) VALUES
(1, 'Basketball', 1, 1),
(2, 'Volleyball', 2, 3),
(3, 'Football', 1, 0),
(4, 'Chess', 1, 0),
(5, 'E-sport Mobile Legends', 1, 0),
(7, 'Arnis', 1, 0),
(8, 'Baseball', 2, 0),
(9, 'E-Sports Valorant', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `sport` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(25) NOT NULL,
  `stage` int(1) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Qualified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `sport`, `name`, `logo`, `stage`, `status`) VALUES
(1, 1, 'Basketball Team1', 'CUAPb', 2, 'Qualified'),
(2, 2, 'Volleyball Team1', 'f0fJB', 1, 'Qualified'),
(3, 2, 'Volleyball Team2', 'QylEK', 0, 'Eliminated'),
(4, 2, 'Volleyball Team3', 'hJuYA', 1, 'Qualified'),
(5, 2, 'Volleyball Team4', 'Equkx', 1, 'Qualified'),
(6, 2, 'Volleyball Team5', 'xkkOj', 0, 'Eliminated'),
(7, 2, 'Volleyball Team6', 'uGirA', 1, 'Qualified'),
(8, 2, 'Volleyball Team7', 'LO1lq', 2, 'Qualified'),
(9, 2, 'Volleyball Team8', 'KzoMs', 2, 'Qualified'),
(10, 1, 'Basketball Team2', 'Nuppw', 1, 'Qualified'),
(11, 1, 'Basketball Team3', 'Soy9i', 1, 'Qualified'),
(12, 1, 'Basketball Team4', 'S4i6v', 1, 'Qualified');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `course` int(255) NOT NULL,
  `athlete` int(10) NOT NULL,
  `sport` int(255) DEFAULT NULL,
  `team` int(11) DEFAULT NULL,
  `verification` int(1) NOT NULL DEFAULT 0,
  `image` varchar(5) NOT NULL,
  `waiver` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `lastname`, `firstname`, `middlename`, `email`, `password`, `year`, `course`, `athlete`, `sport`, `team`, `verification`, `image`, `waiver`) VALUES
(1, 'Montejo', 'Renzo Louis', 'Fajilas', 'renzolouismontejo@gmail.com', '1234', 1, 1, 1, 2, 1, 1, '79799', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sport`
--
ALTER TABLE `sport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sport`
--
ALTER TABLE `sport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
