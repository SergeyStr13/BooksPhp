<?php

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

$fields = [];

if ($title != '' && $description != '') {
	$fields['name'] = $title;
	$fields['description'] = $description;

	echo "Введенные данные корректны";

	saveDataJson($fields);
} else {
	echo "Заполните пустые поля";
}

function booksItems() {

}

function saveDataJson($data) {

	/*
	{
		"id": 1 //++
		"name":"Бла бла бла",
		"description": "Описание"
	}
	*/
   	$numberBook = 1;

	$filename = 'data.json';
	$dataText = '{books:[{'.PHP_EOL
		.'"id":'.$numberBook.PHP_EOL.'}';

	if (!empty($data)) {
		$file = fopen($filename,'a+');
		fwrite($file,$dataText);
		fclose($file);

		$numberBook++;
	} else {
		echo "Ошибка операции";
	}
}

