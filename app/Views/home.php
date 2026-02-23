<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstalFuego — Sistemas de Seguridad Contra Incendios</title>
    <meta name="description" content="InstalFuego: sistemas avanzados.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $base_url ?>/css/styles.css">
</head>
<body class="bg-white">

<div class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3">
        
        <div class="flex items-center justify-between flex-wrap">

            <div class="flex items-center shrink-0">
                <a href="<?= $base_url ?>/">
                    <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                         alt="InstalFuego Logo" class="h-10 md:h-12 object-contain">
                </a>
            </div>

            <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:text-red-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>

            <div class="hidden md:flex flex-1 max-w-2xl mx-4 lg:mx-8 order-last md:order-none w-full md:w-auto mt-3 md:mt-0" id="desktop-search">
                <div class="relative w-full">
                    <input type="text" placeholder="Buscar productos..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-2 lg:space-x-4">
                <?php if (isset($logged_in) && $logged_in): ?>
                    <a href="<?= $base_url ?>/dashboard" class="px-3 py-2 bg-red-700 text-white text-xs lg:text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors whitespace-nowrap">
                        Panel Admin
                    </a>
                    <a href="<?= $base_url ?>/logout" class="text-sm text-gray-600 hover:text-red-700 font-medium">Salir</a>
                <?php else: ?>
                    <a href="<?= $base_url ?>/login" class="text-sm font-medium text-gray-700 hover:text-red-700 whitespace-nowrap">Ingresar</a>
                    <a href="<?= $base_url ?>/register" class="px-3 py-2 bg-red-700 text-white text-xs lg:text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors whitespace-nowrap">
                        Registro
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden mt-4 border-t pt-4">
            <div class="relative mb-4">
                <input type="text" placeholder="Buscar..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex flex-col space-y-3">
                <a href="#" class="text-gray-700 font-medium hover:text-red-600">DETECCIÓN DE HUMO</a>
                <a href="#" class="text-gray-700 font-medium hover:text-red-600">EXTINGUIDORES</a>
                <a href="#" class="text-gray-700 font-medium hover:text-red-600">SISTEMAS DE RIEGO</a>
                <a href="#" class="text-gray-700 font-medium hover:text-red-600">EPP</a>
                <hr>
                <?php if (!isset($logged_in) || !$logged_in): ?>
                    <a href="<?= $base_url ?>/login" class="text-red-600 font-semibold">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<nav class="hidden md:block bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center space-x-4 lg:space-x-8 py-4 overflow-x-auto text-sm lg:text-base whitespace-nowrap">
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">DETECCIÓN DE HUMO</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EXTINGUIDORES</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">RIEGO AUTOMÁTICO</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EPP</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">SERVICIOS</a>
        </div>
    </div>
</nav>

<section class="bg-gray-50 py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
            
            <div class="text-center md:text-left order-2 md:order-1">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                    SISTEMAS DE SEGURIDAD CONTRA INCENDIOS
                </h1>
                <p class="text-gray-600 text-base md:text-lg mb-6">
                    Un sistema avanzado diseñado para detectar y extinguir incendios rápidamente,
                    <span class="text-red-600 font-bold">salvando vidas</span> y protegiendo tus bienes.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="#productos"
                       class="w-full sm:w-auto px-6 py-3 bg-red-700 text-white font-semibold rounded-xl hover:bg-red-800 transition-colors text-center">
                        Ver Productos
                    </a>
                    <a href="<?= $base_url ?>/cotizar"
                       class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-red-500 hover:text-red-600 transition-colors text-center">
                        Cotizar Ahora
                    </a>
                </div>
            </div>

            <div class="flex justify-center order-1 md:order-2">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                     alt="Sistema contra incendios InstalFuego"
                     class="w-3/4 md:w-full max-w-md object-contain">
            </div>
        </div>
    </div>
</section>

