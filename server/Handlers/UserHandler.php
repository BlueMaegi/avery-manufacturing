<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function Login($name, $pass)
{
	connect_to_db();
	$id = ValidatePassword($name, $pass);
	$token = '';
	if($id)
		$token = GenerateToken($id);

	$userData[] = $id;
	$userData[] = $token;
	
	close_db();
	return $userData;
}

function Logout($id)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM Clients WHERE Id = ?","i", array($id));
	if($existing && $existing[0])
	{
		do_query("UPDATE Clients SET Token = NULL WHERE Id = ?","i", array($id));
	}
	
	close_db();
}

function RefreshToken($id, $oldToken)
{
	connect_to_db();
	$token = false;
	if(CheckToken($id, $oldToken))
		$token = GenerateToken($id);
		
	close_db();	
	return $token;
}

function GenerateToken($id) //NOTE: This purposely does not manage its own db connection
{
	
	$existing = do_query("SELECT * FROM Clients WHERE Id = ?","i", array($id));
	if($existing && $existing[0])
	{
		$now = time();
		$random = hash('crc32', $now.$id);
		$hash = hash('sha512', $existing[0]['id'].$existing[0]['name'].$random);
		$skip = rand(3, 9);

		$token = substr_replace($hash, strval($skip), IDX, 0);
		for($i = 1; $i <= strlen($now); $i++)
		{
			$k = ($skip + 1) * $i * -1;
			$k += 1;
			$timeChar = substr($now, $i * -1, 1);
			$token = substr_replace($token, $timeChar, $k, 0);
		}

		do_query("UPDATE Clients SET Token = ? WHERE Id = ?","si", array($random, $id));
		
		return $token;
	}
	
	return false;
}

function ExternalCheckToken($id, $token)
{
	connect_to_db();
	return CheckToken($id, $token);
	close_db();
}

function CheckToken($id, $token)
{
	$existing = do_query("SELECT * FROM Clients WHERE Id = ?","i", array($id));
	if($existing && $existing[0])
	{
		$hash = hash('sha512', $existing[0]['id'].$existing[0]['name'].$existing[0]['token']);
		$now = time();
		
		$skip = substr($token, IDX, 1);
		$token = substr_replace($token, '', IDX, 1);
		
		$dateStr = '';
		for($i = 1; $i <= strlen($now); $i++)
		{
			$k = (($skip + 1) * $i) - ($i - 1);
			$k *= -1;
			$dateStr = substr($token, $k, 1).$dateStr;
			$token = substr_replace($token, '', $k, 1);
		}
		//var_dump($dateStr);
		$difference = $now - intval($dateStr);
		//var_dump($token);
		//var_dump($difference);
		//var_dump($hash);
		if($difference >= 0 && $difference < EXPIRATION && $token == $hash)
			return true;
			
	}
	
	return false;
}

function ValidatePassword($name, $pass)
{
	$existing = do_query("SELECT * FROM Clients WHERE Name = ?","s", array($name));
	if($existing && $existing[0])
	{
		$hash = hash('sha512', $pass.EXTRA_SALT);
		if($hash == $existing[0]['creds'])
			return $existing[0]['id'];
	}
	
	return false;
}

?>
