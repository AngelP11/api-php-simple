<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "clases/conexion/conexion.php";

$conn = new conexion;

/*$query = "INSERT INTO pacientes (nombre, apellido, edad)value('Angel', 'Ponce', 21)";

print_r($conn->nonQueryId($query));*/

?>