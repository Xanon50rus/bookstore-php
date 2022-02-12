<?php
// povezi z bazo
function connectDB()
{
	global $config;

	$servername = $config['db_servername'];
	$username = $config['db_username'];
	$password = $config['db_password'];
	$dbName = $config['db_name'];

	$db = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

	return $db;
}

// preveri ce je vneseno doloceno polje z imenom
function checkValue($name)
{
	$output = null;

	if ($name) {
		$output = (isset($_POST) && isset($_POST[$name])) ? $_POST[$name] : null;
	}

	return $output;
}

// preusmeritev na doloceno spletno stran
function redirect($page)
{
	if ($page) {
		header("Location: $page");
	}
}

// preveri ali se je uporabnik prijavil v aplikacijo
function checkLogin($LoginScreen = false)
{
	$LogIn = (isset($_SESSION) && isset($_SESSION['LogIn'])) ? true : false;

	if ($LoginScreen) {
		if ($LogIn) {
			redirect("shop.php");
		}
	} else {
		if (!$LogIn) {
			redirect("login.php");
		}
	}
}

// izpisi menijsko strukturo
function getNavbar()
{
	$output = null;

	$output .= '<ul>';
	$output .= '<li><a href="dodaj.php">Dodaj</a></li>';
	$output .= '<li><a href="logout.php">Odjava</a></li>';
	$output .= '</ul>';

	return $output;
}

// izpis vseh podatkov iz tabele osebe
function getAllData()
{
	global $db;

	$output = null;

	$sql = $db->prepare("SELECT * FROM oseba");
	$sql->execute();
	$data = $sql->fetchAll(PDO::FETCH_ASSOC);

	foreach ($data as $row) {
		$output .= '<article>';
		$output .= '<img style="width: 200px; height: 150px;" src="' . $row['slika'] . '">';
		$output .= '<h2><a href="pregled.php?id=' . $row['id'] . '">' . $row['ime'] . '</a></h2>';
		$output .= '<article>';
	}

	return $output;
}

// izpis tocno dolocene vrstice glede na id
function getItemData($id)
{
	global $db;

	$output = null;

	if ($id) {
		$sql = $db->prepare("SELECT * FROM oseba WHERE id = ?");
		$sql->execute([$id]);
		$data = $sql->fetchAll(PDO::FETCH_ASSOC);
		$data = (isset($data) && isset($data[0])) ? $data[0] : null;

		var_dump($data);

		if (empty($data)) {
			redirect("pregled.php");
		} else {
			$output .= '<article>';
			$output .= '<img src="' . $data['slika'] . '">';
			$output .= '<h2>' . $data['ime'] . '</h2>';
			$output .= '<p>' . $data['priimek'] . '</p>';
			$output .= '<article>';
		}
	} else {
		redirect("pregled.php");
	}

	return $output;
}
