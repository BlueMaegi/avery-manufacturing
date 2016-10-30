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
	

	if($command == "complete" && isset($_POST['purchase']))
	{
		$purchase = ThrowInvalid(ValidatePurchase($_POST['purchase']));
		ThrowInvalid(ValidatePurchaseProducts($purchase['orderItems']));

		$customer = CreateCustomer($purchase['customer'])[0];
		$order['customerId'] = $customer['id'];
		$order = ValidateOrder($order);
		$order = CreateOrder($order)[0];
		$purchase['shipment']['orderId'] = $order['id'];
		
		$taxRate = ($customer["state"] == "NY")? 0.08 : 0.0;
		$purchase['shipment']['taxAmount'] = $purchase['shipment']['cost'] * $taxRate;
		$shipment = CreateShipment($purchase['shipment'])[0];
		
		$totalCharge = $purchase['shipment']['taxAmount'] + $purchase['shipment']['cost'];
		
		$sufficientStock = true;	
		foreach($purchase['orderItems'] as $item)
		{
			$item['orderId'] = $order['id'];
			$item['shipmentId'] = $shipment['id'];
			$product = GetProduct($item['productId'])[0];
	
			if(!CheckInventoryExists($item['productId'], $item['quantity']))
				$sufficientStock = false;
			
			$item['taxAmount'] = $product['price'] * $taxRate * $item['quantity'];
			$totalCharge += $product['price'] + $item['taxAmount'];
			$newItem = CreateOrderItem($item);
		}
		
		if($sufficientStock)
		{
			$stripeId = CreateCharge($purchase['cardId'], $totalCharge, true);
			$order['stripeChargeId'] = $stripeId;
			UpdateOrder($order);
			foreach($purchase['orderItems'] as $item)
				ProcessInventory($order['id'], $item['productId']);
		}
		else
		{
			$stripeId = CreateCardCustomer($purchase['cardId']);
			$customer['stripeCustomerId'] = $stripeId;
			$customer['lastFour'] = $customer['lastfour'];
			$customer['epAddressId'] = $customer['epaddressid'];
			UpdateCustomer($customer);
			$shipment['status'] = 5;
			$shipment['rateType'] = $shipment['ratetype'];
			$shipment['taxAmount'] = $shipment['taxamount'];
			$shipment['epLabelId'] = $shipment['eplabelid'];
			$shipment['epShipmentId'] = $shipment['epshipmentid'];
			UpdateShipment($shipment);		
		}
		
		$label = PurchaseLabel($shipment['eplabelid'], $shipment['epshipmentid']);
		SaveLabelImage($shipment['id'], $label);
		
		//TODO: send receipt email	
		SetResult(json_encode($totalCharge));	
	}
	
	if($command == "backorder" && isset($_POST['orderId']))
	{
		//check order exists & sufficient inventory
		//calculate charge amount
			//shipping + ship tax
			//(product price * quantity) + orderItem tax
		//charge stripe
		//update order with charge ID
		//process inventory
		//update shipment to status 0
		
	}

	ReturnResult();
}
catch(Exception $e)
{
	ReturnError($e->getCode(), $e->getMessage());
}


function ProcessInventory($orderId, $productId)
{
	$item = GetOrderItem($orderId, $productId)[0];
	$inventoryList = GetInventoryByProduct($productId);
	$currIdx = 0;
	$inStock = 0;
	$remainingQty = $item['quantity'];
	
	if(!CheckInventoryExists($productId, $item['quantity']))
		throw new Exception("Insufficient inventory", 500);

	while($remainingQty > 0 && $inventoryList[$currIdx])
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
}


function ValidatePurchase($data)
{
	if(!is_array($data))
		throw new Exception("Invalid Request", 400);
	
	$purchase = "";
	if(array_key_exists("epLabelId", $data))
	{
		$customer = GetCustomerFromEp(SanitizeString($data['epLabelId'], 100));
		if(array_key_exists("lastFour", $data)) 
			$customer["lastFour"] = ThrowInvalid(ValidateIntParam($data['lastFour'], 4));
		if(array_key_exists("email", $data)) 
			$customer["email"] = ThrowInvalid(SanitizeString($data['email'], 200));
		$customer = ThrowInvalid(ValidateCustomer($customer));
		$purchase['customer'] = $customer;

		$shipment = GetShipmentFromEp(SanitizeString($data['epLabelId'], 100));
		$shipment["status"] = 0;
		$shipment = ThrowInvalid(ValidateShipment($shipment));
		$purchase['shipment'] = $shipment;
	}
	
	if(array_key_exists("cardId", $data) && SanitizeString($data['cardId'], 50))
	{
		$purchase['cardId'] = ThrowInvalid(SanitizeString($data['cardId'], 50));
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
		&& array_key_exists("cardId", $purchase) && array_key_exists("orderItems", $purchase))
		return $purchase;
}

function ValidatePurchaseProducts($items)
{
	foreach($items as $i)
	{
		if(!CheckProductExists($i['productId']))
			return false;
			
	}
	
	return true;
}

/*
"purchase":{
	"cardId":"",
	"lastFour":"",
	"epLabelId":"",
	"orderItems":[
		{"productId":"","quantity":"","taxAmount":"","discount":""},
		{"productId":"","quantity":"","taxAmount":"","discount":""},
		{"productId":"","quantity":"","taxAmount":"","discount":""}
	]
}*/
	

?>
