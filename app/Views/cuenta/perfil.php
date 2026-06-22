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
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-8 border-b border-gray-100 pb-4">Mi Perfil</h2>
                    
                    <?php if (!empty($_SESSION['success_msg'])): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center space-x-2" role="alert">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="block sm:inline font-medium"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['error_msg'])): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center space-x-2" role="alert">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="block sm:inline font-medium"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $base_url ?>/cuenta/perfil" method="POST" class="space-y-6 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre</label>
                                <input type="text" name="nombre" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Apellido</label>
                                <input type="text" name="apellido" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" value="<?= htmlspecialchars($user['apellido']) ?>" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Cédula/RIF</label>
                                <input type="text" name="cedula" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" value="<?= htmlspecialchars($user['cedula']) ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Empresa (Opcional)</label>
                                <input type="text" name="empresa" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" value="<?= htmlspecialchars($user['empresa'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Teléfono</label>
                                <input type="text" name="telefono" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" value="<?= htmlspecialchars($user['telefono']) ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                        </div>
                        <div class="pt-6 mt-8 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="bg-gradient-to-r from-red-600 to-red-800 text-white font-bold py-3.5 px-8 rounded-2xl hover:from-red-700 hover:to-red-900 transition-all duration-300 shadow-lg shadow-red-200 focus:ring-4 focus:ring-red-100 w-full sm:w-auto">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
