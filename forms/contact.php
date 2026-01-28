<?php
// contact.php - Endpoint simple para el formulario de contacto
// Recibe datos POST y los guarda en un archivo .txt con estructura JSON indentada.
// Campos esperados: nombre, correo, asunto, mensaje.

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

// Responde con "OK" para que el JS lo detecte como éxito
echo 'OK';