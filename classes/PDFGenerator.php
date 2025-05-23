<?php
require_once('tcpdf/tcpdf.php');

class PDFGenerator extends TCPDF {
    public function generarRecibo($pedido, $detalles, $cliente) {
        // Configuración del documento
        $this->SetCreator('Sistema de Carrito');
        $this->SetAuthor('Tienda Online');
        $this->SetTitle('Recibo de Compra #' . $pedido['id']);
        $this->SetMargins(15, 15, 15);

        // Añadir página
        $this->AddPage();

        // Logo
        $this->Image('assets/img/logo.png', 10, 10, 30);

        // Título
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'RECIBO DE COMPRA', 0, 1, 'C');
        $this->Ln(10);

        // Información del pedido
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 10, 'Número de Pedido: #' . $pedido['id'], 0, 1);
        $this->Cell(0, 10, 'Fecha: ' . date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])), 0, 1);
        $this->Cell(0, 10, 'Cliente: ' . $cliente['nombre'] . ' ' . $cliente['apellido'], 0, 1);
        $this->Ln(10);

        // Tabla de productos
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(100, 10, 'Producto', 1, 0, 'C');
        $this->Cell(30, 10, 'Precio', 1, 0, 'C');
        $this->Cell(20, 10, 'Cantidad', 1, 0, 'C');
        $this->Cell(30, 10, 'Subtotal', 1, 1, 'C');

        $this->SetFont('helvetica', '', 12);
        foreach ($detalles as $item) {
            $this->Cell(100, 10, $item['producto_nombre'], 1);
            $this->Cell(30, 10, '$' . number_format($item['precio_unitario'], 2), 1, 0, 'R');
            $this->Cell(20, 10, $item['cantidad'], 1, 0, 'C');
            $this->Cell(30, 10, '$' . number_format($item['precio_unitario'] * $item['cantidad'], 2), 1, 1, 'R');
        }

        // Total
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(150, 10, 'TOTAL:', 1, 0, 'R');
        $this->Cell(30, 10, '$' . number_format($pedido['total'], 2), 1, 1, 'R');
        $this->Ln(20);

        // Firma
        $this->Cell(0, 10, '_________________________________________', 0, 1, 'C');
        $this->Cell(0, 10, 'Firma del Cliente', 0, 1, 'C');

        // Guardar o mostrar PDF
        $this->Output('recibo_' . $pedido['id'] . '.pdf', 'I');
    }
}
?>