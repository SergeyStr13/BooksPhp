<?php
	defined('CORE_INDEX') or die('restricted access');

if (empty($formAction)) {
	$formAction = '';
}

?>
<h1>Добавление книги</h1>
<form class="add" action="index.php?<?= $formAction ?>" method="post">
	<input name="title" type="text" class="title" value="<?= $book->title ?? '' ?>">
	<input name="description" type="text" class="description" value="<?= $book->description ?? '' ?>">
	<input name="author" type="text" class="author" value="<?= $book->author ?? '' ?>">
	<button type="submit">Сохранить книгу</button>
</form>
