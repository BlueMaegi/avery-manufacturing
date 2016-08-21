<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/OrderHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

try
{
	$command = ValidateRequest();

	if($command == "get" && !isset($_POST['id']))
	{
		ValidateToken();
		SetResult(ToJson(GetOrders()));
	}

	if($command == "get" && isset($_POST['id']))
	{
		$id = ThrowInvalid(ValidateIntParam($_POST['id']));
		$item = GetOrder($id);
	
		if(!$item)
			throw new Exception("Not Found", 404);
	
		SetResult(ToJson($item));
	}

	if($command == "create" && isset($_POST['order']))
	{
		$item = ThrowInvalid(ValidateOrder($_POST['order']));
		$newOrder = CreateOrder($item);
	
		SetResult(ToJson($newOrder));
	}

	if($command == "update" && isset($_POST['order']))
	{
		$item = ThrowInvalid(ValidateOrder($_POST['order']));
		if(!isset($item['id']))
			throw new Exception("Invalid Request", 400);
		
		$success = UpdateOrder($item);
		SetResult($success);
	}

	if($command == "delete" && isset($_POST['id']))
	{
		ValidateToken();
		$id = ThrowInvalid(ValidateIntParam($_POST['id']));
	
		$success = DeleteOrder($id);
		SetResult($success);
	}
	
	ReturnResult();
}
catch(Exception $e)
{
	ReturnError($e->getCode(), $e->getMessage());
}

?>
