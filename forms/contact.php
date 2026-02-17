<?php
// contact.php - Endpoint simple para el formulario de contacto
// Recibe datos POST y los guarda en un archivo .txt con estructura JSON indentada.
// Campos esperados: nombre, correo, asunto, mensaje.

require __DIR__ . '/../forms/mailer.php'; // Incluir la función de envío de correo
// # VALIDACION DE LOS DATOS RECIBIDOS
// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo 'Método no permitido';
    exit;
}

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
if ($longitudAsunto < 3 || $longitudAsunto > 50) {
    http_response_code(400);
    echo 'El asunto debe tener entre 3 y 50 caracteres';
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
/*-------------------------------------------------------------------------------------------------*/
// Datos de conexion a la base de datos
$host = "localhost";
$user = "root";
$pass = ""; // en Laragon suele ir vacío
$db   = "panda_web"; // nombre de la base de datos

$conn = mysqli_connect($host, $user, $pass, $db); // Establecer la conexión

if (!$conn) {
    http_response_code(500);
    echo "Error al conectar con la base de datos";
    exit;
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


$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

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

// Archivo donde se guardarán los datos
$archivo = '../storage/logs.txt';

// Leer el archivo existente si existe, o crear un array vacío
$contactos = [];
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    if (!empty($contenido)) {
        $contactos = json_decode($contenido, true);
        if (!is_array($contactos)) {
            $contactos = []; // Si el JSON está corrupto, reiniciar
        }
    }
}

// Añadir el nuevo envío al array
$contactos[] = $datosEnvio;

// Guardar el array completo en el archivo con JSON indentado
$jsonIndentado = json_encode($contactos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if (file_put_contents($archivo, $jsonIndentado) === false) {
    http_response_code(500); // Error interno del servidor
    echo 'Error al guardar los datos';
    exit;
}
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