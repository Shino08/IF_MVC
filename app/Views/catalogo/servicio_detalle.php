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
                <div class="flex flex-col items-center">
                    <?php $img = !empty($servicio['imagen_principal']) ? $servicio['imagen_principal'] : 'placeholder.jpg'; ?>
                    <div class="w-full aspect-square bg-gray-50 rounded-xl p-8 flex items-center justify-center border border-gray-100 mb-4 relative overflow-hidden">
                        <img src="<?= $base_url ?? '' ?>/img/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($servicio['nombre'] ?? '') ?>" class="max-w-full max-h-full object-cover">
                        <div class="absolute top-4 left-4 bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                            Servicio Especializado
                        </div>
                    </div>
                </div>

                <!-- Service Info & Actions -->
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-2">CÓDIGO: <?= htmlspecialchars($servicio['codigo'] ?? 'N/A') ?></p>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($servicio['nombre'] ?? '') ?></h1>
                    
                    <?php if (!empty($servicio['tipo_cobro_nombre'])): ?>
                    <p class="text-base text-gray-600 mb-2">Modalidad: <span class="font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded-md text-sm"><?= htmlspecialchars($servicio['tipo_cobro_nombre']) ?></span></p>
                    <?php endif; ?>

                    <div class="prose prose-sm text-gray-600 mb-8 max-w-none mt-4">
                        <p><?= nl2br(htmlspecialchars($servicio['descripcion'] ?? 'No hay descripción disponible para este servicio.')) ?></p>
                    </div>

                    <div class="mt-auto">
                        <form action="<?= $base_url ?? '' ?>/cotizacion/agregar" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <input type="hidden" name="servicio_id" value="<?= htmlspecialchars((string)($servicio['id'] ?? '')) ?>">
                            <input type="hidden" name="precio" value="<?= htmlspecialchars((string)($servicio['precio_referencial'] ?? '0')) ?>">

                            <div class="flex items-end gap-4">
                                <div class="w-24">
                                    <label for="cantidad" class="block text-sm font-semibold text-gray-700 mb-2">Cantidad</label>
                                    <input type="number" id="cantidad" name="cantidad" value="1" min="1" class="input-elegant text-center font-bold px-2 py-3">
                                </div>
                                <div class="flex-1">
                                    <button type="submit" class="w-full btn-primary flex items-center justify-center gap-2 h-[46px]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Agregar a Solicitud de Cotización
                                    </button>
                                </div>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-4 flex items-center justify-center text-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Este servicio requiere evaluación técnica para cotización final.
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
