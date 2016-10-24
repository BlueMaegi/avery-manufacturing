-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Oct 24, 2016 at 04:34 AM
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

DROP TABLE IF EXISTS `Clients`;
CREATE TABLE `Clients` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Creds` varchar(150) NOT NULL,
  `Token` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

DROP TABLE IF EXISTS `Customers`;
CREATE TABLE `Customers` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `City` varchar(30) NOT NULL,
  `State` varchar(3) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `LastFour` int(4) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `EpAddressId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

DROP TABLE IF EXISTS `Inventory`;
CREATE TABLE `Inventory` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `LocationId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `InventoryHistory`
--

DROP TABLE IF EXISTS `InventoryHistory`;
CREATE TABLE `InventoryHistory` (
  `Id` int(11) NOT NULL,
  `InventoryId` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `EventType` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Locations`
--

DROP TABLE IF EXISTS `Locations`;
CREATE TABLE `Locations` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `City` varchar(30) NOT NULL,
  `State` varchar(3) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `Phone` varchar(11) NOT NULL,
  `PrimaryContact` varchar(50) DEFAULT NULL,
  `EpAddressId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OrderItems`
--

DROP TABLE IF EXISTS `OrderItems`;
CREATE TABLE `OrderItems` (
  `OrderId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `TaxAmount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Discount` decimal(10,2) DEFAULT '0.00',
  `ShipmentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

DROP TABLE IF EXISTS `Orders`;
CREATE TABLE `Orders` (
  `Id` int(11) NOT NULL,
  `Code` varchar(10) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `StripeChargeId` varchar(100) DEFAULT NULL,
  `RefundAmount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Enabled` tinyint(1) NOT NULL DEFAULT '1',
  `EpParcelId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Shipments`
--

DROP TABLE IF EXISTS `Shipments`;
CREATE TABLE `Shipments` (
  `Id` int(11) NOT NULL,
  `RateType` varchar(25) NOT NULL,
  `Cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TaxAmount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Status` int(3) NOT NULL DEFAULT '0',
  `OrderId` int(11) NOT NULL,
  `EpLabelId` varchar(100) DEFAULT NULL,
  `EpShipmentId` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Clients`
--
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_inventory_product` (`ProductId`),
  ADD KEY `fk_inventory_locations` (`LocationId`);

--
-- Indexes for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_history_inventory` (`InventoryId`);

--
-- Indexes for table `Locations`
--
ALTER TABLE `Locations`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD KEY `fk_Item_Order` (`OrderId`),
  ADD KEY `fk_Item_Product` (`ProductId`),
  ADD KEY `fk_Item_Shipment` (`ShipmentId`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Order_Customer` (`CustomerId`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Shipments`
--
ALTER TABLE `Shipments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Shipment_Order` (`OrderId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Clients`
--
ALTER TABLE `Clients`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Shipments`
--
ALTER TABLE `Shipments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `fk_inventory_locations` FOREIGN KEY (`LocationId`) REFERENCES `Locations` (`Id`),
  ADD CONSTRAINT `fk_inventory_product` FOREIGN KEY (`ProductId`) REFERENCES `Products` (`Id`);

--
-- Constraints for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  ADD CONSTRAINT `fk_history_inventory` FOREIGN KEY (`InventoryId`) REFERENCES `Inventory` (`Id`);

--
-- Constraints for table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD CONSTRAINT `fk_Item_Order` FOREIGN KEY (`OrderId`) REFERENCES `Orders` (`Id`),
  ADD CONSTRAINT `fk_Item_Product` FOREIGN KEY (`ProductId`) REFERENCES `Products` (`Id`),
  ADD CONSTRAINT `fk_Item_Shipment` FOREIGN KEY (`ShipmentId`) REFERENCES `Shipments` (`Id`);

--
-- Constraints for table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `fk_Order_Customer` FOREIGN KEY (`CustomerId`) REFERENCES `Customers` (`Id`);

--
-- Constraints for table `Shipments`
--
ALTER TABLE `Shipments`
  ADD CONSTRAINT `fk_Shipment_Order` FOREIGN KEY (`OrderId`) REFERENCES `Orders` (`Id`);
