<?php
//declare (strict_types=1) //uncomment at php7

$status = session_status();
if($stattus == PHP_SESSION_NONE)
{
	session_start();
}

include_once __DIR__.'/../../../sys/config/dbcred.inc.php';

foreache ($A as $name => $val )
{
	define($name, $val);
}

$actions = ['event_edit' =>['object' => 'Calendar', 'method' => 'processForm', 'header' => 'Location: ../..']];

$dsn = "pgsql:host=".DB_HOST.";dbname=".DB_NAME;
$dbo = new PDO($dsn, 'ziom', 'ziomek');

if ($_POST['token'] == $_SESSION['token'] && isset($actions[$_POST['action']]))
{
	$use_array = $actions[$_POST['action']];
	$obj = new $use_array['object']($dbo);
	$method = $use_array['method'];
	if (TRUE === $msg=$obj->$method())
	{
		header($use_array['header']);
		exit;
	}
	else
	{
		die($msg);
	}
else
{
	header("Location: ../../");
	exit;
}
function __autoload($class_name)
{
	$filename = '../../../sys/class/class.'.strtolower($class_name).'.inc.php';
	if(file_exists($filename))
	{
		include_once $filename;
	}
}


}