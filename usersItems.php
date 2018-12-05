<?php
defined('CORE_INDEX') or die('restricted access');

?>
<h1>Книжный фонд</h1>

<?php if ($canEdit): ?>
	<a class="add" href="index.php?action=userForm" onclick="add">[+]</a>
<?php endif; ?>
<table>
	<tr>
		<th>Имя</th>
		<th>Логин</th>
		<th>Почта</th>
		<th></th>
	</tr>
	<tbody>
		<?php foreach ($users as $key => $value): ?>
			<tr>
				<td><?= $users[$key]->name ?></td>
				<td><?= $users[$key]->login ?></td>
				<td><?= $users[$key]->email ?></td>
				<td>
					<?php if ($canEdit): ?>
						<a class="edit" href="index.php?action=userForm&idUser=<?= $users[$key]->id ?>">[<->]</a>
						<a class="delete" href="index.php?action=deleteUser&idUser=<?= $users[$key]->id ?>">[-]</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>



