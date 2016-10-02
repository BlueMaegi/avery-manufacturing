<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetOrders()
{
	connect_to_db();
	$orders = do_query("SELECT o.*, c.Name FROM Orders o JOIN Customers c on c.Id = o.CustomerId","","");
	close_db();
	return $orders;
}

function GetOrder($orderId)
{
	connect_to_db();
	$order = do_query("SELECT o.*, c.Name as CustomerName, c.Address, c.City, c.State, c.Zip, c.Phone, c.Email, c.LastFour
	FROM Orders o JOIN Customers c ON c.id = o.CustomerId WHERE o.Id = ?","i", [$orderId]);
	close_db();
	return $order;
}

function CreateOrder($order)
{
	connect_to_db();
	$params[] = $order["customerId"];
	$params[] = $order["stripeChargeId"];
	$order = false;
	
	$existing = do_query("SELECT * FROM Customers WHERE Id = ?","i", $params);
	if($existing)
	{
		$id = do_query("INSERT INTO Orders (CustomerId, StripeChargeId, Date) VALUES(?, ?, NOW())", "ss", $params);
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
		$params[] = $order["stripeChargeId"];
		$params[] = $order["id"];	
		var_dump($params);
		
		do_query("UPDATE Orders SET StripeChargeId = ? WHERE Id = ?","si", $params);
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
		$order['stripeChargeId'] = null;
		
		if(array_key_exists("id", $data))
			$order["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("customerId", $data))
			$order["customerId"] = ValidateIntParam($data["customerId"]);
		if(array_key_exists("stripeChargeId", $data))
			$order["stripeChargeId"] = SanitizeString($data["stripeChargeId"], 100);
				
		if(array_key_exists("customerId", $order))
			return $order;
	}

	return false;
}

?>
