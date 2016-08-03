-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Aug 03, 2016 at 04:53 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `AveryManufacturing`
--

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

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
  `DateCreated` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`Id`, `Name`, `Email`, `Address`, `City`, `State`, `Zip`, `LastFour`, `Phone`, `DateCreated`) VALUES
(1, 'First Customer', 'test@test.com', '1 Rainbow Rd', 'Latham', 'NY', '12110', 4242, '2147483647', '2016-08-02 20:50:18'),
(2, 'Ramsay Bolton', 'another@sometime.com', '175 Paine St', 'Green Island', 'NY', '12183', 5555, '5182736781', '2016-08-02 20:57:24'),
(3, 'Arya Stark', 'something@sometime.com', '155 West Commercial St', 'East Rochester', 'NY', '14445', 8094, '5184662174', '2016-08-02 20:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE `Inventory` (
  `ProductId` int(11) NOT NULL,
  `LocationId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Locations`
--

CREATE TABLE `Locations` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `City` varchar(30) NOT NULL,
  `State` varchar(3) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `Phone` varchar(11) NOT NULL,
  `PrimaryContact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Locations`
--

INSERT INTO `Locations` (`Id`, `Name`, `Address`, `City`, `State`, `Zip`, `Phone`, `PrimaryContact`) VALUES
(1, 'Green Island', '177 Paine St', 'Green Island', 'NY', '12183', '5181234567', 'Kristy Avery');

-- --------------------------------------------------------

--
-- Table structure for table `OrderItems`
--

CREATE TABLE `OrderItems` (
  `OrderId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `TaxAmount` decimal(10,0) NOT NULL,
  `Discount` decimal(10,0) DEFAULT NULL,
  `ShipmentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `Id` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `CustomerId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`Id`, `Date`, `CustomerId`) VALUES
(1, '2016-08-02 22:26:22', 2),
(2, '2016-08-02 22:26:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`Id`, `Name`, `Description`, `Price`) VALUES
(1, 'Prody', 'This is a product thing', '7.99'),
(2, 'Another Prod', 'This is fifty cent thingy', '10.50'),
(3, '', '', '0.00'),
(4, '', '', '0.00'),
(5, '', '', '0.00'),
(6, 'Namey', 'This is a desc.', '1.11'),
(8, 'Penguin', 'This is a penguin.', '35.00'),
(9, 'Penguin', 'This is a penguin.', '35.00'),
(10, 'Penguin', 'This is a penguin.', '35.00'),
(11, 'Penguin', 'This is a penguin.', '35.00'),
(12, 'Pikachu', 'Shocking.', '5.00'),
(13, 'Penguin', 'This is a penguin.', '35.00'),
(14, 'Armadillo', 'This is a penguin.', '35.00');

-- --------------------------------------------------------

--
-- Table structure for table `Shipments`
--

CREATE TABLE `Shipments` (
  `Id` int(11) NOT NULL,
  `RateType` varchar(25) NOT NULL,
  `Cost` decimal(10,0) NOT NULL,
  `Status` int(3) NOT NULL,
  `OrderId` int(11) NOT NULL,
  `EpLabelId` varchar(100) DEFAULT NULL,
  `EpShipmentId` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD KEY `fk_inventory_product` (`ProductId`),
  ADD KEY `fk_inventory_locations` (`LocationId`);

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
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
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
