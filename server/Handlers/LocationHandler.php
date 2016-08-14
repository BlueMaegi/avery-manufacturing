<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetLocations()
{
	connect_to_db();
	$locations = do_query("SELECT * FROM Locations","","");
	close_db();
	return $locations;
}

function GetLocation($locationId)
{
	connect_to_db();
	$location = do_query("SELECT * FROM Locations WHERE Id = ?","i", [$locationId]);
	close_db();
	return $location;
}

function CreateLocation($location)
{
	connect_to_db();
	$params[] = $location["name"];
	$params[] = $location["address"];
	$params[] = $location["city"];
	$params[] = $location["state"];
	$params[] = $location["zip"];
	$params[] = $location["phone"];
	$params[] = $location["primaryContact"];
	
	$id = do_query("INSERT INTO Locations (Name, Address, City, State, Zip, Phone, PrimaryContact)
	 	VALUES(?, ?, ?, ?, ?, ?, ?)", "sssssss", $params);
	$location = do_query("SELECT * FROM Locations WHERE Id = ?","i", array($id));
	
	return $location;
	close_db();
}

function UpdateLocation($location)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM Locations WHERE Id = ?","i", array($location["id"]));
	if($existing)
	{
		$params[] = $location["name"];
		$params[] = $location["address"];
		$params[] = $location["city"];
		$params[] = $location["state"];
		$params[] = $location["zip"];
		$params[] = $location["phone"];
		$params[] = $location["primaryContact"];
		$params[] = $location["id"];	
		
		do_query("UPDATE Locations SET Name = ?, Address = ?, City = ?, State = ?, Zip = ?,
			Phone = ?, PrimaryContact = ? WHERE Id = ?","sssssssi", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteLocation($locationId)
{
	connect_to_db();
	do_query("DELETE FROM Locations WHERE Id = ?","i", array($locationId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function ValidateLocation($data)
{
	if(is_array($data))
	{
		$location = "";
		$location['id'] = null;
		$location["primaryContact"] = null;
		
		if(array_key_exists("id", $data))
			$location["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("name", $data))
			$location["name"] = SanitizeString($data["name"], 50);
		if(array_key_exists("address", $data))
			$location["address"] = SanitizeString($data["address"], 50);
		if(array_key_exists("city", $data))
			$location["city"] = SanitizeString($data["city"], 30);
		if(array_key_exists("state", $data))
			$location["state"] = SanitizeString($data["state"], 3);
		if(array_key_exists("zip", $data))
			$location["zip"] = SanitizeString($data["zip"], 10);
		if(array_key_exists("phone", $data))
			$location["phone"] = SanitizeString($data["phone"], 10);
		if(array_key_exists("primaryContact", $data))
			$location["primaryContact"] = SanitizeString($data["primaryContact"], 50);
				
		if(array_key_exists("name", $location) && array_key_exists("address", $location)
		 	&& array_key_exists("city", $location) && array_key_exists("state", $location) 
		 	&& array_key_exists("zip", $location) && array_key_exists("phone", $location))
			return $location;
	}

	return false;
}

?>
