<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetOrders()
{
	//TODO: architect this so close_db is called only once
	//TODO: include other things that are useful with joins?
	$orders = do_query("SELECT * FROM Orders","","");
	close_db();
	return $orders;
}

function GetOrder($orderId)
{
	$order = do_query("SELECT * FROM Orders WHERE Id = ?","i", [$orderId]);
	close_db();
	return $order;
}

function CreateOrder($order)
{
	$params[] = $order["customerId"];
	$order = false;
	
	$existing = do_query("SELECT * FROM Customers WHERE Id = ?","i", $params);
	if($existing)
	{
		$id = do_query("INSERT INTO Orders (CustomerId, Date) VALUES(?, NOW())", "s", $params);
		$order = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($id));
	}
	
	close_db();
	return $order;
}

function UpdateOrder($order)
{
	$existing = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($order["id"]));
	if($existing)
	{
		//As of now, there's nothing on an order that should be modified...
		//So... Do nothing
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteOrder($orderId)
{
	//TODO: Cascade delete to orderItems?
	do_query("DELETE FROM Orders WHERE Id = ?","i", array($orderId));
	close_db();
	return true; //TODO: some sort of error handling?
}

?>
