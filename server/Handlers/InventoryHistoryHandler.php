<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetHistories($inventoryId)
{
	//TODO: architect this so close_db is called only once
	//TODO: include other things that are useful with joins?
	$histories = do_query("SELECT * FROM InventoryHistory WHERE InventoryId = ?","i", [$inventoryId]);
	close_db();
	return $histories;
}

function GetInventoryHistory($historyId)
{
	$history = do_query("SELECT * FROM InventoryHistory WHERE Id = ?","i", array($historyId));
	close_db();
	return $history;
}

function CreateHistory($history)
{
	$params[] = $history["inventoryId"];
	$params[] = $history["eventType"];
	$params[] = $history["quantity"];
	$history = false;
	
	$existing = do_query("SELECT * FROM Inventory WHERE Id = ?","i", $params);
	if($existing)
	{	
		$id = do_query("INSERT INTO InventoryHistory (InventoryId, EventType, Quantity, Date) VALUES(?, ?, ?, NOW())", "iis", $params);
		$history = do_query("SELECT * FROM InventoryHistory WHERE Id = ?","i", array($id));
	}
	
	close_db();
	return $history;
}

function UpdateHistory($history)
{
	$existing = do_query("SELECT * FROM Inventory WHERE Id = ?","i", array($history["id"]));
	if($existing)
	{
		//As of now, there's nothing on an item history that should be modified...
		//So... Do nothing
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteHistory($historyId)
{
	do_query("DELETE FROM InventoryHistory WHERE Id = ?","i", array($historyId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateHistory($data)
{
	if(is_array($data))
	{
		$history = "";
		if(array_key_exists("id", $data))
			$history["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("inventoryId", $data))
			$history["inventoryId"] = ValidateIntParam($data["inventoryId"]);
		if(array_key_exists("quantity", $data))
			$history["quantity"] = ValidateIntParam($data["quantity"]);
		if(array_key_exists("eventType", $data) && ValidateIntParam($data["eventType"], 3))
		{
			$event = ValidateIntParam($data["eventType"], 3);
			if(in_array($event, TRANSACTIONS))
				$history["eventType"] = ValidateIntParam($data["eventType"], 3);
		}
					
		if(array_key_exists("inventoryId", $history) && array_key_exists("eventType", $history)
			&& array_key_exists("quantity", $history))
			return $history;
	}

	return false;
}

?>
