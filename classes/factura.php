<?php
require_once __DIR__ . '/../config/config.php';
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?= $pedido_id ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .factura-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .factura-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .factura-info {
            margin-bottom: 2rem;
        }
        
        .factura-table {
            width: 100%;
            margin-bottom: 2rem;
            border-collapse: collapse;
        }
        
        .factura-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .factura-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .factura-total {
            text-align: right;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 2px solid var(--primary-color);
        }
        
        .factura-footer {
            margin-top: 3rem;
            text-align: center;
            padding-top: 1rem;
        }
        
        .btn-imprimir {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .btn-imprimir:hover {
            background-color: var(--secondary-color);
        }
        
        @media print {
            header, .btn-imprimir, .btn {
                display: none;
            }
            
            body {
                padding: 0;
                margin: 0;
            }
            
            .factura-container {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Tienda Online</h1>
        <nav>
            <a href="productos.php">Productos</a>
            <a href="carrito.php">Carrito</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main class="factura-container">
        <div class="factura-header">
            <h2>Factura de Compra</h2>
            <p>Número de Pedido: #<?= $pedido_id ?></p>
            <p>Fecha: <?= date('d/m/Y H:i', strtotime($pedido_data['fecha_pedido'])) ?></p>
            <p>Cliente: <?= htmlspecialchars($pedido_data['nombre'] . ' ' . $pedido_data['apellido']) ?></p>
        </div>
        
        <table class="factura-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['producto_nombre']) ?></td>
                    <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio_unitario'] * $item['cantidad'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="factura-total">
            <p>Total a Pagar: $<?= number_format($pedido_data['total'], 2) ?></p>
        </div>
        
        <div class="factura-footer">
            <p>¡Gracias por su compra!</p>
            <button class="btn-imprimir" onclick="window.print()">Imprimir Factura</button>
            <a href="productos.php" class="btn">Volver a la Tienda</a>
        </div>
    </main>
</body>
</html>