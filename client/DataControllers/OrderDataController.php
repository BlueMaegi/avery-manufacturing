<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/OrderHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	ValidateToken();
	echo ToJson(GetOrders());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetOrder($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "create" && isset($_POST['order']))
{
	$item = ThrowInvalid(ValidateOrder($_POST['order']));
	if($item)
	{
		$newOrder = CreateOrder($item);
	
		if($newOrder)
			echo ToJson($newOrder);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['order']))
{
	$item = ThrowInvalid(ValidateOrder($_POST['order']));
	if($item && isset($item['id']))
	{
		$success = UpdateOrder($item);
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
	$success = DeleteOrder($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

?>
