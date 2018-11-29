<?php

	//$fileData = fopen('data.json', 'r');
	$content = null;

	/*
	require_once 'vendor/autoload.php';
	$loader = new Twig_Loader_Filesystem('/views');
	$twig = new Twig_Environment($loader);
	*/
	define('CORE_INDEX', 1);

	$action = $_GET['action'] ?? '';
	if ($action == 'addBook') {
		include 'form.php';
		exit();
	}
	if ($action) {
		include 'action.php';
		exit();
	}


?>
<html>
<head>
	<meta charset="utf-8">
	<link href="form.css" rel="stylesheet">
	<script src="scripts.js"></script>
</head>

<body>
	<?php
	if ($content) {
		echo 'Данных нет';
		//$twig->render();
	} else {
		include 'booksItems.php';
	}

	?>

</body>
</html>