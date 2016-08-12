<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/StripeHandler.php');
require_once(SERVROOT.'Handlers/OrderHandler.php');
require_once(SERVROOT.'Handlers/OrderItemHandler.php');
require_once(SERVROOT.'Handlers/ShipmentHandler.php');
require_once(SERVROOT.'Handlers/CustomerHandler.php');
require_once(SERVROOT.'Handlers/InventoryHandler.php');
require_once(SERVROOT.'Handlers/InventoryHistoryHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidatePurchaseRequest();

if($command == "card" && isset($_POST['id']) && isset($_POST['amount']))
{
	$id = substr(SanitizeString($data["id"]), 0, 35);
	$amount = ValidateFloatParam($_POST['amount'], 2);
	
	$chargeId = CreateCharge($id, $amount);
	
	if($chargeId)
		echo json_encode($chargeId);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "complete")
{
	//1. Create Customer
	//2. Create Order
	//3. Create Shipment(s)
	//4. Create Order Items
		//4.2 Subtract from Inventory
		//4.3 Create Inventory History
	//5. Capture Card Charge
	//6. Purchase EP Label
		//6.2 Trigger Label download
	//7. Send Receipt Email
}

/*
"purchase":{
	"customer":{
		"name":"",
		"address":"",
		"email":"",
		"city":"",
		"state":"",
		"zip":"",
		"phone":"",
		"lastFour":""
	},
	"shipment":{
		"rateType":"",
		"cost":"",
		"status":"", ??
		"EP-rateId":""
	},
	"card":{"token":"", "amount":""},
	"orderItems":[
		{"productId":"","quantity":"","taxAmount":"","discount":""},
		{"productId":"","quantity":"","taxAmount":"","discount":""},
		{"productId":"","quantity":"","taxAmount":"","discount":""}
	]
}*/
	

?>
