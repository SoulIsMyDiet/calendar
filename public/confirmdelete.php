<?php

//declare(strict_types=1); // uncomment ay php7
$status = session_status();
if ($status == PHP_SESSION_NONE)
{
	session_start();
}
if (isset($_POST['event_id']) && isset($_SESSION['user']))
{
	$id = (int)$_POST['event_id'];
echo $id;
print_r($_POST);
print_r($_SESSION);
}
else
{
	header("Location: ./");
	exit;
}
include_once '../sys/core/init.inc.php';

$cal = new Calendar($dbo);
$markup = $cal->confirmDelete($id);
$page_title = "Zobacz wydarzenie";
$css_files = ["style.css", "admin.css"];
include_once 'assets/common/header.inc.php';

?>
<div id="content">
<?php echo $markup.$id; ?>
</div><!-- end #content -->

<?php

include_once 'assets/common/footer.inc.php';

?>
