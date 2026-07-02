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
                        <span class="text-gray-800 font-medium line-clamp-1"><?= htmlspecialchars($servicio['nombre'] ?? 'Detalle del Servicio') ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 md:p-12">
                
                <!-- Service Image Gallery -->
                <div class="flex flex-col w-full">
                    <?php 
                        $allImages = [];
                        $sFile = $servicio['imagen_principal'] ?? '';
                        if (!empty($sFile)) {
                            $allImages[] = ($base_url ?? '') . '/img/servicios/' . $sFile;
                        }
                        foreach ($imagenes ?? [] as $imgObj) {
                            $allImages[] = ($base_url ?? '') . '/img/servicios/' . $imgObj['ruta_imagen'];
                        }
                        if (empty($allImages)) {
                            $allImages[] = ($base_url ?? '') . '/img/user.png';
                        }
                    ?>
                    <div class="w-full aspect-square bg-white rounded-xl p-2 flex items-center justify-center border border-gray-200 mb-4 relative group overflow-hidden"
                         onmousemove="zoomImage(event, this)" 
                         onmouseleave="resetZoom(this)">
                        <img id="main-servicio-image" src="<?= htmlspecialchars($allImages[0]) ?>" alt="<?= htmlspecialchars($servicio['nombre'] ?? '') ?>" class="max-w-full max-h-full object-contain transition-transform duration-200 group-hover:scale-[1.75] cursor-zoom-in" style="transform-origin: center center;">
                        <div class="absolute top-4 left-4 bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm z-10">
                            Servicio Especializado
                        </div>
                        <?php if (count($allImages) > 1): ?>
                            <button onclick="prevImage()" type="button" class="absolute top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white text-gray-800 focus:outline-none transition-colors z-10" style="left: 10px;">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button onclick="nextImage()" type="button" class="absolute top-1/2 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white text-gray-800 focus:outline-none transition-colors z-10" style="right: 10px;">
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
                        const mainImageEl = document.getElementById('main-servicio-image');
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

                        function zoomImage(e, container) {
                            const img = container.querySelector('img');
                            const rect = container.getBoundingClientRect();
                            const x = ((e.clientX - rect.left) / rect.width) * 100;
                            const y = ((e.clientY - rect.top) / rect.height) * 100;
                            img.style.transformOrigin = `${x}% ${y}%`;
                        }

                        function resetZoom(container) {
                            const img = container.querySelector('img');
                            img.style.transformOrigin = 'center center';
                        }

                        function setImage(idx) {
                            updateImage(idx);
                        }
                    </script>
                    <?php endif; ?>
                </div>

                <!-- Service Info & Actions -->
                <div class="flex flex-col">

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($servicio['nombre'] ?? '') ?></h1>
                    
                    <?php if (!empty($servicio['tipo_cobro_nombre'])): ?>
                    <p class="text-base text-gray-600 mb-2">Modalidad: <span class="font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded-md text-sm"><?= htmlspecialchars($servicio['tipo_cobro_nombre']) ?></span></p>
                    <?php endif; ?>

                    <!-- Precio referencial del servicio -->
                    <div class="mb-6 mt-2">
                        <span class="text-2xl font-black text-red-600">
                            $<?= number_format((float)($servicio['precio_referencial'] ?? 0), 2) ?>
                        </span>
                        <span class="text-xs text-gray-400 font-semibold block uppercase tracking-wider mt-0.5">
                            Precio Referencial
                        </span>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 mb-8 mt-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Descripción Técnica
                        </h3>
                        <div class="prose prose-sm text-gray-700 max-w-none leading-relaxed">
                            <p><?= nl2br(htmlspecialchars($servicio['descripcion'] ?? 'No hay descripción técnica disponible.')) ?></p>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <form action="<?= $base_url ?? '' ?>/pedido/agregar" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <input type="hidden" name="servicio_id" value="<?= htmlspecialchars((string)($servicio['id'] ?? '')) ?>">
                            <input type="hidden" name="precio" value="<?= htmlspecialchars((string)($servicio['precio_referencial'] ?? '0')) ?>">

                            <input type="hidden" name="cantidad" value="1">
                            <div class="flex items-end gap-4">
                                <div class="flex-1">
                                    <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2 h-[46px]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Agregar al Carrito
                                    </button>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-4 flex items-center justify-center text-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Este servicio requiere evaluación técnica para presupuesto final.
                            </p>
                        </form>
                    </div>

            </div>
            
            <!-- Similar Services -->
            <?php if (!empty($servicios_similares)): ?>
            <div class="border-t border-gray-100 p-8 md:p-12 bg-white rounded-b-2xl">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Servicios Similares
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($servicios_similares as $sim): ?>
                        <?php 
                            $simImg = !empty($sim['imagen_principal']) 
                                ? ($base_url ?? '') . '/img/servicios/' . htmlspecialchars($sim['imagen_principal'])
                                : ($base_url ?? '') . '/img/Photoroom-20251106_165742.png'; 
                        ?>
                        <a href="<?= $base_url ?? '' ?>/servicio/<?= $sim['id'] ?>" class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                            <div class="absolute top-3 right-3 bg-red-50 text-red-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider z-10 border border-red-100">
                                Servicio
                            </div>
                            <div class="w-full aspect-square bg-white p-6 flex items-center justify-center border-b border-gray-50">
                                <img src="<?= $simImg ?>" alt="<?= htmlspecialchars($sim['nombre']) ?>" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-5 flex flex-col flex-grow bg-gray-50/50">

                                <h3 class="font-bold text-gray-900 text-sm mb-3 group-hover:text-red-600 transition-colors line-clamp-2"><?= htmlspecialchars($sim['nombre']) ?></h3>
                                
                                <div class="mb-3">
                                    <span class="text-sm font-extrabold text-red-600">
                                        $<?= number_format((float)($sim['precio_referencial'] ?? 0), 2) ?>
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
