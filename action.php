<?php
defined('CORE_INDEX') or die('restricted access');

$action = $_GET['action'] ?? '';
switch ($action) {
	case 'insertBook':
		$book = getBookFromRequest();
		if ($book) {
			insertBook($book);
		}
		break;
	case 'updateBook':
		$idBook = $_GET['idBook'] ?? '';
		if ($idBook) {
			$book = getBookFromRequest();
			if ($book) {
				updateBook($idBook,$book);
			}
		}
		break;
	case 'deleteBook':
		$idBook = $_GET['idBook'] ?? '';
		if ($idBook) {
			deleteBook($idBook);
		}
		break;

	// view
	case 'formBook':
		$idBook = $_GET['idBook'] ?? '';
		if ($idBook) {
			$book = getBookById($idBook);
			if (!$book) {
				redirect('index.php');
			}
			$formAction = 'action=updateBook&idBook='.$idBook;

		} else {
			$formAction = 'action=insertBook';
		}
		$view = 'form.php';
		break;

	case 'prepareTest':
		prepareTest();
		break;
	case 'finishTest':
		finishTest();
		break;
	default:
		$view = 'booksItems.php';
}

function getBookFromRequest () {
	$book = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$description = $_POST['description'] ?? '';
		$author = $_POST['author'] ?? '';

		if ($title != '' && $description != '' && $author != '') {
			$book = [];
			$book['title'] = $title;
			$book['description'] = $description;
			$book['author'] = $author;
		}
	}
	return $book;
}

function getBookById($idBook) {
	$filename = 'data.json';
	$cont = file_get_contents($filename);
	$data = json_decode($cont);
	foreach ($data->books as $index => $book) {
		if ($idBook == $book->id) {
			return $book;
		}
	}
	return null;
}

function redirect ($url) {
	header("Location: http://localhost/booksphp/$url");
	exit();
}


function bookItem() {

}

/**
 * @param $book
 */
function insertBook($book) {

	$filename = 'data.json';
	$cont = file_get_contents($filename);
	$data = json_decode($cont);

	$data->maxId++;
	$book['id'] = $data->maxId;
	$data->books[] = $book;

	$cont = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	file_put_contents($filename, $cont);

	redirect('index.php');
}

function updateBook ($idBook,$dataBook) {
	$filename = 'data.json';
	$cont = file_get_contents($filename);
	$data = json_decode($cont);

	foreach ($data->books as $index => $book) {
		if ($idBook == $book->id) {
			$dataBook['id'] = $idBook;
			$data->books[$index] = $dataBook;
			break;
		}
	}
	$cont = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	file_put_contents($filename, $cont);

	redirect('index.php');

}

function deleteBook($idBook) {
	$filename = 'data.json';
	$cont = file_get_contents($filename);
	$data = json_decode($cont);
	foreach ($data->books as $index => $book) {
		if ($idBook == $book->id) {
			array_splice($data->books,$index,1);
			break;
		}
	}
	$cont = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	file_put_contents($filename, $cont);

	redirect('index.php');
}

function clean($value = "") {
	$value = trim($value);

	return $value;
}

function checkLength($value = "", $min, $max) {
	$result = (mb_strlen($value) < $min || mb_strlen($value) > $max);

	return !$result;
}

//custom function
function prepareTest() {
	$filename = 'data.json';
	//$cont = file_get_contents($filename);
	$backupFilename = 'bk_data.json';
	if (file_exists($backupFilename)) {
		unlink($backupFilename);
	}
	copy($filename,$backupFilename);
}

function finishTest() {
	$filename = 'data.json';
	$backupFilename = 'bk_data.json';
	if (!file_exists($backupFilename)) {
		return;
	}
	$content = file_get_contents($backupFilename);
	file_put_contents($filename,$content);
}