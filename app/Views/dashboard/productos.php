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
                <a href="<?= $base_url ?? '' ?>/dashboard/productos/agregar"
                   class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Agregar Producto
                </a>
            </div>
        </header>

        <div class="p-8">

            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex items-center gap-4 shadow-sm">
                <div class="flex-1 relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Buscar por SKU o nombre..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                </div>
                <select class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                    <option value="">Todas las categorías</option>
                </select>
            </div>

            <?php if (empty($productos)): ?>
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 font-bold text-lg mb-1">No hay productos registrados</p>
                    <p class="text-gray-500 text-sm mb-6 max-w-sm">Comienza agregando tu primer producto para armar el catálogo de cotizaciones.</p>
                    <a href="<?= $base_url ?? '' ?>/dashboard/productos/agregar"
                       class="inline-flex items-center px-6 py-2.5 bg-red-700 text-white text-sm font-bold rounded-xl hover:bg-red-800 transition-colors shadow-md hover:shadow-lg">
                        Agregar mi primer producto
                    </a>
                </div>
            <?php else: ?>

                <div class="grid grid-cols-5 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    <?php foreach ($productos as $p): ?>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all flex flex-col relative group">

                            <?php $enStock = (int)$p['existencia'] > 0; ?>
                            <span class="absolute top-0 right-0 z-10 px-3 py-1.5 text-xs font-bold rounded-bl-xl shadow-sm
                                <?= $enStock ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $enStock ? 'Stock: ' . $p['existencia'] : 'Agotado' ?>
                            </span>

                            <div class="aspect-square bg-white flex items-center justify-center p-6 border-b border-gray-100 relative overflow-hidden">
                                <?php 
                                    // 🚨 RUTA CORREGIDA: Apuntando a /img/productos/ 🚨
                                    if (!empty($p['imagen_principal'])) {
                                        $imgSrc = $base_url . '/img/productos/' . htmlspecialchars($p['imagen_principal']);
                                    } else {
                                        $imgSrc = 'https://ui-avatars.com/api/?name=' . urlencode($p['nombre']) . '&background=f3f4f6&color=9ca3af&size=256&font-size=0.33';
                                    }
                                ?>
                                <img src="<?= $imgSrc ?>"
                                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <div class="p-5 flex flex-col flex-1 bg-gray-50/50">
                                <p class="text-xs font-semibold text-red-600 mb-1 uppercase tracking-wide">
                                    <?= htmlspecialchars($p['categoria_nombre'] ?? 'Sin Categoría') ?>
                                </p>
                                <h3 class="text-sm font-bold text-gray-900 mb-1 line-clamp-2 leading-tight">
                                    <?= htmlspecialchars($p['nombre']) ?>
                                </h3>
                                <p class="text-xs text-gray-500 mb-3 font-mono">SKU: <?= htmlspecialchars($p['sku']) ?></p>
                                
                                <p class="text-xl font-black text-gray-900 mb-5 mt-auto">
                                    $<?= number_format((float)$p['precio'], 2) ?>
                                </p>

                                <div class="flex gap-2 mt-auto">
                                    <a href="<?= $base_url ?? '' ?>/dashboard/productos/editar/<?= $p['id'] ?>"
                                       class="flex-1 bg-white border border-gray-200 hover:border-red-300 hover:text-red-700 text-gray-700 px-3 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Editar
                                    </a>

                                    <button type="button"
                                            onclick="confirmarEliminar(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nombre'])) ?>')"
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

</body>
</html>