<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/CustomerHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetCustomers());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetCustomer($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['customer']))
{
	$item = ValidateCustomer($_POST['customer']);
	if($item)
	{
		$newCustomer = CreateCustomer($item);
	
		if($newCustomer)
			echo ToJson($newCustomer);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['customer']))
{
	ValidateToken();
	$item = ValidateCustomer($_POST['customer']);
	if($item)
	{
		$success = UpdateCustomer($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
}

if($command == "delete" && isset($_POST['id']))
{
	ValidateToken();
	$id = ValidateIntParam($_POST['id']);
	$success = DeleteCustomer($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

function ValidateCustomer($data)
{
	if(is_array($data))
	{
		$customer = "";
		if(array_key_exists("id", $data))
			$customer["id"] = intval(substr($data["id"], 0, 11));
		if(array_key_exists("name", $data))
			$customer["name"] = substr(SanitizeString($data["name"]), 0, 50);
		if(array_key_exists("email", $data))
			$customer["email"] = substr(SanitizeString($data["email"]), 0, 200);
		if(array_key_exists("address", $data))
			$customer["address"] = substr(SanitizeString($data["address"]), 0, 50);
		if(array_key_exists("city", $data))
			$customer["city"] = substr(SanitizeString($data["city"]), 0, 30);
		if(array_key_exists("state", $data))
			$customer["state"] = substr(SanitizeString($data["state"]), 0, 3);
		if(array_key_exists("zip", $data))
			$customer["zip"] = substr(SanitizeString($data["zip"]), 0, 10);
		if(array_key_exists("lastFour", $data))
			$customer["lastFour"] = intval(substr(SanitizeString($data["lastFour"]), 0, 4));
		if(array_key_exists("phone", $data))
			$customer["phone"] = substr(SanitizeString($data["phone"]), 0, 10);
				
		if(array_key_exists("name", $customer) && array_key_exists("email", $customer) 
			&& array_key_exists("address", $customer) && array_key_exists("city", $customer)
			&& array_key_exists("state", $customer) && array_key_exists("zip", $customer)
			&& array_key_exists("lastFour", $customer) && array_key_exists("phone", $customer))
			return $customer;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
