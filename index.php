<?php
//Josemanuel Sifontes / 20-70-7436
//Karen Sanchez / 8-1013-654

require_once 'config/config.php';
require_once 'classes/Auth.php';

$auth = new Auth();

// Verificar si el usuario está autenticado
if ($auth->isLoggedIn()) {
    // Usuario autenticado - redirigir a productos.php
    header('Location: productos.php');
} else {
    // Usuario no autenticado - redirigir a login.php
    header('Location: login.php');
}

exit();
?>