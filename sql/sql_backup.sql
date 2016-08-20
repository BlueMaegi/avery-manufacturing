-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Aug 20, 2016 at 06:35 PM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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
(2, 'admin', '7b88aa1efa047a9f1cfecf1eea9f3c11a2364309434f5d18772052b88f829f07ddf1abd8fc82a373759ace8d4cb92a72f1e8b124fa32a7a99194889ae5166ced', '5e624dee');

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
  `DateCreated` datetime NOT NULL,
  `EpAddressId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`Id`, `Name`, `Email`, `Address`, `City`, `State`, `Zip`, `LastFour`, `Phone`, `DateCreated`, `EpAddressId`) VALUES
(1, 'First Customer', 'test@test.com', '1 Rainbow Rd', 'Latham', 'NY', '12110', 4242, '2147483647', '2016-08-02 20:50:18', NULL),
(2, 'Ramsay Bolton', 'another@sometime.com', '175 Paine St', 'Green Island', 'NY', '12183', 5555, '5182736781', '2016-08-02 20:57:24', NULL),
(3, 'Arya Stark', 'something@sometime.com', '155 West Commercial St', 'East Rochester', 'NY', '14445', 8094, '5184662174', '2016-08-02 20:58:24', NULL),
(4, 'Missandei', 'wiseone@naath.com', '25th Pebble Tree', 'Vaes Dothrak', 'ESS', '00800', 3333, '7778889999', '2016-08-13 21:01:59', NULL),
(12, 'Davos Seaworth', 'davos@stormsend.com', 'West Tower Room 6', 'Dragonstone', 'SL', 'BB123', 4444, '1234567890', '2016-08-14 16:48:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE `Inventory` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `LocationId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Inventory`
--

INSERT INTO `Inventory` (`Id`, `ProductId`, `LocationId`, `Quantity`) VALUES
(1, 11, 1, 2),
(4, 12, 1, 14),
(5, 16, 1, 17),
(7, 16, 1, 23),
(8, 16, 1, 23);

-- --------------------------------------------------------

--
-- Table structure for table `InventoryHistory`
--

CREATE TABLE `InventoryHistory` (
  `Id` int(11) NOT NULL,
  `InventoryId` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `EventType` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `InventoryHistory`
--

INSERT INTO `InventoryHistory` (`Id`, `InventoryId`, `Date`, `EventType`, `Quantity`) VALUES
(5, 5, '2016-08-13 23:37:05', 1, 10),
(7, 1, '2016-08-13 23:37:27', 1, 2),
(8, 4, '2016-08-13 23:37:38', 1, 3),
(15, 4, '2016-08-14 16:48:22', 2, 1),
(16, 5, '2016-08-14 16:48:22', 2, 3);

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
  `PrimaryContact` varchar(50) DEFAULT NULL,
  `EpAddressId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Locations`
--

INSERT INTO `Locations` (`Id`, `Name`, `Address`, `City`, `State`, `Zip`, `Phone`, `PrimaryContact`, `EpAddressId`) VALUES
(1, 'Green Island', '177 Paine St', 'Green Island', 'NY', '12183', '5181234567', 'Kristy Avery', 'adr_beb0f77fb1c549309d75b292db1b1e68'),
(2, 'Latham', '25th Pebble Tree', 'Vaes Dothrak', 'NY', '12110', '7778889999', 'wiseone@naath.com', ''),
(3, 'Syracuse', '65th Pebble Tree', 'Syracuse', 'NY', '12510', '123456789', 'another@westeros.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `OrderItems`
--

CREATE TABLE `OrderItems` (
  `OrderId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `TaxAmount` decimal(10,2) NOT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `ShipmentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `OrderItems`
--

INSERT INTO `OrderItems` (`OrderId`, `ProductId`, `Quantity`, `TaxAmount`, `Discount`, `ShipmentId`) VALUES
(1, 9, 1, '2.00', '0.00', NULL),
(1, 3, 1, '0.70', '0.00', NULL),
(2, 10, 2, '3.70', '0.00', NULL),
(2, 11, 2, '0.10', '0.00', NULL),
(3, 13, 3, '8.70', '0.00', NULL),
(10, 12, 1, '3.50', '0.00', NULL),
(10, 16, 3, '6.00', '0.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `Id` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `CustomerId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`Id`, `Date`, `CustomerId`) VALUES
(1, '2016-08-02 22:26:22', 2),
(2, '2016-08-02 22:26:47', 1),
(3, '2016-08-06 14:41:37', 1),
(4, '2016-08-14 00:00:38', 4),
(10, '2016-08-14 16:48:21', 12);

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `EpParcelId` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`Id`, `Name`, `Description`, `Price`, `EpParcelId`) VALUES
(1, 'Prody', 'This is a product thing', '7.99', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(2, 'Another Prod', 'This is fifty cent thingy', '10.50', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(3, '', '', '0.00', 'prcl_50b7f4b4ea01436ba846201a27d4f2de'),
(4, '', '', '0.00', 'prcl_50b7f4b4ea01436ba846201a27d4f2de'),
(5, '', '', '0.00', 'prcl_50b7f4b4ea01436ba846201a27d4f2de'),
(6, 'Namey', 'This is a desc.', '1.11', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(8, 'Penguin', 'This is a penguin.', '35.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(9, 'Penguin', 'This is a penguin.', '35.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(10, 'Penguin', 'This is a penguin.', '35.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(11, 'Penguin', 'This is a penguin.', '35.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(12, 'Pikachu', 'Shocking.', '5.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(13, 'Penguin', 'This is a penguin.', '35.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(14, 'Armadillo', 'This is a penguin.', '35.00', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(16, 'TestName', 'TestDescription', '88.99', 'prcl_50b7f4b4ea01436ba846201a27d4f2de'),
(17, 'UpdatedName', 'TestDescription', '88.95', 'prcl_70d86765e985444d9b5c234d68ec9943'),
(18, 'TestName', 'TestDescription', '88.99', 'prcl_70d86765e985444d9b5c234d68ec9943');

-- --------------------------------------------------------

--
-- Table structure for table `Shipments`
--

CREATE TABLE `Shipments` (
  `Id` int(11) NOT NULL,
  `RateType` varchar(25) NOT NULL,
  `Cost` decimal(10,2) NOT NULL,
  `Status` int(3) NOT NULL,
  `OrderId` int(11) NOT NULL,
  `EpLabelId` varchar(100) DEFAULT NULL,
  `EpShipmentId` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Shipments`
--

INSERT INTO `Shipments` (`Id`, `RateType`, `Cost`, `Status`, `OrderId`, `EpLabelId`, `EpShipmentId`) VALUES
(1, 'Overnight', '25.99', 1, 1, NULL, NULL),
(3, 'FirstClass', '5.35', 0, 3, NULL, NULL),
(4, 'Priority', '8.99', 0, 2, NULL, NULL),
(5, 'Standard', '3.55', 0, 10, NULL, NULL);

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `Shipments`
--
ALTER TABLE `Shipments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
