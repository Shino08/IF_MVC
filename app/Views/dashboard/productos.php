<?php $title = 'Productos'; $active_nav = 'productos'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Productos</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Administra el catálogo de productos InstalFuego</p>
                </div>
                <a href="<?= $base_url ?>/dashboard/productos/agregar"
                   class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Agregar Producto
                </a>
            </div>
        </header>

        <div class="p-8">

            <!-- Filtros -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex items-center gap-4">
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Buscar producto..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option>Todas las categorías</option>
                    <option>Detección de Incendios</option>
                    <option>Extintores</option>
                    <option>Sistemas de Riego</option>
                    <option>EPP</option>
                </select>
            </div>

            <!-- Grid de Cards -->
            <?php
            $productos = $productos ?? [
                ['id' => 1, 'nombre' => 'Extintor CO2 5kg',                          'categoria' => 'Extintores',             'img' => 'Extintor CO2 5kg.png',        'precio' => 150.00,  'activo' => true],
                ['id' => 2, 'nombre' => 'Detector de Humo Óptico',                   'categoria' => 'Detección de Incendios', 'img' => 'Detector de Humo Óptico.png', 'precio' => 45.00,   'activo' => true],
                ['id' => 3, 'nombre' => 'Luz de emergencia ABB',                     'categoria' => 'Sistemas de Riego',      'img' => '1 1814949510.png',            'precio' => 1200.00, 'activo' => true],
                ['id' => 4, 'nombre' => 'Luz de emergencia Guardian de Generac',     'categoria' => 'Detección de Incendios', 'img' => 'Luz emergencia.png',          'precio' => 100.00,  'activo' => false],
                ['id' => 5, 'nombre' => 'Sistema Detector de Incendios Inteligente', 'categoria' => 'Detección de Incendios', 'img' => 'Sistema detector.png',        'precio' => 200.00,  'activo' => true],
            ];
            ?>

            <?php if (empty($productos)): ?>
                <!-- Estado vacío -->
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    <p class="text-gray-500 font-medium mb-1">No hay productos registrados</p>
                    <p class="text-gray-400 text-sm mb-4">Comienza agregando tu primer producto al catálogo</p>
                    <a href="<?= $base_url ?>/dashboard/productos/agregar"
                       class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                        Agregar Producto
                    </a>
                </div>
            <?php else: ?>

                <div class="grid grid-cols-5 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <?php foreach ($productos as $p): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow flex flex-col relative">

                            <!-- Badge esquina superior derecha de TODA la card -->
                            <span class="absolute top-0 right-0 z-10 px-2.5 py-1 text-xs font-semibold rounded-bl-lg
                                <?= $p['activo'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>

                            <!-- Imagen -->
                            <div class="aspect-square bg-gray-100 flex items-center justify-center p-4">
                                <img src="<?= $base_url ?>/img/<?= urlencode($p['img']) ?>"
                                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                                     class="w-full h-full object-contain"
                                     onerror="this.src='<?= $base_url ?>/img/placeholder.png'">
                            </div>

                            <!-- Info -->
                            <div class="p-4 flex flex-col flex-1">
                                <p class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($p['categoria']) ?></p>
                                <h3 class="text-sm font-bold text-gray-900 mb-2 line-clamp-2 flex-1">
                                    <?= htmlspecialchars($p['nombre']) ?>
                                </h3>
                                <p class="text-xl font-bold text-gray-900 mb-4">
                                    $<?= number_format($p['precio'], 2) ?>
                                </p>

                                <!-- Acciones -->
                                <div class="flex gap-2 mt-auto">
                                    <a href="<?= $base_url ?>/dashboard/productos/editar/<?= $p['id'] ?>"
                                       class="flex-1 btn text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Editar
                                    </a>

                                    <button type="button"
                                            onclick="confirmarEliminar(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nombre'])) ?>')"
                                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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

</body>
</html>
