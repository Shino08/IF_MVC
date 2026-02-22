<?php $title = 'Productos'; $active_nav = 'productos'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

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
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex items-center space-x-4">
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Buscar producto..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option>Todas las categorías</option>
                    <option>Detección de Incendios</option>
                    <option>Extintores</option>
                    <option>Sistemas de Riego</option>
                    <option>EPP</option>
                </select>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $productos = $productos ?? [
                            ['nombre' => 'Extintor de Espuma AFFF 10L', 'categoria' => 'Extintores', 'img' => 'extintor-espuma-afffar-3-10-lt-ab-mod-em-10k.jpg', 'activo' => true],
                            ['nombre' => 'Rociador Automático Sprinkler', 'categoria' => 'Sistemas de Riego', 'img' => '054-rociador-sprinkler.jpg', 'activo' => true],
                            ['nombre' => 'Detector de Humo Kidde PI2010', 'categoria' => 'Detección de Incendios', 'img' => 'width=500,height=500.png', 'activo' => true],
                            ['nombre' => 'Panel de Control de Alarma 8Z', 'categoria' => 'Detección de Incendios', 'img' => 'Panel-de-Control-de-Alarma-de-Incendio-de-Zona-Convencional-8.webp', 'activo' => false],
                            ['nombre' => 'Extintor CO2 5Kg',             'categoria' => 'Extintores', 'img' => 'Extintor CO2 5kg.png', 'activo' => true],
                        ];
                        foreach ($productos as $p): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <img src="<?= $base_url ?>/img/<?= urlencode($p['img']) ?>"
                                         alt="<?= htmlspecialchars($p['nombre']) ?>"
                                         class="w-10 h-10 object-contain rounded-lg bg-gray-100 p-1">
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($p['nombre']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($p['categoria']) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                             <?= $p['activo'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                    <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    <a href="<?= $base_url ?>/dashboard/productos/editar/1"
                                       class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
