<?php 
$current_route = $_SERVER['REQUEST_URI'] ?? '';
$is_perfil = strpos($current_route, 'cuenta/perfil') !== false;
$is_seguridad = strpos($current_route, 'cuenta/seguridad') !== false;
$is_cotizaciones = strpos($current_route, 'mis-cotizaciones') !== false;
$is_resumen = strpos($current_route, 'cuenta') !== false && !$is_perfil && !$is_seguridad;

// User info for the avatar
$userName = $_SESSION['user_name'] ?? 'Usuario';
$userEmail = $_SESSION['user_email'] ?? '';
$initial = substr($userName, 0, 1);
?>
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 rounded-full mix-blend-multiply filter blur-2xl opacity-60 transform translate-x-1/2 -translate-y-1/2"></div>
    <div class="relative z-10">
        <div class="flex items-center space-x-4 mb-8">
            <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-red-800 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-lg shadow-red-200 transform rotate-[-5deg]">
                <div class="transform rotate-[5deg]"><?= htmlspecialchars($initial) ?></div>
            </div>
            <div class="overflow-hidden">
                <p class="font-extrabold text-gray-900 truncate text-lg"><?= htmlspecialchars($userName) ?></p>
                <?php if($userEmail): ?>
                    <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($userEmail) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <nav class="flex flex-col space-y-3">
            <a href="<?= $base_url ?? '' ?>/cuenta" class="flex items-center space-x-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-300 <?= $is_resumen ? 'bg-red-50 text-red-700 shadow-sm border border-red-100' : 'text-gray-600 hover:bg-gray-50 hover:text-red-600 border border-transparent hover:border-gray-100' ?>">
                <svg class="w-5 h-5 <?= $is_resumen ? 'text-red-600' : 'text-gray-400' ?> transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Resumen</span>
            </a>
            <a href="<?= $base_url ?? '' ?>/cuenta/perfil" class="flex items-center space-x-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-300 <?= $is_perfil ? 'bg-red-50 text-red-700 shadow-sm border border-red-100' : 'text-gray-600 hover:bg-gray-50 hover:text-red-600 border border-transparent hover:border-gray-100' ?>">
                <svg class="w-5 h-5 <?= $is_perfil ? 'text-red-600' : 'text-gray-400' ?> transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Mi Perfil</span>
            </a>
            <a href="<?= $base_url ?? '' ?>/cuenta/seguridad" class="flex items-center space-x-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-300 <?= $is_seguridad ? 'bg-red-50 text-red-700 shadow-sm border border-red-100' : 'text-gray-600 hover:bg-gray-50 hover:text-red-600 border border-transparent hover:border-gray-100' ?>">
                <svg class="w-5 h-5 <?= $is_seguridad ? 'text-red-600' : 'text-gray-400' ?> transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Seguridad</span>
            </a>
            <a href="<?= $base_url ?? '' ?>/mis-pedidos" class="flex items-center space-x-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-300 <?= $is_cotizaciones ? 'bg-red-50 text-red-700 shadow-sm border border-red-100' : 'text-gray-600 hover:bg-gray-50 hover:text-red-600 border border-transparent hover:border-gray-100' ?>">
                <svg class="w-5 h-5 <?= $is_cotizaciones ? 'text-red-600' : 'text-gray-400' ?> transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Mis Pedidos</span>
            </a>
        </nav>
        <div class="mt-8 pt-6 border-t border-gray-100">
            <a href="<?= $base_url ?? '' ?>/logout" class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>
</div>
