<?php
// contact.php - Endpoint simple para el formulario de contacto
// Este script recibe la petición POST del formulario y devuelve "OK".
// Nota: Por ahora, no procesa ni valida los datos.

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo 'Método no permitido';
    exit;
}
// Aquí se agregaran validaciones y procesamiento de datos
echo 'OK';