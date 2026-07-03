<?php $active_nav = 'servicios'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Servicios</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Administra el catálogo de servicios InstalFuego</p>
                </div>
                <a href="<?= $base_url ?? '' ?>/dashboard/servicios/agregar"
                   class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Agregar Servicio
                </a>
            </div>
        </header>

        <div class="p-8">

            <!-- Barra de búsqueda -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex items-center gap-4 shadow-sm">
                <div class="flex-1 relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="buscador-servicios" placeholder="Buscar por código o nombre..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                </div>
                <select id="filtro-categoria" class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['nombre']) ?>">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (empty($servicios)): ?>
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 font-bold text-lg mb-1">No hay servicios registrados</p>
                    <p class="text-gray-500 text-sm mb-6 max-w-sm">Comienza agregando tu primer servicio para incluirlo en las carritos.</p>
                    <a href="<?= $base_url ?? '' ?>/dashboard/servicios/agregar"
                       class="inline-flex items-center px-6 py-2.5 bg-red-700 text-white text-sm font-bold rounded-xl hover:bg-red-800 transition-colors shadow-md hover:shadow-lg">
                        Agregar mi primer servicio
                    </a>
                </div>
            <?php else: ?>

                <div id="grid-servicios" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    <?php foreach ($servicios as $s): ?>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all flex flex-col relative group"
                             data-card
                             data-nombre="<?= htmlspecialchars(strtolower($s['nombre'])) ?>"
                             data-codigo="<?= htmlspecialchars(strtolower($s['codigo'])) ?>"
                             data-categoria="<?= htmlspecialchars($s['categoria_nombre'] ?? '') ?>">

                            <!-- Badge tipo de cobro -->
                            <span class="absolute top-0 right-0 z-10 px-3 py-1.5 text-xs font-bold rounded-bl-xl shadow-sm bg-blue-100 text-blue-700">
                                <?= htmlspecialchars($s['tipo_cobro_nombre'] ?? 'Sin tipo') ?>
                            </span>

                            <!-- Imagen -->
                            <div class="aspect-square bg-white flex items-center justify-center p-6 border-b border-gray-100 relative overflow-hidden">
                                <?php
                                    $imgFile = $s['imagen_principal'] ?? '';
                                    $imgPathFs = !empty($imgFile) ? (dirname(__DIR__, 3) . '/public/img/servicios/' . $imgFile) : '';
                                    $imgSrc = (!empty($imgFile) && file_exists($imgPathFs))
                                        ? $base_url . '/img/servicios/' . htmlspecialchars($imgFile)
                                        : $base_url . '/img/user.png';
                                ?>
                                <img src="<?= $imgSrc ?>"
                                     alt="<?= htmlspecialchars($s['nombre']) ?>"
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Contenido -->
                            <div class="p-5 flex flex-col flex-1 bg-gray-50/50">
                                <p class="text-xs font-semibold text-blue-600 mb-1 uppercase tracking-wide">
                                    <?= htmlspecialchars($s['categoria_nombre'] ?? 'Sin Categoría') ?>
                                </p>
                                <h3 class="text-sm font-bold text-gray-900 mb-1 line-clamp-2 leading-tight">
                                    <?= htmlspecialchars($s['nombre']) ?>
                                </h3>
                                <p class="text-xs text-gray-500 mb-3 font-mono">COD: <?= htmlspecialchars($s['codigo']) ?></p>

                                <p class="text-xl font-black text-gray-900 mb-5 mt-auto">
                                    $<?= number_format((float)$s['precio_referencial'], 2) ?>
                                </p>

                                <div class="flex gap-2 mt-auto">
                                    <a href="<?= $base_url ?? '' ?>/dashboard/servicios/editar/<?= $s['id'] ?>"
                                       class="flex-1 bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-700 text-gray-700 px-3 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Editar
                                    </a>

                                    <button type="button"
                                            onclick="confirmarEliminar(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['nombre'])) ?>', this.closest('[data-card]'))"
                                            class="flex-1 bg-white border border-gray-200 hover:bg-red-50 hover:border-red-200 hover:text-red-700 text-gray-700 px-3 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </main>
</div>

<!-- ── MODAL ELIMINAR SERVICIO ─────────────────────────────────────── -->
<div id="modal-eliminar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-lg font-bold text-gray-900 text-center mb-1">Eliminar servicio</h3>
        <p class="text-gray-500 text-sm text-center mb-6">
            ¿Eliminar <span id="modal-nombre-servicio" class="font-semibold text-gray-800"></span>?
            Esta acción no se puede deshacer.
        </p>
        <div id="modal-error" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2 rounded-lg mb-4"></div>
        <div class="flex gap-3">
            <button id="btn-cancelar-eliminar"
                    class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </button>
            <button id="btn-confirmar-eliminar"
                    class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <span id="btn-eliminar-text">Eliminar</span>
                <svg id="btn-eliminar-spinner" class="hidden animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>const BASE_URL = "<?= $base_url ?? '' ?>";</script>
<script src="<?= $base_url ?? '' ?>/js/servicios.js"></script>
</body>
</html>
