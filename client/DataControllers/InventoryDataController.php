<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/InventoryHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();
ValidateToken();

if($command == "get" && isset($_POST['locationId']) && !isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['locationId']);
	echo ToJson(GetInventory($id));
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetInventoryItem($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['inventory']))
{
	$item = ValidateInventoryItem($_POST['inventory']);
	if($item)
	{
		$newInventory = CreateInventoryItem($item);
	
		if($newInventory)
			echo ToJson($newInventory);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['inventory']))
{
	$item = ValidateInventoryItem($_POST['inventory']);
	if($item)
	{
		$success = UpdateInventoryItem($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
}

if($command == "delete" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$success = DeleteInventory($id);
	
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

	
function ValidateInventoryItem($data)
{
	if(is_array($data))
	{
		$inventory = "";
		if(array_key_exists("id", $data))
			$inventory["id"] = substr(intval($data["id"]), 0, 11);
		if(array_key_exists("locationId", $data))
			$inventory["locationId"] = substr(intval($data["locationId"]), 0, 11);
		if(array_key_exists("productId", $data))
			$inventory["productId"] = substr(intval($data["productId"]), 0, 11);
		if(array_key_exists("quantity", $data))
			$inventory["quantity"] = substr(intval($data["quantity"]), 0, 11);
				
		if(array_key_exists("locationId", $inventory) && array_key_exists("productId", $inventory)
			&& array_key_exists("quantity", $inventory))
			return $inventory;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
