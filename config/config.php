<?php
$host = "localhost";
$usuario = "root";
$contraseña = "1234";
$base_datos = "comercio";

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
