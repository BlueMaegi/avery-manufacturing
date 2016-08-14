<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetOrders()
{
	connect_to_db();
	//TODO: include other things that are useful with joins?
	$orders = do_query("SELECT * FROM Orders","","");
	close_db();
	return $orders;
}

function GetOrder($orderId)
{
	connect_to_db();
	$order = do_query("SELECT * FROM Orders WHERE Id = ?","i", [$orderId]);
	close_db();
	return $order;
}

function CreateOrder($order)
{
	connect_to_db();
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
	connect_to_db();
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
	connect_to_db();
	//TODO: Cascade delete to orderItems?
	do_query("DELETE FROM Orders WHERE Id = ?","i", array($orderId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateOrder($data)
{
	if(is_array($data))
	{
		$order = "";
		$order['id'] = null;
		
		if(array_key_exists("id", $data))
			$order["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("customerId", $data))
			$order["customerId"] = ValidateIntParam($data["customerId"]);
				
		if(array_key_exists("customerId", $order))
			return $order;
	}

	return false;
}

?>
