<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_carrito');

// Incluir autoloader
require_once __DIR__ . '/autoload.php';

// Iniciar sesión
session_start();


// Otras configuraciones
define('SITE_URL', 'http://localhost/carrito_compras');
?>