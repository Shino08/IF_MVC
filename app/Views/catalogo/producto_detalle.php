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
                <div class="flex flex-col w-full">
                    <?php 
                        $allImages = [];
                        if (!empty($producto['imagen_principal'])) {
                            $allImages[] = ($base_url ?? '') . '/img/productos/' . $producto['imagen_principal'];
                        }
                        foreach ($imagenes ?? [] as $imgObj) {
                            $allImages[] = ($base_url ?? '') . '/img/productos/' . $imgObj['ruta_imagen'];
                        }
                        if (empty($allImages)) {
                            $allImages[] = ($base_url ?? '') . '/img/user.png';
                        }
                    ?>
                    <div class="w-full aspect-square bg-white rounded-xl p-2 flex items-center justify-center border border-gray-200 mb-4 relative group">
                        <img id="main-product-image" src="<?= htmlspecialchars($allImages[0]) ?>" alt="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" class="max-w-full max-h-full object-contain transition-all duration-300">
                        <?php if (count($allImages) > 1): ?>
                            <button onclick="prevImage()" type="button" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white text-gray-800 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button onclick="nextImage()" type="button" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white text-gray-800 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        <?php endif; ?>
                    </div>
                    <?php if (count($allImages) > 1): ?>
                    <div class="flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                        <?php foreach ($allImages as $idx => $imgSrc): ?>
                            <button onclick="setImage(<?= $idx ?>)" type="button" class="shrink-0 w-20 h-20 bg-white border-2 <?= $idx === 0 ? 'border-red-600' : 'border-transparent' ?> rounded-lg overflow-hidden focus:outline-none transition-colors thumb-btn" data-idx="<?= $idx ?>">
                                <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-full h-full object-contain p-1">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <script>
                        const allImages = <?= json_encode($allImages) ?>;
                        let currentImgIdx = 0;
                        const mainImageEl = document.getElementById('main-product-image');
                        const thumbs = document.querySelectorAll('.thumb-btn');

                        function updateImage(idx) {
                            currentImgIdx = idx;
                            mainImageEl.src = allImages[currentImgIdx];
                            thumbs.forEach((el, i) => {
                                if (i === currentImgIdx) {
                                    el.classList.add('border-red-600');
                                    el.classList.remove('border-transparent');
                                } else {
                                    el.classList.remove('border-red-600');
                                    el.classList.add('border-transparent');
                                }
                            });
                        }

                        function nextImage() {
                            let nextIdx = currentImgIdx + 1;
                            if (nextIdx >= allImages.length) nextIdx = 0;
                            updateImage(nextIdx);
                        }

                        function prevImage() {
                            let prevIdx = currentImgIdx - 1;
                            if (prevIdx < 0) prevIdx = allImages.length - 1;
                            updateImage(prevIdx);
                        }

                        function setImage(idx) {
                            updateImage(idx);
                        }
                    </script>
                    <?php endif; ?>
                </div>

                <!-- Product Info & Actions -->
                <div class="flex flex-col">

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($producto['nombre'] ?? '') ?></h1>
                    
                    <?php if (!empty($producto['marca'])): ?>
                    <p class="text-base text-gray-600 mb-2">Marca: <span class="font-semibold text-gray-900"><?= htmlspecialchars($producto['marca']) ?></span></p>
                    <?php endif; ?>

                    <!-- Precio del producto -->
                    <div class="mb-6">
                        <span class="text-2xl font-black text-red-600">
                            $<?= number_format((float)($producto['precio'] ?? 0), 2) ?>
                        </span>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 mb-8 mt-2">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Descripción Técnica
                        </h3>
                        <div class="prose prose-sm text-gray-700 max-w-none leading-relaxed">
                            <p><?= nl2br(htmlspecialchars($producto['descripcion'] ?? 'No hay descripción técnica disponible.')) ?></p>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <form action="<?= $base_url ?? '' ?>/pedido/agregar" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <input type="hidden" name="producto_id" value="<?= htmlspecialchars((string)($producto['id'] ?? '')) ?>">
                            <input type="hidden" name="precio" value="<?= htmlspecialchars((string)($producto['precio'] ?? '0')) ?>">

                            <?php if (!empty($producto['estado_disponibilidad'])): ?>
                            <div class="mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= $producto['estado_disponibilidad'] === 'En Stock' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $producto['estado_disponibilidad'] === 'En Stock' ? 'bg-green-500' : 'bg-orange-500' ?>"></span>
                                    <?= htmlspecialchars($producto['estado_disponibilidad']) ?>
                                </span>
                            </div>
                            <?php endif; ?>

                            <div class="flex items-end gap-4">
                                <div class="w-24">
                                    <label for="cantidad" class="block text-sm font-semibold text-gray-700 mb-2">Cantidad <?= !empty($producto['cantidad_minima_pedido']) ? '<span class="text-[10px] text-gray-400 font-normal block">(Mín. ' . $producto['cantidad_minima_pedido'] . ')</span>' : '' ?></label>
                                    <input type="number" id="cantidad" name="cantidad" value="<?= htmlspecialchars((string)($producto['cantidad_minima_pedido'] ?? '1')) ?>" min="<?= htmlspecialchars((string)($producto['cantidad_minima_pedido'] ?? '1')) ?>" class="input-elegant text-center font-bold px-2 py-3 w-full">
                                </div>
                                <div class="flex-1">
                                    <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2 h-[46px]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Agregar al Carrito
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
            <?php if (!empty($producto['ficha_tecnica_pdf']) || !empty($producto['certificaciones']) || !empty($producto['dimensiones']) || !empty($producto['info_garantia']) || !empty($producto['info_envio'])): ?>
            <div class="border-t border-gray-100 p-8 md:p-12 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Información Detallada</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (!empty($producto['certificaciones'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-2">Certificaciones</h4>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($producto['certificaciones']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($producto['dimensiones'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-2">Dimensiones</h4>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($producto['dimensiones']) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($producto['info_garantia'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-2">Garantía</h4>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($producto['info_garantia']) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($producto['info_envio'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-2">Info de Envío</h4>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($producto['info_envio']) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($producto['ficha_tecnica_pdf'])): ?>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 flex flex-col justify-between">
                        <div class="mb-4">
                            <h4 class="font-bold text-gray-900 mb-1">Ficha Técnica</h4>
                            <p class="text-sm text-gray-500">Documento PDF detallado</p>
                        </div>
                        <a href="<?= $base_url ?? '' ?>/docs/<?= htmlspecialchars($producto['ficha_tecnica_pdf']) ?>" target="_blank" class="text-center text-red-600 hover:text-red-800 font-bold bg-red-50 px-4 py-2 rounded-lg transition-colors w-full">Descargar PDF</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Similar Products -->
            <?php if (!empty($productos_similares)): ?>
            <div class="border-t border-gray-100 p-8 md:p-12 bg-white rounded-b-2xl">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Productos Similares
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($productos_similares as $sim): ?>
                        <?php 
                            $simImg = !empty($sim['imagen_principal']) 
                                ? ($base_url ?? '') . '/img/productos/' . htmlspecialchars($sim['imagen_principal'])
                                : ($base_url ?? '') . '/img/Photoroom-20251106_165742.png'; 
                        ?>
                        <a href="<?= $base_url ?? '' ?>/producto/<?= $sim['id'] ?>" class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                            <div class="absolute top-3 right-3 bg-red-50 text-red-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider z-10 border border-red-100">
                                Producto
                            </div>
                            <div class="w-full aspect-square bg-white p-6 flex items-center justify-center border-b border-gray-50">
                                <img src="<?= $simImg ?>" alt="<?= htmlspecialchars($sim['nombre']) ?>" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-5 flex flex-col flex-grow bg-gray-50/50">

                                <h3 class="font-bold text-gray-900 text-sm mb-3 group-hover:text-red-600 transition-colors line-clamp-2"><?= htmlspecialchars($sim['nombre']) ?></h3>
                                
                                <div class="mb-3">
                                    <span class="text-sm font-extrabold text-red-600">
                                        $<?= number_format((float)($sim['precio'] ?? 0), 2) ?>
                                    </span>
                                </div>
                                
                                <div class="mt-auto pt-3 flex items-center justify-between border-t border-gray-100/50">
                                    <span class="text-sm font-semibold text-red-600 group-hover:text-red-700 transition-colors">Ver Detalles</span>
                                    <svg class="w-5 h-5 text-red-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
