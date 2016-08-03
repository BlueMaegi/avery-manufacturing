<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetProducts()
{
	//TODO: architect this so close_db is called only once
	$products = do_query("SELECT * FROM Products","","");
	close_db();
	return $products;
}

function GetProduct($productId)
{
	$product = do_query("SELECT * FROM Products WHERE Id = ?","i", [$productId]);
	close_db();
	return $product;
}

function CreateProduct($product)
{
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
	do_query("DELETE FROM Products WHERE Id = ?","i", array($productId));
	close_db();
	return true; //TODO: some sort of error handling?
}

?>
