<?php
define("WEBROOT", "http://".$_SERVER['HTTP_HOST'].'/');
define("DOCROOT", $_SERVER['DOCUMENT_ROOT'].'/');
define("SERVROOT", '/Applications/MAMP/server/');

define("DB_NAME", 'averymanufacturing');
define("DB_UNAME", 'server_app');
define("DB_PWD", 'password');

define("EXTRA_SALT", 'Capers');
define("IDX", 43);
define("EXPIRATION", 300);

define("STRIPE_KEY", 'sk_test_TD3vu14u4eVIBbDUfQ0kKWc5');
//define("STRIPE_KEY", 'sk_test_DwTrW0asvdfn4ujyMJcIAimy'); //DO NOT USE, THIS IS GAVANT'S

const TRANSACTIONS = array('Add_Inventory' => 1, "Sale" => 2, "Return" => 3);

?>
