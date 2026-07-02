<?php require_once __DIR__ . '/layouts/header.php'; ?>

<section class="bg-gray-50 py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
            
            <div class="text-center md:text-left order-2 md:order-1">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                    SISTEMAS DE SEGURIDAD CONTRA INCENDIOS
                </h1>
                <p class="text-gray-600 text-base md:text-lg mb-6">
                    Un sistema avanzado diseñado para detectar y extinguir incendios rápidamente,
                    <span class="text-red-600 font-bold">salvando vidas</span> y protegiendo tus bienes.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="<?= $base_url ?? '' ?>/catalogo"
                       class="w-full sm:w-auto px-8 py-3 bg-red-700 text-white font-bold rounded-full hover:bg-red-800 transition-colors text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200">
                        Ver Productos
                    </a>
                </div>
            </div>

            <div class="flex justify-center order-1 md:order-2">
                <img src="<?= $base_url ?? '' ?>/img/Photoroom-20251106_165742.png"
                     alt="Sistema contra incendios InstalFuego"
                     class="w-3/4 md:w-full max-w-md object-contain drop-shadow-xl">
            </div>
        </div>
    </div>
</section>

<section id="productos" class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 text-center md:text-left">EXPLORAR PRODUCTOS</h2>
        <p class="text-gray-600 mb-1 text-center md:text-left">Soluciones de Seguridad</p>
        <p class="text-red-600 font-semibold mb-8 text-center md:text-left">Contra Incendios</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            if (!empty($destacados)):
                foreach ($destacados as $p): ?>
                <div class="bg-gray-50 rounded-lg p-6 relative hover:shadow-xl transition-shadow border border-gray-100">
                    <button class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </button>
                    <?php
                        $pImgFile = $p['imagen_principal'] ?? '';
                        $pImgPath = !empty($pImgFile) ? (dirname(__DIR__,2) . '/public/img/productos/' . $pImgFile) : '';
                        $pExists = !empty($pImgFile) && file_exists($pImgPath);
                        $pSrc = $pExists ? ($base_url ?? '') . '/img/productos/' . htmlspecialchars($pImgFile) : ($base_url ?? '') . '/img/Photoroom-20251106_165742.png';
                    ?>
                    <img src="<?= $pSrc ?>"
                         alt="<?= htmlspecialchars($p['nombre']) ?>"
                         class="w-full h-40 md:h-48 object-contain mb-4 hover:scale-105 transition-transform duration-300">
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-medium"><?= htmlspecialchars($p['categoria_nombre'] ?? 'Producto') ?></p>
                    <p class="text-sm font-bold text-gray-900 leading-tight"><?= htmlspecialchars($p['nombre']) ?></p>
                </div>
                <?php endforeach; 
            endif; ?>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 text-center md:text-left">
            <div class="mb-4 md:mb-0">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">NUESTROS SERVICIOS</h2>
                <p class="text-gray-600 text-sm md:text-base">
                    Soluciones <span class="text-red-600 font-bold">técnicas y operativas</span>
                </p>
            </div>
            <a href="<?= $base_url ?? '' ?>/catalogo" class="inline-flex items-center text-gray-700 font-semibold hover:text-red-600 transition-colors group">
                Ver todo el catálogo
                <svg class="w-6 h-6 ml-2 bg-red-600 text-white rounded-full p-1.5 group-hover:bg-red-700 transition-colors shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            foreach ($servicios ?? [] as $s): 
                $sImgFile = $s['imagen_principal'] ?? '';
                $sImgPath = !empty($sImgFile) ? (dirname(__DIR__,2) . '/public/img/servicios/' . $sImgFile) : '';
                $sExists = !empty($sImgFile) && file_exists($sImgPath);
                $sSrc = $sExists ? ($base_url ?? '') . '/img/servicios/' . htmlspecialchars($sImgFile) : ($base_url ?? '') . '/img/Photoroom-20251106_165742.png';
            ?>
            <div class="bg-white rounded-lg p-6 hover:shadow-xl transition-shadow flex flex-col justify-between border border-gray-100">
                <div>
                    <img src="<?= $sSrc ?>"
                         alt="<?= htmlspecialchars($s['nombre']) ?>"
                         class="w-full h-32 object-contain mb-4 hover:scale-105 transition-transform duration-300">
                    <p class="text-sm font-bold text-gray-900 mb-4 text-center">
                        <?= htmlspecialchars($s['nombre']) ?>
                    </p>
                </div>
                <a href="<?= $base_url ?? '' ?>/servicio/<?= $s['id'] ?>" class="inline-block text-center border-2 border-gray-200 rounded-full px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all w-full shadow-sm">
                    Ver Detalles
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl md:text-3xl font-bold text-gray-900">ÚLTIMOS AGREGADOS</h2>
            <div class="hidden sm:flex space-x-2">
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php
            if (!empty($ultimos)):
                foreach ($ultimos as $u):
                    $uImgFile = $u['imagen_principal'] ?? '';
                    $uImgDir = $u['tipo'] === 'producto' ? '/img/productos/' : '/img/servicios/';
                    $uFsDir = $u['tipo'] === 'producto' ? '/public/img/productos/' : '/public/img/servicios/';
                    $uImgPath = !empty($uImgFile) ? (dirname(__DIR__,2) . $uFsDir . $uImgFile) : '';
                    $uExists = !empty($uImgFile) && file_exists($uImgPath);
                    $uSrc = $uExists ? ($base_url ?? '') . $uImgDir . htmlspecialchars($uImgFile) : ($base_url ?? '') . '/img/Photoroom-20251106_165742.png';
                    $uLink = ($base_url ?? '') . '/' . $u['tipo'] . '/' . $u['id'];
            ?>
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-lg transition-shadow group cursor-pointer">
                <img src="<?= $uSrc ?>"
                     alt="<?= htmlspecialchars($u['nombre']) ?>"
                     class="w-full h-32 object-contain mb-3 group-hover:scale-105 transition-transform duration-300">
                <p class="text-sm font-bold text-gray-900 mb-3 truncate"><?= htmlspecialchars($u['nombre']) ?></p>
                <a href="<?= $uLink ?>" class="inline-block text-center border border-gray-300 rounded-full px-4 py-2 text-xs font-bold text-gray-700 hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors w-full shadow-sm">
                    Ver Detalles
                </a>
            </div>
            <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>