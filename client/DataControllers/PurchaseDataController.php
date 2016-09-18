<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/StripeHandler.php');
require_once(SERVROOT.'Handlers/EasyPostHandler.php');
require_once(SERVROOT.'Handlers/OrderHandler.php');
require_once(SERVROOT.'Handlers/OrderItemHandler.php');
require_once(SERVROOT.'Handlers/ShipmentHandler.php');
require_once(SERVROOT.'Handlers/CustomerHandler.php');
require_once(SERVROOT.'Handlers/ProductHandler.php');
require_once(SERVROOT.'Handlers/InventoryHandler.php');
require_once(SERVROOT.'Handlers/InventoryHistoryHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

try
{
	$command = ValidateRequest('purchase');
	
	if($command == "card" && isset($_POST['id']) && isset($_POST['amount']))
	{
		$id = SanitizeString($_POST["id"], 35);
		$amount = ThrowInvalid(ValidateFloatParam($_POST['amount'], 2));
	
		$chargeId = CreateCharge($id, $amount);
	
		SetResult(json_encode($chargeId));
	}

	if($command == "shipping" && isset($_POST['address']))
	{
		$address = ThrowInvalid(ValidateAddress($_POST['address']));

		$epAddress = VerifyAddress($address);
		if(!isset($epAddress['object']))
			throw new Exception("Invalid Request ".$epAddress, 400);
	
		SetResult(json_encode($epAddress['id']));
	}

	if($command == "rates" && isset($_POST['addressId']) && isset($_POST['productId']))
	{
		$addressId = ThrowInvalid(SanitizeString($_POST["addressId"], 50));
		$productId = ThrowInvalid(ValidateIntParam($_POST['productId']));

		$rates = GetShipmentRates($addressId, $productId, 1); //HARD CODED LOCATION HERE
		SetResult(json_encode($rates));
	}
	

	//TODO: clean this up so it only takes a stripe card id, easypost rate id, customer email, 
	//and a list of productIds & quantities
	if($command == "complete" && isset($_POST['purchase']))
	{
		$purchase = ThrowInvalid(ValidatePurchase($_POST['purchase']));
		ThrowInvalid(ValidatePurchaseProducts($purchase['orderItems']));

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
		$label = PurchaseLabel($shipment['eplabelid'], $shipment['epshipmentid']);
		SaveLabelImage($shipment['id'], $label);
		
		//TODO: send receipt email	
		SetResult(json_encode($label));	
	}

	ReturnResult();
}
catch(Exception $e)
{
	ReturnError($e->getCode(), $e->getMessage());
}




function ValidatePurchase($data)
{
	if(!is_array($data))
		throw new Exception("Invalid Request", 400);
	
	$purchase = "";
	if(array_key_exists("customer", $data))
	{
		$customer = ThrowInvalid(ValidateCustomer($data['customer']));
		$purchase['customer'] = $customer;
	}
	
	if(array_key_exists("shipment", $data))
	{
		$shipment = ThrowInvalid(ValidateShipment($data['shipment']));
		$purchase['shipment'] = $shipment;
	}
	
	if(array_key_exists("chargeId", $data) && SanitizeString($data['chargeId'], 50))
	{
		$purchase['chargeId'] = ThrowInvalid(SanitizeString($data['chargeId'], 50));
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
