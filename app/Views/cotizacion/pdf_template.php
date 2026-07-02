<?php
// Template for PDF generation
$subtotal = (float)$cotizacion['subtotal'];
$iva = (float)$cotizacion['impuestos'];
$descuento = (float)$cotizacion['descuento'];
$costoEnvio = (float)$cotizacion['costo_envio'];
$totalFinal = (float)$cotizacion['total'];

$cotizacionNum = str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT);
$year = date('Y', strtotime($cotizacion['fecha_solicitud']));
$fecha = date('d/m/Y', strtotime($cotizacion['fecha_solicitud']));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido #<?= $cotizacion['id'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .header td { vertical-align: top; }
        .company-info h2 { margin: 0; color: #111; }
        .company-info p { margin: 2px 0; color: #555; }
        .quote-info { text-align: right; }
        .quote-info h1 { margin: 0 0 10px 0; color: #111; text-transform: uppercase; font-size: 24px; }
        .quote-info p { margin: 2px 0; }
        .client-info { background: #f9f9f9; padding: 15px; margin-bottom: 20px; border: 1px solid #eee; }
        .client-info h3 { margin-top: 0; font-size: 14px; text-transform: uppercase; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .client-table { width: 100%; }
        .client-table td { width: 50%; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .items-table th { background: #f5f5f5; font-weight: bold; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        .totals-table { width: 100%; }
        .totals-table td { padding: 5px; }
        .totals-container { width: 40%; float: right; }
        .total-row { font-weight: bold; font-size: 14px; border-top: 2px solid #ddd; padding-top: 5px; color: #d32f2f; }
        .notes { margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; }
        .notes h4 { font-size: 12px; text-transform: uppercase; margin-bottom: 5px; }
        .notes p, .notes ul { color: #555; }
        .clear { clear: both; }
    </style>
</head>
<body><?php 
$logoFile = dirname(__DIR__, 3) . '/public/img/Photoroom-20251106_165742.png';
$logoSrc = '';
if (file_exists($logoFile)) {
    $imgData = base64_encode(file_get_contents($logoFile));
    $logoSrc = 'data:image/png;base64,' . $imgData;
}
?>

<div class="header">
    <table>
        <tr>
            <td class="company-info">
                <?php if (!empty($logoSrc)): ?>
                <img src="<?= $logoSrc ?>" alt="InstalFuego" style="height:70px; margin-bottom: 8px;">
                <?php endif; ?>
                <h2>InstalFuego C.A.</h2>
                <p>RIF: J-12345678-9</p>
                <p>Av. Principal, Caracas, Venezuela</p>
                <p>contacto@instalfuego.com | +58 412-1234567</p>
            </td>
            <td class="quote-info">
                <h1>Pedido</h1>
                <p><strong>Nro:</strong> #PED-<?= $year ?>-<?= $cotizacionNum ?></p>
                <p><strong>Fecha:</strong> <?= $fecha ?></p>
                <p><strong>Validez:</strong> 15 días hábiles</p>
                <p><strong>Estado:</strong> <?= strtoupper($cotizacion['estado_nombre']) ?></p>
            </td>
        </tr>
    </table>
</div>

<div class="client-info">
    <h3>Facturado a:</h3>
    <table class="client-table">
        <tr>
            <td>
                <strong><?= htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']) ?></strong><br>
                <?= htmlspecialchars($cotizacion['cliente_empresa'] ?? 'Persona Natural') ?><br>
                CI/RIF: <?= htmlspecialchars($cotizacion['cliente_cedula'] ?? 'N/A') ?>
            </td>
            <td>
                <?= htmlspecialchars($cotizacion['cliente_email']) ?><br>
                <?= htmlspecialchars($cotizacion['cliente_telefono'] ?? 'Teléfono no provisto') ?>
            </td>
        </tr>
    </table>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th>Descripción del Item</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Cant.</th>
            <th class="text-right">P. Unitario ($)</th>
            <th class="text-right">Subtotal ($)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalles as $item): 
            $lineTotal = $item['cantidad'] * $item['precio_unitario'];
        ?>
        <tr>
            <td>
                <strong><?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?></strong><br>
            </td>
            <td class="text-center"><?= !empty($item['producto_id']) ? 'Prod' : 'Serv' ?></td>
            <td class="text-center"><?= !empty($item['producto_id']) ? (int)$item['cantidad'] : 'N/A' ?></td>
            <td class="text-right"><?= number_format((float)$item['precio_unitario'], 2) ?></td>
            <td class="text-right"><?= number_format((float)$lineTotal, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="totals-container">
    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">$<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php if (isset($cotizacion['aplica_iva']) && $cotizacion['aplica_iva'] == 1): ?>
        <tr>
            <td>IVA (<?= number_format((float)($cotizacion['tasa_iva'] ?? 16), 0) ?>%):</td>
            <td class="text-right">$<?= number_format($iva, 2) ?></td>
        </tr>
        <?php elseif (isset($cotizacion['aplica_iva']) && $cotizacion['aplica_iva'] == 0): ?>
        <tr>
            <td>IVA / Impuestos:</td>
            <td class="text-right">Exento ($0.00)</td>
        </tr>
        <?php else: ?>
        <tr>
            <td>IVA / Impuestos:</td>
            <td class="text-right">$<?= number_format($iva, 2) ?></td>
        </tr>
        <?php endif; ?>
        <?php if($descuento > 0): ?>
        <tr>
            <td style="color:red;">Descuento:</td>
            <td class="text-right" style="color:red;">-$<?= number_format($descuento, 2) ?></td>
        </tr>
        <?php endif; ?>
        <?php if (!empty($cotizacion['costo_envio']) && $cotizacion['costo_envio'] > 0): ?>
        <tr>
            <td>Costo de Envío:</td>
            <td class="text-right">$<?= number_format($cotizacion['costo_envio'], 2) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="total-row">Total USD:</td>
            <td class="text-right total-row">$<?= number_format($totalFinal, 2) ?></td>
        </tr>
    </table>
</div>
<div class="clear"></div>    <?php if (!empty($cotizacion['tasabcv'])): ?>
    <div style="margin-top: 20px; padding: 10px; background: #fef9e7; border: 1px solid #f9e79f; border-radius: 4px; font-size: 10px;">
        <strong>Tasa de Cambio Referencial:</strong>
        Bs. <?= number_format((float)$cotizacion['tasabcv'], 4, ',', '.') ?> / $1 USD
        (al <?= date('d/m/Y', strtotime($cotizacion['fecha_solicitud'])) ?>)<br>
        <?php if (!empty($cotizacion['montousd'])): ?>
        <span style="color: #666;">Equivalente: <strong>$<?= number_format((float)$cotizacion['montousd'], 2) ?> USD</strong></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($cotizacion['tipo_entrega'])): ?>
    <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; font-size: 11px;">
        <h4 style="margin-top:0; margin-bottom:5px;">Información de Logística</h4>
        <strong>Método de Entrega:</strong> <?= $cotizacion['tipo_entrega'] === 'domicilio' ? 'Envío a Domicilio' : 'Retiro en Tienda' ?><br>
        <?php if ($cotizacion['tipo_entrega'] === 'domicilio' && !empty($cotizacion['direccion_envio'])): ?>
        <strong>Dirección:</strong> <?= htmlspecialchars($cotizacion['direccion_envio']) ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="notes">
        <h4>Notas Técnicas y Comerciales</h4>
        <p><?= nl2br(htmlspecialchars($cotizacion['notas_tecnicas'] ?? 'No se especificaron notas adicionales.')) ?></p>

        <h4>Términos y Condiciones</h4>
        <ul>
            <li>Los precios están expresados en dólares americanos (USD).</li>
            <li>Formas de pago aceptadas: Transferencia bancaria y Pago Móvil.</li>
            <li>El monto total incluye impuestos aplicables y, cuando corresponda, costo de envío o domicilio.</li>
            <li>El costo de entrega a domicilio será determinado según la ubicación y coordinado previamente con el cliente.</li>
            <li>La ejecución de la entrega o servicio se realizará una vez confirmado el pago.</li>
            <li>Garantía aplicable según especificaciones del fabricante.</li>
            <li>Para coordinar pagos en divisas, comuníquese con soporte vía WhatsApp: <?= \App\Core\Config::WHATSAPP_DISPLAY ?></li>
        </ul>
    </div>

</body>
</html>
