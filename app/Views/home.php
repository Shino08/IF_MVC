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

<!-- ── Header / Navegación ───────────────────────────────────────────── -->
<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Top bar -->
        <div class="flex items-center justify-between py-3">
            <a href="<?= $base_url ?>/">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png" alt="InstalFuego Logo" class="h-12 object-contain">
            </a>

            <div class="flex-1 max-w-xl mx-8">
                <div class="relative">
                    <input type="text" placeholder="Buscar productos, marcas y más…"
                           class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <?php if (isset($logged_in) && $logged_in): ?>
                    <a href="<?= $base_url ?>/dashboard"
                       class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Panel Admin
                    </a>
                    <a href="<?= $base_url ?>/logout" class="text-sm text-gray-600 hover:text-red-700 font-medium transition-colors">Salir</a>
                <?php else: ?>
                    <a href="<?= $base_url ?>/login"
                       class="text-sm font-medium text-gray-700 hover:text-red-700 transition-colors">Iniciar Sesión</a>
                    <a href="<?= $base_url ?>/register"
                       class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                        Registrarse
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Categorías nav -->
        <nav class="flex items-center space-x-8 pb-3 text-sm">
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">Detección de Humo</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">Extinguidores</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">Sistemas de Riego</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EPP</a>
            <a href="#" class="text-gray-700 font-medium hover:text-red-600 transition-colors">Servicios</a>
        </nav>
    </div>
</header>

<!-- ── Hero ─────────────────────────────────────────────────────────── -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full mb-4 uppercase tracking-wider">
                    Líder en Colombia
                </span>
                <h1 class="text-5xl font-extrabold text-gray-900 leading-tight mb-5">
                    Sistemas de<br>
                    <span class="text-red-700">Seguridad Contra<br>Incendios</span>
                </h1>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    Un sistema avanzado diseñado para detectar y extinguir incendios rápidamente,
                    <strong class="text-red-600">salvando vidas</strong> y protegiendo tus bienes.
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
                     alt="Sistema contra incendios InstalFuego" class="w-full max-w-md object-contain drop-shadow-xl">
            </div>
        </div>
    </div>
</section>

