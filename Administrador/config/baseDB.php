<?php
// conexion a la base de datos
$host = 'localhost';
$bd = 'sitio01';
$usuario = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $password);
    if ($conexion) {
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
