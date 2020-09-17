-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2020 at 08:54 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reel_mediaresource`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

-- DROP DATABASE IF EXISTS `reel_mediaresource`;

-- CREATE DATABASE IF NOT EXISTS `reel_mediaresource`;
-- USE `reel_mediaresource`;

CREATE TABLE `admin` (
  `admin_id` int(11) UNSIGNED NOT NULL,
  `admin_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rates_schedule_time`
--

CREATE TABLE `rates_schedule_time` (
  `id` int(11) UNSIGNED NOT NULL,
  `time` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resource_media_files`
--

CREATE TABLE `resource_media_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `fileHash` varchar(64) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `fileType` varchar(32) NOT NULL,
  `fileSize` int(10) UNSIGNED NOT NULL,
  `isVideo` tinyint(1) NOT NULL DEFAULT 0,
  `associatedSchedules` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resource_media_ipaddress`
--

CREATE TABLE `resource_media_ipaddress` (
  `id` int(10) UNSIGNED NOT NULL,
  `ipAddress` varchar(64) NOT NULL,
  `clientName` varchar(128) NOT NULL,
  `ipAddressHash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resource_media_schedule`
--

CREATE TABLE `resource_media_schedule` (
  `id` int(10) UNSIGNED NOT NULL,
  `fileHash` varchar(64) NOT NULL,
  `scheduleName` varchar(255) NOT NULL,
  `fileOrder` int(10) UNSIGNED NOT NULL,
  `ipAddressHash` varchar(64) NOT NULL,
  `timer` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supper_admin`
--

CREATE TABLE `supper_admin` (
  `supper_admin_id` int(11) UNSIGNED NOT NULL,
  `supper_admin_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `rates_schedule_time`
--
ALTER TABLE `rates_schedule_time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_media_files`
--
ALTER TABLE `resource_media_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_filehash` (`fileHash`);

--
-- Indexes for table `resource_media_ipaddress`
--
ALTER TABLE `resource_media_ipaddress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_iphash` (`ipAddressHash`);

--
-- Indexes for table `resource_media_schedule`
--
ALTER TABLE `resource_media_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key_filehash` (`fileHash`),
  ADD KEY `key_iphash` (`ipAddressHash`);

--
-- Indexes for table `supper_admin`
--
ALTER TABLE `supper_admin`
  ADD PRIMARY KEY (`supper_admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rates_schedule_time`
--
ALTER TABLE `rates_schedule_time`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_media_files`
--
ALTER TABLE `resource_media_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_media_ipaddress`
--
ALTER TABLE `resource_media_ipaddress`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_media_schedule`
--
ALTER TABLE `resource_media_schedule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supper_admin`
--
ALTER TABLE `supper_admin`
  MODIFY `supper_admin_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
