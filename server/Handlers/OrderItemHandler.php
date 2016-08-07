<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetOrderItems($orderId)
{
	//TODO: architect this so close_db is called only once
	$orders = do_query("SELECT * FROM OrderItems WHERE OrderId = ?","i",array($orderId));
	close_db();
	return $orders;
}

function GetOrderItem($orderId, $productId)
{
	$order = do_query("SELECT * FROM OrderItems WHERE OrderId = ? And ProductId = ?","ii", array($orderId, $productId));
	close_db();
	return $order;
}

function CreateOrderItem($orderItem)
{
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
	do_query("DELETE FROM OrderItems WHERE OrderId = ? AND ProductId = ?","ii", array($orderId, $productId));
	close_db();
	return true; //TODO: some sort of error handling?
}

?>
