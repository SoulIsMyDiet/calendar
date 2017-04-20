<?php

declare(strict_types=1);

include_once '/var/www/html/calendar/sys/config/dbcred.inc.php';

//defining configuration constants
foreach( $A as $name => $val)
{
	define($name, $val);
}
$dbs = "pgsql:host=".DB_HOST.";dbname=".DB_NAME;
$dbo = new PDO($dbs, DB_USER, DB_PASS);

function __autoload($class){
	$filename = "/var/www/html/calendar/sys/class/class.".$class.".inc.php";
	if(file_exists($filename))
	{
		include_once $filename;
	}
}
