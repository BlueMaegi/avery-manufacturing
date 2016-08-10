<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Handlers/UserHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateUserRequest();

if($command == "login" && isset($_POST['name']) && isset($_POST['word']))
{
	$name = substr(SanitizeString($_POST['name']), 0, 50);
	$pass = substr(SanitizeString($_POST['word']), 0, 150);
	$token = Login($name, $pass);
	echo ToJson($token);
}

if($command == "logout" && isset($_POST['id']))
{
	$id = ValidateIntParam($_POST['id']);
	Logout($id);
	echo true;
}
	

if($command == "refresh" && isset($_POST['id']) && && isset($_POST['auth']))
{
	$id = ValidateIntParam($_POST['id']);
	$old = substr(SanitizeString($_POST['auth']), 0, 130);
	$new = RefreshToken($id, $old);
	return $new;
}		

	//echo GenerateToken(2);
	//echo CheckToken(2, '0490808e9b25409b4e67052786682909aa219f747c54a4908c9b62325a012bff27359a40c5ba3008da1efabfb1179745947751790a37f73d069cc5e83f1d783d11e1cc0d0b5');
	//echo ValidatePassword('admin', 'concrete2');
?>
