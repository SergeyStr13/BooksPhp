<?php

	//$fileData = fopen('data.json', 'r');
	$content = null;

	/*
	require_once 'vendor/autoload.php';
	$loader = new Twig_Loader_Filesystem('/views');
	$twig = new Twig_Environment($loader);
	*/
?>
<html>
<head>
	<meta charset="utf-8">
	<link href="form.css" rel="stylesheet">
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