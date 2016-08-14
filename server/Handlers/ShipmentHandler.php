<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetShipments()
{
	//TODO: architect this so close_db is called only once
	$shipment = do_query("SELECT * FROM Shipments","","");
	close_db();
	return $shipment;
}

function GetShipment($id)
{
	$shipment = do_query("SELECT * FROM Shipments WHERE id = ?","i", array($id));
	close_db();
	return $shipment;
}

function CreateShipment($shipment)
{
	$params[] = $shipment["rateType"];
	$params[] = $shipment["cost"];
	$params[] = $shipment["status"];
	$params[] = $shipment["orderId"];
	$params[] = $shipment["epLabelId"];
	$params[] = $shipment["epShipmentId"];
	
	$existingOrder = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($shipment["orderId"]));
	$shipment = false;
	
	if($existingOrder)
	{
		$id = do_query("INSERT INTO Shipments (RateType, Cost, Status, OrderId, EpLabelId, EpShipmentId) VALUES(?, ?, ?, ?, ?, ?)", "ssiiss", $params);
		$shipment = do_query("SELECT * FROM Shipments WHERE id = ?","i", array($id));
	}
	
	close_db();
	return $shipment;
}

function UpdateShipment($shipment)
{
	$existing = do_query("SELECT * FROM Shipments WHERE id = ?","i", array($shipment["id"]));
	if($existing)
	{
		$params[] = $shipment["rateType"];
		$params[] = $shipment["cost"];
		$params[] = $shipment["status"];
		$params[] = $shipment["epLabelId"];
		$params[] = $shipment["epShipmentId"];
		$params[] = $shipment["id"];
		do_query("UPDATE Shipments SET RateType = ?, Cost = ?, Status = ?, EpLabelId = ?, EpShipmentId = ? WHERE Id = ?","ssissi", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteShipment($id)
{
	do_query("DELETE FROM Shipments WHERE id = ?","i", array($id));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateShipment($data)
{
	if(is_array($data))
	{
		$shipment = "";
		$shipment["epLabelId"] = null;
		$shipment["epShipmentId"] = null;
		
		if(array_key_exists("id", $data))
			$shipment["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("rateType", $data))
			$shipment["rateType"] = SanitizeString($data["rateType"], 25);
		if(array_key_exists("cost", $data))
			$shipment["cost"] = ValidateFloatParam($data["cost"]);
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
