<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/ProductHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateRequest();
//var_dump($command);
//var_dump($_POST);

if($command == "get" && !isset($_POST['id']))
{
	echo ToJson(GetProducts());
}

if($command == "get" && isset($_POST['id']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	$item = GetProduct($id);
	
	if($item)
		echo ToJson($item);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
}

if($command == "create" && isset($_POST['product']))
{
	if(ValidateToken())
	{
		$item = ThrowInvalid(ValidateProduct($_POST['product']));
		if($item)
		{
			$newProduct = CreateProduct($item);
	
			if($newProduct)
				echo ToJson($newProduct);
			else 
				header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
		}
	}
}

if($command == "update" && isset($_POST['product']))
{
	if(ValidateToken())
	{
		$item = ThrowInvalid(ValidateProduct($_POST['product']));
		if($item && isset($item['id']))
		{
			$success = UpdateProduct($item);
			if($success)
				echo $success;
			else 
				header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
		}
		else
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	}
}

if($command == "delete" && isset($_POST['id']))
{
	if(ValidateToken())
	{
		$id = ThrowInvalid(ValidateIntParam($_POST['id']));
		$success = DeleteProduct($id);
		if($success)
			echo $success;
		else 
			header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);	
	}
}

?>
