<?php $title = 'Detalle de Solicitud #' . $cotizacion['id']; $active_nav = 'cotizaciones'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<?php
$pasoActual = (int)($_GET['paso'] ?? 1);
$pasos = [
    1 => ['label' => 'Ítems', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    2 => ['label' => 'Configurar', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
    3 => ['label' => 'Revisar y Emitir', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
];
$totalPasos = count($pasos);

// Cálculos para preview
$calcSubtotal = 0;
foreach ($detalles as $item) $calcSubtotal += $item['cantidad'] * $item['precio_unitario'];
$previewDescuento = (float)($cotizacion['descuento'] ?? 0);
$previewImpuestos = (float)($cotizacion['impuestos'] ?? 0);
$previewTotal = $calcSubtotal + $previewImpuestos - $previewDescuento;
if ($previewTotal < 0) $previewTotal = 0;
?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <!-- ─── HEADER ─── -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex-shrink-0 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="<?= $base_url ?? '' ?>/dashboard/cotizaciones" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl font-bold text-gray-900">Solicitud #COT<?= str_pad((string)$cotizacion['id'], 6, '0', STR_PAD_LEFT) ?></h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?php 
                            if($cotizacion['estado_id'] == 2) echo 'bg-yellow-100 text-yellow-800';
                            elseif($cotizacion['estado_id'] == 3) echo 'bg-blue-100 text-blue-800';
                            elseif($cotizacion['estado_id'] == 4) echo 'bg-green-100 text-green-800';
                            elseif($cotizacion['estado_id'] == 5) echo 'bg-red-100 text-red-800';
                            else echo 'bg-gray-100 text-gray-800';
                        ?>">
                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $cotizacion['estado_nombre']))) ?>
                        </span>
                    </div>
                    <p class="text-gray-400 text-xs mt-0.5">Recibida el <?= date('d/m/Y H:i', strtotime($cotizacion['fecha_solicitud'])) ?> • <?= htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']) ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <?php if ($cotizacion['estado_id'] == 2): ?>
                    <span class="text-xs text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full font-semibold">Pendiente de revisión</span>
                <?php endif; ?>
                <a href="<?= $base_url ?? '' ?>/cotizacion/pdf/<?= $cotizacion['id'] ?>" target="_blank" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    PDF
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="mx-8 mt-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm font-medium flex items-center gap-2 animate-fade-in">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <?php echo htmlspecialchars($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="mx-8 mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm font-medium flex items-center gap-2 animate-fade-in">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <?php echo htmlspecialchars($_SESSION['error_msg']); unset($_SESSION['error_msg']); ?>
            </div>
        <?php endif; ?>

        <!-- ─── STEP NAVIGATOR ─── -->
        <div class="bg-white border-b border-gray-200 px-8 py-0">
            <div class="flex items-center gap-0 max-w-3xl mx-auto">
                <?php for ($i = 1; $i <= $totalPasos; $i++): 
                    $isActive = ($pasoActual === $i);
                    $isDone = ($pasoActual > $i);
                ?>
                    <a href="?paso=<?= $i ?>" class="flex-1 flex flex-col items-center py-4 relative <?= $isActive ? 'text-red-600' : ($isDone ? 'text-green-600' : 'text-gray-400') ?> hover:text-red-500 transition-colors group">
                        <!-- Connector line -->
                        <?php if ($i < $totalPasos): ?>
                            <div class="absolute top-[30px] left-[60%] right-0 h-0.5 <?= $isDone ? 'bg-green-500' : 'bg-gray-200' ?> -z-0"></div>
                        <?php endif; ?>
                        <!-- Circle -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold z-10 transition-all duration-300 <?= $isActive ? 'bg-red-600 text-white ring-4 ring-red-100' : ($isDone ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200') ?>">
                            <?php if ($isDone): ?>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <?php else: ?>
                                <?= $i ?>
                            <?php endif; ?>
                        </div>
                        <span class="text-[10px] font-semibold mt-1.5 uppercase tracking-wider whitespace-nowrap <?= $isActive ? 'text-red-600' : ($isDone ? 'text-green-600' : 'text-gray-400') ?>">
                            <?= $pasos[$i]['label'] ?>
                        </span>
                    </a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- ─── CONTENIDO ─── -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-6 max-w-5xl mx-auto">

                <!-- ════════ PASO 1: ÍTEMS ════════ -->
                <div class="<?= $pasoActual === 1 ? 'block' : 'hidden' ?>">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Ítems Solicitados
                            </h2>
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full"><?= count($detalles) ?> item(s)</span>
                        </div>

                        <?php if (empty($detalles)): ?>
                            <div class="p-16 text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <p class="text-sm font-medium">Esta cotización no tiene ítems</p>
                                <p class="text-xs mt-1">El cliente debe agregar productos o servicios desde el catálogo.</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100">
                                            <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Item</th>
                                            <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider">Cant.</th>
                                            <th class="px-4 py-3 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">P. Unitario</th>
                                            <th class="px-4 py-3 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                                            <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider w-16"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <?php foreach ($detalles as $item): 
                                            $lineTotal = $item['cantidad'] * $item['precio_unitario'];
                                            $imgFile = !empty($item['producto_id']) ? ($item['producto_imagen'] ?? '') : ($item['servicio_imagen'] ?? '');
                                            $imgDir = !empty($item['producto_id']) ? '/img/productos/' : '/img/servicios/';
                                            $imgPath = !empty($imgFile) ? (dirname(__DIR__,3) . '/public' . $imgDir . $imgFile) : '';
                                            $imgUrl = (!empty($imgFile) && file_exists($imgPath)) ? ($base_url ?? '') . $imgDir . htmlspecialchars($imgFile) : ($base_url ?? '') . '/img/user.png';
                                        ?>
                                        <tr class="hover:bg-gray-50/50 transition-colors" data-detalle-id="<?= $item['id'] ?>">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 bg-gray-50 rounded-lg border border-gray-100 p-1 shrink-0">
                                                        <img class="w-full h-full object-contain" src="<?= $imgUrl ?>" alt="">
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 truncate max-w-[200px]"><?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?></p>
                                                        <p class="text-[11px] text-gray-400 font-mono"><?= !empty($item['producto_id']) ? 'SKU: ' . htmlspecialchars($item['sku'] ?? 'N/A') : 'COD: ' . htmlspecialchars($item['codigo'] ?? 'N/A') ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex text-[10px] font-bold px-2 py-0.5 rounded-full <?= !empty($item['producto_id']) ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' ?>">
                                                    <?= !empty($item['producto_id']) ? 'Producto' : 'Servicio' ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="number" step="0.01" min="0.01" value="<?= $item['cantidad'] ?>"
                                                       class="item-cantidad w-20 px-2 py-1.5 text-sm text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                                       data-detalle-id="<?= $item['id'] ?>">
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="inline-flex items-center gap-0.5">
                                                    <span class="text-gray-400 text-xs">$</span>
                                                    <input type="number" step="0.01" min="0" value="<?= number_format((float)$item['precio_unitario'], 2, '.', '') ?>"
                                                           class="item-precio w-22 px-2 py-1.5 text-sm text-right border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                                           data-detalle-id="<?= $item['id'] ?>">
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 item-subtotal">
                                                $<?= number_format((float)$lineTotal, 2) ?>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" onclick="confirmarAccion('eliminar', <?= $item['id'] ?>, '<?= htmlspecialchars(addslashes(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre'])) ?>')"
                                                        class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Eliminar ítem">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totals bar -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-xs text-gray-500">Los precios se guardan automáticamente al editarlos</span>
                                <div class="text-right">
                                    <span class="text-sm text-gray-600">Subtotal: </span>
                                    <span class="text-lg font-bold text-gray-900">$<?= number_format($calcSubtotal, 2) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Datos Cliente -->
                    <div class="mt-4 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                            Cliente
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div><span class="text-gray-500 text-xs">Nombre</span><p class="font-medium text-gray-900"><?= htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']) ?></p></div>
                            <div><span class="text-gray-500 text-xs">Cédula</span><p class="font-medium text-gray-900"><?= htmlspecialchars($cotizacion['cliente_cedula'] ?? 'N/A') ?></p></div>
                            <div><span class="text-gray-500 text-xs">Email</span><p class="font-medium text-blue-600"><?= htmlspecialchars($cotizacion['cliente_email']) ?></p></div>
                            <div><span class="text-gray-500 text-xs">Teléfono</span><p class="font-medium text-gray-900"><?= htmlspecialchars($cotizacion['cliente_telefono'] ?? 'N/A') ?></p></div>
                        </div>
                    </div>

                    <!-- Next button -->
                    <div class="mt-6 flex justify-end">
                        <a href="?paso=2" class="inline-flex items-center px-6 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm gap-2">
                            Continuar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- ════════ PASO 2: CONFIGURAR ════════ -->
                <div class="<?= $pasoActual === 2 ? 'block' : 'hidden' ?>">
                    <form id="comercial-form" action="<?= $base_url ?? '' ?>/dashboard/cotizaciones/actualizar-comercial" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <input type="hidden" name="cotizacion_id" value="<?= $cotizacion['id'] ?>">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                                Configuración Comercial
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" value="<?= htmlspecialchars($cotizacion['fecha_vencimiento'] ?? '') ?>" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Método de Pago</label>
                                <select name="id_metodo_pago" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($metodosPago as $mp): ?>
                                    <option value="<?= $mp['id'] ?>" <?= ($cotizacion['id_metodo_pago'] ?? null) == $mp['id'] ? 'selected' : '' ?>><?= htmlspecialchars($mp['metodo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Descuento <span class="text-gray-400 normal-case font-normal">($)</span></label>
                                <input type="number" step="0.01" min="0" name="descuento" value="<?= number_format((float)($cotizacion['descuento'] ?? 0), 2, '.', '') ?>" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">IVA / Impuestos <span class="text-gray-400 normal-case font-normal">($)</span></label>
                                <input type="number" step="0.01" min="0" name="impuestos" value="<?= number_format((float)($cotizacion['impuestos'] ?? 0), 2, '.', '') ?>" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Condiciones de Pago</label>
                                <input type="text" name="condiciones_pago" value="<?= htmlspecialchars($cotizacion['condiciones_pago'] ?? '') ?>" placeholder="Ej: 50% anticipo, 50% contra entrega" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Proyecto / Referencia</label>
                                <input type="text" name="proyecto_referencia" value="<?= htmlspecialchars($cotizacion['proyecto_referencia'] ?? '') ?>" placeholder="Ej: Proy-2026-001" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                            <a href="?paso=1" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                                Atrás
                            </a>
                            <div class="flex gap-3">
                                <button type="submit" name="paso" value="2" class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                    Guardar Cambios
                                </button>
                                <button type="submit" name="paso" value="3" class="px-5 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                                    Guardar y Continuar
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ════════ PASO 3: REVISAR Y EMITIR ════════ -->
                <div class="<?= $pasoActual === 3 ? 'block' : 'hidden' ?> space-y-6">

                    <!-- Notas -->
                    <form action="<?= $base_url ?? '' ?>/dashboard/cotizaciones/actualizar-comercial" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <input type="hidden" name="cotizacion_id" value="<?= $cotizacion['id'] ?>">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-base font-bold text-gray-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                Notas
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    Notas Internas
                                    <span class="text-[8px] text-yellow-600 bg-yellow-50 px-1 py-0.5 rounded font-normal">solo admin</span>
                                </label>
                                <textarea name="notas_internas" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-yellow-50/30 focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Observaciones internas del equipo..."><?= htmlspecialchars($cotizacion['notas_internas'] ?? '') ?></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    Notas para el Cliente
                                    <span class="text-[8px] text-blue-600 bg-blue-50 px-1 py-0.5 rounded font-normal">visible en PDF</span>
                                </label>
                                <textarea name="notas_tecnicas" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Notas comerciales visibles para el cliente..."><?= htmlspecialchars($cotizacion['notas_tecnicas'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                Guardar Notas
                            </button>
                        </div>
                    </form>

                    <!-- Preview Resumido -->
                    <?php if (!empty($detalles)): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="text-base font-bold text-gray-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Resumen de Cotización
                            </h2>
                            <div class="flex gap-2">
                                <a href="<?= $base_url ?? '' ?>/cotizacion/pdf/<?= $cotizacion['id'] ?>" target="_blank" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">InstalFuego C.A.</p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-gray-900">$<?= number_format($previewTotal, 2) ?></p>
                                    <p class="text-xs text-gray-400">Total USD</p>
                                </div>
                            </div>
                            <div class="flex gap-4 text-xs text-gray-500 border-t border-gray-100 pt-3">
                                <span>Subtotal: <strong class="text-gray-700">$<?= number_format($calcSubtotal, 2) ?></strong></span>
                                <?php if ($previewImpuestos > 0): ?>
                                <span>IVA: <strong class="text-gray-700">$<?= number_format($previewImpuestos, 2) ?></strong></span>
                                <?php endif; ?>
                                <?php if ($previewDescuento > 0): ?>
                                <span class="text-red-500">Dcto: -$<?= number_format($previewDescuento, 2) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($cotizacion['condiciones_pago'])): ?>
                                <span class="text-gray-400">│ <?= htmlspecialchars($cotizacion['condiciones_pago']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Acciones -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-base font-bold text-gray-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Acciones
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php if ($cotizacion['estado_id'] == 2): // Pendiente revisión ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Emitir -->
                                    <button type="button" onclick="confirmarAccion('emitir', <?= $cotizacion['id'] ?>)"
                                            class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Emitir Cotización
                                    </button>
                                    <!-- Rechazar -->
                                    <button type="button" onclick="confirmarAccion('rechazar', <?= $cotizacion['id'] ?>)"
                                            class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Rechazar Cotización
                                    </button>
                                </div>
                                <p class="text-xs text-gray-400 text-center mt-3">Al emitir, el cliente recibirá la cotización y podrá verla en su panel.</p>
                            <?php elseif ($cotizacion['estado_id'] == 3): ?>
                                <div class="space-y-4">
                                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-center">
                                        <p class="text-sm font-bold text-blue-800">Cotización Emitida</p>
                                        <p class="text-xs text-blue-600 mt-1">Esperando respuesta del cliente.</p>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <button type="button" onclick="confirmarAccion('aprobar', <?= $cotizacion['id'] ?>)"
                                                class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Aprobar Cotización
                                        </button>
                                        <button type="button" onclick="confirmarAccion('rechazar', <?= $cotizacion['id'] ?>)"
                                                class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Rechazar Cotización
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-400 text-center">Si el cliente confirmó, márcala como aprobada.</p>
                                </div>
                            <?php elseif ($cotizacion['estado_id'] == 4): ?>
                                <div class="bg-green-50 border border-green-100 rounded-lg p-4 text-center">
                                    <p class="text-sm font-bold text-green-800">✅ Cotización Aprobada por el Cliente</p>
                                </div>
                            <?php elseif ($cotizacion['estado_id'] == 5): ?>
                                <div class="bg-red-50 border border-red-100 rounded-lg p-4 text-center">
                                    <p class="text-sm font-bold text-red-800">❌ Cotización Rechazada</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="flex justify-between">
                        <a href="?paso=2" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                            Atrás
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </main>
</div>

<!-- ════════ MODAL DE CONFIRMACIÓN REUTILIZABLE ════════ -->
<div id="modal-confirmacion" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-auto overflow-hidden transform transition-all">
        <!-- Icono dinámico -->
        <div class="pt-8 pb-2 flex justify-center">
            <div id="modal-icon" class="w-14 h-14 rounded-full flex items-center justify-center">
                <!-- Se llena por JS -->
            </div>
        </div>
        <div class="px-6 pb-6 text-center">
            <h3 id="modal-titulo" class="text-lg font-bold text-gray-900 mb-2"></h3>
            <p id="modal-mensaje" class="text-sm text-gray-500 mb-6"></p>
            <div id="modal-input-container" class="hidden mb-4">
                <input type="text" id="modal-input" placeholder="Motivo del rechazo..." required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="cerrarModal()" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" id="modal-btn-confirmar" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors shadow-sm">
                    <!-- Se llena por JS -->
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ─── Modal de confirmación reutilizable ───
let accionPendiente = null;
let accionId = null;

function confirmarAccion(accion, id, nombre) {
    accionPendiente = accion;
    accionId = id;
    
    const modal = document.getElementById('modal-confirmacion');
    const icon = document.getElementById('modal-icon');
    const titulo = document.getElementById('modal-titulo');
    const mensaje = document.getElementById('modal-mensaje');
    const btnConfirmar = document.getElementById('modal-btn-confirmar');
    const inputContainer = document.getElementById('modal-input-container');
    const input = document.getElementById('modal-input');
    
    inputContainer.classList.add('hidden');
    input.value = '';
    
    if (accion === 'eliminar') {
        icon.innerHTML = '<svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
        icon.className = 'w-14 h-14 rounded-full flex items-center justify-center bg-red-50';
        titulo.textContent = 'Eliminar ítem';
        mensaje.textContent = nombre ? '¿Eliminar "' + nombre + '" de la cotización?' : '¿Eliminar este ítem?';
        btnConfirmar.textContent = 'Eliminar';
        btnConfirmar.className = 'flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm';
    } else if (accion === 'emitir') {
        icon.innerHTML = '<svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
        icon.className = 'w-14 h-14 rounded-full flex items-center justify-center bg-blue-50';
        titulo.textContent = 'Emitir Cotización';
        mensaje.textContent = 'Se cambiará el estado a "Enviada" y el cliente podrá ver la cotización en su panel. ¿Continuar?';
        btnConfirmar.textContent = 'Emitir';
        btnConfirmar.className = 'flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm';
    } else if (accion === 'aprobar') {
        icon.innerHTML = '<svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        icon.className = 'w-14 h-14 rounded-full flex items-center justify-center bg-green-50';
        titulo.textContent = 'Aprobar Cotización';
        mensaje.textContent = 'Se marcará como "Aprobada por el Cliente". ¿Confirmar?';
        btnConfirmar.textContent = 'Aprobar';
        btnConfirmar.className = 'flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm';
    } else if (accion === 'rechazar') {
        icon.innerHTML = '<svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        icon.className = 'w-14 h-14 rounded-full flex items-center justify-center bg-red-50';
        titulo.textContent = 'Rechazar Cotización';
        mensaje.textContent = 'Indica el motivo del rechazo:';
        btnConfirmar.textContent = 'Rechazar';
        btnConfirmar.className = 'flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm';
        inputContainer.classList.remove('hidden');
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

document.getElementById('modal-btn-confirmar').addEventListener('click', function() {
    const modal = document.getElementById('modal-confirmacion');
    
    if (accionPendiente === 'eliminar') {
        const formData = new FormData();
        formData.append('detalle_id', accionId);
        fetch('<?= $base_url ?? '' ?>/dashboard/cotizaciones/eliminar-item', {
            method: 'POST', body: formData
        }).then(r => r.json()).then(d => { if (d.success) location.reload(); else alert('Error'); });
    } else if (accionPendiente === 'emitir') {
        const formData = new FormData();
        formData.append('cotizacion_id', accionId);
        fetch('<?= $base_url ?? '' ?>/dashboard/cotizaciones/emitir', {
            method: 'POST', body: formData
        }).then(r => {
            if (r.redirected) { window.location.href = r.url; }
            else { location.reload(); }
        });
    } else if (accionPendiente === 'aprobar') {
        const formData = new FormData();
        formData.append('cotizacion_id', accionId);
        fetch('<?= $base_url ?? '' ?>/dashboard/cotizaciones/aprobar', {
            method: 'POST', body: formData
        }).then(r => {
            if (r.redirected) { window.location.href = r.url; }
            else { location.reload(); }
        });
    } else if (accionPendiente === 'rechazar') {
        const input = document.getElementById('modal-input');
        if (!input.value.trim()) { input.classList.add('border-red-500'); return; }
        const formData = new FormData();
        formData.append('cotizacion_id', accionId);
        formData.append('motivo_rechazo', input.value);
        fetch('<?= $base_url ?? '' ?>/dashboard/cotizaciones/rechazar', {
            method: 'POST', body: formData
        }).then(r => {
            if (r.redirected) { window.location.href = r.url; }
            else { location.reload(); }
        });
    }
    
    cerrarModal();
});

function cerrarModal() {
    document.getElementById('modal-confirmacion').classList.add('hidden');
    document.getElementById('modal-confirmacion').classList.remove('flex');
    accionPendiente = null;
    accionId = null;
}

// Cerrar modal al hacer clic fuera
document.getElementById('modal-confirmacion').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// ─── Actualizar precio/cantidad inline ───
document.querySelectorAll('.item-precio, .item-cantidad').forEach(input => {
    let timeout;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const val = parseFloat(this.value);
        if (isNaN(val) || val <= 0) return;
        
        const isPrecio = this.classList.contains('item-precio');
        const endpoint = isPrecio ? 'update-precio' : 'update-cantidad';
        const field = isPrecio ? 'precio_unitario' : 'cantidad';
        
        timeout = setTimeout(() => {
            const formData = new FormData();
            formData.append('detalle_id', this.dataset.detalleId);
            formData.append(field, val);
            
            fetch('<?= $base_url ?? '' ?>/dashboard/cotizaciones/' + endpoint, {
                method: 'POST', body: formData
            })
            .then(r => r.json())
            .then(d => { if (d.success) location.reload(); });
        }, 600);
    });
});
</script>

<style>
.animate-fade-in { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
.w-22 { width: 5.5rem; }
</style>

</body>
</html>
