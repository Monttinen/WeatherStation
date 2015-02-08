-- phpMyAdmin SQL Dump
-- version 4.2.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:1194
-- Generation Time: Jan 23, 2015 at 09:36 PM
-- Server version: 5.6.20-log
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weather`
--

-- --------------------------------------------------------

--
-- Table structure for table `measurement`
--

CREATE TABLE IF NOT EXISTS `measurement` (
`id` int(11) NOT NULL,
  `sensorId` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `temperature` decimal(10,2) DEFAULT NULL,
  `humidity` decimal(10,2) DEFAULT NULL,
  `pressure` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `sensor`
--

CREATE TABLE IF NOT EXISTS `sensor` (
`id` int(11) NOT NULL,
  `name` varchar(10) COLLATE utf8_bin NOT NULL,
  `temperature` tinyint(1) NOT NULL DEFAULT '0',
  `humidity` tinyint(1) NOT NULL DEFAULT '0',
  `pressure` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(200) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sensor`
--

INSERT INTO `sensor` (`id`, `name`, `temperature`, `humidity`, `pressure`, `description`) VALUES
(1, 'BMP150', 1, 0, 1, 'A pressure/temperature sensor for outside use.'),
(2, 'DHT11', 1, 1, 0, 'A temperature/humidity sensor for inside use.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `measurement`
--
ALTER TABLE `measurement`
 ADD PRIMARY KEY (`id`,`sensorId`), ADD KEY `sensorId` (`sensorId`);

--
-- Indexes for table `sensor`
--
ALTER TABLE `sensor`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `measurement`
--
ALTER TABLE `measurement`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `sensor`
--
ALTER TABLE `sensor`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `measurement`
--
ALTER TABLE `measurement`
ADD CONSTRAINT `measurement_ibfk_1` FOREIGN KEY (`sensorId`) REFERENCES `sensor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
