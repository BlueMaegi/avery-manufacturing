<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/OrderItemHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && isset($_POST['orderId']) && !isset($_POST['productId']))
{
	$id = ValidateIntParam($_POST['orderId']);
	echo ToJson(GetOrderItems($id));
}

if($command == "get" && isset($_POST['orderId']) && isset($_POST['productId']))
{
	$orderId = ValidateIntParam($_POST['orderId']);
	$productId = ValidateIntParam($_POST['productId']);
	$item = GetOrderItem($orderId, $productId);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['orderItem']))
{
	$item = ValidateOrderItem($_POST['orderItem']);
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
	$item = ValidateOrderItem($_POST['orderItem']);
	if($item)
	{
		$success = UpdateOrderItem($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
}

if($command == "delete" && isset($_POST['orderId']) && isset($_POST['productId']))
{
	$orderId = ValidateIntParam($_POST['orderId']);
	$productId = ValidateIntParam($_POST['productId']);
	$success = DeleteOrderItem($orderId, $productId);
	
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

	
function ValidateOrderItem($data)
{
	if(is_array($data))
	{
		$order = "";
		$order["discount"] = null;
		$order["shipmentId"] = null;
		
		if(array_key_exists("orderId", $data))
			$order["orderId"] = substr(intval($data["orderId"]), 0, 11);
		if(array_key_exists("productId", $data))
			$order["productId"] = substr(intval($data["productId"]), 0, 11);
		if(array_key_exists("quantity", $data))
			$order["quantity"] = substr(intval($data["quantity"]), 0, 11);
		if(array_key_exists("taxAmount", $data))
			$order["taxAmount"] = substr(floatval($data["taxAmount"]), 0, 11);
		if(array_key_exists("discount", $data))
			$order["discount"] = substr(floatval($data["discount"]), 0, 11);
		if(array_key_exists("shipmentId", $data))
			$order["shipmentId"] = substr(intval($data["shipmentId"]), 0, 11);

		if(array_key_exists("orderId", $order) && array_key_exists("productId", $order)
			&& array_key_exists("quantity", $order) && array_key_exists("taxAmount", $order))
			return $order;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
