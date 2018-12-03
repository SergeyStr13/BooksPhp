<?php
defined('CORE_INDEX') or die('restricted access');

	$fileData = file_get_contents('data.json');
	$dataJson = json_decode($fileData);
	//var_dump($dataJson->books[0]->title) ;
	//$dataJson->books
	$books = $dataJson->books;

?>
<h1>Книжный фонд</h1>

<a class="add" href="index.php?action=formBook" onclick="add">[+]</a>

<table>
	<tr>
		<th>Наименование</th>
		<th>Описание</th>
		<th>Авторы</th>
	</tr>
	<tbody>
		<?php foreach ($books as $key => $value): ?>
			<tr>
				<td><?= $books[$key]->title ?></td>
				<td><?= $books[$key]->description ?></td>
				<td><?= $books[$key]->author ?></td>
				<td>
					<a class="edit" href="index.php?action=formBook&idBook=<?= $books[$key]->id ?>">[<->]</a>
					<a class="delete" href="index.php?action=deleteBook&idBook=<?= $books[$key]->id ?>">[-]</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>



