<?php
	defined('CORE_INDEX') or die('restricted access');

if (empty($formAction)) {
	$formAction = '';
}

?> 

<h1>Пользователь</h1>
<form class="add" action="index.php?<?= $formAction ?>" method="post">
	<div style="overflow: hidden; height: 0;">
		<input type="password" name="fakePassword">
	</div>
	<input name="name" type="text" class="nameUser" value="<?= $user->name ?? '' ?>">
	<input name="login" type="text" class="login" value="<?= $user->login ?? '' ?>">
	<input name="password" type="password" class="password" value="<?= $user->password ?? '' ?>">
	<input name="email" type="text" class="email" value="<?= $user->email ?? '' ?>">
	<?php /*<input name="password" type="text" class="author" value="<?= $user->password ?? '' ?>"> */?>
	<button type="submit">Сохранить описание</button>
</form>
