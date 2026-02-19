<?php
/*
|--------------------------------------------------------------------------
| Configuración de errores
|--------------------------------------------------------------------------
| En desarrollo puedes cambiar display_errors a 1
| En producción debe estar en 0
*/

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/errors.log');
error_reporting(E_ALL);

// Ocultar errores de mysqli
mysqli_report(MYSQLI_REPORT_OFF);


/*
|--------------------------------------------------------------------------
| Cargar variables del archivo .env
|--------------------------------------------------------------------------
*/

$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Validar que tenga =
        if (!str_contains($line, '=')) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);

        $name  = trim($name);
        $value = trim($value);

        // Eliminar comillas si existen
        $value = trim($value, "\"'");

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
