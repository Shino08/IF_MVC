<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reportar Pago</h1>
            <p class="text-gray-600 mt-2">Pedido #PED-<?= date('Y', strtotime($cotizacion['fecha_solicitud'])) ?>-<?= str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT) ?></p>
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
                    </ul>
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <p class="font-semibold mb-2">¿Deseas pagar en divisas o efectivo?</p>
                        <a href="https://wa.me/584121234567?text=Hola,%20quiero%20coordinar%20el%20pago%20en%20divisas%20del%20pedido%20PED-<?= date('Y', strtotime($cotizacion['fecha_solicitud'])) ?>-<?= str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT) ?>" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-[#25D366] text-white font-bold rounded-lg hover:bg-[#20b858] transition-colors shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-5.824 4.737-10.56 10.566-10.56 2.82 0 5.47 1.098 7.464 3.092 1.993 1.994 3.093 4.643 3.092 7.464-.001 5.825-4.739 10.56-10.563 10.561z"/></svg>
                            Coordinar Pago en Divisas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Detalles del Pago</h2>
                    
                    <form action="<?= $base_url ?? '' ?>/pedido/pagar/<?= $cotizacion['id'] ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="moneda" value="VES">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago *</label>
                                <select name="metodo_pago_id" class="input-elegant" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($metodos as $m): 
                                        $nombre = strtolower($m['metodo'] ?? $m['nombre'] ?? '');
                                        if (strpos($nombre, 'efectivo') !== false || strpos($nombre, 'divisas') !== false) {
                                            continue;
                                        }
                                    ?>
                                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['metodo'] ?? $m['nombre'] ?? 'Método') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Monto Pagado (Bs.) *</label>
                                <input type="number" step="0.01" name="monto" class="input-elegant" required>
                            </div>
                            <div id="container-referencia">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Número de Referencia *</label>
                                <input type="text" id="input-referencia" name="referencia" class="input-elegant" required>
                            </div>
                            <div id="container-banco">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Banco de Origen</label>
                                <input type="text" name="banco_origen" class="input-elegant" placeholder="Opcional">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono / Cédula</label>
                                <input type="text" name="telefono_pagador" class="input-elegant" placeholder="Opcional">
                            </div>
                        </div>

                        <div class="mb-6" id="container-comprobante">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comprobante (Captura de pantalla) *</label>
                            <input type="file" id="input-comprobante" name="comprobante" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" accept="image/*,.pdf" required>
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

<script>
// Lógica extra si es necesaria, pero ya no se requiere toggle de campos de efectivo
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
