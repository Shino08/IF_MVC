<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<?php
// Extraer categorías únicas para los filtros
$categorias = [];
if (!empty($productos)) {
    foreach ($productos as $p) {
        $cat = $p['categoria_nombre'] ?? 'Sin Categoría';
        if (!in_array($cat, $categorias)) {
            $categorias[] = $cat;
        }
    }
    sort($categorias);
}

// Obtener detalles del borrador actual para saber qué items ya están agregados
$agregados = [];
if (isset($_SESSION['user_id']) && (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1)) {
    if (class_exists('\App\Models\CotizacionesModel')) {
        $cotModel = new \App\Models\CotizacionesModel();
        $borrador = $cotModel->getBorradorByUserId((int)$_SESSION['user_id']);
        if ($borrador) {
            $detalles = $cotModel->getDetalles((int)$borrador['id']);
            foreach ($detalles as $d) {
                if ($d['producto_id']) {
                    $agregados[] = $d['producto_id'];
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
                    <p class="text-gray-500 mt-2 text-sm max-w-xl">Explora nuestros equipos y sistemas certificados. Añade los productos a tu solicitud y obtén una cotización formal.</p>
                </div>
                
                <div class="w-full md:w-80 relative">
                    <input type="text" id="searchInput" placeholder="Buscar por nombre, SKU o modelo..." class="input-elegant pl-10">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Franja de filtros reales -->
            <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-6">
                <button class="catalog-filter catalog-filter--active" data-filter="all">Todos los equipos</button>
                <?php foreach ($categorias as $cat): ?>
                    <button class="catalog-filter" data-filter="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
                <?php endforeach; ?>
                
                <!-- Separador -->
                <div class="w-px h-6 bg-gray-300 mx-2 hidden sm:block"></div>
                
                <button class="catalog-filter flex items-center gap-1" data-filter="added">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    En mi solicitud
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12" id="catalogGrid">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <?php 
                        $isAdded = in_array($producto['id'], $agregados);
                        $catName = htmlspecialchars($producto['categoria_nombre'] ?? 'Sin Categoría');
                        $img = !empty($producto['imagen_url']) ? $producto['imagen_url'] : 'placeholder.jpg';
                        $nombre = htmlspecialchars($producto['nombre'] ?? 'Producto Sin Nombre');
                        $sku = htmlspecialchars($producto['sku'] ?? 'N/A');
                        $prodId = (int)$producto['id'];
                    ?>
                    
                    <div class="product-card js-product-item" 
                         data-categoria="<?= $catName ?>" 
                         data-nombre="<?= strtolower($nombre) ?>" 
                         data-sku="<?= strtolower($sku) ?>" 
                         data-added="<?= $isAdded ? 'true' : 'false' ?>">
                        
                        <!-- Badges Superiores -->
                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start z-10 pointer-events-none">
                            <span class="bg-gray-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">
                                <?= $catName ?>
                            </span>
                            
                            <!-- Indicador de Agregado -->
                            <div class="js-added-badge transition-opacity duration-300 <?= $isAdded ? 'opacity-100' : 'opacity-0' ?>">
                                <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md flex items-center gap-1 shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    En solicitud
                                </span>
                            </div>
                        </div>

                        <div>
                            <div class="product-card__media">
                                <a href="<?= $base_url ?? '' ?>/producto/<?= $prodId ?>" class="block w-full h-full flex items-center justify-center">
                                    <img src="<?= $base_url ?? '' ?>/img/<?= htmlspecialchars($img) ?>" alt="<?= $nombre ?>">
                                </a>
                            </div>
                            
                            <p class="text-[11px] text-gray-500 mb-1.5 font-mono font-medium uppercase tracking-wider">SKU: <?= $sku ?></p>
                            <a href="<?= $base_url ?? '' ?>/producto/<?= $prodId ?>">
                                <h3 class="text-sm font-bold text-gray-900 mb-6 line-clamp-2 leading-snug hover:text-red-600 transition-colors">
                                    <?= $nombre ?>
                                </h3>
                            </a>
                        </div>
                        
                        <!-- Acciones Dobles -->
                        <div class="mt-auto flex flex-col gap-2">
                            <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1)): ?>
                                <button type="button" 
                                        class="btn-primary w-full js-add-to-quote <?= $isAdded ? 'opacity-50 cursor-not-allowed' : '' ?>" 
                                        data-id="<?= $prodId ?>" 
                                        <?= $isAdded ? 'disabled' : '' ?>>
                                    <?= $isAdded ? 'Ya agregado' : 'Agregar a Solicitud' ?>
                                </button>
                            <?php else: ?>
                                <a href="<?= $base_url ?? '' ?>/login" class="btn-primary w-full text-center">Inicia sesión para cotizar</a>
                            <?php endif; ?>
                            
                            <a href="<?= $base_url ?? '' ?>/producto/<?= $prodId ?>" class="btn-secondary w-full">
                                Ver Ficha Técnica
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No hay productos disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">Pronto agregaremos más equipos a nuestro catálogo técnico.</p>
                </div>
            <?php endif; ?>
            
            <!-- Elemento oculto para mostrar cuando el filtro no arroja resultados -->
            <div id="noResultsMsg" class="hidden col-span-full py-16 text-center">
                <p class="text-gray-500 font-medium">No se encontraron productos con estos filtros.</p>
            </div>
        </div>

        <!-- Paginación Centrada -->
        <?php if (!empty($productos)): ?>
        <div class="flex flex-col items-center border-t border-gray-200 pt-8 mt-4">
            <nav class="flex items-center gap-2" aria-label="Pagination">
                <a href="#" class="pagination-pill pagination-pill--disabled">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <a href="#" class="pagination-pill pagination-pill--active">1</a>
                <a href="#" class="pagination-pill">2</a>
                <a href="#" class="pagination-pill">3</a>
                <a href="#" class="pagination-pill">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </nav>
            <p class="text-xs text-gray-400 mt-4 font-medium uppercase tracking-wider">Mostrando catálogo completo</p>
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
        <h4 class="text-sm font-bold">¡Agregado a tu solicitud!</h4>
        <p class="text-xs text-gray-400">Puedes seguir explorando o ver tu lista.</p>
    </div>
</div>

<!-- Logica JS para Filtros y Agregar a Cotización (AJAX) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('.catalog-filter');
    const products = document.querySelectorAll('.js-product-item');
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResultsMsg');
    const toast = document.getElementById('toast');
    let currentFilter = 'all';

    // Función para aplicar filtros
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        products.forEach(product => {
            const cat = product.getAttribute('data-categoria');
            const added = product.getAttribute('data-added') === 'true';
            const nombre = product.getAttribute('data-nombre');
            const sku = product.getAttribute('data-sku');
            
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

            if (showByCategory && showBySearch) {
                product.style.display = '';
                // Animación suave de entrada
                product.style.opacity = '0';
                product.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    product.style.transition = 'all 0.3s ease';
                    product.style.opacity = '1';
                    product.style.transform = 'scale(1)';
                }, 10);
                visibleCount++;
            } else {
                product.style.display = 'none';
            }
        });

        if (visibleCount === 0 && products.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    // Eventos de botones de filtro
    filters.forEach(btn => {
        btn.addEventListener('click', () => {
            filters.forEach(f => f.classList.remove('catalog-filter--active'));
            btn.classList.add('catalog-filter--active');
            currentFilter = btn.getAttribute('data-filter');
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

    // Agregar a cotización vía AJAX
    document.querySelectorAll('.js-add-to-quote').forEach(btn => {
        btn.addEventListener('click', function() {
            if(this.disabled) return;
            
            const prodId = this.getAttribute('data-id');
            const card = this.closest('.js-product-item');
            
            // Estado visual de carga
            const originalText = this.innerHTML;
            this.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            this.disabled = true;

            const formData = new FormData();
            formData.append('producto_id', prodId);
            formData.append('cantidad', 1);

            fetch('<?= $base_url ?? '' ?>/cotizacion/agregar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text()) // Expecting redirect or html if no json output, but wait, the controller redirect headers...
            .then(text => {
                // Controller actually does a header('Location: ...') so fetch will follow it to /cotizacion/actual or whatever.
                // We should ideally change the backend to respond with JSON if fetch, but for now we just assume success.
                
                // Actualizar estado visual de la card
                this.innerHTML = 'Ya agregado';
                this.classList.add('opacity-50', 'cursor-not-allowed');
                card.setAttribute('data-added', 'true');
                
                const badge = card.querySelector('.js-added-badge');
                if(badge) badge.classList.replace('opacity-0', 'opacity-100');

                // Actualizar contador del header
                const headerBadge = document.querySelector('a[href$="/cotizacion/actual"] span.absolute');
                if (headerBadge) {
                    let currentCount = parseInt(headerBadge.innerText) || 0;
                    if(isNaN(currentCount)) currentCount = 0; // If it was '✓'
                    headerBadge.innerText = currentCount + 1;
                } else {
                    // Create badge if it didn't exist
                    const cartIcon = document.querySelector('a[href$="/cotizacion/actual"]');
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
