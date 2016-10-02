<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetOrderItems($orderId)
{
	connect_to_db();
	$orders = do_query("SELECT oi.*, p.Name as ProductName, p.Price FROM OrderItems oi JOIN Products p on p.Id = oi.ProductId WHERE oi.OrderId = ?","i",array($orderId));
	close_db();
	return $orders;
}

function GetOrderItem($orderId, $productId)
{
	connect_to_db();
	$order = do_query("SELECT * FROM OrderItems WHERE OrderId = ? And ProductId = ?","ii", array($orderId, $productId));
	close_db();
	return $order;
}

function CreateOrderItem($orderItem)
{
	connect_to_db();
	$params[] = $orderItem["orderId"];
	$params[] = $orderItem["productId"];
	$params[] = $orderItem["quantity"];
	$params[] = $orderItem["taxAmount"];
	$params[] = $orderItem["discount"];
	$order = false;
	
	$existingOrder = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($orderItem["orderId"]));
	$existingProduct = do_query("SELECT * FROM Products WHERE Id = ?","i", array($orderItem["productId"]));
	if($existingOrder && $existingProduct)
	{
		do_query("INSERT INTO OrderItems (OrderId, ProductId, Quantity, TaxAmount, Discount) VALUES(?, ?, ?, ?, ?)", "iiiss", $params);
		$order = do_query("SELECT * FROM OrderItems WHERE orderId = ? AND productId = ?","ii", array($orderItem["orderId"], $orderItem["productId"]));
	}
	
	close_db();
	return $order;
}

function UpdateOrderItem($orderItem)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM OrderItems WHERE OrderId = ? AND ProductId = ?","ii", array($orderItem["orderId"], $orderItem["productId"]));
	if($existing)
	{
		$params[] = $orderItem["quantity"];
		$params[] = $orderItem["taxAmount"];
		$params[] = $orderItem["discount"];
		$params[] = $orderItem["shipmentId"];
		$params[] = $orderItem["orderId"];
		$params[] = $orderItem["productId"];
		
		do_query("UPDATE OrderItems SET Quantity = ?, TaxAmount = ?, Discount = ?, shipmentId = ? WHERE OrderId = ? AND ProductId = ?","issiii", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteOrderItem($orderId, $productId)
{
	connect_to_db();
	do_query("DELETE FROM OrderItems WHERE OrderId = ? AND ProductId = ?","ii", array($orderId, $productId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateOrderItem($data)
{
	if(is_array($data))
	{
		$order = "";
		$order["orderId"] = null;
		$order["discount"] = null;
		$order["shipmentId"] = null;
		
		if(array_key_exists("orderId", $data))
			$order["orderId"] = ValidateIntParam($data["orderId"]);
		if(array_key_exists("productId", $data))
			$order["productId"] = ValidateIntParam($data["productId"]);
		if(array_key_exists("quantity", $data))
			$order["quantity"] = ValidateIntParam($data["quantity"]);
		if(array_key_exists("taxAmount", $data))
			$order["taxAmount"] = ValidateFloatParam($data["taxAmount"]);
		if(array_key_exists("discount", $data))
			$order["discount"] = ValidateFloatParam($data["discount"]);
		if(array_key_exists("shipmentId", $data))
			$order["shipmentId"] = ValidateIntParam($data["shipmentId"]);

		if(array_key_exists("orderId", $order) && array_key_exists("productId", $order)
			&& array_key_exists("quantity", $order) && array_key_exists("taxAmount", $order))
			return $order;
	}

	return false;
}

?>
