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
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-8 border-b border-gray-100 pb-4">Mis Pedidos</h2>
                    
                    <?php if (!empty($_SESSION['success_msg'])): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                            <span class="block sm:inline"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($carritos)): ?>
                        <div class="bg-white rounded-xl p-10 text-center border border-gray-100 shadow-sm mt-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aún no tienes pedidos</h3>
                            <p class="text-gray-500 mb-6">Tu historial de pedidos está vacío. Explora nuestro catálogo y realiza tu primera compra.</p>
                            <a href="<?= $base_url ?? '' ?>/catalogo" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm hover:shadow-md transform hover:-translate-y-0.5 duration-200">
                                Ir al Catálogo
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># Pedido</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $estadoMap = [
                                        'borrador'               => ['label' => 'Borrador',           'class' => 'bg-gray-100 text-gray-600'],
                                        'pendiente_revision'     => ['label' => 'Recibido',            'class' => 'bg-blue-100 text-blue-800'],
                                        'enviada'                => ['label' => 'Recibido',            'class' => 'bg-blue-100 text-blue-800'],
                                        'aceptada por el cliente'=> ['label' => 'Pendiente de Pago',  'class' => 'bg-yellow-100 text-yellow-800'],
                                        'rechazada'              => ['label' => 'Cancelado',           'class' => 'bg-red-100 text-red-800'],
                                        'vencida'                => ['label' => 'Cancelado',           'class' => 'bg-red-100 text-red-800'],
                                    ];
                                    $estadoPedidoMap = [
                                        'pendiente_pago'   => ['label' => 'Pendiente de Pago',  'class' => 'bg-yellow-100 text-yellow-800'],
                                        'pago_por_validar' => ['label' => 'Pago en Revisión',   'class' => 'bg-blue-100 text-blue-800'],
                                        'procesando'       => ['label' => 'Preparando Pedido',  'class' => 'bg-purple-100 text-purple-800'],
                                        'despachado'       => ['label' => 'En Camino',          'class' => 'bg-indigo-100 text-indigo-800'],
                                        'entregado'        => ['label' => 'Entregado',          'class' => 'bg-green-100 text-green-800'],
                                        'cancelado'        => ['label' => 'Cancelado',          'class' => 'bg-red-100 text-red-800'],
                                    ];
                                    foreach ($carritos as $cot):
                                        $pedidoEstado = $cot['estado_pedido'] ?? null;
                                        if ($pedidoEstado && isset($estadoPedidoMap[$pedidoEstado])) {
                                            $badgeInfo = $estadoPedidoMap[$pedidoEstado];
                                        } else {
                                            $key = strtolower($cot['estado_nombre'] ?? '');
                                            $badgeInfo = $estadoMap[$key] ?? ['label' => strtoupper($key), 'class' => 'bg-gray-100 text-gray-800'];
                                        }
                                        $pedidoNum = '#PED-' . date('Y', strtotime($cot['fecha_solicitud'])) . '-' . str_pad((string)$cot['id'], 4, '0', STR_PAD_LEFT);
                                    ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"><?= htmlspecialchars($pedidoNum) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($cot['fecha_solicitud'])) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                <?php if (!empty($cot['total']) && (float)$cot['total'] > 0): ?>
                                                    $<?= number_format((float)$cot['total'], 2) ?>
                                                <?php else: ?>
                                                    <span class="text-gray-400">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $badgeInfo['class'] ?>">
                                                    <?= htmlspecialchars($badgeInfo['label']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="<?= $base_url ?>/mis-pedidos/<?= $cot['id'] ?>" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors">Ver Detalle</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    </div> <!-- relative z-10 -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
