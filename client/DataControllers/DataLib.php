<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
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

function ValidateIntParam($data)
{
	$integer = intval(substr($data,0,11));
	if($integer > 0)
		return $integer;
		
	header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request", true, 400);
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
