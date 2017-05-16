<?php
//declare(strict_types=1);

include_once __DIR__.'/../sys/core/init.inc.php'; //lib initializing database

$cal = new Calendar ($dbo, "2017-05-01 12:00:00");
//$cal->_loadEventData(2);
$page_title = "Kalendarz";
$css_files=['style.css', 'admin.css'];

include_once 'assets/common/header.inc.php';
?>
<div id ="content">
<?php
echo $cal->buildCalendar();
//$cal->displayEvent(2);
?>
</div><!-- end #content -->
<p>
<?php
echo isset($_SESSION['user'] ? "Zalogowany" : "Wylogowany"
?>
</p>
<?php
include_once 'assets/common/footer.inc.php';

/*
if (is_object ($cal) )
{
	echo "<pre>", var_dump($cal), "</pre>";
}
 */
