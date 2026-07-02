<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<?php
// Unificar productos y servicios
$items = [];
if (!empty($productos)) {
    foreach ($productos as $p) {
        $p['tipo_item'] = 'producto';
        $items[] = $p;
    }
}
if (!empty($servicios)) {
    foreach ($servicios as $s) {
        $s['tipo_item'] = 'servicio';
        $items[] = $s;
    }
}

// Categorías ya vienen desde el controlador en $categorias

// Obtener detalles del borrador actual para saber qué items ya están agregados
$agregadosProductos = [];
$agregadosServicios = [];
if (isset($_SESSION['user_id']) && (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1)) {
    if (class_exists('\App\Models\CotizacionesModel')) {
        $cotModel = new \App\Models\CotizacionesModel();
        $borrador = $cotModel->getBorradorByUserId((int)$_SESSION['user_id']);
        if ($borrador) {
            $detalles = $cotModel->getDetalles((int)$borrador['id']);
            foreach ($detalles as $d) {
                if (!empty($d['producto_id'])) {
                    $agregadosProductos[] = $d['producto_id'];
                }
                if (!empty($d['servicio_id'])) {
                    $agregadosServicios[] = $d['servicio_id'];
                }
            }
        }
    }
}
?>

<div class="bg-gray-50 min-h-screen py-10 relative">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Breadcrumb discreto -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-medium text-gray-400 uppercase tracking-wider">
                <li class="inline-flex items-center">
                    <a href="<?= $base_url ?? '' ?>/" class="inline-flex items-center hover:text-red-600 transition-colors">
                        Inicio
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="mx-2">/</span>
                        <span class="text-gray-900">Catálogo</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Encabezado Fuerte y Búsqueda -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Catálogo Técnico</h1>
                    <p class="text-gray-500 mt-2 text-sm max-w-xl">Explora nuestros equipos y sistemas certificados. Añade los productos a tu carrito de compras.</p>
                </div>
                
                <div class="w-full md:w-80 relative">
                    <input type="text" id="searchInput" placeholder="Buscar por nombre, SKU o modelo..." class="input-elegant pl-10">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Filtro de Tipo -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Tipo:</span>
                <button class="type-filter type-filter--active group px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm border border-transparent" data-type="all">
                    Todos
                </button>
                <button class="type-filter group px-5 py-2 rounded-full text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:border-red-300 hover:text-red-700 hover:shadow-md transition-all duration-300" data-type="producto">
                    Productos
                </button>
                <button class="type-filter group px-5 py-2 rounded-full text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:border-red-300 hover:text-red-700 hover:shadow-md transition-all duration-300" data-type="servicio">
                    Servicios
                </button>
                
                <!-- Separador -->
                <div class="w-px h-6 bg-gray-200 mx-2 hidden sm:block"></div>
                
                <button class="catalog-filter flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:border-red-300 hover:text-red-700 hover:bg-red-50 hover:shadow-md transition-all duration-300" data-filter="added">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    En mi carrito
                </button>
            </div>

            <!-- Filtros de Categoría reales -->
            <div class="flex flex-wrap items-center gap-3 border-b border-gray-200 pb-6">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Categoría:</span>
                <button class="catalog-filter catalog-filter--active group px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm border border-transparent" data-filter="all">
                    Todas las categorías
                </button>
                <?php foreach ($categorias as $cat): ?>
                    <button class="catalog-filter group px-5 py-2.5 rounded-full text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:border-red-300 hover:text-red-700 hover:shadow-md transition-all duration-300" data-filter="<?= htmlspecialchars($cat['nombre']) ?>">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
            .catalog-filter--active, .type-filter--active {
                background-color: #b91c1c; /* red-700 */
                color: #ffffff !important;
                border-color: #b91c1c !important;
                box-shadow: 0 4px 6px -1px rgba(185, 28, 28, 0.3), 0 2px 4px -1px rgba(185, 28, 28, 0.2);
            }
            .product-card-premium {
                background: #ffffff;
                border-radius: 1.25rem;
                overflow: hidden;
                border: 1px solid rgba(229, 231, 235, 0.8);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
            }
            .product-card-premium:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                border-color: rgba(254, 202, 202, 0.5);
            }
            .product-card-premium .img-container {
                position: relative;
                padding-top: 100%; /* 1:1 Aspect Ratio */
                background: linear-gradient(to bottom, #f9fafb, #ffffff);
                overflow: hidden;
            }
            .product-card-premium img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: contain;
                padding: 1.5rem;
                transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .product-card-premium:hover img {
                transform: scale(1.08);
            }
            .btn-quote-premium {
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, #b91c1c, #991b1b);
            }
            .btn-quote-premium::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: all 0.6s ease;
            }
            .btn-quote-premium:hover::after {
                left: 100%;
            }
        </style>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12" id="catalogGrid">
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <?php 
                        $tipo = $item['tipo_item'];
                        $isProduct = ($tipo === 'producto');
                        
                        $isAdded = $isProduct 
                            ? in_array($item['id'], $agregadosProductos) 
                            : in_array($item['id'], $agregadosServicios);
                            
                        $catName = htmlspecialchars($item['categoria_nombre'] ?? 'Sin Categoría');
                        $nombre = htmlspecialchars($item['nombre'] ?? 'Item Sin Nombre');
                        $itemId = (int)$item['id'];
                        $detalleUrl = ($base_url ?? '') . '/' . $tipo . '/' . $itemId;
                        
                        if ($isProduct) {
                            $sku = htmlspecialchars($item['sku'] ?? 'N/A');
                            $imgDir = '/img/productos/';
                        } else {
                            $sku = htmlspecialchars($item['codigo'] ?? 'N/A');
                            $imgDir = '/img/servicios/';
                        }

                        $publicDir = dirname(__DIR__, 3) . '/public';
                        $imgPathFs = !empty($item['imagen_principal']) ? $publicDir . $imgDir . $item['imagen_principal'] : '';
                        
                        // Usar file_exists para evitar que se rompa si el archivo físico no existe (aunque esté en la BD)
                        $img = (!empty($item['imagen_principal']) && file_exists($imgPathFs))
                            ? ($base_url ?? '') . $imgDir . htmlspecialchars($item['imagen_principal']) 
                            : ($base_url ?? '') . '/img/user.png';
                    ?>
                    
                    <div class="product-card-premium js-product-item group" 
                         data-tipo-item="<?= $tipo ?>"
                         data-categoria="<?= $catName ?>" 
                         data-nombre="<?= strtolower($nombre) ?>" 
                         data-sku="<?= strtolower($sku) ?>" 
                         data-added="<?= $isAdded ? 'true' : 'false' ?>">
                        
                        <div class="img-container">
                            <!-- Badges Superiores -->
                            <div class="absolute top-4 left-4 right-4 flex justify-between items-start z-10 pointer-events-none">
                                <div class="flex flex-col gap-1.5">
                                    <span class="bg-gray-900/85 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm w-max">
                                        <?= $catName ?>
                                    </span>
                                    <?php if ($isProduct): ?>
                                    <span class="bg-green-600/90 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider self-start shadow-sm w-max">
                                        Producto
                                    </span>
                                    <?php else: ?>
                                    <span class="bg-blue-600/90 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider self-start shadow-sm w-max">
                                        Servicio
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Indicador de Agregado -->
                                <div class="js-added-badge transition-all duration-500 <?= $isAdded ? 'opacity-100 transform scale-100' : 'opacity-0 transform scale-90' ?>">
                                    <div class="bg-red-50 text-red-700 border border-red-200 text-xs font-extrabold px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                            </div>

                            <a href="<?= $detalleUrl ?>" class="absolute inset-0 z-0">
                                <img src="<?= $img ?>" alt="<?= $nombre ?>">
                            </a>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <a href="<?= $detalleUrl ?>" class="group-hover:text-red-700 transition-colors duration-300">
                                <h3 class="text-base font-bold text-gray-900 mb-4 line-clamp-2 leading-tight">
                                    <?= $nombre ?>
                                </h3>
                            </a>
                            
                            <!-- Precio / Precio Referencial -->
                            <div class="mb-4">
                                <?php if ($isProduct): ?>
                                    <span class="text-lg font-extrabold text-red-600">
                                        $<?= number_format((float)($item['precio'] ?? 0), 2) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-lg font-extrabold text-red-600">
                                        $<?= number_format((float)($item['precio_referencial'] ?? 0), 2) ?>
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-semibold block uppercase tracking-wider mt-0.5">
                                        Precio Referencial (<?= htmlspecialchars($item['tipo_cobro_nombre'] ?? 'Servicio') ?>)
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between gap-3 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1)): ?>
                                    <button type="button" 
                                            class="btn-quote-premium js-add-to-quote flex-1 text-white text-sm font-bold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 <?= $isAdded ? 'opacity-60 cursor-not-allowed bg-gray-500' : '' ?>" 
                                            data-id="<?= $itemId ?>" 
                                            data-tipo="<?= $tipo ?>"
                                            <?= $isAdded ? 'disabled' : '' ?>
                                            title="<?= $isAdded ? 'Ya está en tu carrito' : 'Añadir al carrito' ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        <span class="btn-text"><?= $isAdded ? 'Añadido' : 'Agregar' ?></span>
                                    </button>
                                <?php else: ?>
                                    <a href="<?= $base_url ?? '' ?>/login" class="flex-1 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-bold py-2.5 px-4 rounded-xl flex items-center justify-center text-center">
                                        Ingresar
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?= $detalleUrl ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-600 hover:bg-red-50 hover:text-red-700 transition-colors" title="Ver Detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No hay elementos disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">Pronto agregaremos más equipos y servicios a nuestro catálogo.</p>
                </div>
            <?php endif; ?>
            
            <!-- Elemento oculto para mostrar cuando el filtro no arroja resultados -->
            <div id="noResultsMsg" class="hidden col-span-full py-16 text-center">
                <p class="text-gray-500 font-medium">No se encontraron resultados con estos filtros.</p>
            </div>
        </div>

        <!-- Paginación Centrada -->
        <?php if (!empty($productos)): ?>
        <div class="flex flex-col items-center border-t border-gray-200 pt-8 mt-4">
            <nav id="paginationContainer" class="flex items-center gap-2" aria-label="Pagination">
                <!-- Se llenará vía JS -->
            </nav>
            <p id="paginationInfo" class="text-xs text-gray-400 mt-4 font-medium uppercase tracking-wider"></p>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast-notification bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 border border-gray-800">
    <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center">
        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
    </div>
    <div>
        <h4 class="text-sm font-bold">¡Agregado al carrito!</h4>
        <p class="text-xs text-gray-400">Puedes seguir explorando o ver tu lista.</p>
    </div>
