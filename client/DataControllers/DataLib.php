<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/UserHandler.php');
//-------------------------------------------------

function ValidateRequest()
{
	$restFunctions = ["get", "create", "update", "delete"];
	if(isset($_POST['func']))
	{
		$command = strtolower(substr($_POST['func'], 0, 7));
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
	$restFunctions = ["address", "shipping", "card", "complete"];
	if(isset($_POST['func']))
	{
		$command = strtolower(substr($_POST['func'], 0, 9));
	 	if(in_array($command, $restFunctions))
	 		return $command;
	}
	
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidateIntParam($data)
{
	$integer = intval(substr($data,0,11));
	if($integer > 0)
		return $integer;
		
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidateFloatParam($data, $decimals)
{
	$float = round(floatval(substr($data,0,15)), $decimals);
	if($float > 0)
		return $float;
		
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
}

function ValidateToken()
{
	if(isset($_POST['authId']) && isset($_POST['auth']))
	{
		$id = intval(substr($_POST['authId'], 0, 11));
		$token = SanitizeString(substr($_POST['auth'], 0, 150)); 
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

function SanitizeString($dirty)
{
	return $dirty;
}

?>
