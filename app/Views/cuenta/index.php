<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <nav class="flex flex-col space-y-1">
                        <a href="<?= $base_url ?>/cuenta" class="px-4 py-2 rounded-md font-medium bg-red-50 text-red-700">Resumen</a>
                        <a href="<?= $base_url ?>/cuenta/perfil" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Mi Perfil</a>
                        <a href="<?= $base_url ?>/cuenta/seguridad" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Seguridad</a>
                        <a href="<?= $base_url ?>/mis-cotizaciones" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Mis Solicitudes</a>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">Resumen de mi cuenta</h2>
                    <p class="text-gray-700 mb-4">Bienvenido, <strong class="font-bold text-gray-900"><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></strong>.</p>
                    <p class="text-gray-600">Desde el panel de control de tu cuenta, puedes visualizar tus solicitudes recientes, y editar tu contraseña y los detalles de tu cuenta.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
