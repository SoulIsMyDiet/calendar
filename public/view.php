<?php
//declare(strict_types=1);//uncomment at php7

if(isset($_GET['event_id']))
{
	$id = preg_replace('/[^0-9]/', '', $_GET['event_id']);//making sure we take care of int
	if (empty($id))
	{
		header("location: ./");
		exit;
	}
}
else//for example if wejust write */view.php in the browser

{
	header("Location: ./");
	exit;
}

include_once __DIR__.'/../sys/core/init.inc.php';

$page_title = "podejrzyj wydarzenie";
$css_files = ["style.css", "admin.css"];
include_once 'assets/common/header.inc.php';

$cal = new Calendar($dbo);

?>
<div id = "content">
<?php echo $cal->displayEvent($id);?> 
<a href="./">&laquo; Powrót do Kalendarza</a>
</div><!-- end #content -->
<?php
include_once 'assets/common/footer.inc.php';

?>
