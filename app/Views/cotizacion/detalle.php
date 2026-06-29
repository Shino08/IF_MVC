<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10 print:bg-white print:py-0">
    <div class="max-w-4xl mx-auto px-4 print:px-0">
        
        <!-- Actions (Hidden on print) -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 print:hidden">
            <a href="<?= $base_url ?? '' ?>/mis-pedidos" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <a href="<?= $base_url ?? '' ?>/pedido/pdf/<?= $cotizacion['id'] ?>" target="_blank" class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-md text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-lg hover:shadow-xl ring-1 ring-red-400/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Descargar PDF
                </a>
                <?php if ($cotizacion['estado_id'] == 4): ?>
                <a href="<?= $base_url ?? '' ?>/factura/pdf/<?= $cotizacion['id'] ?>" target="_blank" class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-md text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl ring-1 ring-green-400/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Descargar Factura
                </a>
                <?php endif; ?>
                <?php if(isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <form action="<?= $base_url ?? '' ?>/pedido/enviar_correo/<?= $cotizacion['id'] ?>" method="POST" class="inline">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Enviar por Correo
                    </button>
                </form>
                <?php endif; ?>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Imprimir
                </button>
                <?php if($cotizacion['estado_id'] == 3 || $cotizacion['estado_id'] == 4): ?>
                    <?php if(isset($pedido) && $pedido['estado_pedido'] === 'pago_por_validar'): ?>
                        <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-yellow-800 bg-yellow-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pago en Revisión
                        </span>
                    <?php elseif(isset($pedido) && in_array($pedido['estado_pedido'], ['procesando', 'despachado', 'entregado'])): ?>
                        <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-green-800 bg-green-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Pago Completado
                        </span>
                    <?php else: ?>
                        <a href="<?= $base_url ?? '' ?>/pedido/pagar/<?= $cotizacion['id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Reportar Pago
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Document -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden print:shadow-none print:border-none print:rounded-none">
            
            <!-- Header -->
            <div class="px-8 py-10 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <img src="<?= $base_url ?? '' ?>/img/Photoroom-20251106_165742.png" alt="InstalFuego Logo" class="h-16 md:h-20 mb-4 object-contain">
                    <h2 class="text-xl font-bold text-gray-900">InstalFuego C.A.</h2>
                    <p class="text-sm text-gray-500 mt-1">RIF: J-12345678-9</p>
                    <p class="text-sm text-gray-500">Av. Principal, Caracas, Venezuela</p>
                    <p class="text-sm text-gray-500">contacto@instalfuego.com | +58 412-1234567</p>
                </div>
                <div class="text-left md:text-right">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider mb-2">Presupuesto</h1>
                    <p class="text-sm text-gray-600"><span class="font-bold">Nro:</span> #COT-<?= date('Y', strtotime($cotizacion['fecha_solicitud'])) ?>-<?= str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT) ?></p>
                    <p class="text-sm text-gray-600 mt-1"><span class="font-bold">Fecha:</span> <?= date('d/m/Y', strtotime($cotizacion['fecha_solicitud'])) ?></p>
                    <p class="text-sm text-gray-600 mt-1"><span class="font-bold">Validez:</span> 15 días hábiles</p>
                    <div class="mt-4 inline-block px-3 py-1 text-xs font-bold rounded-full border <?php 
                        if($cotizacion['estado_id'] == 2) echo 'border-yellow-200 bg-yellow-50 text-yellow-800';
                        elseif($cotizacion['estado_id'] == 3) echo 'border-green-200 bg-green-50 text-green-800';
                        else echo 'border-red-200 bg-red-50 text-red-800'; 
                    ?>">
                        <?= htmlspecialchars(strtoupper($cotizacion['estado_nombre'])) ?>
                    </div>
                </div>
            </div>

            <!-- Client Info -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Cotizado a:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']) ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($cotizacion['cliente_empresa'] ?? 'Persona Natural') ?></p>
                        <p class="text-sm text-gray-600">CI/RIF: <?= htmlspecialchars($cotizacion['cliente_cedula'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($cotizacion['cliente_email']) ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($cotizacion['cliente_telefono'] ?? 'Teléfono no provisto') ?></p>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="px-8 py-8">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 text-sm font-bold text-gray-900">Descripción del Item</th>
                            <th class="py-3 text-center text-sm font-bold text-gray-900">Tipo</th>
                            <th class="py-3 text-center text-sm font-bold text-gray-900">Cant.</th>
                            <th class="py-3 text-right text-sm font-bold text-gray-900">P. Unitario ($)</th>
                            <th class="py-3 text-right text-sm font-bold text-gray-900">Subtotal ($)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php 
                        $subtotalCalc = 0;
                        foreach ($detalles as $item): 
                            $lineTotal = $item['cantidad'] * $item['precio_unitario'];
                            $subtotalCalc += $lineTotal;
                        ?>
                            <tr>
                                <td class="py-4">
                                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?></p>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5"><?= !empty($item['producto_id']) ? 'SKU: ' . htmlspecialchars($item['sku'] ?? 'N/A') : 'COD: ' . htmlspecialchars($item['codigo'] ?? 'N/A') ?></p>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="text-xs font-medium px-2 py-1 rounded-md <?= !empty($item['producto_id']) ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' ?>">
                                        <?= !empty($item['producto_id']) ? 'Prod' : 'Serv' ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center text-sm text-gray-700"><?= $item['cantidad'] ?></td>
                                <td class="py-4 text-right text-sm text-gray-700"><?= number_format((float)$item['precio_unitario'], 2) ?></td>
                                <td class="py-4 text-right text-sm font-medium text-gray-900"><?= number_format((float)$lineTotal, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <?php
                // Usar valores guardados de la base de datos
                $subtotal = (float)$cotizacion['subtotal'];
                $iva = (float)$cotizacion['impuestos'];
                $descuento = (float)$cotizacion['descuento'];
                $costoEnvio = (float)($cotizacion['costo_envio'] ?? 0);
                $totalFinal = (float)$cotizacion['total'];
            ?>
            <div class="px-8 flex justify-end">
                <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">Subtotal:</span>
                        <span class="font-medium text-gray-900">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">IVA / Impuestos:</span>
                        <span class="font-medium text-gray-900">$<?= number_format($iva, 2) ?></span>
                    </div>
                    <?php if($descuento > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-red-500">Descuento:</span>
                        <span class="font-medium text-red-500">-$<?= number_format($descuento, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($costoEnvio > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">Costo de Envío:</span>
                        <span class="font-medium text-gray-900">$<?= number_format($costoEnvio, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-black pt-3 border-t border-gray-200">
                        <span class="text-gray-900">Total USD:</span>
                        <span class="text-red-600">$<?= number_format($totalFinal, 2) ?></span>
                    </div>
                    <?php if (!empty($cotizacion['montousd'])): ?>
                    <div class="flex justify-between text-sm pt-1">
                        <span class="text-gray-500">En Bs. (tasa BCV):</span>
                        <span class="font-semibold text-gray-700">Bs. <?= number_format((float)$cotizacion['montousd'], 2, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Logistics -->
            <?php if (!empty($cotizacion['tipo_entrega'])): ?>
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 mt-8 rounded-b-xl">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Información de Entrega</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tipo de Entrega</p>
                        <span class="block text-sm text-gray-900 mt-1"><?= $cotizacion['tipo_entrega'] === 'domicilio' ? 'Envío a Domicilio' : 'Retiro en Tienda' ?></span>
                    </div>
                    <?php if ($cotizacion['tipo_entrega'] === 'domicilio' && !empty($cotizacion['direccion_envio'])): ?>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Dirección</p>
                        <span class="block text-sm text-gray-900 mt-1"><?= htmlspecialchars($cotizacion['direccion_envio']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Notes -->
            <div class="px-8 py-8 border-t border-gray-200">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Notas Técnicas y Comerciales</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line mb-6"><?= htmlspecialchars($cotizacion['notas_tecnicas'] ?? 'No se especificaron notas adicionales.') ?></p>                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Términos y Condiciones</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Los precios están expresados en dólares americanos (USD).</li>
                    <li>La validez de esta cotización es de 15 días hábiles a partir de su emisión.</li>
                    <li>Formas de pago aceptadas: Transferencia bancaria, Pago Móvil, efectivo, divisas.</li>
                    <li>La entrega se realizará en los plazos acordados tras la confirmación de pago.</li>
                    <li>Garantía aplicable según especificaciones del fabricante.</li>
                </ul>

                <!-- Tasa BCV y WhatsApp -->
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg print:bg-yellow-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <p class="text-xs font-bold text-yellow-800 uppercase tracking-wider mb-0.5">Tasa de Cambio Referencial</p>
                            <?php if (!empty($cotizacion['tasabcv'])): ?>
                            <p class="text-sm text-yellow-900">
                                <strong>Bs. <?= number_format((float)$cotizacion['tasabcv'], 4, ',', '.') ?></strong> / $1 USD
                                <span class="text-yellow-700 text-xs">(al <?= date('d/m/Y', strtotime($cotizacion['fecha_solicitud'])) ?>)</span>
                            </p>
                            <?php if (!empty($cotizacion['montousd'])): ?>
                            <p class="text-xs text-yellow-700 mt-0.5">
                                Equivale a ≈ <strong>$<?= number_format((float)$cotizacion['montousd'], 2) ?> USD</strong>
                            </p>
                            <?php endif; ?>
                            <?php else: ?>
                            <p class="text-sm text-yellow-800">Consulte la tasa del día con su asesor.</p>
                            <?php endif; ?>
                        </div>
                        <a href="<?= \App\Core\Config::whatsappUrl('Presupuesto #COT-' . date('Y', strtotime($cotizacion['fecha_solicitud'])) . '-' . str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT)) ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition-all shadow-sm hover:shadow-md flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Coordinar Pago
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
@media print {
    body {
        -webkit-print-color-adjust: exact;
        background-color: white !important;
        margin: 1.5cm !important;
    }
    .sticky.top-0, nav, footer, .print\:hidden {
        display: none !important;
    }
    .bg-gray-50 {
        background-color: white !important;
    }
    .max-w-4xl {
        max-width: 100% !important;
        width: 100% !important;
    }
    @page {
        size: auto;
        margin: 0;
    }
}
</style>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
