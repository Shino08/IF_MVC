<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Solicitud Actual</h1>
            <p class="text-gray-600 mt-2">Revisa los productos y servicios que has seleccionado para cotizar.</p>
        </div>

        <?php if (!empty($_SESSION['success_msg'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                <p class="text-green-700"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['error_msg'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <p class="text-red-700"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($detalles)): ?>
            <div class="bg-white rounded-xl p-10 text-center border border-gray-200 shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tu lista está vacía</h3>
                <p class="text-gray-500 mb-6">Navega por nuestro catálogo para agregar items a tu cotización.</p>
                <a href="<?= $base_url ?? '' ?>/catalogo" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                    Explorar Catálogo
                </a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Columna Izquierda: Items -->
                <div class="flex-1">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($detalles as $item): ?>
                                <li class="p-6 flex flex-col sm:flex-row items-center gap-6">
                                    
                                    <div class="flex items-center gap-4 flex-1 w-full text-center sm:text-left">
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
                                        <div class="hidden sm:block flex-shrink-0 w-20 h-20 bg-gray-50 rounded-lg p-2 border border-gray-100">
                                            <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?>" class="w-full h-full object-contain mix-blend-multiply">
                                        </div>
                                        <div>
                                            <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= !empty($item['producto_id']) ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                                    <?= !empty($item['producto_id']) ? 'Producto' : 'Servicio' ?>
                                                </span>
                                            </div>
                                            <h4 class="text-lg font-bold text-gray-900 line-clamp-2">
                                                <?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?>
                                            </h4>
                                            <p class="text-sm text-gray-500 font-mono mt-1">
                                                <?= !empty($item['producto_id']) ? 'SKU: ' . htmlspecialchars($item['sku'] ?? 'N/A') : 'COD: ' . htmlspecialchars($item['codigo'] ?? 'N/A') ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 w-full sm:w-auto justify-center sm:justify-end">
                                        <form action="<?= $base_url ?? '' ?>/cotizacion/item/actualizar" method="POST" class="flex items-center form-actualizar-cantidad">
                                            <input type="hidden" name="detalle_id" value="<?= $item['id'] ?>">
                                            <div class="flex items-center border border-gray-300 rounded-lg bg-white overflow-hidden w-24">
                                                <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" class="w-full text-center text-gray-900 font-medium py-2 focus:outline-none focus:ring-0 border-none bg-transparent" min="1" step="0.5">
                                            </div>
                                            <button type="submit" class="ml-2 p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Actualizar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                        </form>

                                        <form action="<?= $base_url ?? '' ?>/cotizacion/item/eliminar" method="POST" class="form-eliminar-item">
                                            <input type="hidden" name="detalle_id" value="<?= $item['id'] ?>">
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors bg-red-50 rounded-lg hover:bg-red-100" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Columna Derecha: Formulario Final -->
                <div class="w-full lg:w-96">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Completar Solicitud</h3>
                        
                        <form action="<?= $base_url ?? '' ?>/cotizacion/enviar" method="POST">
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notas Técnicas / Observaciones (Opcional)</label>
                                <textarea name="notas_tecnicas" rows="4" class="input-elegant resize-none" placeholder="Ingresa especificaciones adicionales, dimensiones, o detalles del proyecto..."></textarea>
                            </div>
                            
                            <button type="submit" class="w-full btn-primary h-[50px]">
                                Enviar Solicitud de Cotización
                            </button>
                            <p class="text-xs text-center text-gray-500 mt-4">Un asesor se pondrá en contacto contigo a la brevedad.</p>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-actualizar-cantidad').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if(data.success) location.reload();
                else alert(data.error);
            });
        });
    });

    document.querySelectorAll('.form-eliminar-item').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if(!confirm('¿Seguro que deseas eliminar este item?')) return;
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if(data.success) location.reload();
                else alert(data.error);
            });
        });
    });
});
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
