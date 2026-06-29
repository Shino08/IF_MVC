<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reportar Pago</h1>
            <p class="text-gray-600 mt-2">Presupuesto #COT-<?= date('Y', strtotime($cotizacion['fecha_solicitud'])) ?>-<?= str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT) ?></p>
        </div>

        <?php if (!empty($_SESSION['error_msg'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <p class="text-red-700"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Resumen e Información -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Resumen del Pedido</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Monto a Pagar</p>
                                <p class="text-2xl font-bold text-red-600">$<?= number_format($totalFinal, 2) ?></p>
                            </div>
                            <?php if (!empty($cotizacion['tasabcv'])): ?>
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 font-semibold uppercase">Equivalente BCV</p>
                                <p class="text-lg font-bold text-gray-900">Bs. <?= number_format($totalFinal * $cotizacion['tasabcv'], 2, ',', '.') ?></p>
                                <p class="text-xs text-gray-400 mt-1">Tasa: Bs. <?= number_format($cotizacion['tasabcv'], 4, ',', '.') ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm text-blue-800 shadow-sm">
                    <p class="font-bold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Información para Pagos
                    </p>
                    <ul class="list-disc list-inside space-y-2">
                        <li><strong>Pago Móvil:</strong><br>Banesco (0134)<br>V-12345678<br>0412-1234567</li>
                        <li><strong>Transferencia:</strong><br>Consultar cuentas disponibles.</li>
                        <li><strong>Efectivo o Divisas:</strong><br>Se coordinará al entregar.</li>
                    </ul>
                </div>
            </div>

            <!-- Columna Derecha: Formulario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Detalles del Pago</h2>
                    
                    <form action="<?= $base_url ?? '' ?>/pedido/pagar/<?= $cotizacion['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago *</label>
                                <select name="metodo_pago_id" class="input-elegant" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($metodos as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['metodo'] ?? $m['nombre'] ?? 'Método') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Moneda *</label>
                                <select name="moneda" class="input-elegant" required>
                                    <option value="USD">USD ($)</option>
                                    <option value="VES">Bolívares (Bs)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Monto Pagado *</label>
                                <input type="number" step="0.01" name="monto" class="input-elegant" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Número de Referencia *</label>
                                <input type="text" name="referencia" class="input-elegant" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Banco de Origen</label>
                                <input type="text" name="banco_origen" class="input-elegant" placeholder="Opcional">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono / Cédula</label>
                                <input type="text" name="telefono_pagador" class="input-elegant" placeholder="Opcional">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comprobante (Captura de pantalla) *</label>
                            <input type="file" name="comprobante" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" accept="image/*,.pdf" required>
                        </div>

                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                            <a href="<?= $base_url ?? '' ?>/mis-pedidos/<?= $cotizacion['id'] ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancelar</a>
                            <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">Confirmar y Reportar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
