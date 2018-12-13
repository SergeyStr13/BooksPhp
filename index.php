<?php
	$content = null;
	/*
	require_once 'vendor/autoload.php';
	$loader = new Twig_Loader_Filesystem('/views');
	$twig = new Twig_Environment($loader);
	*/
	define('CORE_INDEX', 1); // test


	$view = '';
	session_start();
	$userId = $_SESSION['uid'] ?? '';
	$userName = $_SESSION['name'] ?? '';

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
		<?php echo ($userId)
			? '<a href="index.php?action=signOut">Выйти</a>
				<span> '.$userName.'</span>'
			: '<a href="index.php?action=signIn">Войти</a>'?>
	</div>
	<div class="menu">
		<ul>
			<li><a href="index.php?action=booksItems">Книги</a></li>
			<li><a href="index.php?action=usersItems">Пользователи</a></li>
		</ul>
	</div>
	<?php
		include $view;
	?>

</body>
</html>
