<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/ShipmentHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	ValidateToken();
	echo ToJson(GetShipments());
}

if($command == "get" && isset($_POST['id']))
{
	ValidateToken();
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetShipment($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "create" && isset($_POST['shipment']))
{
	$item = ThrowInvalid(ValidateShipment($_POST['shipment']));
	if($item)
	{
		$newShipment = CreateShipment($item);
	
		if($newShipment)
			echo ToJson($newShipment);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['shipment']))
{
	$item = ThrowInvalid(ValidateShipment($_POST['shipment']));
	if($item && isset($item['id']))
	{
		$success = UpdateShipment($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "delete" && isset($_POST['id']))
{
	ValidateToken();
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$success = DeleteShipment($id);
	
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

?>
