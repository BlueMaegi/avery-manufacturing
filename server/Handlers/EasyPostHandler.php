<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/easypost/easypost.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------
\EasyPost\EasyPost::setApiKey(EASYPOST_KEY);

function VerifyAddress($address)
{
	$params = array(
		"verify"  => array("delivery"),
		"verify_strict" => false,
		"street1" => $address["street1"],
		"street2" => $address["street2"],
		"city"    => $address["city"] ,
		"state"   => $address["state"] ,
		"zip"     => $address["zip"] ,
		"country" => "US",
		"company" => $address["company"] ,
		"phone"   => $address["phone"],
		"name"	  => $address["name"]  
	);

	$address = \EasyPost\Address::create($params);
	if($address['verifications']['delivery']['success'])
		return $address;
	else
		return $address['verifications']['delivery']['errors'];
}

function GetShipmentRates($addressId, $productId, $locationId)
{
	connect_to_db();
	
	$originId = do_query("SELECT epAddressId FROM Locations WHERE Id = ?","i", array($locationId))[0]['epaddressid'];
	$parcelId = do_query("SELECT epParcelId FROM Products WHERE Id = ?", "i", array($productId))[0]['epparcelid'];
	close_db();
	
	$originAddress = \EasyPost\Address::retrieve($originId);
	$parcel = \EasyPost\Parcel::retrieve($parcelId);
	$toAddress = \EasyPost\Address::retrieve($addressId);
	
	$shipment = \EasyPost\Shipment::create(array(
	  "to_address" => $toAddress,
	  "from_address" => $originAddress,
	  "parcel" => $parcel
	));
	
	if(isset($shipment['rates']) && count($shipment['rates']) > 0)
	{
		$rates = "";
		foreach($shipment['rates'] as $rate)
		{
			$currRate['cost'] = $rate['rate'];
			$currRate['name'] = $rate['service'];
			$currRate['id'] = $rate['id'];
			$currRate['shipmentId'] = $rate['shipment_id'];
			$currRate['carrier'] = $rate['carrier'];
			
			$rates[] = $currRate;
		}
		
		return $rates;
	}
	return false;
}

function CreateParcel($parcel)
{
	$epParcel = \EasyPost\Parcel::create(array(
	  "length" => $parcel['length'],
	  "width" => $parcel['width'],
	  "height" => $parcel['height'],
	  "weight" => $parcel['weight']
	));
	
	return $epParcel;
}

function PurchaseLabel($rateId, $shipmentId)
{
	$shipment = \EasyPost\Shipment::retrieve($shipmentId);
	$label = $shipment->buy($rateId)['postage_label']['label_pdf_url'];
	return $label;
}

function ValidateAddress($data)
{
	if(is_array($data))
	{
		$address = "";
		$address['company'] = null;
		$address['street2'] = null;
		$address['name'] = null;
		
		if(array_key_exists("street1", $data))
			$address["street1"] = SanitizeString($data["street1"]);
		if(array_key_exists("street2", $data))
			$address["street2"] = SanitizeString($data["street2"]);
		if(array_key_exists("city", $data))
			$address["city"] = SanitizeString($data["city"]);
		if(array_key_exists("state", $data))
			$address["state"] = SanitizeString($data["state"], 3);	
		if(array_key_exists("zip", $data))
			$address["zip"] = SanitizeString($data["zip"], 15);
		if(array_key_exists("company", $data))
			$address["company"] = SanitizeString($data["company"]);
		if(array_key_exists("phone", $data))
			$address["phone"] = SanitizeString($data["phone"], 15);
		if(array_key_exists("name", $data))
			$address["name"] = SanitizeString($data["name"]);

		if(array_key_exists("street1", $address) && array_key_exists("city", $address)
			&& array_key_exists("state", $address) && array_key_exists("zip", $address)
			&& array_key_exists("phone", $address) 
			&& (array_key_exists("name", $address) || array_key_exists("company", $address)))
			return $address;
	}
	
	return false;
}

function ValidateParcel($data)
{
	if(is_array($data))
	{
		$parcel = "";
		if(array_key_exists("length", $data))
			$parcel["length"] = ValidateFloatParam($data["length"]);
		if(array_key_exists("width", $data))
			$parcel["width"] = ValidateFloatParam($data["width"]);
		if(array_key_exists("height", $data))
			$parcel["height"] = ValidateFloatParam($data["height"]);
		if(array_key_exists("weight", $data))
			$parcel["weight"] = ValidateFloatParam($data["weight"]);
			
		if(array_key_exists("length", $parcel) && array_key_exists("width", $parcel)
			&& array_key_exists("height", $parcel) && array_key_exists("weight", $parcel))
			return $parcel;
	}
	
	return false;
}

function SaveLabelImage($shipmentId, $url)
{

}

?>
