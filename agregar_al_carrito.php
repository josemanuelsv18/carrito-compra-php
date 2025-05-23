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
    $nombre = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    
    if ($producto_id && $nombre && $precio > 0) {
        $carrito = new Carrito();
        $carrito->agregarProducto($producto_id, $nombre, $precio);
    }
}

header('Location: carrito.php');
exit();
?>