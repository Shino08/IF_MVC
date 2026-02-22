<?php $title = 'Solicitudes de Cotización'; $active_nav = 'cotizaciones'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Solicitudes de Cotización</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Gestiona todas las solicitudes de clientes</p>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-6">
            <!-- Filtros de estado -->
            <div class="flex items-center space-x-2">
                <?php $estados = ['Todas' => '', 'Pendientes' => 'pendiente', 'Aprobadas' => 'aprobada', 'Enviadas' => 'enviada']; ?>
                <?php foreach ($estados as $label => $val): ?>
                <button class="px-4 py-2 text-sm font-medium rounded-full border
                               <?= ($filtro ?? '') === $val
                                   ? 'bg-red-700 text-white border-red-700'
                                   : 'bg-white text-gray-600 border-gray-300 hover:border-red-400' ?>">
                    <?= $label ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto / Servicio</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $cotizaciones = $cotizaciones ?? [
                            ['id' => 'COT-001', 'cliente' => 'Juan Pérez',     'producto' => 'Extintor CO2 5Kg',           'fecha' => '22 Feb 2026', 'estado' => 'pendiente'],
                            ['id' => 'COT-002', 'cliente' => 'María González', 'producto' => 'Sistema de Riego Automático', 'fecha' => '21 Feb 2026', 'estado' => 'aprobada'],
                            ['id' => 'COT-003', 'cliente' => 'Carlos Ruiz',    'producto' => 'Detector de Humo Kidde',      'fecha' => '20 Feb 2026', 'estado' => 'enviada'],
                            ['id' => 'COT-004', 'cliente' => 'Ana Torres',     'producto' => 'Panel de Alarma 8 Zonas',     'fecha' => '19 Feb 2026', 'estado' => 'pendiente'],
                        ];
                        $badge = ['pendiente' => 'bg-yellow-100 text-yellow-700', 'aprobada' => 'bg-green-100 text-green-700', 'enviada' => 'bg-blue-100 text-blue-700'];
                        foreach ($cotizaciones as $cot): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-gray-500 text-xs"><?= $cot['id'] ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($cot['cliente']) ?></td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($cot['producto']) ?></td>
                            <td class="px-6 py-4 text-gray-500"><?= $cot['fecha'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge[$cot['estado']] ?>">
                                    <?= ucfirst($cot['estado']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= $base_url ?>/dashboard/cotizaciones/detalle/<?= $cot['id'] ?>"
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
