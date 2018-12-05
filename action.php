<?php
defined('CORE_INDEX') or die('restricted access');

$action = $_GET['action'] ?? '';

switch ($action) {

	// module Books
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
	case 'bookForm':
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
		$view = 'bookForm.php';
		break;

	// module User
	case 'insertUser':
		$user = getUserFromRequest();
		if ($user) {
			insertUser($user);
		}
		break;
	case 'updateUser':
		$idUser = $_GET['idUser'] ?? '';
		if ($idUser) {
			$user = getUserFromRequest();
			if ($user) {
				updateUser($idUser,$user);
			}
		}
		break;
	case 'deleteUser':
		$idUser = $_GET['idUser'] ?? '';
		if ($idUser) {
			deleteUser($idUser);
		}
		break;
	case 'userForm':
		$idUser = $_GET['idUser'] ?? '';
		if ($idUser) {
			$user = getUserById($idUser);
			if (!$user) {
				redirect('index.php');
			}
			$formAction = 'action=updateUser&idUser='.$idUser;

		} else {
			$formAction = 'action=insertUser';
		}
		$view = 'userForm.php';
		break;

	// standart
	case 'signIn':
		$_POST['login'] = 'superadmin';
		$_POST['password'] = '123';

		$login = $_POST['login'] ?? '';
		$password = $_POST['password'] ?? '';

		if ($login != '' && $password != '') {
			signIn($login,$password);

		}
		redirect('index.php');
		break;
	case 'signOut':
		signOut();
		//$view = 'booksItems.php';
		redirect('index.php');
		break;

	case 'prepareTest':
		prepareTest();
		break;
	case 'finishTest':
		finishTest();
		break;
	default:
		$canEdit = ($userId != '');
		$isAdmin = ($userId == 1);
		if ($isAdmin) {
			$data = loadResource('user');
			$users = $data->users;
			$view = 'usersItems.php';
		} else {
			$data = loadResource('book');
			$books = $data->books;
			$view = 'booksItems.php';
		}
}

////////////////////////////////////////////////////////////////////
function redirect ($url) {
	header("Location: http://localhost/booksphp/$url");
	exit();
}

function loadResource($resource) {
	$filename = "resources/$resource.json";
	$cont = file_get_contents($filename);
	return json_decode($cont);

}

function saveResource($resource, $data) {
	$filename = "resources/$resource.json";
	$cont = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	file_put_contents($filename, $cont);
}

//////////////////////////////////////////////////////////////////////////////////////////
/// Book
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
	$data = loadResource('book');
	foreach ($data->books as $index => $book) {
		if ($idBook == $book->id) {
			return $book;
		}
	}
	return null;
}

/**
 * @param $book
 */
function insertBook($book) {

	$data = loadResource('book');

	$data->maxId++;
	$book['id'] = $data->maxId;
	$data->books[] = $book;

	saveResource('book', $data);

	redirect('index.php');
}

function updateBook ($idBook,$dataBook) {
	$data = loadResource('book');

	foreach ($data->books as $index => $book) {
		if ($idBook == $book->id) {
			$dataBook['id'] = $idBook;
			$data->books[$index] = $dataBook;
			break;
		}
	}
	saveResource('book', $data);

	redirect('index.php');

}

function deleteBook($idBook) {
	$data = loadResource('book');
	foreach ($data->books as $index => $book) {
		if ($idBook == $book->id) {
			array_splice($data->books,$index,1);
			break;
		}
	}
	saveResource('book', $data);

	redirect('index.php');
}

///////////////////////////////////////////////////////////////////
/// User
function getUserFromRequest () {
	$user = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = isset($_POST['name']) ? $_POST['name'] : null;
		$login = $_POST['login'] ?? '';
		$email = $_POST['email'] ?? '';

		if ($name != '' && $login != '' && $email != '') {
			$user = [];
			$user['name'] = $name;
			$user['login'] = $login;
			$user['email'] = $email;
		}
	}
	return $user;
}

function getUserById($idUser) {
	$data = loadResource('user');
	foreach ($data->users as $index => $user) {
		if ($idUser == $user->id) {
			return $user;
		}
	}
	return null;
}
function insertUser($user) {

	$data = loadResource('user');

	$data->maxId++;
	$user['id'] = $data->maxId;
	$data->users[] = $user;

	saveResource('user', $data);

	redirect('index.php');
}

function updateUser ($idUser,$dataUser) {
	$data = loadResource('user');

	foreach ($data->users as $index => $user) {
		if ($idUser == $user->id) {
			$dataUser['id'] = $idUser;
			$data->users[$index] = $dataUser;
			break;
		}
	}
	saveResource('user', $data);

	redirect('index.php');

}

function deleteUser($idUser) {
	$data = loadResource('user');
	foreach ($data->users as $index => $user) {
		if ($idUser == $user->id) {
			array_splice($data->users,$index,1);
			break;
		}
	}
	saveResource('user', $data);

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

// block authorise
function signIn($login, $password) {

	$data = loadResource('user');
	foreach ($data->users as $user) {
		if ($user->login === $login && $user->password === $password) {
			//$userId = $user->id;
			startSession();
			$_SESSION['uid'] = $user->id;
			return true;
		}
	}
	return false;
}

function signOut() {
	//closeSession();
	destroySessin();
	//return '';
}

function startSession() {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
}

function closeSession() {
	if ( session_status() !== PHP_SESSION_NONE ) {
		session_commit();
		//setcookie(session_name(), session_id(), time()-60*60*24);
		//session_unset();
		//session_destroy();
	}
}

function destroySessin() {
	if (session_status() !== PHP_SESSION_NONE) {
		setcookie(session_name(), session_id(), time()-60*60*24);
		session_unset();
		session_destroy();
	}
}

//custom function
function prepareTest() {
	$filename = 'resources/book.json';
	//$cont = file_get_contents($filename);
	$backupFilename = 'resources/bk_book.json';
	if (file_exists($backupFilename)) {
		unlink($backupFilename);
	}
	copy($filename,$backupFilename);
}

function finishTest() {
	$filename = 'resources/book.json';
	$backupFilename = 'resources/bk_book.json';
	if (!file_exists($backupFilename)) {
		return;
	}
	$content = file_get_contents($backupFilename);
	file_put_contents($filename,$content);
}