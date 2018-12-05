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
	session_start();
	$userId = $_SESSION['uid'] ?? '';

	require 'action.php';
?>
<html>
<head>
	<meta charset="utf-8">
	<link href="css/form.css" rel="stylesheet">
	<script src="js/scripts.js"></script>
</head>

<body>
	<div class="head">
		<?php echo ($userId) ? '<a href="index.php?action=signOut">Выйти</a>' : '<a href="index.php?action=signIn">Войти</a>'?>
	</div>
	<?php
		include $view;
	?>

</body>
</html>