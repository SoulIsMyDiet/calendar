<?php

//declare (strict_types=1);

include_once __DIR__.'/../sys/core/init.inc.php';

$page_title = "Dodaj/edytuj wydarzenie";
$css_files = ["style.css", "admin.css"];
include_once 'assets/common/header.inc.php';

$cal = new Calendar($dbo);

?>
<div id="content">
<?php echo $cal->displayForm(); ?>
</div><!-- end #content -->
<?php

include_once 'assets/common/footer.inc.php';

?>
