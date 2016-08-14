<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
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
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetCustomer($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "create" && isset($_POST['customer']))
{
	$item = ThrowInvalid(ValidateCustomer($_POST['customer']));
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
	$item = ThrowInvalid(ValidateCustomer($_POST['customer']));
	if($item && isset($item['id']))
	{
		$success = UpdateCustomer($item);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}	
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "delete" && isset($_POST['id']))
{
	ValidateToken();
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$success = DeleteCustomer($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

?>
