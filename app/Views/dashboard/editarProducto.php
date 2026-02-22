<?php $title = 'Editar Producto'; $active_nav = 'productos'; ?>
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
                    <h1 class="text-2xl font-bold text-gray-900">Editar Producto</h1>
                    <p class="text-gray-500 text-sm">Modifica los datos del producto</p>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-2xl">
                <form action="<?= $base_url ?>/dashboard/productos/actualizar/<?= $producto['id'] ?? 0 ?>" method="POST" enctype="multipart/form-data"
                      class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 space-y-6">

                    <?php if (isset($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del producto</label>
                        <input type="text" name="nombre" id="nombre" required
                               value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1.5">Categoría</label>
                        <select name="categoria_id" id="categoria_id" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="1" <?= ($producto['categoria_id'] ?? 0) == 1 ? 'selected' : '' ?>>Detección de Incendios</option>
                            <option value="2" <?= ($producto['categoria_id'] ?? 0) == 2 ? 'selected' : '' ?>>Extintores</option>
                            <option value="3" <?= ($producto['categoria_id'] ?? 0) == 3 ? 'selected' : '' ?>>Sistemas de Riego</option>
                            <option value="4" <?= ($producto['categoria_id'] ?? 0) == 4 ? 'selected' : '' ?>>EPP</option>
                        </select>
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                    </div>

                    <?php if (!empty($producto['imagen'])): ?>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Imagen actual</p>
                        <img src="<?= $base_url ?>/img/<?= htmlspecialchars($producto['imagen']) ?>"
                             alt="Imagen actual" class="h-24 object-contain rounded-lg border border-gray-200 p-2">
                    </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nueva imagen (opcional)</label>
                        <input type="file" name="imagen" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <a href="<?= $base_url ?>/dashboard/productos"
                           class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                            Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
