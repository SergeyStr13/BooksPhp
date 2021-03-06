<?php
defined('CORE_INDEX') or die('restricted access');

//section Database query
try {
	$user = 'root';
	$pass = '';
	$db = new PDO('mysql:host=localhost;dbname=booksphp', $user, $pass, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
	]);
	//$db->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
	echo $ex->getMessage();
}
//getBookDb($connection);
//print_r(PDO::getAvailableDrivers());

////////////////////////////////////////////////////////////////////////////////////////////////////
///  Routing
$action = $_GET['action'] ?? '';
$params = [];

switch ($action) {

	//route component Books
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
	case 'booksItems':
		[$view, $params] = getItemsBooks($userId);
		break;

	//route component User
	case 'insertUser':
		$user = getUserFromRequest();
		if ($user) {
			//insertUser($user);
			insertUserDb($user,$db);
		}
		break;
	case 'updateUser':
		$idUser = $_GET['idUser'] ?? '';
		if ($idUser) {
			// $user = getUserByIdDb($idUser,$db);
			$user = getUserFromRequest();
			if ($user) {
				//updateUser($idUser,$user);
				updateUserDb($idUser,$user,$db);
			}
		}
		break;
	case 'deleteUser':
		$idUser = $_GET['idUser'] ?? '';
		if ($idUser) {
			//deleteUser($idUser);
			deleteUserDb($idUser,$db);
		}
		break;
	case 'userForm':
		$idUser = $_GET['idUser'] ?? '';
		if ($idUser) {
			//$user = getUserById($idUser);
			$user = getUserByIdDb($idUser,$db);
			if (!$user) {
				redirect('index.php');
			}
			$formAction = 'action=updateUser&idUser='.$idUser;
			$titleU = 'Редактирование';
		} else {
			$formAction = 'action=insertUser';
			$titleU = 'Добавление';
		}
		$view = 'userForm.php';
		break;
	case 'usersItems':
		[$view, $params] = getItemsUsersDb($userId,$db);
		//[$view, $params] = getItemsUsers($userId);
		break;

	// route authorise
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
	//route Test
	case 'prepareTest':
		prepareTest();
		break;
	case 'finishTest':
		finishTest();
		break;

	default:
		$isAdmin = ($userId == 1);
		if ($isAdmin) {
			[$view, $params] = getItemsUsersDb($userId, $db);
			/*[$users, $view]= getItems('books','book');
			[$view, $params] = getItemsUsers($userId);*/
			/* $data = loadResource('user');
			$users = $data->users;
			$view = 'usersItems.php'; */
		} else {
			[$view, $params] = getItemsBooks($userId);
			/* $data = loadResource('book');
			$books = $data->books;
			$view = 'booksItems.php'; */
		}
}
extract($params, EXTR_SKIP);

////////////////////////////////////////////////////////////////////
///
///
///
function redirect ($url) {
	header("Location: http://localhost/booksphp/$url");
	exit();
}

/**
 * @param $resource
 * @return mixed
 */
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
//// universal function CRUD for file json
function getElementById($idElement, $elements, $resource) {
	$data = loadResource($resource);
	foreach ($data->$elements as $index => $element) {
		if ($idElement == $element->id) {
			return $element;
		}
	}
	return null;
}

function getItems($elements, $resource) {
	$data = loadResource($resource);
	return $data->$elements;
}

function insertElement($element, $elements, $resource) {
	$data = loadResource($resource);

	$data->maxId++;
	$element['id'] = $data->maxId;
	$data->$elements[] = $element;

	saveResource($resource, $data);
	redirect('index.php');
}

function updateElement ($idElement, $elements, $dataEl, $resource) {
	$data = loadResource($resource);

	foreach ($data->$elements as $index => $element) {
		if ($idElement == $element->id) {
			$dataEl['id'] = $idElement;
			$data->$elements[$index] = $dataEl;
			break;
		}
	}
	saveResource($resource, $data);
	redirect('index.php');
}

