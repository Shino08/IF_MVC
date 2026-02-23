<?php $title = 'Agregar Producto'; $active_nav = 'productos'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <a href="<?= $base_url ?? '' ?>/dashboard/productos"
                   class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Agregar Nuevo Producto</h1>
                    <p class="text-gray-500 text-sm">Introduce los detalles técnicos y de inventario</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-8">

                <div class="xl:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Ficha del Producto</h2>

                        <div id="ajax-alert" class="hidden px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="ajax-alert-text"></span>
                        </div>

                        <form action="<?= $base_url ?? '' ?>/dashboard/productos/store" method="POST" enctype="multipart/form-data" id="productoForm">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-semibold text-gray-900 mb-2">Nombre del Producto <span class="text-red-600">*</span></label>
                                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Extintor de PQS 10 Lbs" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <div>
                                    <label for="sku" class="block text-sm font-semibold text-gray-900 mb-2">SKU / Código <span class="text-red-600">*</span></label>
                                    <input type="text" id="sku" name="sku" placeholder="Ej: EXT-PQS-10" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="categoria_id" class="block text-sm font-semibold text-gray-900 mb-2">Categoría <span class="text-red-600">*</span></label>
                                    <select id="categoria_id" name="categoria_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white" required>
                                        <option value="">Seleccione una categoría</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="precio" class="block text-sm font-semibold text-gray-900 mb-2">Precio Ref. (Base) <span class="text-red-600">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                                        <input type="number" step="0.01" id="precio" name="precio" placeholder="0.00" class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="marca" class="block text-sm font-semibold text-gray-900 mb-2">Marca</label>
                                    <input type="text" id="marca" name="marca" placeholder="Ej: Kidde" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                <div>
                                    <label for="modelo" class="block text-sm font-semibold text-gray-900 mb-2">Modelo</label>
                                    <input type="text" id="modelo" name="modelo" placeholder="Ej: Pro 10" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                <div>
                                    <label for="existencia" class="block text-sm font-semibold text-gray-900 mb-2">Stock Inicial</label>
                                    <input type="number" id="existencia" name="existencia" value="0" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="descripcion" class="block text-sm font-semibold text-gray-900 mb-2">Descripción Técnica</label>
                                <textarea id="descripcion" name="descripcion" rows="4" placeholder="Especificaciones, usos recomendados, componentes..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    Imágenes del Producto <span class="text-gray-500 font-normal">(Máximo 5)</span>
                                </label>
                                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer" onclick="document.getElementById('file-upload').click()">
                                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600 font-medium">Haz clic para seleccionar imágenes</p>
                                    <p class="text-xs text-gray-500 mt-2">La primera imagen seleccionada será la Principal.</p>
                                    <input type="file" id="file-upload" name="imagenes[]" accept="image/jpeg, image/png, image/webp" multiple class="hidden">
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex justify-end">
                                <button type="submit" id="btn-save" class="w-full md:w-auto px-8 bg-red-700 hover:bg-red-800 text-white py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                                    <span id="btn-text">Guardar Producto</span>
                                    <svg id="btn-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="xl:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Vista Previa</h2>

                        <div id="preview-section" class="mb-6">
                            <div id="image-placeholder" class="border-2 border-dashed border-gray-200 rounded-2xl aspect-square flex flex-col items-center justify-center p-4 text-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-400 text-xs px-4">Selecciona hasta 5 imágenes para visualizarlas aquí.</p>
                            </div>

                            <div id="preview-container" class="grid grid-cols-2 gap-3 hidden">
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                Tips B2B
                            </p>
                            <ul class="space-y-3 text-xs text-gray-600">
                                <li class="flex items-start">
                                    <span class="text-red-600 mr-2">•</span>
                                    <span><strong>SKU único:</strong> Evita duplicados para un inventario preciso.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-red-600 mr-2">•</span>
                                    <span><strong>Calidad:</strong> Usa imágenes claras (fondo blanco).</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<script>
    const BASE_URL = "<?= $base_url ?? '' ?>";
</script>
<script src="<?= $base_url ?? '' ?>/js/agregarProductos.js"></script>
</body>
</html>