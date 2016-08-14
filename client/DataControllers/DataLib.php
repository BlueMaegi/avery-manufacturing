<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/UserHandler.php');
//-------------------------------------------------

function ValidateRequest()
{
	$restFunctions = ["get", "create", "update", "delete"];
	if(isset($_POST['func']))
	{
		$command = strtolower(SanitizeString($_POST['func'], 7));
	 	if(in_array($command, $restFunctions))
	 		return $command;
	}
	
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidateUserRequest()
{
	$restFunctions = ["login", "logout", "refresh"];
	if(isset($_POST['func']))
	{
		$command = strtolower(substr($_POST['func'], 0, 8));
	 	if(in_array($command, $restFunctions))
	 		return $command;
	}
	
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidatePurchaseRequest()
{
	$restFunctions = ["shipping", "card", "complete"];
	if(isset($_POST['func']))
	{
		$command = strtolower(substr($_POST['func'], 0, 9));
	 	if(in_array($command, $restFunctions))
	 		return $command;
	}
	
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ThrowInvalid($data)
{
	if($data == false || is_null($data) || $data === "")	
		header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
	
	return $data;
}

function ValidateToken()
{
	if(isset($_POST['authId']) && isset($_POST['auth']))
	{
		$id = ValidateIntParam($_POST['authId']);
		$token = SanitizeString($_POST['auth'], 150); 
		//var_dump($token);
		if(CheckToken($id, $token))
			return true;
	}

	header($_SERVER["SERVER_PROTOCOL"]." 403 Unauthorized", true, 403);
}

function ToJson($resultSet)
{
	$jsonResult = '{"data":[';

	if(is_array($resultSet))
	{
		foreach($resultSet as $obj)
		{
			$jsonResult .= "{";
		
			foreach($obj as $key => $value)
			{
				$jsonResult .= '"'.$key.'":"'.$value.'",';
			}
		
			$jsonResult = substr($jsonResult, 0, - 1);
			$jsonResult .= "},";
		}
		$jsonResult = substr($jsonResult, 0, - 1);
	}
	
	$jsonResult .= ']}';
	return $jsonResult;
}

?>
