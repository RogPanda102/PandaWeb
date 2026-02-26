<?php
// contact.php - Endpoint simple para el formulario de contacto
// Campos esperados: nombre, correo, asunto, mensaje.

require_once __DIR__ . '/../config/app.php'; // Configuración general (ocultar errores, etc.)
require __DIR__ . '/../forms/mailer.php'; // Incluir la función de envío de correo

// # VALIDACION DE LOS DATOS RECIBIDOS
// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo 'Método no permitido';
    exit;
}

if (!empty($_POST['website'])) {
    http_response_code(400);
    exit('Bot detectado');
}

// Limitar envíos a 1 cada 30 segundos por sesión
if (isset($_SESSION['last_submit']) && 
    (time() - $_SESSION['last_submit']) < 30) {
    http_response_code(429);
    echo 'Espera antes de enviar otro mensaje.';
    exit;
}

$_SESSION['last_submit'] = time();

// Sanitizar y obtener los datos del formulario
$nombre = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$correo = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$asunto = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$mensaje = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';


// Validaciones de longitud para el nombre
$longitudNombre = strlen($nombre);
if ($longitudNombre < 2 || $longitudNombre > 30) {
    http_response_code(400); // Solicitud incorrecta
    echo 'El nombre debe tener entre 2 y 30 caracteres';
    exit;
}

// Validaciones de longitud para el correo
$longitudCorreo = strlen($correo);
if ($longitudCorreo > 150) {
    http_response_code(400);
    echo 'El correo electrónico no puede exceder 150 caracteres';
    exit;
}

// Validaciones de longitud para el asunto
$longitudAsunto = strlen($asunto);
if ($longitudAsunto < 5 || $longitudAsunto > 100) {
    http_response_code(400);
    echo 'El asunto debe tener entre 5 y 100 caracteres';
    exit;
}

// Validaciones de longitud para el mensaje
$longitudMensaje = strlen($mensaje);
if ($longitudMensaje < 10 || $longitudMensaje > 2000) {
    http_response_code(400);
    echo 'El mensaje debe tener entre 10 y 2000 caracteres';
    exit;
}

// Validación básica de los campos existentes
if (empty($nombre) || empty($correo) || empty($asunto) || empty($mensaje)) {
    http_response_code(400); // Solicitud incorrecta
    echo 'Todos los campos son obligatorios';
    exit;
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Correo electrónico inválido';
    exit;
}
// Protección contra header injection
if (preg_match("/[\r\n]/", $correo)) {
    http_response_code(400);
    echo 'Entrada inválida';
    exit;
}

/*-------------------------------------------------------------------------------------------------*/
// Datos de conexion a la base de datos
// Cargar configuración
$dbConfig = require '../config/bd.php';

// Conectar
$conn = mysqli_connect(
    $dbConfig['host'],
    $dbConfig['user'],
    $dbConfig['pass'],
    $dbConfig['db']
);

if (!$conn) {
    http_response_code(500);
    echo "Error al conectar con la base de datos";
    exit;
}

// Bloquear múltiples envíos desde misma IP en 1 minuto
$query = mysqli_prepare($conn, "
    SELECT COUNT(*) 
    FROM contactos 
    WHERE ip = ? 
    AND fecha >= NOW() - INTERVAL 1 MINUTE
");

mysqli_stmt_bind_param($query, "s", $ip);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $count);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

if ($count > 3) {
    http_response_code(429);
    exit('Demasiados envíos desde tu IP.');
}

$stmt = mysqli_prepare($conn, "
  INSERT INTO contactos
  (nombre, correo, asunto, mensaje, ip)
  VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    http_response_code(500);
    echo "Error en la consulta SQL";
    exit;
}


$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

mysqli_stmt_bind_param(
  $stmt,
  "sssss",
  $nombre,
  $correo,
  $asunto,
  $mensaje,
  $ip
);

$result = mysqli_stmt_execute($stmt);

if (!$result) {
    http_response_code(500);
    echo "Error al guardar el mensaje";
    exit;
}

/*-------------------------------------------------------------------------------------------------*/
// Array con los datos del envío
$datosEnvio = [
    'fecha' => date('Y-m-d H:i:s'), // timestamp para referencia
    'nombre' => $nombre,
    'correo' => $correo,
    'asunto' => $asunto,
    'mensaje' => $mensaje
];
mysqli_close($conn); // Cerrar la conexión a la base de datos

// Enviar correo
$enviado = enviarCorreo($nombre, $correo, $asunto, $mensaje);

if (!$enviado) {
    http_response_code(500);
    echo "Se guardó, pero no se pudo enviar el correo";
    exit;
}

// Responde con "OK" para que el JS lo detecte como éxito
echo 'OK';