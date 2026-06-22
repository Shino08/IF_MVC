<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                <li class="inline-flex items-center">
                    <a href="<?= $base_url ?? '' ?>/" class="inline-flex items-center hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Inicio
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 md:ml-2 text-gray-800 font-medium">Catálogo</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header and Filter Pills -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Catálogo de Productos</h1>
                    <p class="text-gray-600 mt-2">Encuentra los mejores equipos para tu seguridad.</p>
                </div>
                <!-- Filter Pills -->
                <div class="flex flex-wrap gap-2">
                    <a href="#" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-900 text-white">Todos</a>
                    <a href="#" class="px-4 py-2 rounded-full text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 transition-colors">Cámaras</a>
                    <a href="#" class="px-4 py-2 rounded-full text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 transition-colors">Sistemas de Alarma</a>
                    <a href="#" class="px-4 py-2 rounded-full text-sm font-medium bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 transition-colors">Accesorios</a>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="bg-white rounded-2xl p-6 hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-gray-100 group relative">
                        <div class="absolute top-4 right-4 bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">Nuevo</div>
                        <div>
                            <?php $img = !empty($producto['imagen_url']) ? $producto['imagen_url'] : 'placeholder.jpg'; ?>
                            <div class="w-full h-48 bg-gray-50 rounded-xl mb-6 p-4 flex items-center justify-center">
                                <a href="<?= $base_url ?? '' ?>/producto/<?= htmlspecialchars((string)($producto['id'] ?? '')) ?>">
                                    <img src="<?= $base_url ?? '' ?>/img/<?= htmlspecialchars($img) ?>"
                                         alt="<?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?>"
                                         class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
                                </a>
                            </div>
                            
                            <p class="text-xs text-gray-400 mb-2 font-medium uppercase tracking-wider">SKU: <?= htmlspecialchars($producto['sku'] ?? 'N/A') ?></p>
                            <a href="<?= $base_url ?? '' ?>/producto/<?= htmlspecialchars((string)($producto['id'] ?? '')) ?>">
                                <h3 class="text-base font-bold text-gray-900 mb-4 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors">
                                    <?= htmlspecialchars($producto['nombre'] ?? 'Producto Sin Nombre') ?>
                                </h3>
                            </a>
                        </div>
                        
                        <a href="<?= $base_url ?? '' ?>/producto/<?= htmlspecialchars((string)($producto['id'] ?? '')) ?>" class="mt-auto w-full bg-white border-2 border-gray-900 text-gray-900 rounded-xl px-6 py-3 text-sm font-bold hover:bg-gray-900 hover:text-white transition-all flex items-center justify-center gap-2 group-hover:border-red-600 group-hover:bg-red-600 group-hover:text-white">
                            Ver Detalles
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No hay productos disponibles</h3>
                    <p class="mt-1 text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($productos)): ?>
        <div class="flex items-center justify-between border-t border-gray-200 pt-6 mt-8">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Anterior</a>
                <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Siguiente</a>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Mostrando <span class="font-medium">1</span> a <span class="font-medium">12</span> de <span class="font-medium">24</span> resultados
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            <span class="sr-only">Anterior</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-red-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">1</a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">3</a>
                        <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            <span class="sr-only">Siguiente</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
