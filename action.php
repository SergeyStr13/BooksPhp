<?php
defined('CORE_INDEX') or die('restricted access');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$title = isset($_POST['title']) ? $_POST['title'] : null;
	$description = $_POST['description'] ?? '';
	$author = $_POST['author'] ?? '';

}

function clean($value = "") {
	$value = trim($value);

	return $value;
}

function checkLength($value = "", $min, $max) {
	$result = (mb_strlen($value) < $min || mb_strlen($value) > $max);

	return !$result;
}

$book = [];

if ($title != '' && $description != '' && $author != '') {
	$book['name'] = $title;
	$book['description'] = $description;
	$book['author'] = $author;

	echo "Введенные данные корректны";

	addBook($book);
	//redirect(/);
	$url = '/';
	header("Location : http://localhost/BooksPhp$url");

} else {
	echo "Заполните пустые поля";
}

function booksItems() {

}

function addBook($book) {

	$filename = 'data.json';
	$cont = file_get_contents($filename);
	$data = json_decode($cont);

	$data->maxId++;
	$book['id'] = $data->maxId;
	$data->books[] = $book;

	$cont = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	file_put_contents($filename, $cont);

}

