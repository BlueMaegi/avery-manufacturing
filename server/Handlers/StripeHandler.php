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

	$response = \Stripe\Charge::create(array(
	  "amount" => $amount,
	  "currency" => "usd",
	  "source" => $token,
	  "description" => "",
	  "capture" => false
	));
	
	return $response['id']; //TODO: catch errors
}

function CaptureCharge($id)
{
	$charge = \Stripe\Charge::retrieve($id);
	$charge->capture();
	//TODO: more catch errors
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

?>