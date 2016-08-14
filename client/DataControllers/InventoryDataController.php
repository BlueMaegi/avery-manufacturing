<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
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
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetInventoryItem($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['inventory']))
{
	$item = ThrowInvalid(ValidateInventoryItem($_POST['inventory']));
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
	$item = ThrowInvalid(ValidateInventoryItem($_POST['inventory']));
	if($item && isset($item['id']))
	{
		$success = UpdateInventoryItem($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}
	else
		header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);	
}

if($command == "delete" && isset($_POST['id']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$success = DeleteInventory($id);
	
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

?>
