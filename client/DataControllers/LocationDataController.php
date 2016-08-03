<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/LocationHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetLocations());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetLocation($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['location']))
{
	$item = ValidateLocation($_POST['location']);
	if($item)
	{
		$newLocation = CreateLocation($item);
	
		if($newLocation)
			echo ToJson($newLocation);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['location']))
{
	$item = ValidateLocation($_POST['location']);
	if($item)
	{
		$success = UpdateLocation($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
}

if($command == "delete" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$success = DeleteLocation($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

function ValidateLocation($data)
{
	if(is_array($data))
	{
		$location = "";
		if(array_key_exists("id", $data))
			$location["id"] = intval(substr($data["id"], 0, 11));
		if(array_key_exists("name", $data))
			$location["name"] = substr(SanitizeString($data["name"]), 0, 50);
		if(array_key_exists("address", $data))
			$location["address"] = substr(SanitizeString($data["address"]), 0, 50);
		if(array_key_exists("city", $data))
			$location["city"] = substr(SanitizeString($data["city"]), 0, 30);
		if(array_key_exists("state", $data))
			$location["state"] = substr(SanitizeString($data["state"]), 0, 3);
		if(array_key_exists("zip", $data))
			$location["zip"] = substr(SanitizeString($data["zip"]), 0, 10);
		if(array_key_exists("primaryContact", $data))
			$location["primaryContact"] = substr(SanitizeString($data["primaryContact"]), 0, 50);
		if(array_key_exists("phone", $data))
			$location["phone"] = substr(SanitizeString($data["phone"]), 0, 10);
				
		if(array_key_exists("name", $location) && array_key_exists("address", $location)
		 	&& array_key_exists("city", $location) && array_key_exists("state", $location) 
		 	&& array_key_exists("zip", $location) && array_key_exists("phone", $location))
			return $location;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
