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
    $carrito = new Carrito();
    $carrito->vaciarCarrito();
}

header('Location: carrito.php');
exit();
?>