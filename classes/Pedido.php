<?php
class Pedido {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function crearPedido($usuario_id, $items, $total) {
        // Iniciar transacción
        $this->db->begin_transaction();

        try {
            // Insertar pedido
            $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
            $stmt->bind_param("ids", $usuario_id, $total);
            $stmt->execute();
            $pedido_id = $this->db->insert_id;

            // Insertar detalles del pedido
            $stmt = $this->db->prepare("INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            
            foreach ($items as $item) {
                $stmt->bind_param("iiid", $pedido_id, $item['id'], $item['cantidad'], $item['precio']);
                $stmt->execute();
            }

            $this->db->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getPedido($pedido_id) {
        $stmt = $this->db->prepare("SELECT p.*, u.nombre, u.apellido FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?");
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getDetallesPedido($pedido_id) {
        $stmt = $this->db->prepare("SELECT dp.*, pr.nombre as producto_nombre FROM detalles_pedido dp JOIN productos pr ON dp.producto_id = pr.id WHERE dp.pedido_id = ?");
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>