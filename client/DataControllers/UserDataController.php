<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Handlers/UserHandler.php');
require_once('DataLib.php');
//-------------------------------------------------

$command = ValidateUserRequest();

if($command == "login" && isset($_POST['name']) && isset($_POST['word']))
{
	$name = SanitizeString($_POST['name'], 50);
	$pass = SanitizeString($_POST['word'], 150);
	$token = Login($name, $pass);
	echo json_encode($token);
}

if($command == "logout" && isset($_POST['authId']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['authId']));
	Logout($id);
	echo true;
}
	

if($command == "refresh" && isset($_POST['authId']) && isset($_POST['auth']))
{
	$id = ThrowInvalid(ValidateIntParam($_POST['authId']));
	$old = SanitizeString($_POST['auth'], 150);
	$new = RefreshToken($id, $old);
	return $new;
}		

//echo ValidatePassword('admin', 'concrete');
?>
