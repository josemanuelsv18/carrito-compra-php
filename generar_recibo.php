<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../classes/PDFGenerator.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Pedido.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['pedido_id'])) {
    header('Location: productos.php');
    exit();
}

$pedido_id = $_GET['pedido_id'];
$pedido = new Pedido();

// Verificar que el pedido pertenece al usuario
$pedido_data = $pedido->getPedido($pedido_id);
if (!$pedido_data || $pedido_data['usuario_id'] != $_SESSION['user_id']) {
    header('Location: productos.php');
    exit();
}

// Obtener detalles del pedido
$detalles = $pedido->getDetallesPedido($pedido_id);

// Generar PDF
$pdf = new PDFGenerator();
$pdf->generarRecibo($pedido_data, $detalles, [
    'nombre' => $pedido_data['nombre'],
    'apellido' => $pedido_data['apellido']
]);
?>