<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/LocationHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();
ValidateToken();

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetLocations());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetLocation($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['location']))
{
	$item = ThrowInvalid(ValidateLocation($_POST['location']));
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
	$item = ThrowInvalid(ValidateLocation($_POST['location']));
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
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$success = DeleteLocation($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}



?>
