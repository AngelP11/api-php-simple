<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "clases/auth.class.php";
require_once "clases/respuestas.class.php";

$_auth = new auth;
$_respuestas = new respuestas;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$body = file_get_contents("php://input");
	$bodyArray = $_auth->login($body);

	print_r(json_encode($bodyArray));
} else {
	echo 'metodo no permitido';
}

?>