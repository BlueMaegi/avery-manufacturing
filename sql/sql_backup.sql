-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Aug 07, 2016 at 07:04 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `AveryManufacturing`
--

-- --------------------------------------------------------

--
-- Table structure for table `InventoryHistory`
--

CREATE TABLE `InventoryHistory` (
  `Id` int(11) NOT NULL,
  `InventoryId` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `EventType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_history_inventory` (`InventoryId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  ADD CONSTRAINT `fk_history_inventory` FOREIGN KEY (`InventoryId`) REFERENCES `Inventory` (`Id`);
