<?php

$nav = $active_nav ?? 'dashboard';

$links = [
    'dashboard'    => ['href' => $base_url . '/dashboard',            'label' => 'Dashboard',          'icon' => 'M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z'],
    'cotizaciones' => ['href' => $base_url . '/dashboard/cotizaciones','label' => 'Pedidos', 'icon' => 'M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z'],
    'productos'    => ['href' => $base_url . '/dashboard/productos',  'label' => 'Gest. Productos',   'icon' => 'M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z'],
    'categorias'   => ['href' => $base_url . '/dashboard/categorias', 'label' => 'Gest. Categoría',  'icon' => 'M4 3a2 2 0 100 4h12a2 2 0 100-4H4zM3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z'],
    'servicios'    => ['href' => $base_url . '/dashboard/servicios',  'label' => 'Servicios',         'icon' => 'M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z'],
    'reportes'     => ['href' => $base_url . '/dashboard/reportes',   'label' => 'Reportes',          'icon' => 'M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z'],
];

$initials = strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 2));
$userName  = htmlspecialchars($_SESSION['user_name']  ?? 'Administrador');
$userEmail = htmlspecialchars($_SESSION['user_email'] ?? '');
?>

<aside class="w-64 bg-white border-r border-gray-200 flex flex-col">

    <!-- Logo -->
    <div class="h-24 flex items-center justify-center px-6 border-b border-gray-200">
        <a href="<?= $base_url ?>/">
            <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                 alt="InstalFuego Logo"
                 class="h-20 object-contain">
        </a>
    </div>

    <!-- Navegación -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <?php foreach ($links as $key => $link): ?>
            <?php $isActive = ($nav === $key); ?>
            <a href="<?= $link['href'] ?>"
               class="flex items-center px-4 py-3 rounded-lg font-medium transition-colors
                      <?= $isActive
                            ? 'text-red-700 bg-red-50'
                            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' ?>">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="<?= $link['icon'] ?>"/>
                </svg>
                <?= $link['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Usuario + Cerrar Sesión -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center gap-3">

            <!-- Avatar con iniciales -->
            <div class="w-9 h-9 rounded-full bg-red-700 text-white flex items-center
                        justify-center font-bold text-sm flex-shrink-0">
                <?= $initials ?>
            </div>

            <!-- Nombre y email -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate"><?= $userName ?></p>
                <p class="text-xs text-gray-500 truncate"><?= $userEmail ?></p>
            </div>

            <!-- Botón cerrar sesión -->
            <a href="<?= $base_url ?>/logout"
               title="Cerrar Sesión"
               class="text-gray-400 hover:text-red-600 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>

        </div>
    </div>

</aside>
