<?php
class Carrito {
    public function __construct() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [
                'items' => [],
                'total' => 0,
                'cantidad_items' => 0
            ];
        }
    }

    public function agregarProducto($producto_id, $nombre, $precio, $cantidad = 1) {
        if (isset($_SESSION['carrito']['items'][$producto_id])) {
            $_SESSION['carrito']['items'][$producto_id]['cantidad'] += $cantidad;
            $_SESSION['carrito']['items'][$producto_id]['subtotal'] = 
                $_SESSION['carrito']['items'][$producto_id]['cantidad'] * $precio;
        } else {
            $_SESSION['carrito']['items'][$producto_id] = [
                'id' => $producto_id,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'subtotal' => $precio * $cantidad
            ];
        }
        
        $this->actualizarTotales();
    }

    public function eliminarProducto($producto_id) {
        if (isset($_SESSION['carrito']['items'][$producto_id])) {
            unset($_SESSION['carrito']['items'][$producto_id]);
            $this->actualizarTotales();
            return true;
        }
        return false;
    }

    public function actualizarCantidad($producto_id, $cantidad) {
        if (isset($_SESSION['carrito']['items'][$producto_id]) && $cantidad > 0) {
            $_SESSION['carrito']['items'][$producto_id]['cantidad'] = $cantidad;
            $_SESSION['carrito']['items'][$producto_id]['subtotal'] = 
                $cantidad * $_SESSION['carrito']['items'][$producto_id]['precio'];
            $this->actualizarTotales();
            return true;
        }
        return false;
    }

    private function actualizarTotales() {
        $total = 0;
        $cantidad_items = 0;
        
        foreach ($_SESSION['carrito']['items'] as $item) {
            $total += $item['subtotal'];
            $cantidad_items += $item['cantidad'];
        }
        
        $_SESSION['carrito']['total'] = $total;
        $_SESSION['carrito']['cantidad_items'] = $cantidad_items;
    }

    public function getCarrito() {
        return $_SESSION['carrito'];
    }

    public function vaciarCarrito() {
        $_SESSION['carrito'] = [
            'items' => [],
            'total' => 0,
            'cantidad_items' => 0
        ];
    }
}
?>