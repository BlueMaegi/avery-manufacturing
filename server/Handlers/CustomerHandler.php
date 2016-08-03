<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetCustomers()
{
	//TODO: architect this so close_db is called only once
	$customers = do_query("SELECT * FROM Customers","","");
	close_db();
	return $customers;
}

function GetCustomer($customerId)
{
	$customer = do_query("SELECT * FROM Customers WHERE Id = ?","i", [$customerId]);
	close_db();
	return $customer;
}

function CreateCustomer($customer)
{
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
	do_query("DELETE FROM Customers WHERE Id = ?","i", array($customerId));
	close_db();
	return true; //TODO: some sort of error handling?
}

?>
