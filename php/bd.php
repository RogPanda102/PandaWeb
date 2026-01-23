<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "admin";
$bd = "cotizacion";

// Crear conexión
$conexion = mysqli_connect($servidor, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
//echo "Conexión exitosa";

?>