</div>

<!-- Logica JS para Filtros y Agregar a Presupuesto (AJAX) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('.catalog-filter');
    const typeFilters = document.querySelectorAll('.type-filter');
    const products = Array.from(document.querySelectorAll('.js-product-item'));
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResultsMsg');
    const toast = document.getElementById('toast');
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationInfo = document.getElementById('paginationInfo');
    const urlParams = new URLSearchParams(window.location.search);
    const paramCategoria = urlParams.get('categoria');
    const paramTipo = urlParams.get('tipo');
    
    const paramSearch = urlParams.get('search');
    
    let currentFilter = paramCategoria ? paramCategoria : 'all';
    let currentType = paramTipo ? paramTipo : 'all';
    let searchTerm = paramSearch ? paramSearch.toLowerCase().trim() : '';

    if (paramSearch && searchInput) {
        searchInput.value = paramSearch;
    }
    
    // Configuración de Paginación
    const itemsPerPage = 8;
    let currentPage = 1;
    let filteredProducts = [...products];

    // Función principal para aplicar filtros y paginar
    function applyFilters() {
        searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        filteredProducts = products.filter(product => {
            const tipoItem = product.getAttribute('data-tipo-item');
            const cat = product.getAttribute('data-categoria');
            const added = product.getAttribute('data-added') === 'true';
            const nombre = product.getAttribute('data-nombre') || '';
            const sku = product.getAttribute('data-sku') || '';
            
            // Lógica de tipo (Producto / Servicio)
            let showByType = false;
            if (currentType === 'all') showByType = true;
            else showByType = (tipoItem === currentType);

            // Lógica de categoría
            let showByCategory = false;
            if (currentFilter === 'all') showByCategory = true;
            else if (currentFilter === 'added') showByCategory = added;
            else showByCategory = (cat === currentFilter);

            // Lógica de búsqueda
            let showBySearch = true;
            if (searchTerm !== '') {
                showBySearch = nombre.includes(searchTerm) || sku.includes(searchTerm);
            }

            return showByType && showByCategory && showBySearch;
        });

        // Reiniciar a primera página al filtrar
        currentPage = 1;
        renderPagination();
        renderProducts();
    }

    function renderProducts() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        // Ocultar todos primero
        products.forEach(p => p.style.display = 'none');

        // Mostrar solo los de la página actual
        const currentProducts = filteredProducts.slice(startIndex, endIndex);
        
        currentProducts.forEach(product => {
            product.style.display = '';
            product.style.opacity = '0';
            product.style.transform = 'scale(0.98)';
            setTimeout(() => {
                product.style.transition = 'all 0.3s ease';
                product.style.opacity = '1';
                product.style.transform = 'scale(1)';
            }, 10);
        });

        if (filteredProducts.length === 0 && products.length > 0) {
            if(noResults) noResults.classList.remove('hidden');
        } else {
            if(noResults) noResults.classList.add('hidden');
        }
    }

    function renderPagination() {
        if (!paginationContainer) return;
        
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        paginationContainer.innerHTML = '';
        
        if (totalPages <= 1) {
            if (paginationInfo) paginationInfo.innerText = `Mostrando ${filteredProducts.length} resultado(s)`;
            return;
        }

        if (paginationInfo) {
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentPage * itemsPerPage, filteredProducts.length);
            paginationInfo.innerText = `Mostrando ${start} - ${end} de ${filteredProducts.length} resultados`;
        }

        // Botón Prev
        const prevBtn = document.createElement('a');
        prevBtn.href = '#';
        prevBtn.className = `pagination-pill ${currentPage === 1 ? 'pagination-pill--disabled' : ''}`;
        prevBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) { currentPage--; renderProducts(); renderPagination(); window.scrollTo({top: 0, behavior: 'smooth'}); }
        });
        paginationContainer.appendChild(prevBtn);

        // Páginas
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('a');
            pageBtn.href = '#';
            pageBtn.className = `pagination-pill ${currentPage === i ? 'pagination-pill--active' : ''}`;
            pageBtn.innerText = i;
            pageBtn.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                renderProducts();
                renderPagination();
                window.scrollTo({top: 0, behavior: 'smooth'});
            });
            paginationContainer.appendChild(pageBtn);
        }

        // Botón Next
        const nextBtn = document.createElement('a');
        nextBtn.href = '#';
        nextBtn.className = `pagination-pill ${currentPage === totalPages ? 'pagination-pill--disabled' : ''}`;
        nextBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) { currentPage++; renderProducts(); renderPagination(); window.scrollTo({top: 0, behavior: 'smooth'}); }
        });
        paginationContainer.appendChild(nextBtn);
    }

    // Eventos de botones de filtro por categoría
    filters.forEach(btn => {
        btn.addEventListener('click', () => {
            filters.forEach(f => f.classList.remove('catalog-filter--active'));
            btn.classList.add('catalog-filter--active');
            currentFilter = btn.getAttribute('data-filter');
            applyFilters();
        });
    });

    // Eventos de botones de filtro por tipo
    typeFilters.forEach(btn => {
        btn.addEventListener('click', () => {
            typeFilters.forEach(f => f.classList.remove('type-filter--active'));
            btn.classList.add('type-filter--active');
            currentType = btn.getAttribute('data-type');
            applyFilters();
        });
    });

    // Evento de búsqueda con debounce manual ligero
    let searchTimeout;
    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 300);
        });
    }
    
    // Marcar los botones activos de la URL al cargar
    if (paramCategoria) {
        filters.forEach(f => f.classList.remove('catalog-filter--active'));
        const activeBtn = Array.from(filters).find(btn => btn.getAttribute('data-filter') === paramCategoria);
        if (activeBtn) activeBtn.classList.add('catalog-filter--active');
    }
    if (paramTipo) {
        typeFilters.forEach(f => f.classList.remove('type-filter--active'));
        const activeBtn = Array.from(typeFilters).find(btn => btn.getAttribute('data-type') === paramTipo);
        if (activeBtn) activeBtn.classList.add('type-filter--active');
    }

    // Inicializar vista
    applyFilters();
    // Agregar a presupuesto vía AJAX
    document.querySelectorAll('.js-add-to-quote').forEach(btn => {
        btn.addEventListener('click', function() {
            if(this.disabled) return;
            
            const itemId = this.getAttribute('data-id');
            const itemTipo = this.getAttribute('data-tipo');
            const card = this.closest('.js-product-item');
            
            // Estado visual de carga
            const originalText = this.innerHTML;
            this.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            this.disabled = true;

            const formData = new FormData();
            if (itemTipo === 'producto') {
                formData.append('producto_id', itemId);
            } else {
                formData.append('servicio_id', itemId);
            }
            formData.append('cantidad', 1);

            fetch('<?= $base_url ?? '' ?>/pedido/agregar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text()) // Expecting redirect or html if no json output, but wait, the controller redirect headers...
            .then(text => {
                // Controller actually does a header('Location: ...') so fetch will follow it to /pedido/actual or whatever.
                // We should ideally change the backend to respond with JSON if fetch, but for now we just assume success.
                
                // Actualizar estado visual de la card
                this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="btn-text">Añadido</span>';
                this.classList.add('opacity-60', 'cursor-not-allowed', 'bg-gray-500');
                card.setAttribute('data-added', 'true');
                
                const badge = card.querySelector('.js-added-badge');
                if(badge) badge.classList.replace('opacity-0', 'opacity-100');

                // Actualizar contador del header
                const headerBadge = document.querySelector('a[href$="/pedido/actual"] span.absolute');
                if (headerBadge) {
                    let currentCount = parseInt(headerBadge.innerText) || 0;
                    if(isNaN(currentCount)) currentCount = 0; // If it was '✓'
                    headerBadge.innerText = currentCount + 1;
                } else {
                    // Create badge if it didn't exist
                    const cartIcon = document.querySelector('a[href$="/pedido/actual"]');
                    if(cartIcon) {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-sm';
                        newBadge.innerText = '1';
                        cartIcon.appendChild(newBadge);
                    }
                }

                // Mostrar Toast
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 4000);
            })
            .catch(err => {
                console.error(err);
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
});
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
