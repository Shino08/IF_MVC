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
                        <a href="<?= $base_url ?>/cuenta/seguridad" class="px-4 py-2 rounded-md font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">Seguridad</a>
                        <a href="<?= $base_url ?>/mis-cotizaciones" class="px-4 py-2 rounded-md font-medium bg-red-50 text-red-700">Mis Solicitudes</a>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">Historial de Cotizaciones</h2>
                    
                    <?php if (!empty($_SESSION['success_msg'])): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <span class="block sm:inline"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($cotizaciones)): ?>
                        <div class="bg-white rounded-xl p-10 text-center border border-gray-100 shadow-sm mt-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aún no tienes solicitudes</h3>
                            <p class="text-gray-500 mb-6">Tu historial de cotizaciones está vacío. Explora nuestro catálogo y empieza a armar tu primera solicitud.</p>
                            <a href="<?= $base_url ?? '' ?>/catalogo" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm hover:shadow-md transform hover:-translate-y-0.5 duration-200">
                                Ir al Catálogo
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># Solicitud</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($cotizaciones as $cot): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars((string)$cot['id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($cot['fecha_solicitud'])) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <?= htmlspecialchars(strtoupper($cot['estado_nombre'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="<?= $base_url ?>/mis-cotizaciones/<?= $cot['id'] ?>" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">Ver Detalle</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
