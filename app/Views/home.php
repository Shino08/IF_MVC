<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstalFuego — Sistemas de Seguridad Contra Incendios</title>
    <meta name="description" content="InstalFuego: sistemas avanzados de detección y extinción de incendios. Extintores, rociadores, detectores de humo y más.">
    <link rel="stylesheet" href="<?= $base_url ?>/css/output.css">
    <link rel="stylesheet" href="<?= $base_url ?>/css/styles.css">
</head>
<body class="bg-white">

<!-- ───────────────── HEADER ───────────────── -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

        <!-- Logo -->
        <div class="flex items-center">
            <a href="<?= $base_url ?>/">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                     alt="InstalFuego Logo" class="h-12 object-contain">
            </a>
        </div>

        <!-- Buscador -->
        <div class="flex items-center space-x-4 flex-1 max-w-2xl ml-8">
            <div class="flex-1 relative">
                <input type="text" placeholder="Buscar productos, marcas y más"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Acciones de usuario -->
        <div class="flex items-center space-x-4">
            <?php if (isset($logged_in) && $logged_in): ?>
                <a href="<?= $base_url ?>/dashboard"
                   class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Panel Admin
                </a>
                <a href="<?= $base_url ?>/logout"
                   class="text-sm text-gray-600 hover:text-red-700 font-medium transition-colors">
                    Salir
                </a>
            <?php else: ?>
                <a href="<?= $base_url ?>/login"
                   class="text-sm font-medium text-gray-700 hover:text-red-700 transition-colors">
                    Iniciar Sesión
                </a>
                <a href="<?= $base_url ?>/register"
                   class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                    Registrarse
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ───────────────── NAV CATEGORÍAS ───────────────── -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center space-x-8 py-4">
            <button class="flex items-center text-gray-700 font-medium">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </button>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">DETECCIÓN DE HUMO</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EXTINGUIDORES</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">SISTEMAS DE RIEGO AUTOMÁTICO</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EQUIPOS DE PROTECCIÓN PERSONAL (EPP)</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">SERVICIOS</a>
        </div>
    </div>
</nav>

<!-- ───────────────── HERO ───────────────── -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    SISTEMAS DE SEGURIDAD CONTRA INCENDIOS
                </h1>
                <p class="text-gray-600 text-lg mb-6">
                    Un sistema avanzado diseñado para detectar y extinguir incendios rápidamente,
                    <span class="text-red-600">salvando vidas</span> y protegiendo tus bienes.
                </p>
                <div class="flex items-center space-x-4">
                    <a href="#productos"
                       class="px-6 py-3 bg-red-700 text-white font-semibold rounded-xl hover:bg-red-800 transition-colors">
                        Ver Productos
                    </a>
                    <a href="<?= $base_url ?>/cotizar"
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-red-500 hover:text-red-600 transition-colors">
                        Cotizar Ahora
                    </a>
                </div>
            </div>
            <div class="flex justify-center">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                     alt="Sistema contra incendios InstalFuego"
                     class="w-full max-w-md object-contain">
            </div>
        </div>
    </div>
</section>

