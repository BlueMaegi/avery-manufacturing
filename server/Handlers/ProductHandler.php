<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetProducts()
{
	connect_to_db();
	$products = do_query("SELECT * FROM Products","","");
	close_db();
	return $products;
}

function GetProduct($productId)
{
	connect_to_db();
	$product = do_query("SELECT * FROM Products WHERE Id = ?","i", [$productId]);
	close_db();
	return $product;
}

function CreateProduct($product)
{
	connect_to_db();
	$params[] = $product["name"];
	$params[] = $product["description"];
	$params[] = $product["price"];
	
	$id = do_query("INSERT INTO Products (Name, Description, Price) VALUES(?, ?, ?)", "sss", $params);
	$product = do_query("SELECT * FROM Products WHERE Id = ?","i", array($id));

	return $product;
	close_db();
}

function UpdateProduct($product)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM Products WHERE Id = ?","i", array($product["id"]));
	if($existing)
	{
		$params[] = $product["name"];
		$params[] = $product["description"];
		$params[] = $product["price"];
		$params[] = $product["id"];	
		do_query("UPDATE Products SET Name = ?, Description = ?, Price = ? WHERE Id = ?","sssi", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteProduct($productId)
{
	connect_to_db();
	do_query("DELETE FROM Products WHERE Id = ?","i", array($productId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateProduct($data)
{
	if(is_array($data))
	{
		$product = "";
		$product["description"] = null;
		$product['id'] = null;
		
		if(array_key_exists("id", $data))
			$product["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("name", $data))
			$product["name"] = SanitizeString($data["name"], 50);
		if(array_key_exists("description", $data))
			$product["description"] = SanitizeString($data["description"], 100);
		if(array_key_exists("price", $data))
			$product["price"] = ValidateFloatParam($data["price"]);
				
		if(array_key_exists("name", $product) && array_key_exists("price", $product) 
			&& array_key_exists("description", $product))
			return $product;
	}

	return false;
}

?>
