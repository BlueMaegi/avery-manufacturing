-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Sep 18, 2016 at 01:04 AM
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
(2, 'admin', '7b88aa1efa047a9f1cfecf1eea9f3c11a2364309434f5d18772052b88f829f07ddf1abd8fc82a373759ace8d4cb92a72f1e8b124fa32a7a99194889ae5166ced', '4c75b51f');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`Id`, `Name`, `Email`, `Address`, `City`, `State`, `Zip`, `LastFour`, `Phone`, `DateCreated`, `EpAddressId`) VALUES
(1, 'First Customer', 'test@test.com', '1 Rainbow Rd', 'Latham', 'NY', '12110', 4242, '2147483647', '2016-08-02 20:50:18', NULL),
(2, 'Ramsay Bolton', 'another@sometime.com', '175 Paine St', 'Green Island', 'NY', '12183', 5555, '5182736781', '2016-08-02 20:57:24', NULL),
(3, 'Arya Stark', 'something@sometime.com', '155 West Commercial St', 'East Rochester', 'NY', '14445', 8094, '5184662174', '2016-08-02 20:58:24', 'adr_e358ab7b1b004fb99f8070859b209012'),
(4, 'Missandei', 'wiseone@naath.com', '25th Pebble Tree', 'Vaes Dothrak', 'ES', '00800', 3333, '7778889999', '2016-08-13 21:01:59', NULL),
(12, 'Davos Seaworth', 'davos@stormsend.com', 'West Tower Room 6', 'Dragonstone', 'SL', 'BB123', 4444, '1234567890', '2016-08-14 16:48:21', NULL),
(13, 'Joffrey', 'horrible@stormsend.com', '1 Iron Throne', 'Kings Landing', 'CL', '12345', 4444, '7778889999', '2016-08-20 16:22:47', 'adr_4dad3f0d3f9940b3a887b9ae88c8c649'),
(14, 'Joffrey Baratheon', 'horrible@stormsend.com', '2182 5th Ave', 'Troy', 'NY', '12180', 4242, '1234567890', '2016-08-20 20:55:12', 'adr_012990246b054be2be84190e0d8551fd'),
(15, 'Joffrey Baratheon', 'horrible@stormsend.com', '2182 5th Ave', 'Troy', 'NY', '12180', 4242, '1234567890', '2016-08-20 20:56:13', 'adr_012990246b054be2be84190e0d8551fd'),
(16, 'Joffrey Baratheon', 'horrible@stormsend.com', '2182 5th Ave', 'Troy', 'NY', '12180', 4242, '1234567890', '2016-08-20 22:16:15', NULL),
(17, 'Joe Cupcakes', 'joe@mallorys.com', '17 liftbridge lane', 'Fairport', 'NY', '14450', 4242, '5853334444', '2016-09-17 18:04:09', NULL),
(18, 'Joe Cupcakes', 'joe@mallorys.com', '17 liftbridge lane', 'Fairport', 'NY', '14450', 4242, '5853334444', '2016-09-17 18:06:27', NULL),
(19, 'Joe Cupcakes', 'joe@mallorys.com', '17 liftbridge lane', 'Fairport', 'NY', '14450', 4242, '5853334444', '2016-09-17 18:10:01', NULL),
(20, 'Joe Cupcakes', 'joe@mallorys.com', '17 liftbridge lane', 'fairport', 'NY', '14450', 4242, '5851234567', '2016-09-17 18:11:40', NULL),
(21, 'Joe Cupcake', 'joe@cupcakes.com', '17 liftbridge lane', 'fairport', 'NY', '14450', 4242, '5857773333', '2016-09-17 18:50:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE `Inventory` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `LocationId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Inventory`
--

INSERT INTO `Inventory` (`Id`, `ProductId`, `LocationId`, `Quantity`) VALUES
(1, 11, 1, 2),
(4, 12, 1, 2),
(5, 16, 1, 15),
(7, 16, 1, 23),
(8, 16, 1, 23),
(9, 3, 1, 39);

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `InventoryHistory`
--

INSERT INTO `InventoryHistory` (`Id`, `InventoryId`, `Date`, `EventType`, `Quantity`) VALUES
(5, 5, '2016-08-13 23:37:05', 1, 10),
(7, 1, '2016-08-13 23:37:27', 1, 2),
(8, 4, '2016-08-13 23:37:38', 1, 3),
(15, 4, '2016-08-14 16:48:22', 2, 1),
(16, 5, '2016-08-14 16:48:22', 2, 3),
(17, 9, '2016-08-20 20:00:32', 1, 40),
(19, 4, '2016-08-20 20:55:12', 2, 1),
(20, 5, '2016-08-20 20:55:12', 2, 1),
(21, 4, '2016-08-20 20:56:13', 2, 1),
(22, 5, '2016-08-20 20:56:13', 2, 1),
(23, 9, '2016-08-20 22:16:15', 2, 1),
(24, 9, '2016-09-17 18:04:09', 2, 1),
(25, 4, '2016-09-17 18:04:09', 2, 2),
(26, 9, '2016-09-17 18:06:27', 2, 1),
(27, 4, '2016-09-17 18:06:27', 2, 2),
(28, 9, '2016-09-17 18:10:01', 2, 1),
(29, 4, '2016-09-17 18:10:01', 2, 2),
(30, 9, '2016-09-17 18:11:40', 2, 1),
(31, 4, '2016-09-17 18:11:40', 2, 2),
(32, 9, '2016-09-17 18:50:33', 2, 1),
(33, 4, '2016-09-17 18:50:33', 2, 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Locations`
--

INSERT INTO `Locations` (`Id`, `Name`, `Address`, `City`, `State`, `Zip`, `Phone`, `PrimaryContact`, `EpAddressId`) VALUES
(1, 'Green Island', '177 Paine St', 'Green Island', 'NY', '12183', '5181234567', 'Kristy Avery', 'adr_be5cedf8d53048f88506d1e5a9cec982'),
(2, 'Latham', '25th Pebble Tree', 'Vaes Dothrak', 'NY', '12110', '7778889999', 'wiseone@naath.com', ''),
(3, 'Syracuse', '65th Pebble Tree', 'Syracuse', 'NY', '12510', '123456789', 'another@westeros.com', ''),
(4, 'Rockland', '678 State St', 'Rockland', 'NY', '11526', '7778889999', 'something@newyork.com', NULL);

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
(10, 16, 3, '6.00', '0.00', NULL),
(11, 3, 9, '55.00', '0.00', NULL),
(12, 12, 1, '3.50', '0.00', NULL),
(12, 16, 1, '6.00', '0.00', NULL),
(13, 12, 1, '3.50', '0.00', NULL),
(13, 16, 1, '6.00', '0.00', NULL),
(14, 3, 1, '13.50', '0.00', NULL),
(15, 3, 1, '0.00', '0.00', NULL),
(15, 12, 2, '0.00', '0.00', NULL),
(16, 3, 1, '0.00', '0.00', NULL),
(16, 12, 2, '0.00', '0.00', NULL),
(17, 3, 1, '0.00', '0.00', NULL),
(17, 12, 2, '0.00', '0.00', NULL),
(18, 3, 1, '0.00', '0.00', NULL),
(18, 12, 2, '0.00', '0.00', NULL),
(19, 3, 1, '0.00', '0.00', NULL),
(19, 12, 2, '0.00', '0.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `Id` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `CustomerId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`Id`, `Date`, `CustomerId`) VALUES
(1, '2016-08-02 22:26:22', 2),
(2, '2016-08-02 22:26:47', 1),
(3, '2016-08-06 14:41:37', 1),
(4, '2016-08-14 00:00:38', 4),
(10, '2016-08-14 16:48:21', 12),
(11, '2016-08-20 20:18:08', 3),
(12, '2016-08-20 20:55:12', 14),
(13, '2016-08-20 20:56:13', 15),
(14, '2016-08-20 22:16:15', 16),
(15, '2016-09-17 18:04:09', 17),
(16, '2016-09-17 18:06:27', 18),
(17, '2016-09-17 18:10:01', 19),
(18, '2016-09-17 18:11:40', 20),
(19, '2016-09-17 18:50:32', 21);

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`Id`, `Name`, `Description`, `Price`, `EpParcelId`) VALUES
(1, 'Prody', 'This is a product thing', '7.99', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(2, 'Another Prod', 'This is fifty cent thingy', '10.50', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(3, 'Cashews', 'Very crunchy', '4.95', 'prcl_bff7cea6558a4e15b1dad7f329a7411d'),
(4, '', '', '0.00', 'prcl_bff7cea6558a4e15b1dad7f329a7411d'),
(6, 'Namey', 'This is a desc.', '1.11', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(8, 'Penguin', 'This is a penguin.', '35.00', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(9, 'Penguin', 'This is a penguin.', '35.00', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(10, 'Penguin', 'This is a penguin.', '35.00', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(11, 'Penguin', 'This is a penguin.', '35.00', 'prcl_29e5d32e3e144248b0477e9a8adc7d24'),
(12, 'Pikachu', 'Shocking.', '5.00', 'prcl_41e3e5bb50a04377b0b9145b7a5e479a'),
(13, 'Penguin', 'This is a penguin.', '35.00', 'prcl_41e3e5bb50a04377b0b9145b7a5e479a'),
(14, 'Armadillo', 'This is a penguin.', '35.00', 'prcl_41e3e5bb50a04377b0b9145b7a5e479a'),
(16, 'TestName', 'TestDescription', '88.99', 'prcl_bff7cea6558a4e15b1dad7f329a7411d'),
(17, 'UpdatedName', 'TestDescription', '88.95', 'prcl_41e3e5bb50a04377b0b9145b7a5e479a'),
(18, 'TestName', 'TestDescription', '88.99', 'prcl_41e3e5bb50a04377b0b9145b7a5e479a'),
(19, 'Except Name', 'Meanderthal', '43.99', 'prcl_bff7cea6558a4e15b1dad7f329a7411d');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Shipments`
--

INSERT INTO `Shipments` (`Id`, `RateType`, `Cost`, `Status`, `OrderId`, `EpLabelId`, `EpShipmentId`) VALUES
(1, 'Overnight', '25.99', 1, 1, NULL, NULL),
(3, 'FirstClass', '5.35', 0, 3, NULL, NULL),
(4, 'Priority', '8.99', 0, 2, NULL, NULL),
(6, 'Priority', '5.99', 0, 11, NULL, NULL),
(7, 'Priority', '5.77', 0, 12, 'rate_dccb229380014b8f9e6142c2f2fdc09c', 'shp_ec30fd69690c42c0b99e726dd5088904'),
(8, 'Priority', '5.77', 0, 13, 'rate_dccb229380014b8f9e6142c2f2fdc09c', 'shp_ec30fd69690c42c0b99e726dd5088904'),
(9, 'Priority', '5.77', 0, 14, 'rate_4b466e2b53e44f60a4c435781f4d276c', 'shp_7ab6ab8303584020a395ba8bcffe4734'),
(10, 'Priority', '5.95', 0, 15, NULL, NULL),
(11, 'Priority', '5.95', 0, 16, NULL, NULL),
(12, 'Priority', '5.95', 0, 17, NULL, NULL),
(13, 'First', '2.60', 0, 18, 'rate_aa298b78146546ba9f317d5d8323cef0', NULL),
(14, 'Priority', '5.95', 0, 19, 'rate_b793e4ca016b4a8eaa980b634dec8f48', 'shp_16c6e8d74b7c40738e73677824ee44f6');

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `InventoryHistory`
--
ALTER TABLE `InventoryHistory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `Shipments`
--
ALTER TABLE `Shipments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
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