<!-- ───────────────── PRODUCTOS DESTACADOS ───────────────── -->
<section id="productos" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">EXPLORAR PRODUCTOS Y SERVICIOS</h2>
        <p class="text-gray-600 mb-1">Explora Nuestra Gama de Soluciones de Seguridad</p>
        <p class="text-red-600 font-semibold mb-8">Contra Incendios</p>

        <div class="grid grid-cols-4 gap-6">
            <?php
            $destacados = [
                ['img' => 'extintor-espuma-afffar-3-10-lt-ab-mod-em-10k.jpg', 'cat' => 'Extinguidor',  'nombre' => 'Extintor de Espuma'],
                ['img' => 'bombeo-detalle-bci-min.png',                        'cat' => 'Extinguidor',  'nombre' => 'Bomba Contra Incendio'],
                ['img' => 'C2-REC.jpg',                                         'cat' => 'Servicio',     'nombre' => 'Recarga tu extintor hoy y sigue protegido.'],
                ['img' => 'lampara-sovica.jpg',                                 'cat' => 'Iluminación',  'nombre' => 'Iluminación de emergencia'],
            ];
            foreach ($destacados as $p): ?>
            <div class="bg-gray-50 rounded-lg p-6 relative hover:shadow-lg transition-shadow">
                <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </button>
                <img src="<?= $base_url ?>/img/<?= htmlspecialchars($p['img']) ?>"
                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                     class="w-full h-48 object-contain mb-4">
                <p class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($p['cat']) ?></p>
                <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($p['nombre']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ───────────────── DETECTORES DE HUMO ───────────────── -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">DETECTOR DE HUMO</h2>
                <p class="text-gray-600">
                    Un dispositivo esencial para la prevención
                    <span class="text-red-600">temprana de incendios</span>
                </p>
            </div>
            <a href="#" class="flex items-center text-gray-700 font-semibold hover:text-red-600 transition-colors">
                Ver más
                <svg class="w-5 h-5 ml-2 bg-red-600 text-white rounded-full p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-4 gap-6">
            <?php
            $detectores = [
                ['img' => 'width=500,height=500.png',      'nombre' => 'Kidde PI2010'],
                ['img' => '1828804-first-alert-sco5.webp', 'nombre' => 'First Alert SC0501B'],
                ['img' => 'BRK-7010B-2.jpg',               'nombre' => 'Honeywell SA300'],
                ['img' => 'BR-7010B_alt2.jpg',             'nombre' => 'BRK Electronics PA010IAC'],
            ];
            foreach ($detectores as $d): ?>
            <div class="bg-white rounded-lg p-6 hover:shadow-lg transition-shadow">
                <img src="<?= $base_url ?>/img/<?= htmlspecialchars($d['img']) ?>"
                     alt="<?= htmlspecialchars($d['nombre']) ?>"
                     class="w-full h-32 object-contain mb-4">
                <p class="text-sm font-semibold text-gray-900 mb-4 text-center">
                    <?= htmlspecialchars($d['nombre']) ?>
                </p>
                <button class="border border-gray-300 rounded-full px-6 py-2 text-sm font-semibold hover:bg-gray-50 w-full">
                    Cotizar Ahora
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ───────────────── ÚLTIMOS PRODUCTOS ───────────────── -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">ÚLTIMOS PRODUCTOS AGREGADOS</h2>
            <div class="flex space-x-2">
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-6">
            <?php
            $ultimos = [
                ['img' => '054-rociador-sprinkler.jpg',                                          'nombre' => 'Rociador Automático de Agua (Sprinkler System)'],
                ['img' => '3000432_-_REG_25-60-IGG-320_CO2-RGB.jpg',                            'nombre' => 'Rociador de Gas Inerte'],
                ['img' => 'istockphoto-536036697-612x612.jpg',                                   'nombre' => 'Manguera de Extinción de Incendios'],
                ['img' => 'D_NQ_NP_2X_648498-MLV102079027440_122025-T.webp',                   'nombre' => 'Cinta para pared Exterior 10 Lb'],
                ['img' => 'Panel-de-Control-de-Alarma-de-Incendio-de-Zona-Convencional-8.webp', 'nombre' => 'Panel de Control de Alarmas Integrado'],
            ];
            foreach ($ultimos as $u): ?>
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-lg transition-shadow">
                <img src="<?= $base_url ?>/img/<?= htmlspecialchars($u['img']) ?>"
                     alt="<?= htmlspecialchars($u['nombre']) ?>"
                     class="w-full h-32 object-contain mb-3">
                <p class="text-sm font-semibold text-gray-900 mb-2"><?= htmlspecialchars($u['nombre']) ?></p>
                <button class="border border-gray-300 rounded-full px-4 py-1.5 text-xs font-semibold hover:bg-gray-50 w-full">
                    Cotizar Ahora
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ───────────────── FOOTER ───────────────── -->
<footer class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-4 gap-8 mb-10">
            <div class="col-span-2">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                     alt="InstalFuego Logo" class="h-10 object-contain mb-4">
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                    Especialistas en sistemas de seguridad contra incendios. Protegemos lo que más valoras.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-900 mb-4">Productos</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-red-600 transition-colors">Extintores</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Detectores de Humo</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Sistemas de Riego</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">EPP</a></li>
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

        <div class="border-t border-gray-300 mt-12 pt-8 flex justify-center items-center text-sm text-gray-600">
            <p>© 2026 InstalFuego. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script src="<?= $base_url ?>/js/main.js"></script>
</body>
</html>
