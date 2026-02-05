-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 01:52 AM
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
-- Create database if it doesn't exist
--

CREATE DATABASE IF NOT EXISTS `ojt_hr_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Switch to the database
--

USE `ojt_hr_system`;

-- --------------------------------------------------------

--
-- Table structure for table `intern_list`
--

CREATE TABLE IF NOT EXISTS `intern_list` (
  `intern_id` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `intern_display_id` varchar(20) GENERATED ALWAYS AS (concat('ojt-',lpad(`intern_id`,3,'0'))) VIRTUAL,
  `user_id` int(11) NOT NULL,
  `date_of_employment` varchar(15) NOT NULL,
  `intern_last_name` varchar(20) NOT NULL,
  `intern_first_name` varchar(30) NOT NULL,
  `intern_middle_initial` varchar(2) NOT NULL,
  `intern_course` varchar(50) NOT NULL,
  `intern_dept` varchar(30) NOT NULL,
  `total_hours_needed` int(5) NOT NULL,
  `accumulated_hours` int(5) NOT NULL,
  `remaining_hours` int(5) NOT NULL,
  `school` varchar(100) NOT NULL,
  `time_sheet` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_list`
--

CREATE TABLE IF NOT EXISTS `request_list` (
  `request_no` int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `request_no_display` varchar(20) GENERATED ALWAYS AS (concat('ojt-request-',lpad(`request_no`,5,'0'))) VIRTUAL,
  `request_date` varchar(15) NOT NULL,
  `submitted_by` varchar(50) NOT NULL,
  `request_subject` varchar(50) NOT NULL,
  `request_main` varchar(500) NOT NULL,
  `request_status` varchar(20) NOT NULL,
  `request_attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(30) NOT NULL,
  `password` varchar(155) NOT NULL,
  `last_ping` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT IGNORE INTO `users` (`user_id`, `username`, `password`, `last_ping`) VALUES
(1, 'admin', '$2y$10$.sMMm0rIutbWwITNbDCWiuMP6BPKubhC7jf1BCR0VJyGw2/xw4mTe', 1770102507);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
