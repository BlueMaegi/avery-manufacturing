<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
//-------------------------------------------------

$id = ValidateIntParam($_GET['id']);
$filePath = SERVROOT.'Labels/'.$id.'.pdf';

if ($id && file_exists($filePath)) {

	header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
	header("Content-Type: application/pdf");
	header("Content-Transfer-Encoding: Binary");
	header("Content-Length:".filesize($filePath));
	header("Content-Disposition: inline; filename=label_$id.pdf");
	//note: use "attachment" ^ disposition to force download
	readfile($filePath);
	die();        
} else {
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
} 

?>

