<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/InventoryHistoryHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();
ValidateToken();

if($command == "get" && !isset($_POST['id']) && isset($_POST['inventoryId']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['inventoryId']));
	echo ToJson(GetHistories($id));
}

if($command == "get" && isset($_POST['id']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetInventoryHistory($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['inventoryHistory']))
{
	$item = ThrowInvalid(ValidateHistory($_POST['inventoryHistory']));
	if($item)
	{
		$newHistory = CreateHistory($item);
	
		if($newHistory)
			echo ToJson($newHistory);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['inventoryHistory']))
{
	$item = ThrowInvalid(ValidateHistory($_POST['inventoryHistory']));
	if($item)
	{
		$success = UpdateHistory($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}
}

if($command == "delete" && isset($_POST['id']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$success = DeleteHistory($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

?>
