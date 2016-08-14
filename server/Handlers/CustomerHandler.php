<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetCustomers()
{
	connect_to_db();
	$customers = do_query("SELECT * FROM Customers","","");
	close_db();
	return $customers;
}

function GetCustomer($customerId)
{
	connect_to_db();
	$customer = do_query("SELECT * FROM Customers WHERE Id = ?","i", [$customerId]);
	close_db();
	return $customer;
}

function CreateCustomer($customer)
{
	connect_to_db();
	$params[] = $customer["name"];
	$params[] = $customer["email"];
	$params[] = $customer["address"];
	$params[] = $customer["city"];
	$params[] = $customer["state"];
	$params[] = $customer["zip"];
	$params[] = $customer["lastFour"];
	$params[] = $customer["phone"];
	
	$id = do_query("INSERT INTO Customers (Name, Email, Address, City, State, Zip, LastFour, Phone, DateCreated)
	 VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())", "ssssssii", $params);
	$customer = do_query("SELECT * FROM Customers WHERE Id = ?","i", array($id));

	return $customer;
	close_db();
}

function UpdateCustomer($customer)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM Customers WHERE Id = ?","i", array($customer["id"]));
	if($existing)
	{
		$params[] = $customer["name"];
		$params[] = $customer["email"];
		$params[] = $customer["address"];
		$params[] = $customer["city"];
		$params[] = $customer["state"];
		$params[] = $customer["zip"];
		$params[] = $customer["lastFour"];
		$params[] = $customer["phone"];
		$params[] = $customer["id"];	
		
		do_query("UPDATE Customers SET Name = ?, Email = ?, Address = ?, City = ?, State = ?,
		Zip = ?, LastFour = ?, Phone = ? WHERE Id = ?","ssssssisi", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteCustomer($customerId)
{
	connect_to_db();
	do_query("DELETE FROM Customers WHERE Id = ?","i", array($customerId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateCustomer($data)
{
	if(is_array($data))
	{
		$customer = "";
		$customer['id'] = null;
		
		if(array_key_exists("id", $data) && ValidateIntParam($data["id"]))
			$customer["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("name", $data))
			$customer["name"] = SanitizeString($data["name"], 50);
		if(array_key_exists("email", $data))
			$customer["email"] = SanitizeString($data["email"], 200);
		if(array_key_exists("address", $data))
			$customer["address"] = SanitizeString($data["address"], 50);
		if(array_key_exists("city", $data))
			$customer["city"] = SanitizeString($data["city"], 30);
		if(array_key_exists("state", $data))
			$customer["state"] = SanitizeString($data["state"], 3);
		if(array_key_exists("zip", $data))
			$customer["zip"] = SanitizeString($data["zip"], 10);
		if(array_key_exists("lastFour", $data) && ValidateIntParam($data["lastFour"], 4))
			$customer["lastFour"] = ValidateIntParam($data["lastFour"], 4);
		if(array_key_exists("phone", $data))
			$customer["phone"] = SanitizeString($data["phone"], 10);
				
		if(array_key_exists("name", $customer) && array_key_exists("email", $customer) 
			&& array_key_exists("address", $customer) && array_key_exists("city", $customer)
			&& array_key_exists("state", $customer) && array_key_exists("zip", $customer)
			&& array_key_exists("lastFour", $customer) && array_key_exists("phone", $customer))
			return $customer;
	}

	return false;
}


?>