<section id="productos" class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 text-center md:text-left">EXPLORAR PRODUCTOS</h2>
        <p class="text-gray-600 mb-1 text-center md:text-left">Soluciones de Seguridad</p>
        <p class="text-red-600 font-semibold mb-8 text-center md:text-left">Contra Incendios</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $destacados = [
                ['img' => 'extintor-espuma-afffar-3-10-lt-ab-mod-em-10k.jpg', 'cat' => 'Extinguidor',  'nombre' => 'Extintor de Espuma'],
                ['img' => 'bombeo-detalle-bci-min.png',                        'cat' => 'Extinguidor',  'nombre' => 'Bomba Contra Incendio'],
                ['img' => 'C2-REC.jpg',                                         'cat' => 'Servicio',     'nombre' => 'Recarga de Extintores'],
                ['img' => 'lampara-sovica.jpg',                                 'cat' => 'Iluminación',  'nombre' => 'Iluminación Emergencia'],
            ];
            foreach ($destacados as $p): ?>
            <div class="bg-gray-50 rounded-lg p-6 relative hover:shadow-lg transition-shadow">
                <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </button>
                <img src="<?= $base_url ?>/img/<?= htmlspecialchars($p['img']) ?>"
                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                     class="w-full h-40 md:h-48 object-contain mb-4">
                <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide"><?= htmlspecialchars($p['cat']) ?></p>
                <p class="text-sm font-semibold text-gray-900 leading-tight"><?= htmlspecialchars($p['nombre']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 text-center md:text-left">
            <div class="mb-4 md:mb-0">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">DETECTOR DE HUMO</h2>
                <p class="text-gray-600 text-sm md:text-base">
                    Prevención <span class="text-red-600 font-bold">temprana de incendios</span>
                </p>
            </div>
            <a href="#" class="inline-flex items-center text-gray-700 font-semibold hover:text-red-600 transition-colors">
                Ver todo el catálogo
                <svg class="w-5 h-5 ml-2 bg-red-600 text-white rounded-full p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $detectores = [
                ['img' => 'width=500,height=500.png',      'nombre' => 'Kidde PI2010'],
                ['img' => '1828804-first-alert-sco5.webp', 'nombre' => 'First Alert SC0501B'],
                ['img' => 'BRK-7010B-2.jpg',               'nombre' => 'Honeywell SA300'],
                ['img' => 'BR-7010B_alt2.jpg',             'nombre' => 'BRK Electronics PA010'],
            ];
            foreach ($detectores as $d): ?>
            <div class="bg-white rounded-lg p-6 hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div>
                    <img src="<?= $base_url ?>/img/<?= htmlspecialchars($d['img']) ?>"
                         alt="<?= htmlspecialchars($d['nombre']) ?>"
                         class="w-full h-32 object-contain mb-4">
                    <p class="text-sm font-semibold text-gray-900 mb-4 text-center">
                        <?= htmlspecialchars($d['nombre']) ?>
                    </p>
                </div>
                <button class="border border-gray-300 rounded-full px-6 py-2 text-sm font-semibold hover:bg-red-600 hover:text-white hover:border-red-600 transition-all w-full">
                    Cotizar Ahora
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl md:text-3xl font-bold text-gray-900">ÚLTIMOS AGREGADOS</h2>
            <div class="hidden sm:flex space-x-2">
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php
            $ultimos = [
                ['img' => '054-rociador-sprinkler.jpg',                                          'nombre' => 'Rociador Automático'],
                ['img' => '3000432_-_REG_25-60-IGG-320_CO2-RGB.jpg',                            'nombre' => 'Rociador Gas Inerte'],
                ['img' => 'istockphoto-536036697-612x612.jpg',                                   'nombre' => 'Manguera Extinción'],
                ['img' => 'D_NQ_NP_2X_648498-MLV102079027440_122025-T.webp',                   'nombre' => 'Cinta Exterior'],
                ['img' => 'Panel-de-Control-de-Alarma-de-Incendio-de-Zona-Convencional-8.webp', 'nombre' => 'Panel de Control'],
            ];
            foreach ($ultimos as $u): ?>
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-lg transition-shadow">
                <img src="<?= $base_url ?>/img/<?= htmlspecialchars($u['img']) ?>"
                     alt="<?= htmlspecialchars($u['nombre']) ?>"
                     class="w-full h-32 object-contain mb-3">
                <p class="text-sm font-semibold text-gray-900 mb-2 truncate"><?= htmlspecialchars($u['nombre']) ?></p>
                <button class="border border-gray-300 rounded-full px-4 py-1.5 text-xs font-semibold hover:bg-red-600 hover:text-white transition-colors w-full">
                    Cotizar
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<footer class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 text-center md:text-left">
            <div class="col-span-1 md:col-span-2">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                     alt="InstalFuego Logo" class="h-10 object-contain mb-4 mx-auto md:mx-0">
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto md:mx-0">
                    Especialistas en sistemas de seguridad contra incendios. Protegemos lo que más valoras con tecnología certificada.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-900 mb-4">Productos</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-red-600 transition-colors">Extintores</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Detectores de Humo</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Sistemas de Riego</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-900 mb-4">Empresa</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-red-600 transition-colors">Acerca de</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Servicios</a></li>
                    <li><a href="<?= $base_url ?>/login" class="hover:text-red-600 transition-colors">Acceder</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-300 mt-12 pt-8 flex flex-col md:flex-row justify-center items-center text-sm text-gray-600">
            <p>© 2026 InstalFuego. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    });
</script>

<script src="<?= $base_url ?>/js/main.js"></script>
</body>
</html>