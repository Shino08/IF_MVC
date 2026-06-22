<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10 print:bg-white print:py-0">
    <div class="max-w-4xl mx-auto px-4 print:px-0">
        
        <!-- Actions (Hidden on print) -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 print:hidden">
            <a href="<?= $base_url ?? '' ?>/mis-cotizaciones" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
            </a>
            <div class="flex items-center gap-3">
                <a href="<?= $base_url ?? '' ?>/cotizacion/pdf/<?= $cotizacion['id'] ?>" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Descargar PDF
                </a>
                <?php if(isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <form action="<?= $base_url ?? '' ?>/cotizacion/enviar_correo/<?= $cotizacion['id'] ?>" method="POST" class="inline">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Enviar por Correo
                    </button>
                </form>
                <?php endif; ?>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Imprimir
                </button>
                <?php if($cotizacion['estado_id'] == 3): ?>
                <form action="<?= $base_url ?? '' ?>/cotizacion/confirmar" method="POST" class="inline">
                    <input type="hidden" name="cotizacion_id" value="<?= $cotizacion['id'] ?>">
                    <button type="button" onclick="alert('Funcionalidad de pago / confirmación a implementar');" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirmar Interés
                    </button>
                </form>
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
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider mb-2">Cotización</h1>
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
                // Use the calculated subtotal from the DB or re-calculated above.
                // Assuming $cotizacion['subtotal'] exists, but $subtotalCalc is precise to details.
                $subtotal = $subtotalCalc;
                $iva = $subtotal * 0.16;
                $descuento = 0.00;
                $totalFinal = $subtotal + $iva - $descuento;
            ?>
            <div class="px-8 flex justify-end">
                <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">Subtotal:</span>
                        <span class="font-medium text-gray-900">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">IVA (16%):</span>
                        <span class="font-medium text-gray-900">$<?= number_format($iva, 2) ?></span>
                    </div>
                    <?php if($descuento > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-red-500">Descuento:</span>
                        <span class="font-medium text-red-500">-$<?= number_format($descuento, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-black pt-3 border-t border-gray-200">
                        <span class="text-gray-900">Total USD:</span>
                        <span class="text-red-600">$<?= number_format($totalFinal, 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="px-8 py-8 mt-8 border-t border-gray-200">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Notas Técnicas y Comerciales</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line mb-6"><?= htmlspecialchars($cotizacion['notas_tecnicas'] ?? 'No se especificaron notas adicionales.') ?></p>

                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Términos y Condiciones</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Los precios están expresados en dólares americanos (USD).</li>
                    <li>La validez de esta cotización es de 15 días hábiles a partir de su emisión.</li>
                    <li>Formas de pago aceptadas: Transferencia bancaria, Zelle, efectivo.</li>
                    <li>La entrega se realizará en los plazos acordados tras la confirmación de pago.</li>
                    <li>Garantía aplicable según especificaciones del fabricante.</li>
                </ul>
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
