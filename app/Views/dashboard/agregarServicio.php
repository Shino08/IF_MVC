<?php
$modoEditar   = (($modo ?? 'crear') === 'editar');
$servicioId   = $servicio['id'] ?? 0;
$imgDir       = ($base_url ?? '') . '/img/servicios/';
$formAction   = $modoEditar
    ? ($base_url ?? '') . '/dashboard/servicios/actualizar/' . $servicioId
    : ($base_url ?? '') . '/dashboard/servicios/store';
$tituloHeader = $modoEditar ? 'Editar Servicio' : 'Agregar Nuevo Servicio';
$btnLabel     = $modoEditar ? 'Actualizar Servicio' : 'Guardar Servicio';
$active_nav   = 'servicios';
?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <a href="<?= $base_url ?? '' ?>/dashboard/servicios"
                   class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?= $tituloHeader ?></h1>
                    <p class="text-gray-500 text-sm">Mano de obra, inspecciones, recargas y más</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-8">

                <!-- ── Formulario ─────────────────────────────────── -->
                <div class="xl:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Ficha del Servicio</h2>

                        <!-- Alerta inline -->
                        <div id="ajax-alert" class="hidden px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="ajax-alert-text"></span>
                        </div>

                        <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data" id="servicioForm">

                            <?php if ($modoEditar): ?>
                                <input type="hidden" name="servicio_id" value="<?= $servicioId ?>">
                            <?php endif; ?>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-semibold text-gray-900 mb-2">Nombre del Servicio <span class="text-red-600">*</span></label>
                                    <input type="text" id="nombre" name="nombre"
                                           placeholder="Ej: Recarga de Extintor PQS 10 Lbs"
                                           value="<?= htmlspecialchars($servicio['nombre'] ?? '') ?>"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <div>
                                    <label for="codigo" class="block text-sm font-semibold text-gray-900 mb-2">Código <span class="text-red-600">*</span></label>
                                    <input type="text" id="codigo" name="codigo"
                                           placeholder="Ej: SRV-001"
                                           value="<?= htmlspecialchars($servicio['codigo'] ?? '') ?>"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="categoria_id" class="block text-sm font-semibold text-gray-900 mb-2">Categoría</label>
                                    <select id="categoria_id" name="categoria_id"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                                        <option value="">Sin categoría</option>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"
                                                <?= ($servicio['categoria_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="tipo_cobro_id" class="block text-sm font-semibold text-gray-900 mb-2">Tipo de Cobro</label>
                                    <select id="tipo_cobro_id" name="tipo_cobro_id"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($tiposCobro as $tc): ?>
                                            <option value="<?= $tc['id'] ?>"
                                                <?= ($servicio['tipo_cobro_id'] ?? 0) == $tc['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($tc['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="precio" class="block text-sm font-semibold text-gray-900 mb-2">Precio Referencial <span class="text-red-600">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                                        <input type="number" step="0.01" id="precio" name="precio"
                                               placeholder="0.00"
                                               value="<?= htmlspecialchars($servicio['precio_referencial'] ?? '') ?>"
                                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="descripcion" class="block text-sm font-semibold text-gray-900 mb-2">Descripción del Servicio</label>
                                <textarea id="descripcion" name="descripcion" rows="4"
                                          placeholder="Detalla qué incluye el servicio, condiciones, tiempos, etc."
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"><?= htmlspecialchars($servicio['descripcion'] ?? '') ?></textarea>
                            </div>

                            <!-- ── Imagen actual (modo editar) ─────── -->
                            <?php if ($modoEditar && !empty($servicio['imagen_principal'])): ?>
                            <div class="mb-6" id="imagen-actual-section">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Imagen actual</h3>
                                <div class="relative group inline-block" id="img-servicio-wrapper">
                                    <div class="w-32 h-32 bg-white border border-gray-200 rounded-xl overflow-hidden">
                                        <img src="<?= $imgDir . htmlspecialchars($servicio['imagen_principal']) ?>"
                                             id="img-servicio-preview"
                                             alt="Imagen del servicio" class="w-full h-full object-contain p-1">
                                    </div>
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex flex-col items-center justify-center gap-2">
                                        <label class="cursor-pointer bg-white text-gray-800 text-xs font-bold px-2 py-1 rounded-lg hover:bg-gray-100 transition">
                                            Cambiar
                                            <input type="file" accept="image/*" class="hidden"
                                                   id="img-replace-input"
                                                   data-servicio-id="<?= $servicioId ?>"
                                                   data-codigo="<?= htmlspecialchars($servicio['codigo']) ?>">
                                        </label>
                                        <button type="button" id="img-delete-btn"
                                                class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-lg hover:bg-red-700 transition"
                                                data-servicio-id="<?= $servicioId ?>">
                                            Borrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- ── Subir imagen nueva ──────────────── -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    <?= $modoEditar ? 'Reemplazar imagen' : 'Imagen del Servicio' ?>
                                    <span class="text-gray-500 font-normal">(1 imagen)</span>
                                </label>
                                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer"
                                     onclick="document.getElementById('file-upload').click()">
                                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600 font-medium">Haz clic para seleccionar una imagen</p>
                                    <input type="file" id="file-upload" name="imagen" accept="image/jpeg,image/png,image/webp" class="hidden">
                                </div>
                                <div id="preview-nueva" class="hidden mt-4 relative w-40 h-40 border border-gray-200 rounded-xl overflow-hidden bg-white mx-auto">
                                    <img id="preview-nueva-img" src="" alt="Preview" class="w-full h-full object-contain p-1">
                                    <button type="button" id="btn-quitar-preview"
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700">✕</button>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex justify-end">
                                <button type="submit" id="btn-save"
                                        class="w-full md:w-auto px-8 bg-red-700 hover:bg-red-800 text-white py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                                    <span id="btn-text"><?= $btnLabel ?></span>
                                    <svg id="btn-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ── Sidebar vista previa ───────────────────────── -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Vista Previa</h2>

                        <div id="sidebar-preview-wrap">
                            <?php if ($modoEditar && !empty($servicio['imagen_principal'])): ?>
                                <div id="sidebar-preview-img-wrap" class="aspect-square bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
                                    <img src="<?= $imgDir . htmlspecialchars($servicio['imagen_principal']) ?>"
                                         id="sidebar-preview-img"
                                         alt="Imagen" class="w-full h-full object-contain p-2">
                                </div>
                            <?php else: ?>
                                <div id="sidebar-placeholder" class="border-2 border-dashed border-gray-200 rounded-2xl aspect-square flex flex-col items-center justify-center p-4 text-center mb-4">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-gray-400 text-xs">Selecciona una imagen para previsualizarla.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                Tipos de cobro
                            </p>
                            <ul class="space-y-2 text-xs text-gray-600">
                                <li><span class="text-red-600 mr-1">•</span><strong>Por hora:</strong> Mano de obra técnica</li>
                                <li><span class="text-red-600 mr-1">•</span><strong>Por unidad:</strong> Recargas, inspecciones</li>
                                <li><span class="text-red-600 mr-1">•</span><strong>Por metro:</strong> Instalaciones lineales</li>
                                <li><span class="text-red-600 mr-1">•</span><strong>Por proyecto:</strong> Cotización global</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<!-- ── MODAL NOTIFICACIÓN ─────────────────────────────────────────── -->
<div id="notif-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">
        <div class="flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4" id="notif-icon-wrap"></div>
        <h3 class="text-base font-bold text-gray-900 text-center mb-1" id="notif-title"></h3>
        <p class="text-gray-500 text-sm text-center mb-5" id="notif-message"></p>
        <button id="notif-close" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-colors">
            Entendido
        </button>
    </div>
</div>

<!-- ── MODAL CONFIRMAR BORRAR IMAGEN ─────────────────────────────── -->
<div id="confirm-img-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-900 text-center mb-1">¿Borrar imagen?</h3>
        <p class="text-gray-500 text-sm text-center mb-5">Esta acción no se puede deshacer.</p>
        <div class="flex gap-3">
            <button id="confirm-img-cancel" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</button>
            <button id="confirm-img-ok" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-colors">Borrar</button>
        </div>
    </div>
</div>

<script>
    const BASE_URL    = "<?= $base_url ?? '' ?>";
    const MODO_EDITAR = <?= $modoEditar ? 'true' : 'false' ?>;
    const SERVICIO_ID = <?= $servicioId ?>;
</script>
<script src="<?= $base_url ?? '' ?>/js/servicioForm.js"></script>
</body>
</html>