function deleteElement($idElement, $elements, $resource) {
	$data = loadResource($resource);
	foreach ($data->$elements as $index => $element) {
		if ($idElement == $element->id) {
			array_splice($data->$elements,$index,1);
			break;
		}
	}
	saveResource($resource, $data);

	redirect('index.php');
}




//<editor-fold desc="Component Book">
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

function getItemsBooks($userId) {
	$canEdit = ($userId != '');
	$books = getItems('books', 'book');
	$view = 'booksItems.php';
	return [$view, ['books' => $books, 'canEdit' => $canEdit]];
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
	redirect('index.php/?action=booksItems');
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
// with DB
function getItemsBookDb($connection) {
	$bookDb = $connection->query('select * from book');
	$items = $bookDb->fetchAll(PDO::FETCH_ASSOC);
	var_dump($items);
}
//</editor-fold>

//<editor-fold desc="Component User">
function getUserFromRequest () {
	$user = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = isset($_POST['name']) ? $_POST['name'] : null;
		$login = $_POST['login'] ?? '';
		$password = $_POST['password'] ?? '';
		$email = $_POST['email'] ?? '';
		if ($name != '' && $login != '' && $email != '') {
			$user = (object) compact('name','login', 'password', 'email');
			/*$user = [];
			$user['name'] = $name;
			$user['login'] = $login;
			$user['email'] = $email;*/
		}
	}
	return $user;
}

function getUserById($idUser, $mode) {
	$data = loadResource('user');
	foreach ($data->users as $index => $user) {
		if ($idUser == $user->id) {
			return $user;
		}
	}
	return null;
}

function getItemsUsers($userId) {
	if ($userId !== 1) {
		redirect('index.php');
	}
	$users = getItems('users', 'user');
	$view = 'usersItems.php';
	return [$view, ['users' => $users]];
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

// with user DB
function getUserByIdDb($idUser, PDO $db) {
	$query = $db->prepare('select * from user where user.id = ?');
	$query->execute([$idUser]);
	$user = $query->fetch(PDO::FETCH_OBJ);
	//var_dump($user);
	return $user;
}

/**
 * @param int $userId
 * @param PDO $db
 * @return array;
 */
function getItemsUsersDb($userId, PDO $db) {
	if ($userId !== 1) {
		redirect('index.php');
	}
	$query = $db->query('select * from user');
	$users = $query->fetchAll(PDO::FETCH_CLASS);//PDO::FETCH_ASSOC);
	$view = 'usersItems.php';
	return [$view, ['users' => $users]];
}

/**
 * @param $user
 * @param PDO $db
 */
function insertUserDb($user, PDO $db) {
	/*$query = $db->prepare("INSERT INTO user (`login`, `password`, `email`)".
		" values ({$user['title']},{$user['description']},{$user['author']})"); */
	$user = (array)$user;
	$keys = array_keys($user);
	$fields = implode(',',$keys);
	$values = implode(',',array_fill(0, count($keys), '?')); // "'".implode("','", array_values($user))."'";
	$query = $db->prepare("insert into user ({$fields}) values ({$values}) ");
	$query->execute(array_values($user));
	redirect('index.php');
}

function updateUserDb($idUser,$user,$db) {
	$user->id = $idUser;
	$query = $db->prepare("update user set name = :name, login = :login, "
		."password = :password, email = :email where user.id= :id");
	$query->execute((array) $user);
	redirect('index.php');
}

function deleteUserDb($idUser,PDO $db) {
	$query = $db->prepare("delete from user where user.id = ?");
	$query->execute([$idUser]);
	redirect('index.php');
}
//</editor-fold>


function clean($value = "") {
	$value = trim($value);
	return $value;
}

function checkLength($value = "", $min, $max) {
	$result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
	return !$result;
}

//<editor-fold desc="Authorise">
function signIn($login, $password) {

	$data = loadResource('user');
	foreach ($data->users as $user) {
		if ($user->login === $login && $user->password === $password) {
			//$userId = $user->id;
			startSession();
			$_SESSION['name'] = $user->name;
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
//</editor-fold>

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