<?php $title = 'Reportes'; $active_nav = 'reportes'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reportes</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Estadísticas y análisis del sistema</p>
                </div>
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar PDF
                </button>
            </div>
        </header>

        <div class="p-8 space-y-6">

            <!-- KPIs resumen -->
            <div class="grid grid-cols-3 gap-6">
                <?php $resumen = [
                    ['label' => 'Cotizaciones este mes', 'value' => '24', 'change' => '+12%', 'up' => true],
                    ['label' => 'Cotizaciones aprobadas', 'value' => '18', 'change' => '75%', 'up' => true],
                    ['label' => 'Tiempo respuesta promedio', 'value' => '2.4h', 'change' => '-15%', 'up' => true],
                ]; foreach ($resumen as $r): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <p class="text-gray-500 text-sm mb-2"><?= $r['label'] ?></p>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold text-gray-900"><?= $r['value'] ?></span>
                        <span class="text-sm font-semibold <?= $r['up'] ? 'text-green-600' : 'text-red-600' ?> bg-green-50 px-2 py-0.5 rounded-full">
                            <?= $r['change'] ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Tabla de actividad -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-5">Actividad Reciente</h2>
                <div class="space-y-3">
                    <?php $actividad = [
                        ['accion' => 'Nueva cotización recibida', 'detalle' => 'Juan Pérez - Extintor CO2 5Kg', 'hora' => 'Hace 5 min', 'tipo' => 'verde'],
                        ['accion' => 'Producto actualizado',      'detalle' => 'Rociador Automático Sprinkler', 'hora' => 'Hace 20 min','tipo' => 'azul'],
                        ['accion' => 'Cotización aprobada',       'detalle' => 'María González - COT-002',      'hora' => 'Hace 1h',    'tipo' => 'verde'],
                        ['accion' => 'Nueva categoría creada',    'detalle' => 'EPP — Equipos de Protección',   'hora' => 'Hace 3h',    'tipo' => 'morado'],
                    ];
                    $dot = ['verde' => 'bg-green-500', 'azul' => 'bg-blue-500', 'morado' => 'bg-purple-500'];
                    foreach ($actividad as $a): ?>
                    <div class="flex items-start space-x-4 py-2 border-b border-gray-100 last:border-0">
                        <div class="mt-1.5 w-2.5 h-2.5 <?= $dot[$a['tipo']] ?> rounded-full flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900"><?= $a['accion'] ?></p>
                            <p class="text-xs text-gray-500"><?= $a['detalle'] ?></p>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0"><?= $a['hora'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
