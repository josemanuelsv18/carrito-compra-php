<?php
require_once 'config/config.php';
require_once 'classes/Auth.php';
require_once 'classes/Producto.php';
require_once 'classes/Carrito.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$producto = new Producto();
$productos = $producto->getAll();

$carrito = new Carrito();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
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

    <main class="productos-container">
        <h2>Nuestros Productos</h2>
        
        <div class="productos-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                    <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
                    <form method="POST" action="agregar_al_carrito.php">
                        <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                        <input type="hidden" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">
                        <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                        <button type="submit" class="btn">Agregar al Carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>