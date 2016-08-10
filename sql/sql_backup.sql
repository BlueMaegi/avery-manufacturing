-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Aug 10, 2016 at 05:40 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `AveryManufacturing`
--

-- --------------------------------------------------------

--
-- Table structure for table `Clients`
--

CREATE TABLE `Clients` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Creds` varchar(150) NOT NULL,
  `Token` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Clients`
--

INSERT INTO `Clients` (`Id`, `Name`, `Creds`, `Token`) VALUES
(1, 'filler', '42', NULL),
(2, 'admin', '7b88aa1efa047a9f1cfecf1eea9f3c11a2364309434f5d18772052b88f829f07ddf1abd8fc82a373759ace8d4cb92a72f1e8b124fa32a7a99194889ae5166ced', 'eefd792a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Clients`
--
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Clients`
--
ALTER TABLE `Clients`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;