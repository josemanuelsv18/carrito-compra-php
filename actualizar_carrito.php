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
    $cantidad = $_POST['cantidad'] ?? 1;
    
    if ($producto_id && $cantidad > 0) {
        $carrito = new Carrito();
        $carrito->actualizarCantidad($producto_id, $cantidad);
    }
}

header('Location: carrito.php');
exit();
?>