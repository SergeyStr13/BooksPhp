<?php
	//echo "."

?> 
<html>
<head>
	<meta charset="utf-8">

</head>

<body>	
	<h1>Добро</h1>
	<form class="add" action="index.php?action=saveBook" method="post">
		<input name="title" type="text" class="title">
		<input name="description" type="text" class="description">
		<input name="author" type="text" class="author">
		<button type="submit">Сохранить описание</button>
	</form>
</body>
</html>
