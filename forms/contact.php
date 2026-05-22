<?php
// contact.php - Endpoint simple para el formulario de contacto
// Campos esperados: nombre, correo, asunto, mensaje.

require_once __DIR__ . '/../config/app.php';
require __DIR__ . '/../forms/mailer.php';

// 🔥 IMPORTANTE: iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Método no permitido';
    exit;
}

// Honeypot
if (!empty($_POST['website'])) {
    http_response_code(400);
    exit('Bot detectado');
}

// Rate limit por sesión (30s)
if (isset($_SESSION['last_submit']) && 
    (time() - $_SESSION['last_submit']) < 30) {
    http_response_code(429);
    echo 'Espera antes de enviar otro mensaje.';
    exit;
}

$_SESSION['last_submit'] = time();

// Sanitizar
$nombre  = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$correo  = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$asunto  = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$mensaje = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validaciones
if (strlen($nombre) < 2 || strlen($nombre) > 30) {
    http_response_code(400);
    exit('El nombre debe tener entre 2 y 30 caracteres');
}

if (strlen($correo) > 150) {
    http_response_code(400);
    exit('El correo no puede exceder 150 caracteres');
}

if (strlen($asunto) < 5 || strlen($asunto) > 100) {
    http_response_code(400);
    exit('El asunto debe tener entre 5 y 100 caracteres');
}

if (strlen($mensaje) < 10 || strlen($mensaje) > 2000) {
    http_response_code(400);
    exit('El mensaje debe tener entre 10 y 2000 caracteres');
}

// Validación básica de los campos existentes
if (empty($nombre) || empty($correo) || empty($asunto) || empty($mensaje)) {
    http_response_code(400);
    exit('Todos los campos son obligatorios');
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Correo electrónico inválido');
}

if (preg_match("/[\r\n]/", $correo)) {
    http_response_code(400);
    exit('Entrada inválida');
}
// BACKUP SIN BASE DE DATOS (CSV)
$archivo = __DIR__ . '/../storage/contactos.csv';

// Crear carpeta si no existe
if (!file_exists(dirname($archivo))) {
    mkdir(dirname($archivo), 0755, true);
}

// Crear archivo con encabezados si no existe
if (!file_exists($archivo)) {
    file_put_contents($archivo, "fecha,nombre,correo,asunto,mensaje\n");
}

// Guardar registro
$linea = sprintf(
    "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
    date('Y-m-d H:i:s'),
    addslashes($nombre),
    addslashes($correo),
    addslashes($asunto),
    addslashes($mensaje)
);

file_put_contents($archivo, $linea, FILE_APPEND);

// Enviar correo a la empresa
$empresa = enviarCorreoEmpresa($nombre, $correo, $asunto, $mensaje);

if (!$empresa) {
    http_response_code(500);
    exit("No se pudo enviar el correo");
}

// Enviar correo al cliente
enviarCorreoCliente($nombre, $correo);
// RESPUESTA PARA JS
echo 'OK';