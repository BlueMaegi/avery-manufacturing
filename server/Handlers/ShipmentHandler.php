<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetShipments()
{
	connect_to_db();
	$shipment = do_query("SELECT * FROM Shipments","","");
	close_db();
	return $shipment;
}

function GetShipment($id)
{
	connect_to_db();
	$shipment = do_query("SELECT * FROM Shipments WHERE id = ?","i", array($id));
	close_db();
	return $shipment;
}

function CreateShipment($shipment)
{
	connect_to_db();
	$params[] = $shipment["rateType"];
	$params[] = $shipment["cost"];
	$params[] = $shipment["status"];
	$params[] = $shipment["orderId"];
	$params[] = $shipment["epLabelId"];
	$params[] = $shipment["epShipmentId"];
	$params[] = $shipment["taxAmount"];
	
	$existingOrder = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($shipment["orderId"]));
	$shipment = false;
	
	if($existingOrder)
	{
		$id = do_query("INSERT INTO Shipments (RateType, Cost, Status, OrderId, EpLabelId, EpShipmentId, TaxAmount) VALUES(?, ?, ?, ?, ?, ?, ?)", "ssiisss", $params);
		$shipment = do_query("SELECT * FROM Shipments WHERE id = ?","i", array($id));
	}
	
	close_db();
	return $shipment;
}

function UpdateShipment($shipment)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM Shipments WHERE id = ?","i", array($shipment["id"]));
	if($existing)
	{
		$params[] = $shipment["rateType"];
		$params[] = $shipment["cost"];
		$params[] = $shipment["status"];
		$params[] = $shipment["epLabelId"];
		$params[] = $shipment["epShipmentId"];
		$params[] = $shipment["taxAmount"];
		$params[] = $shipment["id"];
		
		do_query("UPDATE Shipments SET RateType = ?, Cost = ?, Status = ?, EpLabelId = ?, EpShipmentId = ?, TaxAmount = ? WHERE Id = ?","ssisssi", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteShipment($id)
{
	connect_to_db();
	do_query("DELETE FROM Shipments WHERE id = ?","i", array($id));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateShipment($data)
{
	if(is_array($data))
	{
		$shipment = "";
		$shipment['id'] = null;
		$shipment['orderId'] = null;
		$shipment["epLabelId"] = null;
		$shipment["epShipmentId"] = null;
		$shipment["taxAmount"] = 0;
		
		if(array_key_exists("id", $data))
			$shipment["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("rateType", $data))
			$shipment["rateType"] = SanitizeString($data["rateType"], 25);
		if(array_key_exists("cost", $data))
			$shipment["cost"] = ValidateFloatParam($data["cost"]);
		if(array_key_exists("taxAmount", $data))
			$shipment["taxAmount"] = ValidateFloatParam($data["taxAmount"]);
		if(array_key_exists("status", $data))
			$shipment["status"] = ValidateIntParam($data["status"], 3);
		if(array_key_exists("orderId", $data))
			$shipment["orderId"] = ValidateIntParam($data["orderId"]);
		if(array_key_exists("epLabelId", $data))
			$shipment["epLabelId"] = SanitizeString($data["epLabelId"], 100);
		if(array_key_exists("epShipmentId", $data))
			$shipment["epShipmentId"] = SanitizeString($data["epShipmentId"], 100);
				
		if(array_key_exists("rateType", $shipment) && array_key_exists("cost", $shipment)
			&& array_key_exists("status", $shipment))
			return $shipment;
	}

	return false;
}

?>