<!-- ── Explorar Productos ────────────────────────────────────────────── -->
<section id="productos" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-1">Explorar Productos y Servicios</h2>
            <p class="text-gray-500">Explora nuestra gama de soluciones de seguridad <span class="text-red-600 font-semibold">contra incendios</span></p>
        </div>

        <div class="grid grid-cols-4 gap-6">
            <?php
            $destacados = [
                ['img' => 'extintor-espuma-afffar-3-10-lt-ab-mod-em-10k.jpg', 'cat' => 'Extinguidor',    'nombre' => 'Extintor de Espuma AFFF'],
                ['img' => 'bombeo-detalle-bci-min.png',                        'cat' => 'Extinguidor',    'nombre' => 'Bomba Contra Incendio'],
                ['img' => 'C2-REC.jpg',                                         'cat' => 'Servicio',       'nombre' => 'Recarga de Extintor'],
                ['img' => 'lampara-sovica.jpg',                                 'cat' => 'Iluminación',    'nombre' => 'Iluminación de Emergencia'],
            ];
            foreach ($destacados as $p): ?>
            <div class="bg-gray-50 rounded-2xl p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 group">
                <img src="<?= $base_url ?>/img/<?= urlencode($p['img']) ?>"
                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                     class="w-full h-44 object-contain mb-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1"><?= $p['cat'] ?></p>
                <p class="text-sm font-semibold text-gray-900 mb-3"><?= $p['nombre'] ?></p>
                <button class="w-full border border-gray-300 rounded-full py-2 text-xs font-semibold text-gray-700
                               hover:bg-red-700 hover:text-white hover:border-red-700 transition-colors">
                    Cotizar Ahora
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Detectores de Humo ───────────────────────────────────────────── -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Detector de Humo</h2>
                <p class="text-gray-500">Dispositivo esencial para la prevención <span class="text-red-600">temprana de incendios</span></p>
            </div>
            <a href="#" class="flex items-center text-sm font-semibold text-gray-700 hover:text-red-700 transition-colors">
                Ver más
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-4 gap-6">
            <?php
            $detectores = [
                ['img' => 'width=500,height=500.png',         'nombre' => 'Kidde PI2010'],
                ['img' => '1828804-first-alert-sco5.webp',    'nombre' => 'First Alert SC0501B'],
                ['img' => 'BRK-7010B-2.jpg',                  'nombre' => 'Honeywell SA300'],
                ['img' => 'BR-7010B_alt2.jpg',                'nombre' => 'BRK Electronics PA010IAC'],
            ];
            foreach ($detectores as $d): ?>
            <div class="bg-white rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                <img src="<?= $base_url ?>/img/<?= urlencode($d['img']) ?>"
                     alt="<?= htmlspecialchars($d['nombre']) ?>"
                     class="w-full h-32 object-contain mb-4">
                <p class="text-sm font-semibold text-gray-900 mb-4 text-center"><?= $d['nombre'] ?></p>
                <button class="w-full border border-gray-300 rounded-full py-2 text-xs font-semibold text-gray-700
                               hover:bg-red-700 hover:text-white hover:border-red-700 transition-colors">
                    Cotizar Ahora
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Últimos productos ─────────────────────────────────────────────── -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Últimos Productos Agregados</h2>

        <div class="grid grid-cols-5 gap-5">
            <?php
            $ultimos = [
                ['img' => '054-rociador-sprinkler.jpg',                        'nombre' => 'Rociador Automático Sprinkler'],
                ['img' => '3000432_-_REG_25-60-IGG-320_CO2-RGB.jpg',          'nombre' => 'Rociador de Gas Inerte'],
                ['img' => 'istockphoto-536036697-612x612.jpg',                 'nombre' => 'Manguera de Extinción'],
                ['img' => 'D_NQ_NP_2X_648498-MLV102079027440_122025-T.webp', 'nombre' => 'Extintor Pared Exterior 10Lb'],
                ['img' => 'Panel-de-Control-de-Alarma-de-Incendio-de-Zona-Convencional-8.webp', 'nombre' => 'Panel de Control de Alarma'],
            ];
            foreach ($ultimos as $u): ?>
            <div class="bg-white rounded-xl p-4 border border-gray-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                <img src="<?= $base_url ?>/img/<?= urlencode($u['img']) ?>"
                     alt="<?= htmlspecialchars($u['nombre']) ?>"
                     class="w-full h-32 object-contain mb-3">
                <p class="text-xs font-semibold text-gray-900 mb-3 line-clamp-2"><?= $u['nombre'] ?></p>
                <button class="w-full border border-gray-300 rounded-full py-1.5 text-xs font-semibold text-gray-700
                               hover:bg-red-700 hover:text-white hover:border-red-700 transition-colors">
                    Cotizar Ahora
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Footer ───────────────────────────────────────────────────────── -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-4 gap-8 mb-10">
            <div class="col-span-2">
                <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                     alt="InstalFuego Logo" class="h-10 object-contain mb-4 brightness-0 invert">
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Especialistas en sistemas de seguridad contra incendios. Protegemos lo que más valoras.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Productos</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Extintores</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Detectores de Humo</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Sistemas de Riego</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">EPP</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Empresa</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Acerca de</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Servicios</a></li>
                    <li><a href="<?= $base_url ?>/login" class="hover:text-white transition-colors">Acceder</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 flex justify-between items-center text-xs text-gray-500">
            <div class="flex space-x-6">
                <a href="#" class="hover:text-gray-300 transition-colors">Términos y Condiciones</a>
                <a href="#" class="hover:text-gray-300 transition-colors">Aviso de Privacidad</a>
            </div>
            <p>© 2026 InstalFuego. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script src="<?= $base_url ?>/js/main.js"></script>
</body>
</html>
