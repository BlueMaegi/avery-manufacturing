<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/StripeHandler.php');
require_once(SERVROOT.'Handlers/OrderHandler.php');
require_once(SERVROOT.'Handlers/OrderItemHandler.php');
require_once(SERVROOT.'Handlers/ShipmentHandler.php');
require_once(SERVROOT.'Handlers/CustomerHandler.php');
require_once(SERVROOT.'Handlers/ProductHandler.php');
require_once(SERVROOT.'Handlers/InventoryHandler.php');
require_once(SERVROOT.'Handlers/InventoryHistoryHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidatePurchaseRequest();

if($command == "card" && isset($_POST['id']) && isset($_POST['amount']))
{
	$id = SanitizeString($_POST["id"], 35);
	$amount = ThrowInvalid(ValidateFloatParam($_POST['amount'], 2));
	
	$chargeId = CreateCharge($id, $amount);
	
	if($chargeId)
		echo json_encode($chargeId);
	else
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 403);
}

if($command == "shipping")
{

}

if($command == "complete" && isset($_POST['purchase']))
{
	$purchase = ValidatePurchase($_POST['purchase']);
	if($purchase && ValidatePurchaseProducts($purchase['orderItems']))
	{
		$customer = CreateCustomer($purchase['customer'])[0];
		$order['customerId'] = $customer['id'];
		$order = CreateOrder($order)[0];
			
		foreach($purchase['orderItems'] as $item)
		{
			$item['orderId'] = $order['id'];
			$inventoryList = GetInventoryByProduct($item['productId']);
			$currIdx = 0;
			$remainingQty = $item['quantity'];
			
			while($remainingQty > 0)
			{
				$inventory = $inventoryList[$currIdx];
				if($inventory['quantity'] > 0)
				{
					$toSubtract = min($inventory['quantity'], $remainingQty);
					$inventory['quantity'] -= $toSubtract;
					$inventory['locationId'] = $inventory['locationid'];//quick hack
					$inventory['productId'] = $inventory['productid'];
					UpdateInventoryItem($inventory);
					$remainingQty -= $toSubtract;
					
					$history['inventoryId'] = $inventory['id'];
					$history['quantity'] = $toSubtract;
					$history['eventType'] = TRANSACTIONS['Sale'];
					$hist = CreateHistory($history);
				}
				else
					$currIdx ++;
			}
			
			$newItem = CreateOrderItem($item);
		}
		
		CaptureCharge($purchase['chargeId']);
		
		$purchase['shipment']['orderId'] = $order['id'];
		$shipment = CreateShipment($purchase['shipment'])[0];
		//TODO: Purchase and download label
			
		//TODO: send receipt email	
		echo true;	
	}
	else
		header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidatePurchase($data)
{
	if(is_array($data))
	{
		$purchase = "";
		if(array_key_exists("customer", $data))
		{
			$customer = ValidateCustomer($data['customer']);
			if($customer)
				$purchase['customer'] = $customer;
		}
		
		if(array_key_exists("shipment", $data))
		{
			$shipment = ValidateShipment($data['shipment']);
			if($shipment)
				$purchase['shipment'] = $shipment;
		}
		
		if(array_key_exists("chargeId", $data) && SanitizeString($data['chargeId'], 50))
		{
			$purchase['chargeId'] = SanitizeString($data['chargeId'], 50);
		}
		
		if(array_key_exists("orderItems", $data) && is_array($data['orderItems']))
		{
			$cleanItems = [];
			foreach($data['orderItems'] as $item)
			{
				$clean = ValidateOrderItem($item);
				if($clean)
					$cleanItems[] = $clean;
			}
			
			if(count($cleanItems) > 0)
				$purchase['orderItems'] = $cleanItems;
		}
				
		if(array_key_exists("customer", $purchase) && array_key_exists("shipment", $purchase) 
			&& array_key_exists("chargeId", $purchase) && array_key_exists("orderItems", $purchase))
			return $purchase;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidatePurchaseProducts($items)
{
	foreach($items as $i)
	{
		if(!CheckProductExists($i['productId']))
			return false;
			
		if(!CheckInventoryExists($i['productId'], $i['quantity']))
			return false;
	}
	
	return true;
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
	"chargeId":"",
	"orderItems":[
		{"productId":"","quantity":"","taxAmount":"","discount":""},
		{"productId":"","quantity":"","taxAmount":"","discount":""},
		{"productId":"","quantity":"","taxAmount":"","discount":""}
	]
}*/
	

?>
