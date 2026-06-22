<?php $title = 'Detalle de Solicitud #' . $cotizacion['id']; $active_nav = 'cotizaciones'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0 flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900">Solicitud #COT<?= str_pad((string)$cotizacion['id'], 6, '0', STR_PAD_LEFT) ?></h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?php 
                        if($cotizacion['estado_id'] == 2) echo 'bg-yellow-100 text-yellow-800';
                        elseif($cotizacion['estado_id'] == 3) echo 'bg-green-100 text-green-800';
                        else echo 'bg-red-100 text-red-800'; 
                    ?>">
                        <?= htmlspecialchars(ucfirst($cotizacion['estado_nombre'])) ?>
                    </span>
                </div>
                <p class="text-gray-500 text-sm mt-1">Recibida el <?= date('d/m/Y H:i', strtotime($cotizacion['fecha_solicitud'])) ?></p>
            </div>
            <a href="<?= $base_url ?? '' ?>/dashboard/cotizaciones" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Volver a Solicitudes
            </a>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Principal -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Items Solicitados</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <?php foreach ($detalles as $item): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php
                                                    if (!empty($item['producto_id'])) {
                                                        $imgFile = $item['producto_imagen'] ?? '';
                                                        $imgDir = '/img/productos/';
                                                    } else {
                                                        $imgFile = $item['servicio_imagen'] ?? '';
                                                        $imgDir = '/img/servicios/';
                                                    }
                                                    $publicDir = dirname(__DIR__, 3) . '/public';
                                                    $imgPathFs = !empty($imgFile) ? $publicDir . $imgDir . $imgFile : '';
                                                    $imgUrl = (!empty($imgFile) && file_exists($imgPathFs)) 
                                                        ? ($base_url ?? '') . $imgDir . htmlspecialchars($imgFile)
                                                        : ($base_url ?? '') . '/img/user.png';
                                                ?>
                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-50 rounded-lg p-1 border border-gray-100">
                                                    <img class="h-full w-full object-contain mix-blend-multiply" src="<?= $imgUrl ?>" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 line-clamp-1">
                                                        <?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-500 font-mono mt-0.5">
                                                        <?= !empty($item['producto_id']) ? 'SKU: ' . htmlspecialchars($item['sku'] ?? 'N/A') : 'COD: ' . htmlspecialchars($item['codigo'] ?? 'N/A') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full <?= !empty($item['producto_id']) ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                                <?= !empty($item['producto_id']) ? 'Producto' : 'Servicio' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                                            <?= $item['cantidad'] ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if (!empty($cotizacion['notas_tecnicas'])): ?>
                    <div class="bg-blue-50 rounded-lg border border-blue-100 p-6">
                        <h4 class="text-sm font-bold text-blue-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Notas del Cliente / Descripción
                        </h4>
                        <p class="text-blue-800 text-sm whitespace-pre-line"><?= htmlspecialchars($cotizacion['notas_tecnicas']) ?></p>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Columna Lateral -->
                <div class="space-y-6">
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Datos del Cliente</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Nombre Completo</p>
                                <p class="text-sm font-medium text-gray-900 mt-1"><?= htmlspecialchars($cotizacion['cliente_nombre'] . ' ' . $cotizacion['cliente_apellido']) ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Cédula/RIF</p>
                                <p class="text-sm font-medium text-gray-900 mt-1"><?= htmlspecialchars($cotizacion['cliente_cedula'] ?? 'No provisto') ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Empresa</p>
                                <p class="text-sm font-medium text-gray-900 mt-1"><?= htmlspecialchars($cotizacion['cliente_empresa'] ?? 'No provisto') ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Correo Electrónico</p>
                                <a href="mailto:<?= htmlspecialchars($cotizacion['cliente_email']) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                                    <?= htmlspecialchars($cotizacion['cliente_email']) ?>
                                </a>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Teléfono</p>
                                <a href="tel:<?= htmlspecialchars($cotizacion['cliente_telefono'] ?? '') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                                    <?= htmlspecialchars($cotizacion['cliente_telefono'] ?? 'N/A') ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Acciones</h3>
                        
                        <div class="space-y-3">
                            <a href="<?= $base_url ?? '' ?>/cotizacion/detalle/<?= $cotizacion['id'] ?>" target="_blank" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver Cotización Formal
                            </a>

                            <?php if ($cotizacion['estado_id'] == 2): // Pendiente ?>
                            <form action="<?= $base_url ?? '' ?>/dashboard/cotizaciones/procesar" method="POST">
                                <input type="hidden" name="cotizacion_id" value="<?= $cotizacion['id'] ?>">
                                <input type="hidden" name="accion" value="aceptar">
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aceptar y Procesar
                                </button>
                            </form>

                            <form action="<?= $base_url ?? '' ?>/dashboard/cotizaciones/procesar" method="POST">
                                <input type="hidden" name="cotizacion_id" value="<?= $cotizacion['id'] ?>">
                                <input type="hidden" name="accion" value="rechazar">
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Rechazar
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>
</div>
</body>
</html>
