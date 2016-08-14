<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/OrderItemHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && isset($_POST['orderId']) && !isset($_POST['productId']))
{
	ValidateToken();
	$id = ThrowInvalid(ValidateIntParam($_POST['orderId']));
	echo ToJson(GetOrderItems($id));
}

if($command == "get" && isset($_POST['orderId']) && isset($_POST['productId']))
{
	ValidateToken();
	$orderId = ThrowInvalid(ValidateIntParam($_POST['orderId']));
	$productId = ThrowInvalid(ValidateIntParam($_POST['productId']));
	$item = GetOrderItem($orderId, $productId);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "create" && isset($_POST['orderItem']))
{
	$item = ThrowInvalid(ValidateOrderItem($_POST['orderItem']));
	if($item)
	{
		$newOrder = CreateOrderItem($item);
	
		if($newOrder)
			echo ToJson($newOrder);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['orderItem']))
{
	$item = ThrowInvalid(ValidateOrderItem($_POST['orderItem']));
	if($item)
	{
		$success = UpdateOrderItem($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
	else
		header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

if($command == "delete" && isset($_POST['orderId']) && isset($_POST['productId']))
{
	ValidateToken();
	$orderId = ThrowInvalid(ValidateIntParam($_POST['orderId']));
	$productId = ThrowInvalid(ValidateIntParam($_POST['productId']));
	$success = DeleteOrderItem($orderId, $productId);
	
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

?>
