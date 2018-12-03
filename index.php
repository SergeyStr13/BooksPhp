<?php

	//$fileData = fopen('data.json', 'r');
	$content = null;

	/*
	require_once 'vendor/autoload.php';
	$loader = new Twig_Loader_Filesystem('/views');
	$twig = new Twig_Environment($loader);
	*/
	define('CORE_INDEX', 1);

	$view = '';
	include 'action.php';

?>
<html>
<head>
	<meta charset="utf-8">
	<link href="form.css" rel="stylesheet">
	<script src="scripts.js"></script>
</head>

<body>
	<div class="head"></div>
	<?php
		include $view;
		/*$action = $_GET['action'] ?? '';
		if ($action) {
			if ($action == 'formBook') {
				include 'form.php';
			} else {

			}
		} else {
			include 'booksItems.php';
		}*/


	?>

</body>
</html>