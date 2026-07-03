<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10 print:bg-white print:py-0">
    <div class="max-w-4xl mx-auto px-4 print:px-0">
        
        <!-- Mensajes de éxito/error -->
        <?php if (!empty($_SESSION['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative print:hidden" role="alert">
                <span class="block sm:inline"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error_msg'])): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative print:hidden" role="alert">
                <span class="block sm:inline"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Actions (Hidden on print) -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 print:hidden">
            <a href="<?= $base_url ?? '' ?>/mis-pedidos" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <a href="<?= $base_url ?? '' ?>/carrito/pdf/<?= $carrito['id'] ?>" target="_blank" class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-md text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-lg hover:shadow-xl ring-1 ring-red-400/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Descargar PDF
                </a>
                <?php if ($carrito['estado_id'] == 4): ?>
                <a href="<?= $base_url ?? '' ?>/factura/pdf/<?= $carrito['id'] ?>" target="_blank" class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-md text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl ring-1 ring-green-400/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Descargar Factura
                </a>
                <?php endif; ?>
                <?php if(isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <form action="<?= $base_url ?? '' ?>/pedido/enviar_correo/<?= $carrito['id'] ?>" method="POST" class="inline">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Enviar por Correo
                    </button>
                </form>
                <?php endif; ?>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Imprimir
                </button>
                <?php if($carrito['estado_id'] == 3): ?>
                    <form action="<?= $base_url ?? '' ?>/pedido/aceptar/<?= $carrito['id'] ?>" method="POST" class="inline">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-md text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Confirmar Pedido
                        </button>
                    </form>
                    <form action="<?= $base_url ?? '' ?>/pedido/rechazar/<?= $carrito['id'] ?>" method="POST" class="inline">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-red-300 shadow-sm text-sm font-bold rounded-lg text-red-600 bg-white hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancelar Pedido
                        </button>
                    </form>
                <?php endif; ?>
                <?php if($carrito['estado_id'] == 4): ?>
                    <?php if(isset($pedido) && $pedido['estado_pedido'] === 'pago_por_validar'): ?>
                        <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-yellow-800 bg-yellow-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pago en Revisión
                        </span>
                    <?php elseif(isset($pedido) && in_array($pedido['estado_pedido'], ['procesando', 'despachado', 'entregado'])): ?>
                        <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-green-800 bg-green-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Pago Completado
                        </span>
                    <?php else: ?>
                        <a href="<?= $base_url ?? '' ?>/pedido/pagar/<?= $carrito['id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Reportar Pago
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
    </div>

    <!-- ===== TIMELINE DE SEGUIMIENTO ===== -->
    <?php
    // Mapear estado_pedido al step activo del timeline
    $stepOrder = ['pendiente_pago', 'pago_por_validar', 'procesando', 'despachado', 'entregado'];
    $currentStep = $pedido['estado_pedido'] ?? 'pendiente_pago';
    $isCancelled  = ($currentStep === 'cancelado');
    $currentStepIndex = array_search($currentStep, $stepOrder);
    if ($currentStepIndex === false) $currentStepIndex = 0;

    $esDomicilio = ($carrito['tipo_entrega'] ?? '') === 'domicilio';

    $steps = [
        [
            'key'   => 'pendiente_pago',
            'label' => 'Pedido Recibido',
            'sub'   => 'Pedido confirmado en el sistema',
            'date'  => !empty($pedido['fecha_creacion'])
                        ? date('d/m/Y H:i', strtotime($pedido['fecha_creacion']))
                        : date('d/m/Y H:i', strtotime($carrito['fecha_solicitud'])),
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
        ],
        [
            'key'   => 'pago_por_validar',
            'label' => 'Pago Reportado',
            'sub'   => 'Comprobante enviado, en espera de validación',
            'date'  => !empty($pedido['fecha_pago_reportado'])
                        ? date('d/m/Y H:i', strtotime($pedido['fecha_pago_reportado']))
                        : null,
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
        ],
        [
            'key'   => 'procesando',
            'label' => 'Pago Validado',
            'sub'   => 'Pago confirmado — preparando tu pedido',
            'date'  => !empty($pedido['fecha_pago_validado'])
                        ? date('d/m/Y H:i', strtotime($pedido['fecha_pago_validado']))
                        : null,
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        ],
        [
            'key'   => 'despachado',
            'label' => $esDomicilio ? 'En Camino' : 'Listo para Retiro',
            'sub'   => $esDomicilio ? 'Tu pedido está en ruta' : 'Puedes retirar tu pedido en tienda',
            'date'  => null,
            'icon'  => $esDomicilio
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
        ],
        [
            'key'   => 'entregado',
            'label' => 'Entregado',
            'sub'   => '¡Pedido completado!',
            'date'  => null,
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        ],
    ];
    ?>
    <?php if (!$isCancelled && isset($pedido)): ?>
    <div class="mt-6 mb-6 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden print:hidden">
        <div class="px-6 py-4 border-b border-gray-50">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Estado del Pedido
            </h2>
        </div>
        <div class="px-6 py-6">
            <div class="flex items-start justify-between relative">
                <!-- Línea de progreso -->
                <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-100 z-0" style="left:24px;right:24px;"></div>
                <?php
                $totalSteps = count($steps);
                $progressPct = $currentStepIndex > 0
                    ? min(100, round(($currentStepIndex / ($totalSteps - 1)) * 100))
                    : 0;
                ?>
                <div class="absolute top-5 h-0.5 bg-red-500 z-0 transition-all duration-700"
                     style="left:24px; width: calc(<?= $progressPct ?>% - 0px); max-width: calc(100% - 48px);"></div>

                <?php foreach ($steps as $i => $step):
                    $isDone    = $i < $currentStepIndex;
                    $isActive  = $i === $currentStepIndex;
                    $isPending = $i > $currentStepIndex;

                    $circleClass = $isDone   ? 'bg-red-500 border-red-500 text-white'
                                 : ($isActive ? 'bg-white border-red-500 text-red-600 ring-4 ring-red-50'
                                              : 'bg-white border-gray-200 text-gray-300');
                    $labelClass  = $isDone   ? 'text-gray-500'
                                 : ($isActive ? 'text-gray-900 font-bold'
                                              : 'text-gray-300');
                ?>
                <div class="flex flex-col items-center z-10 flex-1 <?= $i === 0 ? 'items-start' : ($i === $totalSteps - 1 ? 'items-end' : 'items-center') ?>">
                    <!-- Círculo -->
                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all duration-300 <?= $circleClass ?>">
                        <?php if ($isDone): ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        <?php else: ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?= $step['icon'] ?>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <!-- Label -->
                    <p class="mt-2 text-xs text-center leading-tight max-w-[80px] <?= $labelClass ?>">
                        <?= htmlspecialchars($step['label']) ?>
                    </p>
                    <!-- Fecha si existe -->
                    <?php if (!empty($step['date'])): ?>
                        <p class="text-[10px] text-gray-400 text-center mt-0.5"><?= $step['date'] ?></p>
                    <?php endif; ?>
                    <!-- Punto de actividad (sólo el activo) -->
                    <?php if ($isActive): ?>
                        <p class="text-[10px] text-red-500 font-semibold text-center mt-0.5 animate-pulse">Aquí</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Mensaje contextual -->
            <div class="mt-6 p-3 rounded-lg <?php
                if ($currentStep === 'pendiente_pago')   echo 'bg-yellow-50 border border-yellow-100 text-yellow-800';
                elseif ($currentStep === 'pago_por_validar') echo 'bg-blue-50 border border-blue-100 text-blue-800';
                elseif ($currentStep === 'procesando')   echo 'bg-purple-50 border border-purple-100 text-purple-800';
                elseif ($currentStep === 'despachado')   echo 'bg-indigo-50 border border-indigo-100 text-indigo-800';
                else                                     echo 'bg-green-50 border border-green-100 text-green-800';
            ?>">
                <p class="text-xs font-semibold">
                    <?php if ($currentStep === 'pendiente_pago'): ?>
                        Tu pedido está confirmado. Reporta tu pago para que lo procesemos.
                    <?php elseif ($currentStep === 'pago_por_validar'): ?>
                        Recibimos tu comprobante. Lo estamos verificando — te notificaremos pronto.
                    <?php elseif ($currentStep === 'procesando'): ?>
                        Pago validado. Tu pedido está siendo preparado.
                    <?php elseif ($currentStep === 'despachado' && $esDomicilio): ?>
                        Tu pedido salió del almacén. Está en ruta hacia ti.
                    <?php elseif ($currentStep === 'despachado'): ?>
                        Tu pedido está listo. Puedes pasar a retirarlo por nuestra tienda.
                    <?php else: ?>
                        ¡Pedido completado! Gracias por tu compra.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    <?php elseif ($isCancelled): ?>
    <div class="mt-6 mb-6 bg-red-50 border border-red-200 rounded-xl px-6 py-4 flex items-center gap-3 print:hidden">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-semibold text-red-700">Este pedido fue cancelado. Contacta a soporte si necesitas asistencia.</p>
    </div>
    <?php endif; ?>

    <?php
    $estadoLabels = [
        'borrador'               => ['label' => 'Borrador',            'color' => 'border-gray-200 bg-gray-50 text-gray-600'],
        'pendiente_revision'     => ['label' => 'Recibido',             'color' => 'border-blue-200 bg-blue-50 text-blue-800'],
        'enviada'                => ['label' => 'Recibido',             'color' => 'border-blue-200 bg-blue-50 text-blue-800'],
        'aceptada por el cliente'=> ['label' => 'Pendiente de Pago',   'color' => 'border-yellow-200 bg-yellow-50 text-yellow-800'],
        'rechazada'              => ['label' => 'Cancelado',            'color' => 'border-red-200 bg-red-50 text-red-800'],
        'vencida'                => ['label' => 'Cancelado',            'color' => 'border-red-200 bg-red-50 text-red-800'],
    ];
    $estadoPedidoLabels = [
        'pendiente_pago'   => ['label' => 'Pendiente de Pago',  'color' => 'border-yellow-200 bg-yellow-50 text-yellow-800'],
        'pago_por_validar' => ['label' => 'Pago en Revisión',   'color' => 'border-blue-200 bg-blue-50 text-blue-800'],
        'procesando'       => ['label' => 'Preparando Pedido',  'color' => 'border-purple-200 bg-purple-50 text-purple-800'],
        'despachado'       => ['label' => 'En Camino',          'color' => 'border-indigo-200 bg-indigo-50 text-indigo-800'],
        'entregado'        => ['label' => 'Entregado',          'color' => 'border-green-200 bg-green-50 text-green-800'],
        'cancelado'        => ['label' => 'Cancelado',          'color' => 'border-red-200 bg-red-50 text-red-800'],
    ];
    $estadoPedido = $pedido['estado_pedido'] ?? null;
    if ($estadoPedido && isset($estadoPedidoLabels[$estadoPedido])) {
        $estadoInfo = $estadoPedidoLabels[$estadoPedido];
    } else {
        $key = strtolower($carrito['estado_nombre'] ?? '');
        $estadoInfo = $estadoLabels[$key] ?? ['label' => strtoupper($key), 'color' => 'border-gray-200 bg-gray-50 text-gray-800'];
    }
    ?>

    <div class="mb-4 flex items-center justify-end print:hidden">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border <?= $estadoInfo['color'] ?> shadow-sm">
            <span class="text-sm font-bold">Estado actual:</span>
            <span class="text-sm uppercase tracking-wide font-black"><?= htmlspecialchars($estadoInfo['label']) ?></span>
        </div>
    </div>

        <!-- Document -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden print:shadow-none print:border-none print:rounded-none">
            
            <!-- Header -->
            <div class="px-8 py-10 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <img src="<?= $base_url ?? '' ?>/img/Photoroom-20251106_165742.png" alt="InstalFuego Logo" class="h-16 md:h-20 mb-4 object-contain">
                    <h2 class="text-xl font-bold text-gray-900">InstalFuego C.A.</h2>
                    <p class="text-sm text-gray-500 mt-1">RIF: J-12345678-9</p>
                    <p class="text-sm text-gray-500">Av. Principal, Caracas, Venezuela</p>
                    <p class="text-sm text-gray-500">contacto@instalfuego.com | +58 412-1234567</p>
                </div>
                <div class="text-left md:text-right">
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-wider mb-2">Pedido</h1>
                    <p class="text-sm text-gray-600"><span class="font-bold">Nro:</span> #PED-<?= date('Y', strtotime($carrito['fecha_solicitud'])) ?>-<?= str_pad((string)$carrito['id'], 4, '0', STR_PAD_LEFT) ?></p>
                    <p class="text-sm text-gray-600 mt-1"><span class="font-bold">Fecha:</span> <?= date('d/m/Y', strtotime($carrito['fecha_solicitud'])) ?></p>
                </div>
            </div>

            <!-- Client Info -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Facturado a:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($carrito['cliente_nombre'] . ' ' . $carrito['cliente_apellido']) ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($carrito['cliente_empresa'] ?? 'Persona Natural') ?></p>
                        <p class="text-sm text-gray-600">CI/RIF: <?= htmlspecialchars($carrito['cliente_cedula'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($carrito['cliente_email']) ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($carrito['cliente_telefono'] ?? 'Teléfono no provisto') ?></p>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="px-8 py-8">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 text-sm font-bold text-gray-900">Descripción del Item</th>
                            <th class="py-3 text-center text-sm font-bold text-gray-900">Tipo</th>
                            <th class="py-3 text-center text-sm font-bold text-gray-900">Cant.</th>
                            <th class="py-3 text-right text-sm font-bold text-gray-900">P. Unitario ($)</th>
                            <th class="py-3 text-right text-sm font-bold text-gray-900">Subtotal ($)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php 
                        $subtotalCalc = 0;
                        foreach ($detalles as $item): 
                            $lineTotal = $item['cantidad'] * $item['precio_unitario'];
                            $subtotalCalc += $lineTotal;
                        ?>
                            <tr>
                                <td class="py-4">
                                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars(!empty($item['producto_id']) ? $item['producto_nombre'] : $item['servicio_nombre']) ?></p>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="text-xs font-medium px-2 py-1 rounded-md <?= !empty($item['producto_id']) ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' ?>">
                                        <?= !empty($item['producto_id']) ? 'Prod' : 'Serv' ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center text-sm text-gray-700"><?= !empty($item['producto_id']) ? (int)$item['cantidad'] : 'N/A' ?></td>
                                <td class="py-4 text-right text-sm text-gray-700"><?= number_format((float)$item['precio_unitario'], 2) ?></td>
                                <td class="py-4 text-right text-sm font-medium text-gray-900"><?= number_format((float)$lineTotal, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <?php
                // Usar valores guardados de la base de datos
                $subtotal = (float)$carrito['subtotal'];
                $iva = (float)$carrito['impuestos'];
                $descuento = (float)$carrito['descuento'];
                $costoEnvio = (float)($carrito['costo_envio'] ?? 0);
                $totalFinal = (float)$carrito['total'];
            ?>
            <div class="px-8 flex justify-end">
                <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">Subtotal:</span>
                        <span class="font-medium text-gray-900">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <?php if (isset($carrito['aplica_iva']) && $carrito['aplica_iva'] == 1): ?>
                            <span class="font-medium text-gray-500">IVA (<?= number_format((float)($carrito['tasa_iva'] ?? 16), 0) ?>%):</span>
                            <span class="font-medium text-gray-900">$<?= number_format($iva, 2) ?></span>
                        <?php elseif (isset($carrito['aplica_iva']) && $carrito['aplica_iva'] == 0): ?>
                            <span class="font-medium text-gray-500">IVA / Impuestos:</span>
                            <span class="font-medium text-gray-900">Exento ($0.00)</span>
                        <?php else: ?>
                            <span class="font-medium text-gray-500">IVA / Impuestos:</span>
                            <span class="font-medium text-gray-900">$<?= number_format($iva, 2) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($descuento > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-red-500">Descuento:</span>
                        <span class="font-medium text-red-500">-$<?= number_format($descuento, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($costoEnvio > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">Costo de Envío:</span>
                        <span class="font-medium text-gray-900">$<?= number_format($costoEnvio, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-lg font-black pt-3 border-t border-gray-200">
                        <span class="text-gray-900">Total USD:</span>
                        <span class="text-red-600">$<?= number_format($totalFinal, 2) ?></span>
                    </div>
                    <?php if (!empty($carrito['montousd'])): ?>
                    <div class="flex justify-between text-sm pt-1">
                        <span class="text-gray-500">En Bs. (tasa BCV):</span>
                        <span class="font-semibold text-gray-700">Bs. <?= number_format((float)$carrito['montousd'], 2, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Logistics -->
            <?php if (!empty($carrito['tipo_entrega'])): ?>
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 mt-8 rounded-b-xl">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Información de Entrega</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tipo de Entrega</p>
                        <span class="block text-sm text-gray-900 mt-1"><?= $carrito['tipo_entrega'] === 'domicilio' ? 'Envío a Domicilio' : 'Retiro en Tienda' ?></span>
                    </div>
                    <?php if ($carrito['tipo_entrega'] === 'domicilio' && !empty($carrito['direccion_envio'])): ?>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Dirección</p>
                        <span class="block text-sm text-gray-900 mt-1"><?= htmlspecialchars($carrito['direccion_envio']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Notes -->
            <div class="px-8 py-8 border-t border-gray-200">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Notas Técnicas y Comerciales</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line mb-6"><?= htmlspecialchars($carrito['notas_tecnicas'] ?? 'No se especificaron notas adicionales.') ?></p>                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Términos y Condiciones</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Los precios están expresados en dólares americanos (USD).</li>
                    <li>Formas de pago aceptadas: Transferencia bancaria y Pago Móvil.</li>
                    <li>El monto total incluye impuestos aplicables y, cuando corresponda, costo de envío o domicilio.</li>
                    <li>El costo de entrega a domicilio será determinado según la ubicación y coordinado previamente con el cliente.</li>
                    <li>La ejecución de la entrega o servicio se realizará una vez confirmado el pago.</li>
                    <li>Garantía aplicable según especificaciones del fabricante.</li>
                    <li>Para coordinar pagos en divisas, comuníquese con soporte vía WhatsApp: <?= \App\Core\Config::WHATSAPP_DISPLAY ?></li>
                </ul>

                <!-- Tasa BCV -->
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg print:bg-yellow-50">
                    <div>
                        <p class="text-xs font-bold text-yellow-800 uppercase tracking-wider mb-0.5">Tasa de Cambio Referencial</p>
                        <?php if (!empty($carrito['tasabcv'])): ?>
                        <p class="text-sm text-yellow-900">
                            <strong>Bs. <?= number_format((float)$carrito['tasabcv'], 4, ',', '.') ?></strong> / $1 USD
                            <span class="text-yellow-700 text-xs">(al <?= date('d/m/Y', strtotime($carrito['fecha_solicitud'])) ?>)</span>
                        </p>
                        <?php if (!empty($carrito['montousd'])): ?>
                        <p class="text-xs text-yellow-700 mt-0.5">
                            Equivale a ≈ <strong>Bs. <?= number_format((float)$carrito['montousd'], 2, ',', '.') ?></strong>
                        </p>
                        <?php endif; ?>
                        <?php else: ?>
                        <p class="text-sm text-yellow-800">Consulte la tasa del día con su asesor.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
@media print {
    body {
        -webkit-print-color-adjust: exact;
        background-color: white !important;
        margin: 1.5cm !important;
    }
    .sticky.top-0, nav, footer, .print\:hidden {
        display: none !important;
    }
    .bg-gray-50 {
        background-color: white !important;
    }
    .max-w-4xl {
        max-width: 100% !important;
        width: 100% !important;
    }
    @page {
        size: auto;
        margin: 0;
    }
}
</style>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
