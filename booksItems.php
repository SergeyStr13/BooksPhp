<?php
	$fileData = file_get_contents('data.json');
	$dataJson = json_decode($fileData);
	//var_dump($dataJson->books[0]->title) ;
	//$dataJson->books
	$books = $dataJson->books;
?>
<h1>Книжный фонд</h1>

<a class="add" href="form.php" onclick="add">[+]</a>
<a class="edit" href="form.php/edit/{id}">[<->]</a>
<a class="delete">[-]</a>
<table>
	<tr>
		<th>Наименование</th>
		<th>Описание</th>
		<th>Авторы</th>
	</tr>
	<tbody>
		<?php
		$p = '';
			foreach ($books as $key => $value ) {
				echo '<tr>';
					echo '<td>'.$books[$key]->title.'</td>';
					echo '<td>'.$books[$key]->description.'</td>';
					echo '<td>'.$books[$key]->author.'</td>';
				echo '</tr>';
				//echo '<td>'.$data->books->description.'</td>';
				//echo '<td>'.$data->books->author.'</td>';
			}
		?>
	</tbody>
</table>



