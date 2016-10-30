<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/stripe/init.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

\Stripe\Stripe::setApiKey(STRIPE_KEY);

function CreateCharge($token, $amount)
{
	$amount = intval($amount * 100);

	/*$response = \Stripe\Charge::create(array(
	  "amount" => $amount,
	  "currency" => "usd",
	  "source" => $token,
	  "description" => ""
	  //"capture" => false
	));
	
	return $response['id']; //TODO: catch errors*/
	
	return "ch_FAKE_CHARGE";
}

function CaptureCharge($id)
{
	//$charge = \Stripe\Charge::retrieve($id);
	//$charge->capture();
}

function RefundCharge($id, $amount)
{
	$amount = intval($amount * 100);

	$refund = \Stripe\Refund::create(array(
	  "charge" => $id,
	  "amount" => $amount
	));
	
	//return $refund? catch errors?
}

function GetCharge($id)
{
	return \Stripe\Charge::retrieve($id);
}

function CreateCardCustomer($token)
{
	/*$customer = \Stripe\Customer::create(array(
  	  "source" => $token,
  	  "description" => "Back-order Customer")
	);
	
	return $customer->id;
	*/
	
	return "ch_FAKE_CUSTOMER";
}

function ChargeCustomer($customerId, $amount)
{
	$amount = intval($amount * 100);

	/*$response = \Stripe\Charge::create(array(
  	  "amount"   => $amount,
  	  "currency" => "usd",
  	  "customer" => $customerId
  	));
  	
  	return $response['id'];
  	*/
  	
  	return "ch_FAKE_CHARGE";
}

?>