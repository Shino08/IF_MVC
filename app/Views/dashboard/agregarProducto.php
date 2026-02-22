<?php $title = 'Agregar Producto'; $active_nav = 'productos'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <a href="<?= $base_url ?>/dashboard/productos"
                   class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Agregar Producto</h1>
                    <p class="text-gray-500 text-sm">Completa los datos del nuevo producto</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-2xl">
                <form action="<?= $base_url ?>/dashboard/productos/guardar" method="POST" enctype="multipart/form-data"
                      class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 space-y-6">

                    <?php if (isset($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del producto</label>
                        <input type="text" name="nombre" id="nombre" required
                               value="<?= htmlspecialchars($form['nombre'] ?? '') ?>"
                               placeholder="Ej. Extintor CO2 5Kg"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1.5">Categoría</label>
                        <select name="categoria_id" id="categoria_id" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Selecciona una categoría</option>
                            <option value="1">Detección de Incendios</option>
                            <option value="2">Extintores</option>
                            <option value="3">Sistemas de Riego</option>
                            <option value="4">EPP</option>
                        </select>
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4"
                                  placeholder="Descripción detallada del producto..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"><?= htmlspecialchars($form['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Imagen del producto</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors cursor-pointer">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500">Arrastra una imagen o <span class="text-red-600 font-medium">haz click para seleccionar</span></p>
                            <input type="file" name="imagen" accept="image/*" class="hidden">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <a href="<?= $base_url ?>/dashboard/productos"
                           class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                            Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
