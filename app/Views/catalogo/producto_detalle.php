<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                <li class="inline-flex items-center">
                    <a href="<?= $base_url ?? '' ?>/" class="inline-flex items-center hover:text-red-600 transition-colors">
                        Inicio
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="<?= $base_url ?? '' ?>/catalogo" class="hover:text-red-600 transition-colors">Catálogo</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-gray-800 font-medium line-clamp-1"><?= htmlspecialchars($producto['nombre'] ?? 'Detalle') ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 md:p-12">
                
                <!-- Product Image Gallery -->
                <div class="flex flex-col items-center">
                    <?php $img = !empty($producto['imagen_principal']) ? $producto['imagen_principal'] : 'placeholder.jpg'; ?>
                    <div class="w-full aspect-square bg-gray-50 rounded-xl p-8 flex items-center justify-center border border-gray-100 mb-4">
                        <img src="<?= $base_url ?? '' ?>/img/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" class="max-w-full max-h-full object-contain">
                    </div>
                </div>

                <!-- Product Info & Actions -->
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-2">SKU: <?= htmlspecialchars($producto['sku'] ?? 'N/A') ?></p>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($producto['nombre'] ?? '') ?></h1>
                    
                    <?php if (!empty($producto['marca'])): ?>
                    <p class="text-base text-gray-600 mb-6">Marca: <span class="font-semibold text-gray-900"><?= htmlspecialchars($producto['marca']) ?></span></p>
                    <?php endif; ?>

                    <div class="prose prose-sm text-gray-600 mb-8 max-w-none">
                        <p><?= nl2br(htmlspecialchars($producto['descripcion'] ?? 'No hay descripción disponible.')) ?></p>
                    </div>

                    <div class="mt-auto">
                        <form action="<?= $base_url ?? '' ?>/cotizacion/agregar" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <input type="hidden" name="producto_id" value="<?= htmlspecialchars((string)($producto['id'] ?? '')) ?>">
                            <input type="hidden" name="precio" value="<?= htmlspecialchars((string)($producto['precio'] ?? '0')) ?>">

                            <div class="flex items-end gap-4">
                                <div class="w-24">
                                    <label for="cantidad" class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                                    <input type="number" id="cantidad" name="cantidad" value="1" min="1" class="input-elegant text-center font-bold px-2 py-3">
                                </div>
                                <div class="flex-1">
                                    <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2 h-[46px]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Agregar a Solicitud de Cotización
                                    </button>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-4 flex items-center justify-center text-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                El precio final será confirmado por un asesor de ventas.
                            </p>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Additional Details Tabs -->
            <?php if (!empty($producto['ficha_tecnica_pdf']) || !empty($producto['certificaciones'])): ?>
            <div class="border-t border-gray-100 p-8 md:p-12 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Especificaciones Técnicas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <?php if (!empty($producto['certificaciones'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-2">Certificaciones</h4>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($producto['certificaciones']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($producto['ficha_tecnica_pdf'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Ficha Técnica</h4>
                            <p class="text-sm text-gray-500">Documento PDF detallado</p>
                        </div>
                        <a href="<?= $base_url ?? '' ?>/docs/<?= htmlspecialchars($producto['ficha_tecnica_pdf']) ?>" target="_blank" class="text-red-600 hover:text-red-800 font-bold bg-red-50 px-4 py-2 rounded-lg transition-colors">Descargar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
