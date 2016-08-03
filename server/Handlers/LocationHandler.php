<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetLocations()
{
	//TODO: architect this so close_db is called only once
	$locations = do_query("SELECT * FROM Locations","","");
	close_db();
	return $locations;
}

function GetLocation($locationId)
{
	$location = do_query("SELECT * FROM Locations WHERE Id = ?","i", [$locationId]);
	close_db();
	return $location;
}

function CreateLocation($location)
{
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
	do_query("DELETE FROM Locations WHERE Id = ?","i", array($locationId));
	close_db();
	return true; //TODO: some sort of error handling?
}

?>
