<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    Solicitud #<?= htmlspecialchars((string)$cotizacion['id']) ?>
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">
                        <?= htmlspecialchars(strtoupper($cotizacion['estado_nombre'])) ?>
                    </span>
                </h1>
                <p class="text-gray-500 mt-1 text-sm">Fecha de solicitud: <?= date('d/m/Y H:i', strtotime($cotizacion['fecha_solicitud'])) ?></p>
            </div>
            
            <a href="<?= $base_url ?? '' ?>/mis-cotizaciones" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al Historial
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Items Cotizados</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($detalles as $item): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
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
                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-50 rounded-lg p-1 border border-gray-100">
                                            <img class="h-full w-full object-contain mix-blend-multiply" src="<?= $imgUrl ?>" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 line-clamp-1">
                                                <?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500 font-mono mt-0.5">
                                                <?= !empty($item['producto_id']) ? 'SKU: ' . htmlspecialchars($item['sku'] ?? 'N/A') : 'COD: ' . htmlspecialchars($item['codigo'] ?? 'N/A') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full <?= $item['producto_id'] ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                        <?= $item['producto_id'] ? 'Producto' : 'Servicio' ?>
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
            <div class="bg-blue-50 rounded-xl border border-blue-100 p-6">
                <h4 class="text-sm font-bold text-blue-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Notas Técnicas
                </h4>
                <p class="text-blue-800 text-sm whitespace-pre-line"><?= htmlspecialchars($cotizacion['notas_tecnicas']) ?></p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
