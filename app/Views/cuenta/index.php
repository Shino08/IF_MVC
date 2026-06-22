<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4">
                <?php require_once dirname(__DIR__) . '/layouts/cuenta-sidebar.php'; ?>
            </div>
            
            <!-- Content -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Resumen de Cuenta</h2>
                        <p class="text-lg text-gray-600">Hola de nuevo, <strong class="font-bold text-gray-900"><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></strong> 👋</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="<?= $base_url ?>/mis-cotizaciones" class="group block p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-red-300 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Mis Solicitudes</h3>
                        </div>
                        <p class="text-gray-500 text-sm">Visualiza el historial y estado de todas las cotizaciones que has solicitado.</p>
                    </a>

                    <a href="<?= $base_url ?>/cuenta/perfil" class="group block p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-red-300 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gray-50 text-gray-600 rounded-xl flex items-center justify-center group-hover:bg-gray-800 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Editar Perfil</h3>
                        </div>
                        <p class="text-gray-500 text-sm">Actualiza tu información personal, empresa y datos de contacto.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
