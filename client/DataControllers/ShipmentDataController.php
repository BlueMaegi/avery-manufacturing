<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/ShipmentHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetShipments());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetShipment($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['shipment']))
{
	$item = ValidateShipment($_POST['shipment']);
	if($item)
	{
		var_dump($item);
		$newShipment = CreateShipment($item);
	
		if($newShipment)
			echo ToJson($newShipment);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['shipment']))
{
	$item = ValidateShipment($_POST['shipment']);
	if($item)
	{
		$success = UpdateShipment($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
}

if($command == "delete" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$success = DeleteShipment($id);
	
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

	
function ValidateShipment($data)
{
	if(is_array($data))
	{
		$shipment = "";
		$shipment["epLabelId"] = null;
		$shipment["epShipmentId"] = null;
		
		if(array_key_exists("id", $data))
			$shipment["id"] = substr(intval($data["id"]), 0, 11);
		if(array_key_exists("rateType", $data))
			$shipment["rateType"] = substr($data["rateType"], 0, 25);
		if(array_key_exists("cost", $data))
			$shipment["cost"] = substr(floatval($data["cost"]), 0, 11);
		if(array_key_exists("status", $data))
			$shipment["status"] = substr(intval($data["status"]), 0, 3);
		if(array_key_exists("orderId", $data))
			$shipment["orderId"] = substr(intval($data["orderId"]), 0, 11);
		if(array_key_exists("epLabelId", $data))
			$shipment["epLabelId"] = substr($data["epLabelId"], 0, 100);
		if(array_key_exists("epShipmentId", $data))
			$shipment["epShipmentId"] = substr($data["epShipmentId"], 0, 100);
				
		if(array_key_exists("rateType", $shipment) && array_key_exists("cost", $shipment)
			&& array_key_exists("status", $shipment) && array_key_exists("orderId", $shipment))
			return $shipment;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
