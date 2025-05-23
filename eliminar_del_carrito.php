<?php
require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Carrito.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'] ?? null;
    
    if ($producto_id) {
        $carrito = new Carrito();
        $carrito->eliminarProducto($producto_id);
    }
}

header('Location: carrito.php');
exit();
?>