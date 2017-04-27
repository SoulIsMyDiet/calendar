<!DOCTYPE html>

<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<title><?php echo $page_title; ?></title>
<?php foreach($css_files as $css): ?>
<link rel="stylesheet" type="text/css" media="screen,projection" href = "assets/css/<?php echo $css; ?>" />
<?php endforeach; ?>
</head>
<body>
