<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/OrderHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetOrders());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetOrder($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['order']))
{
	$item = ValidateOrder($_POST['order']);
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
	$item = ValidateOrder($_POST['order']);
	if($item)
	{
		$success = UpdateOrder($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
}

if($command == "delete" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$success = DeleteOrder($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

	
function ValidateOrder($data)
{
	if(is_array($data))
	{
		$order = "";
		if(array_key_exists("id", $data))
			$order["id"] = substr(intval($data["id"]), 0, 11);
		if(array_key_exists("customerId", $data))
			$order["customerId"] = substr(intval($data["customerId"]), 0, 11);
				
		if(array_key_exists("customerId", $order))
			return $order;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
