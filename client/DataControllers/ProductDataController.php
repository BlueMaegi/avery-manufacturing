<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/ProductHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetProducts());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	$item = GetProduct($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "create" && isset($_POST['product']))
{
	ValidateToken();
	$item = ValidateProduct($_POST['product']);
	if($item)
	{
		$newProduct = CreateProduct($item);
	
		if($newProduct)
			echo ToJson($newProduct);
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
	}
}

if($command == "update" && isset($_POST['product']))
{
	ValidateToken();
	$item = ValidateProduct($_POST['product']);
	if($item)
	{
		$success = UpdateProduct($item);
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
	$success = DeleteProduct($id);
	if($success)
		echo $success;
	else 
		header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
}

	
function ValidateProduct($data)
{
	if(is_array($data))
	{
		$product = "";
		$product["description"] = null;
		
		if(array_key_exists("id", $data))
			$product["id"] = substr(intval($data["id"]), 0, 11);
		if(array_key_exists("name", $data))
			$product["name"] = substr(SanitizeString($data["name"]), 0, 50);
		if(array_key_exists("description", $data))
			$product["description"] = substr(SanitizeString($data["description"]), 0, 100);
		if(array_key_exists("price", $data))
			$product["price"] = floatval(substr(SanitizeString($data["price"]), 0, 15));
				
		if(array_key_exists("name", $product) && array_key_exists("price", $product) 
			&& array_key_exists("description", $product))
			return $product;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

?>
