<?php $title = 'Dashboard'; $active_nav = 'dashboard'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">

    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto flex flex-col">

        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Resumen general del sistema de Instal Fuego</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="p-2 hover:bg-gray-100 rounded-lg relative">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8">

            <!-- Acciones rápidas -->
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h2>
                <div class="grid grid-cols-2 gap-4">
                    <a href="<?= $base_url ?>/dashboard/productos/agregar"
                       class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-800 font-medium">Agregar Producto</span>
                    </a>
                    <a href="<?= $base_url ?>/dashboard/cotizaciones"
                       class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-800 font-medium">Nueva Cotización</span>
                    </a>
                </div>
            </section>

            <!-- KPIs -->
            <div class="grid grid-cols-4 gap-6">
                <?php
                $kpis = [
                    ['label' => 'Solic. Cotización', 'value' => '24', 'badge' => '+12% vs mes anterior', 'badge_color' => 'text-green-600', 'icon_bg' => 'bg-red-100',    'icon_color' => 'text-red-600',    'icon' => 'M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z'],
                    ['label' => 'Productos Activos',  'value' => '156','badge' => 'En 5 categorías',     'badge_color' => 'text-blue-600',  'icon_bg' => 'bg-blue-100',   'icon_color' => 'text-blue-600',   'icon' => 'M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z'],
                    ['label' => 'Servicios Activos',  'value' => '8',  'badge' => 'Instalación y mant.', 'badge_color' => 'text-purple-600','icon_bg' => 'bg-purple-100', 'icon_color' => 'text-purple-600', 'icon' => 'M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z'],
                    ['label' => 'Total Categorías',   'value' => '10', 'badge' => 'Actualizadas',        'badge_color' => 'text-green-600', 'icon_bg' => 'bg-green-100',  'icon_color' => 'text-green-600',  'icon' => 'M4 3a2 2 0 100 4h12a2 2 0 100-4H4zM3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z'],
                ];
                foreach ($kpis as $kpi): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-gray-500 text-sm mb-1"><?= $kpi['label'] ?></p>
                            <p class="text-4xl font-bold text-gray-900"><?= $kpi['value'] ?></p>
                        </div>
                        <div class="w-11 h-11 <?= $kpi['icon_bg'] ?> rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 <?= $kpi['icon_color'] ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="<?= $kpi['icon'] ?>"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs <?= $kpi['badge_color'] ?> font-medium"><?= $kpi['badge'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Productos por categoría -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-5">Productos por Categoría</h3>
                    <div class="space-y-3">
                        <?php
                        $cats = [
                            ['label' => 'Detección de Incendios', 'count' => 45, 'color' => 'bg-red-500',    'pct' => 29],
                            ['label' => 'Extintores',             'count' => 38, 'color' => 'bg-blue-500',   'pct' => 24],
                            ['label' => 'Sistemas de Riego',      'count' => 32, 'color' => 'bg-green-500',  'pct' => 21],
                            ['label' => 'EPP',                    'count' => 28, 'color' => 'bg-yellow-400', 'pct' => 18],
                            ['label' => 'Otros',                  'count' => 13, 'color' => 'bg-purple-500', 'pct' => 8],
                        ];
                        foreach ($cats as $cat): ?>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <div class="flex items-center">
                                    <span class="w-2.5 h-2.5 <?= $cat['color'] ?> rounded-full mr-2"></span>
                                    <span class="text-gray-700"><?= $cat['label'] ?></span>
                                </div>
                                <span class="font-semibold text-gray-900"><?= $cat['count'] ?> prod.</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full">
                                <div class="h-1.5 <?= $cat['color'] ?> rounded-full" style="width:<?= $cat['pct'] ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Últimas cotizaciones -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-5">Últimas Cotizaciones</h3>
                    <div class="space-y-3">
                        <?php
                        $cotis = [
                            ['name' => 'Juan Pérez',      'date' => 'Hoy, 09:15',        'status' => 'Pendiente', 'sc' => 'bg-yellow-100 text-yellow-700'],
                            ['name' => 'María González',  'date' => 'Hoy, 08:42',        'status' => 'Aprobada',  'sc' => 'bg-green-100 text-green-700'],
                            ['name' => 'Carlos Ruiz',     'date' => 'Ayer, 16:30',       'status' => 'Pendiente', 'sc' => 'bg-yellow-100 text-yellow-700'],
                            ['name' => 'Ana Torres',      'date' => '20 Feb, 11:00',     'status' => 'Enviada',   'sc' => 'bg-blue-100 text-blue-700'],
                        ];
                        foreach ($cotis as $c): ?>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?= $c['name'] ?></p>
                                <p class="text-xs text-gray-400"><?= $c['date'] ?></p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full <?= $c['sc'] ?>"><?= $c['status'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div><!-- /p-8 -->
    </main>
</div>
</body>
</html>
