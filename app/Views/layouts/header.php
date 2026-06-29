<?php
$cotizacionCount = 0;
if (isset($_SESSION['user_id']) && (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1)) {
    if (class_exists('\App\Models\CotizacionesModel')) {
        $cotModel = new \App\Models\CotizacionesModel();
        $borrador = $cotModel->getBorradorByUserId((int)$_SESSION['user_id']);
        if ($borrador) {
            $detalles = $cotModel->getDetalles((int)$borrador['id']);
            $cotizacionCount = count($detalles);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'InstalFuego — Sistemas de Seguridad Contra Incendios') ?></title>
    
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/output.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/styles.css?v=<?= time() ?>">
</head>
<body class="bg-white text-gray-900 font-sans">

<div class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between flex-wrap">
            <div class="flex items-center shrink-0">
                <a href="<?= $base_url ?? '' ?>/">
                    <img src="<?= $base_url ?? '' ?>/img/Photoroom-20251106_165742.png" alt="InstalFuego Logo" class="h-10 md:h-12 object-contain">
                </a>
            </div>

            <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:text-red-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
            </button>

            <div class="hidden md:flex flex-1 max-w-2xl mx-4 lg:mx-8 order-last md:order-none w-full md:w-auto mt-3 md:mt-0" id="desktop-search">
                <div class="relative w-full">
                    <input type="text" placeholder="Buscar productos, marcas y más..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                        <a href="<?= $base_url ?? '' ?>/dashboard" class="px-3 py-2 bg-red-700 text-white text-xs lg:text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors whitespace-nowrap shadow-sm">Panel Admin</a>
                        <a href="<?= $base_url ?? '' ?>/logout" class="text-sm text-gray-600 hover:text-red-700 font-medium">Salir</a>
                    <?php else: ?>
                        <div class="flex items-center space-x-6">
                            <a href="<?= $base_url ?? '' ?>/pedido/actual" class="relative hover:text-red-600 transition-colors">
                                <svg class="w-6 h-6 text-gray-600 hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <?php if ($cotizacionCount > 0): ?>
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-sm">
                                    <?= $cotizacionCount ?>
                                </span>
                                <?php endif; ?>
                            </a>
                            <div class="relative group cursor-pointer">
                                <div class="w-10 h-10 bg-red-700 text-white rounded-full flex items-center justify-center font-semibold uppercase shadow-sm border-2 border-transparent group-hover:border-red-200 transition-all">
                                    <?= substr($_SESSION['user_name'] ?? 'U', 0, 1) ?>
                                </div>
                                <div class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg py-2 z-50 hidden group-hover:block border border-gray-100">
                                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                        <p class="text-sm font-bold text-gray-900 truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></p>
                                    </div>
                                    <a href="<?= $base_url ?? '' ?>/cuenta" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors">Mi Perfil</a>
                                    <a href="<?= $base_url ?? '' ?>/mis-pedidos" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors">Mis Pedidos / Presupuestos</a>
                                    <a href="<?= $base_url ?? '' ?>/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors border-t border-gray-50 mt-1">Cerrar Sesión</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= $base_url ?? '' ?>/login" class="text-sm font-medium text-gray-700 hover:text-red-700 whitespace-nowrap">Ingresar</a>
                    <a href="<?= $base_url ?? '' ?>/register" class="px-3 py-2 bg-red-700 text-white text-xs lg:text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors whitespace-nowrap shadow-sm">Registro</a>
                <?php endif; ?>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden mt-4 border-t pt-4 pb-2">
            <div class="relative mb-4">
                <input type="text" placeholder="Buscar..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
            </div>
            <div class="flex flex-col space-y-3">
                <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600">CATÁLOGO</a>
                <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600">EXTINGUIDORES</a>
                <hr class="border-gray-100">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="py-2 flex items-center space-x-3 bg-gray-50 px-3 rounded-lg">
                        <div class="w-8 h-8 bg-red-700 text-white rounded-full flex items-center justify-center font-semibold uppercase text-xs">
                            <?= substr($_SESSION['user_name'] ?? 'U', 0, 1) ?>
                        </div>
                        <span class="font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
                    </div>
                    <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                        <a href="<?= $base_url ?? '' ?>/dashboard" class="text-red-700 font-semibold pl-2">Panel Admin</a>
                    <?php else: ?>
                        <a href="<?= $base_url ?? '' ?>/mis-pedidos" class="text-gray-700 font-medium hover:text-red-600 pl-2">Mis Presupuestos</a>
                    <?php endif; ?>
                    <a href="<?= $base_url ?? '' ?>/logout" class="text-red-600 font-semibold pl-2 pt-2 border-t border-gray-100">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="<?= $base_url ?? '' ?>/login" class="text-gray-700 font-medium pl-2">Ingresar</a>
                    <a href="<?= $base_url ?? '' ?>/register" class="text-red-700 font-semibold pl-2">Registro</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<nav class="hidden md:block bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center space-x-4 lg:space-x-8 py-4 overflow-x-auto text-sm lg:text-base whitespace-nowrap">
            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600 transition-colors">CATÁLOGO</a>
            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600 transition-colors">DETECCIÓN DE HUMO</a>
            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EXTINGUIDORES</a>
            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600 transition-colors">RIEGO AUTOMÁTICO</a>
            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600 transition-colors">EPP</a>
            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-gray-700 font-medium hover:text-red-600 transition-colors">SERVICIOS</a>
        </div>
    </div>
</nav>
