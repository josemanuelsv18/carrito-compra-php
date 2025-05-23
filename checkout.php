<?php
require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Carrito.php';
require_once 'classes/Pedido.php';
require_once 'classes/Database.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$db = (new Database())->connect();
$carrito = new Carrito();
$pedido = new Pedido();

// Obtener información del usuario
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT nombre, apellido, direccion FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = $_POST['direccion'] ?? $usuario['direccion'];
    
    // Crear pedido
    $items = [];
    foreach ($carrito->getCarrito()['items'] as $item) {
        $items[] = [
            'id' => $item['id'],
            'nombre' => $item['nombre'],
            'precio' => $item['precio'],
            'cantidad' => $item['cantidad']
        ];
    }
    
    $total = $carrito->getCarrito()['total'];
    $pedido_id = $pedido->crearPedido($user_id, $items, $total);
    
    if ($pedido_id) {
        // Vaciar carrito
        $carrito->vaciarCarrito();
        
        // Redirigir al recibo
        header("Location: generar_recibo.php?pedido_id=$pedido_id");
        exit();
    } else {
        $error = 'Hubo un error al procesar tu pedido. Por favor intenta nuevamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Tienda Online</h1>
        <nav>
            <a href="productos.php">Productos</a>
            <a href="carrito.php">Carrito (<?= $carrito->getCarrito()['cantidad_items'] ?>)</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main class="checkout-container">
        <h2>Finalizar Compra</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" disabled>
            </div>
            
            <h3>Resumen de Compra</h3>
            <table class="checkout-summary">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito->getCarrito()['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td>$<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                        <td>$<?= number_format($carrito->getCarrito()['total'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <button type="submit" class="btn btn-primary">Confirmar Compra</button>
        </form>
    </main>
</body>
</html>