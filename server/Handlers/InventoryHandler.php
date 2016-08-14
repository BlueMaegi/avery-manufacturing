<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetInventory($locationId)
{
	//TODO: architect this so close_db is called only once
	$inventory = do_query("SELECT * FROM Inventory WHERE LocationId = ?","i",array($locationId));
	close_db();
	return $inventory;
}

function GetInventoryItem($id)
{
	$inventory = do_query("SELECT * FROM Inventory WHERE id = ?","i", array($id));
	close_db();
	return $inventory;
}

function CreateInventoryItem($inventoryItem)
{
	$params[] = $inventoryItem["locationId"];
	$params[] = $inventoryItem["productId"];
	$params[] = $inventoryItem["quantity"];
	$inventory = false;
	
	$existingLocation = do_query("SELECT * FROM Locations WHERE Id = ?","i", array($inventoryItem["locationId"]));
	$existingProduct = do_query("SELECT * FROM Products WHERE Id = ?","i", array($inventoryItem["productId"]));

	if($existingLocation && $existingProduct)
	{
		$id = do_query("INSERT INTO Inventory (LocationId, ProductId, Quantity) VALUES(?, ?, ?)", "iii", $params);
		$inventory = do_query("SELECT * FROM Inventory WHERE Id = ?","i", array($id));
	}
	
	close_db();
	return $inventory;
}

function UpdateInventoryItem($inventoryItem)
{
	$existing = do_query("SELECT * FROM Inventory WHERE id = ?","i", array($inventoryItem["id"]));
	if($existing)
	{
		$params[] = $inventoryItem["quantity"];
		$params[] = $inventoryItem["locationId"];
		$params[] = $inventoryItem["productId"];
		$params[] = $inventoryItem["id"];
		do_query("UPDATE Inventory SET Quantity = ?, LocationId = ?, ProductId = ? WHERE Id = ?","iiii", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteInventory($id)
{
	do_query("DELETE FROM Inventory WHERE id = ?","i", array($id));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateInventoryItem($data)
{
	if(is_array($data))
	{
		$inventory = "";
		if(array_key_exists("id", $data))
			$inventory["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("locationId", $data))
			$inventory["locationId"] = ValidateIntParam($data["locationId"]);
		if(array_key_exists("productId", $data))
			$inventory["productId"] = ValidateIntParam($data["productId"]);
		if(array_key_exists("quantity", $data))
			$inventory["quantity"] = ValidateIntParam($data["quantity"]);
				
		if(array_key_exists("locationId", $inventory) && array_key_exists("productId", $inventory)
			&& array_key_exists("quantity", $inventory))
			return $inventory;
	}
	
	return false;
}

?>
