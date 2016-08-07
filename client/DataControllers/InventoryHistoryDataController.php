<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/InventoryHistoryHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']) && isset($_POST['inventoryId']))
{
	echo ToJson(GetHistories());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetInventoryHistory($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['inventoryHistory']))
{
	$item = ValidateHistory($_POST['inventoryHistory']);
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
	$item = ValidateHistory($_POST['inventoryHistory']);
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
	$id = ValidateIntParam($_POST['id']);
	$success = DeleteHistory($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

	
function ValidateHistory($data)
{
	if(is_array($data))
	{
		$history = "";
		if(array_key_exists("id", $data))
			$history["id"] = substr(intval($data["id"]), 0, 11);
		if(array_key_exists("inventoryId", $data))
			$history["inventoryId"] = substr(intval($data["inventoryId"]), 0, 11);
		if(array_key_exists("eventType", $data))
			$history["eventType"] = substr(intval($data["eventType"]), 0, 3);
				
		if(array_key_exists("inventoryId", $history) && array_key_exists("eventType", $history))
			return $history;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
