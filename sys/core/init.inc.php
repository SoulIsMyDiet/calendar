<?php

declare(strict_types=1);

include_once __DIR__.'/../config/dbcred.inc.php';

//defining configuration constants
foreach( $A as $name => $val)
{
	define($name, $val);
}
//$dbs = "pgsql:host=".DB_HOST.";dbname=".DB_NAME;
//$dbo = new PDO($dbs, DB_USER, DB_PASS);

$dbs = "pgsql:host=localhost;dbname=calendar";
$dbo = new PDO($dbs, 'ziom', 'ziomek');
function __autoload($class){
	$filename = __DIR__."/../class/class.".$class.".inc.php";
	if(file_exists($filename))
	{
		include_once $filename;
	}
}
