<?php

//declare(strict_types=1); // uncomment at php7

include_once '../sys/core/init.inc.php';

$page_title = "Zaloguj się";
$css_files = ["style.css", "admin.css"];

include_once 'assets/common/header.inc.php';

?>

<div id ="content">
	<form action="assets/inc/process.inc.php" method="post">
		<fieldset>
			<legend>Zaloguj się</legend>
			<label for="uname">Nazwa użytkownika </label>
			<input type="text" name="uname" id="uname" value="" />
			<label for="pword">Hasło </label>
			<input type="password" name="pword" id="pword" value="" />
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="user_login" />
			<input type="submit" name="login_submit" value="Zaloguj się" />
			lub <a href="./">anuluj</a>
		</fieldset>
	</form>
</div><!--end #content -->

<?php

include_once 'assets/common/footer.inc.php';

?>