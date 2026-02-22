<?php $title = 'Servicios'; $active_nav = 'servicios'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Servicios</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Administra los servicios disponibles</p>
                </div>
                <button class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Nuevo Servicio
                </button>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-2 gap-6">
                <?php $servicios = $servicios ?? [
                    ['nombre' => 'Instalación de Sistemas de Detección',  'descripcion' => 'Instalación profesional de detectores de humo, calor y gases.', 'activo' => true],
                    ['nombre' => 'Mantenimiento de Extintores',            'descripcion' => 'Recarga, revisión y certificación de extintores portátiles.',    'activo' => true],
                    ['nombre' => 'Diseño de Sistemas Contra Incendios',    'descripcion' => 'Diseño técnico de sistemas de rociadores y gabinetes.',          'activo' => true],
                    ['nombre' => 'Capacitación en Seguridad',              'descripcion' => 'Entrenamiento para brigadas de emergencia y uso de extintores.', 'activo' => false],
                    ['nombre' => 'Asesoría Técnica',                       'descripcion' => 'Consultoría especializada para cumplimiento de normas NFPA.',    'activo' => true],
                    ['nombre' => 'Inspección y Certificación',             'descripcion' => 'Inspecciones periódicas y emisión de certificados de seguridad.','activo' => true],
                ];
                foreach ($servicios as $s): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-gray-900 flex-1 pr-4"><?= htmlspecialchars($s['nombre']) ?></h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium flex-shrink-0
                                     <?= $s['activo'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                            <?= $s['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 flex-1 mb-4"><?= htmlspecialchars($s['descripcion']) ?></p>
                    <div class="flex items-center space-x-2">
                        <button class="flex-1 px-3 py-1.5 border border-gray-200 text-xs font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Editar</button>
                        <button class="px-3 py-1.5 border border-red-200 text-xs font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">Eliminar</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
