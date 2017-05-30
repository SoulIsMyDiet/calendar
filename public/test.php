<?php

include_once '../sys/core/init.inc.php';

$obj = new Admin($dbo);
$hash1 = $obj->testSaltedHash("test");
echo "Skrót \$hash1 bez ciągu zaburzającego:<br/>", $hash1, "<br/><br/>";
sleep(1);
$hash2 = $obj->testSaltedHash("test");
echo "skrót \$hash2 bez ciagu zaburzającego:<br/>", $hash2, "<br /><br />";
sleep(1);
$hash3 = $obj->testSaltedHash("test", $hash2);
echo "skrót \$hash3 z ciągiem zaburzającym \$hash2:<br/>", $hash3, "<br /><br />";
$pass = $obj->testSaltedHash("admin");
echo "skrót hasła 'admin' <br/>", $pass, "<br /><br />";

