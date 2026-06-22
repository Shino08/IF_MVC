<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <nav class="flex flex-col space-y-1">
                        <a href="<?= $base_url ?>/cuenta" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Resumen</a>
                        <a href="<?= $base_url ?>/cuenta/perfil" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Mi Perfil</a>
                        <a href="<?= $base_url ?>/cuenta/seguridad" class="px-4 py-2 rounded-md font-medium bg-red-50 text-red-700">Seguridad</a>
                        <a href="<?= $base_url ?>/mis-cotizaciones" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Mis Solicitudes</a>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">Seguridad</h2>
                    
                    <?php if (!empty($_SESSION['success_msg'])): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <span class="block sm:inline"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['error_msg'])): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                            <span class="block sm:inline"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $base_url ?>/cuenta/seguridad" method="POST" class="space-y-6 max-w-md">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual</label>
                            <input type="password" name="password_actual" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                            <input type="password" name="password_nueva" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" required minlength="8">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmacion" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" required minlength="8">
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="bg-red-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-red-700 transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Actualizar Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
