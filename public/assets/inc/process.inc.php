<?php
//declare (strict_types=1) //uncomment at php7

$status = session_status();
if($status == PHP_SESSION_NONE)
{
	session_start();
}
include_once __DIR__.'/../../../sys/config/dbcred.inc.php';

foreach ($A as $name => $val )
{
	define($name, $val);
}

$actions = ['event_edit' =>['object' => 'Calendar', 'method' => 'processForm', 'header' => 'Location: ../../'],
			'user_login' => ['object' => 'Admin', 'method' => 'processLoginForm', 'header' => 'Location: ../../'],
			'user_logout' => ['object' => 'Admin', 'method' => 'processLogout', 'header' => 'Location: ../../']
			];

$dsn = "pgsql:host=".DB_HOST.";dbname=".DB_NAME;
$dbo = new PDO($dsn, 'ziom', 'ziomek');

if ($_POST['token'] == $_SESSION['token'] && isset($actions[$_POST['action']]))
{
	$use_array = $actions[$_POST['action']];//$_POST['action'] should be 'event_edit'
	$obj = new $use_array['object']($dbo); //we are using table to describeinother words new Calenadr($dbo);
	$method = $use_array['method'];
	if (TRUE === $msg=$obj->$method())
	{
	//echo $obj->$method();
		header($use_array['header']);
		exit;
	}
	else
	{
		die($msg);
	}
}
else
{
	header("Location: ../../");
	exit;
}
function __autoload($class_name)
{
	$filename = '../../../sys/class/class.'.$class_name.'.inc.php';
	if(file_exists($filename))
	{
		include_once $filename;
	}
}



