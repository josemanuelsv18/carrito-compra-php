<?php
require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Carrito.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$carrito = new Carrito();
$items = $carrito->getCarrito()['items'];
$total = $carrito->getCarrito()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
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

    <main class="carrito-container">
        <h2>Tu Carrito de Compras</h2>
        
        <?php if (empty($items)): ?>
            <p>Tu carrito está vacío</p>
            <a href="productos.php" class="btn">Ver Productos</a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td>$<?= number_format($item['precio'], 2) ?></td>
                            <td>
                                <form method="POST" action="actualizar_carrito.php" class="cantidad-form">
                                    <input type="hidden" name="producto_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1">
                                    <button type="submit" class="btn-small">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?= number_format($item['subtotal'], 2) ?></td>
                            <td>
                                <form method="POST" action="eliminar_del_carrito.php">
                                    <input type="hidden" name="producto_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-small btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td colspan="2">$<?= number_format($total, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="carrito-actions">
                <a href="productos.php" class="btn">Seguir Comprando</a>
                <a href="checkout.php" class="btn btn-primary">Finalizar Compra</a>
                <form method="POST" action="vaciar_carrito.php" class="inline-form">
                    <button type="submit" class="btn btn-danger">Vaciar Carrito</button>
                </form>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>