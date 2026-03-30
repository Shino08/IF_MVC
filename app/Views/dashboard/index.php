<?php $title = 'Dashboard'; $active_nav = 'dashboard'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto flex flex-col">

        <!-- ── Header ──────────────────────────────────────────────── -->
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-500 text-sm mt-0.5">
                        Bienvenido, <span class="font-semibold text-gray-800"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                        — <?= date('d \d\e F, Y') ?>
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="<?= $base_url ?>/dashboard/productos/agregar"
                       class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Nuevo Producto
                    </a>
                    <a href="<?= $base_url ?>/dashboard/servicios/agregar"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Nuevo Servicio
                    </a>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8">

            <!-- ── KPIs ───────────────────────────────────────────── -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

                <!-- Productos -->
                <a href="<?= $base_url ?>/dashboard/productos"
                   class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Catálogo</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900 mb-1"><?= $totalProductos ?></p>
                    <p class="text-sm text-gray-500">Productos registrados</p>
                    <?php if ($sinStock > 0): ?>
                    <p class="text-xs text-orange-600 font-medium mt-2">⚠ <?= $sinStock ?> sin stock</p>
                    <?php else: ?>
                    <p class="text-xs text-green-600 font-medium mt-2">✓ Todos con stock</p>
                    <?php endif; ?>
                </a>

                <!-- Servicios -->
                <a href="<?= $base_url ?>/dashboard/servicios"
                   class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Servicios</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900 mb-1"><?= $totalServicios ?></p>
                    <p class="text-sm text-gray-500">Servicios disponibles</p>
                    <p class="text-xs text-blue-600 font-medium mt-2">Mano de obra y más</p>
                </a>

                <!-- Categorías -->
                <a href="<?= $base_url ?>/dashboard/categorias"
                   class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4zM3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Catálogo</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900 mb-1"><?= $totalCategorias ?></p>
                    <p class="text-sm text-gray-500">Categorías activas</p>
                    <p class="text-xs text-green-600 font-medium mt-2">Productos y servicios</p>
                </a>

                <!-- Cotizaciones (placeholder real) -->
                <a href="<?= $base_url ?>/dashboard/cotizaciones"
                   class="bg-gradient-to-br from-red-700 to-red-900 rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow group text-white">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-white/80 bg-white/20 px-2 py-0.5 rounded-full">Próximamente</span>
                    </div>
                    <p class="text-3xl font-black mb-1">—</p>
                    <p class="text-sm text-white/80">Cotizaciones</p>
                    <p class="text-xs text-white/60 font-medium mt-2">Módulo en desarrollo</p>
                </a>

            </div>

            <!-- ── Fila central: Distribución por categoría + Servicios recientes ── -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Productos por categoría -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-base font-bold text-gray-900">Productos por Categoría</h3>
                        <a href="<?= $base_url ?>/dashboard/productos"
                           class="text-xs text-red-600 font-semibold hover:underline">Ver todos →</a>
                    </div>

                    <?php if (empty($catMap)): ?>
                        <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                            <p class="text-sm">Sin productos todavía</p>
                        </div>
                    <?php else: ?>
                        <?php
                        $total  = array_sum($catMap);
                        $colors = ['bg-red-500','bg-blue-500','bg-green-500','bg-yellow-400','bg-purple-500','bg-pink-500','bg-orange-400'];
                        $ci     = 0;
                        ?>
                        <div class="space-y-3">
                        <?php foreach ($catMap as $catLabel => $count):
                            $pct = $total > 0 ? round($count / $total * 100) : 0;
                            $col = $colors[$ci % count($colors)];
                            $ci++;
                        ?>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 <?= $col ?> rounded-full inline-block flex-shrink-0"></span>
                                        <span class="text-gray-700 truncate max-w-[180px]"><?= htmlspecialchars($catLabel) ?></span>
                                    </div>
                                    <span class="font-bold text-gray-900 ml-2"><?= $count ?> <span class="text-gray-400 font-normal text-xs">(<?= $pct ?>%)</span></span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full">
                                    <div class="h-1.5 <?= $col ?> rounded-full transition-all duration-700" style="width:<?= $pct ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Últimos servicios -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-base font-bold text-gray-900">Servicios Activos</h3>
                        <a href="<?= $base_url ?>/dashboard/servicios"
                           class="text-xs text-blue-600 font-semibold hover:underline">Ver todos →</a>
                    </div>

                    <?php if (empty($ultimosServicios)): ?>
                        <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm">Sin servicios todavía</p>
                            <a href="<?= $base_url ?>/dashboard/servicios/agregar" class="text-xs text-blue-600 font-semibold mt-2 hover:underline">Agregar el primero →</a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                        <?php foreach ($ultimosServicios as $s): ?>
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <?php if (!empty($s['imagen_principal'])): ?>
                                            <img src="<?= $base_url ?>/img/servicios/<?= htmlspecialchars($s['imagen_principal']) ?>"
                                                 class="w-9 h-9 object-contain rounded-lg" alt="">
                                        <?php else: ?>
                                            <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($s['nombre']) ?></p>
                                        <p class="text-xs text-gray-400 font-mono"><?= htmlspecialchars($s['codigo']) ?></p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-3">
                                    <p class="text-sm font-bold text-gray-900">$<?= number_format((float)$s['precio_referencial'], 2) ?></p>
                                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700">
                                        <?= htmlspecialchars($s['tipo_cobro_nombre'] ?? '—') ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ── Últimos productos ──────────────────────────────── -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-base font-bold text-gray-900">Últimos Productos Agregados</h3>
                    <a href="<?= $base_url ?>/dashboard/productos" class="text-xs text-red-600 font-semibold hover:underline">Ver todos →</a>
                </div>

                <?php if (empty($ultimosProductos)): ?>
                    <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <p class="text-sm">Sin productos todavía</p>
                        <a href="<?= $base_url ?>/dashboard/productos/agregar" class="text-xs text-red-600 font-semibold mt-2 hover:underline">Agregar el primero →</a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="pb-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Producto</th>
                                    <th class="pb-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">SKU</th>
                                    <th class="pb-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Categoría</th>
                                    <th class="pb-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Precio</th>
                                    <th class="pb-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Stock</th>
                                    <th class="pb-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                            <?php foreach ($ultimosProductos as $p): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <?php
                                            $src = !empty($p['imagen_principal'])
                                                ? $base_url . '/img/productos/' . htmlspecialchars($p['imagen_principal'])
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($p['nombre']) . '&background=f3f4f6&color=9ca3af&size=64&font-size=0.33';
                                            ?>
                                            <img src="<?= $src ?>" class="w-10 h-10 rounded-lg object-contain bg-gray-50 border border-gray-100" alt="">
                                            <span class="font-semibold text-gray-900 truncate max-w-[180px]"><?= htmlspecialchars($p['nombre']) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 font-mono text-gray-500 text-xs"><?= htmlspecialchars($p['sku']) ?></td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                                            <?= htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría') ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-bold text-gray-900">$<?= number_format((float)$p['precio'], 2) ?></td>
                                    <td class="py-3 text-center">
                                        <?php $enStock = (int)$p['existencia'] > 0; ?>
                                        <span class="px-2 py-1 rounded-full text-xs font-bold <?= $enStock ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= $enStock ? $p['existencia'] : 'Agotado' ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="<?= $base_url ?>/dashboard/productos/editar/<?= $p['id'] ?>"
                                           class="text-xs text-red-600 font-semibold hover:underline">Editar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div><!-- /p-8 -->
    </main>
</div>
</body>
</html>
