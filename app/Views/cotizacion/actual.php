<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<?php
// Calcular subtotal desde items
$subtotalCart = 0;
foreach ($detalles as $item) {
    $subtotalCart += (float)$item['precio_unitario'] * (float)$item['cantidad'];
}
?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="<?= $base_url ?? '' ?>/catalogo" class="hover:text-red-600 transition-colors">Catálogo</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="font-medium text-gray-900">Carrito</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Tu Carrito</h1>
        </div>

        <?php if (!empty($_SESSION['success_msg'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                <p class="text-green-700"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_msg'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <p class="text-red-700"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($detalles)): ?>
            <div class="bg-white rounded-2xl p-16 text-center border border-gray-200 shadow-sm">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-6 border border-gray-100">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Explora nuestro catálogo y agrega los productos o servicios que necesitas.</p>
                <a href="<?= $base_url ?? '' ?>/catalogo" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Ver Catálogo
                </a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <!-- ===== COLUMNA IZQUIERDA: Items ===== -->
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-900 text-lg">
                                Items
                                <span class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-700 text-xs font-bold"><?= count($detalles) ?></span>
                            </h2>
                            <a href="<?= $base_url ?? '' ?>/catalogo" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Agregar más
                            </a>
                        </div>
                        <ul class="divide-y divide-gray-50">
                            <?php foreach ($detalles as $item): ?>
                                <?php
                                    $esProducto = !empty($item['producto_id']);
                                    $nombre = $esProducto ? ($item['producto_nombre'] ?? '') : ($item['servicio_nombre'] ?? '');
                                    $imgFile = $esProducto ? ($item['producto_imagen'] ?? '') : ($item['servicio_imagen'] ?? '');
                                    $imgDir  = $esProducto ? '/img/productos/' : '/img/servicios/';
                                    $publicDir = dirname(__DIR__, 3) . '/public';
                                    $imgPathFs = !empty($imgFile) ? $publicDir . $imgDir . $imgFile : '';
                                    $imgUrl = (!empty($imgFile) && file_exists($imgPathFs))
                                        ? ($base_url ?? '') . $imgDir . htmlspecialchars($imgFile)
                                        : ($base_url ?? '') . '/img/user.png';
                                    $precio    = (float)$item['precio_unitario'];
                                    $cantidad  = (float)$item['cantidad'];
                                    $subtotalItem = $precio * $cantidad;
                                ?>
                                <li class="p-5 flex items-center gap-5 hover:bg-gray-50/60 transition-colors group">
                                    <!-- Imagen -->
                                    <div class="flex-shrink-0 w-18 h-18 bg-gray-50 rounded-xl p-2 border border-gray-100 hidden sm:block" style="width:72px;height:72px;">
                                        <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($nombre) ?>" class="w-full h-full object-contain mix-blend-multiply">
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider <?= $esProducto ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' ?>">
                                                <?= $esProducto ? 'PRODUCTO' : 'SERVICIO' ?>
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-900 truncate"><?= htmlspecialchars($nombre) ?></h4>
                                        <p class="text-xs text-gray-400 mt-0.5">$<?= number_format($precio, 2) ?> c/u</p>
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <?php if ($esProducto): ?>
                                            <form action="<?= $base_url ?? '' ?>/pedido/item/actualizar" method="POST" class="flex items-center form-actualizar-cantidad">
                                                <input type="hidden" name="detalle_id" value="<?= $item['id'] ?>">
                                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white w-28">
                                                    <button type="button" class="btn-qty-dec w-8 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors text-base font-bold">−</button>
                                                    <input type="number" name="cantidad" value="<?= (int)$cantidad ?>" class="w-10 text-center text-gray-900 font-bold text-sm py-2 focus:outline-none border-none bg-transparent" min="1" step="1" id="qty-<?= $item['id'] ?>">
                                                    <button type="button" class="btn-qty-inc w-8 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors text-base font-bold">+</button>
                                                </div>
                                                <button type="submit" class="ml-1.5 p-1.5 text-gray-300 hover:text-blue-600 transition-colors" title="Guardar cantidad">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <div class="flex items-center justify-center w-28 h-9 bg-gray-50 rounded-lg border border-gray-200 text-gray-400 text-xs font-medium">
                                                Servicio fijo
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right flex-shrink-0 w-20">
                                        <p class="text-sm font-bold text-gray-900">$<?= number_format($subtotalItem, 2) ?></p>
                                    </div>

                                    <!-- Eliminar -->
                                    <form action="<?= $base_url ?? '' ?>/pedido/item/eliminar" method="POST" class="form-eliminar-item flex-shrink-0">
                                        <input type="hidden" name="detalle_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <!-- Subtotal banner -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm text-gray-500 font-medium">Subtotal (<?= count($detalles) ?> item<?= count($detalles) != 1 ? 's' : '' ?>)</span>
                            <span class="text-lg font-extrabold text-gray-900" id="subtotal-display">$<?= number_format($subtotalCart, 2) ?></span>
                        </div>
                    </div>
                </div>

                <!-- ===== COLUMNA DERECHA: Checkout ===== -->
                <div class="w-full lg:w-[420px] flex-shrink-0">
                    <form action="<?= $base_url ?? '' ?>/pedido/enviar" method="POST" id="checkout-form">

                        <!-- Sección 1: Método de Entrega -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                            <div class="px-6 py-4 border-b border-gray-50">
                                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
                                    Método de Entrega
                                </h2>
                            </div>
                            <div class="p-5">
                                <!-- Opciones de entrega como cards -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <label class="delivery-card cursor-pointer" for="entrega_tienda">
                                        <input type="radio" name="tipo_entrega" id="entrega_tienda" value="retiro_tienda" class="sr-only" required>
                                        <div class="delivery-card-inner border-2 border-gray-200 rounded-xl p-4 text-center transition-all hover:border-red-300">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 mx-auto mb-2 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            </div>
                                            <p class="text-xs font-bold text-gray-900">Retiro en Tienda</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Sin costo</p>
                                        </div>
                                    </label>
                                    <label class="delivery-card cursor-pointer" for="entrega_domicilio">
                                        <input type="radio" name="tipo_entrega" id="entrega_domicilio" value="domicilio" class="sr-only">
                                        <div class="delivery-card-inner border-2 border-gray-200 rounded-xl p-4 text-center transition-all hover:border-red-300">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 mx-auto mb-2 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                            </div>
                                            <p class="text-xs font-bold text-gray-900">Envío a Domicilio</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Coordinar con asesor</p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Campos de dirección (sólo domicilio) -->
                                <div id="div_direccion" class="hidden space-y-3">
                                    <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg border border-blue-100 text-xs text-blue-700">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        El costo de envío será confirmado por nuestro equipo según tu zona.
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1" for="estado_envio">Estado / Región *</label>
                                        <select name="estado_envio" id="estado_envio" class="input-elegant text-sm" required>
                                            <option value="">Selecciona tu estado...</option>
                                            <option value="Distrito Capital">Distrito Capital</option>
                                            <option value="Miranda">Miranda</option>
                                            <option value="Aragua">Aragua</option>
                                            <option value="Carabobo">Carabobo</option>
                                            <option value="Lara">Lara</option>
                                            <option value="Zulia">Zulia</option>
                                            <option value="Bolívar">Bolívar</option>
                                            <option value="Anzoátegui">Anzoátegui</option>
                                            <option value="Mérida">Mérida</option>
                                            <option value="Táchira">Táchira</option>
                                            <option value="Falcón">Falcón</option>
                                            <option value="Sucre">Sucre</option>
                                            <option value="Monagas">Monagas</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1" for="municipio_envio">Municipio / Ciudad *</label>
                                        <input type="text" name="municipio_envio" id="municipio_envio" class="input-elegant text-sm" placeholder="Ej. Chacao, Baruta, Naguanagua..." required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1" for="direccion_envio">Dirección Completa *</label>
                                        <textarea name="direccion_envio" id="direccion_envio" rows="3" class="input-elegant text-sm resize-none" placeholder="Urbanización, calle, edificio/casa, piso, apto. Punto de referencia..." required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1" for="referencia_envio">Punto de Referencia</label>
                                        <input type="text" name="referencia_envio" id="referencia_envio" class="input-elegant text-sm" placeholder="Cerca de, frente a, al lado de...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Observaciones -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                            <div class="px-6 py-4 border-b border-gray-50">
                                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
                                    Observaciones <span class="text-xs font-normal text-gray-400">(Opcional)</span>
                                </h2>
                            </div>
                            <div class="p-5">
                                <textarea name="notas_tecnicas" rows="3" class="input-elegant text-sm resize-none" placeholder="Especificaciones adicionales, detalles del proyecto, preferencias de instalación..."></textarea>
                            </div>
                        </div>

                        <!-- Sección 3: Resumen y Confirmar -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-50">
                                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
                                    Resumen
                                </h2>
                            </div>
                            <div class="p-5">
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal</span>
                                        <span class="font-medium text-gray-900">$<?= number_format($subtotalCart, 2) ?></span>
                                    </div>
                                    <div class="flex justify-between text-gray-400 text-xs" id="envio-row" style="display:none!important;">
                                        <span>Envío estimado</span>
                                        <span class="italic">A confirmar</span>
                                    </div>
                                    <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                                        <span class="font-bold text-gray-900">Total</span>
                                        <span class="text-xl font-extrabold text-gray-900">$<?= number_format($subtotalCart, 2) ?></span>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="submit" id="btn-confirmar" class="w-full btn-primary h-[52px] text-base font-bold flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Confirmar Pedido
                                    </button>
                                    <p class="text-xs text-center text-gray-400 mt-3">
                                        Al confirmar, recibirás instrucciones de pago de inmediato.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- fin columna derecha -->

            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.delivery-card input:checked ~ .delivery-card-inner {
    border-color: #dc2626;
    background-color: #fff5f5;
}
.delivery-card input:checked ~ .delivery-card-inner svg,
.delivery-card input:checked ~ .delivery-card-inner p {
    color: #dc2626;
}
.delivery-card input:checked ~ .delivery-card-inner .w-10 {
    background-color: #fee2e2;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="tipo_entrega"]');
    const divDireccion = document.getElementById('div_direccion');
    const estadoEnvio = document.getElementById('estado_envio');
    const municipioEnvio = document.getElementById('municipio_envio');
    const direccionEnvio = document.getElementById('direccion_envio');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const envioRow = document.getElementById('envio-row');

    function updateDeliveryUI() {
        const selected = document.querySelector('input[name="tipo_entrega"]:checked');
        if (!selected) {
            btnConfirmar.disabled = true;
            return;
        }

        if (selected.value === 'domicilio') {
            divDireccion.classList.remove('hidden');
            estadoEnvio.setAttribute('required', 'required');
            municipioEnvio.setAttribute('required', 'required');
            direccionEnvio.setAttribute('required', 'required');
            if (envioRow) envioRow.style.removeProperty('display');
        } else {
            divDireccion.classList.add('hidden');
            estadoEnvio.removeAttribute('required');
            municipioEnvio.removeAttribute('required');
            direccionEnvio.removeAttribute('required');
            estadoEnvio.value = '';
            municipioEnvio.value = '';
            direccionEnvio.value = '';
            if (envioRow) envioRow.style.display = 'none';
        }

        btnConfirmar.disabled = false;
    }

    radios.forEach(r => r.addEventListener('change', updateDeliveryUI));

    // Qty +/-
    document.querySelectorAll('.btn-qty-dec').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input[type=number]');
            if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
        });
    });
    document.querySelectorAll('.btn-qty-inc').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input[type=number]');
            input.value = parseInt(input.value) + 1;
        });
    });

    // AJAX actualizar cantidad
    document.querySelectorAll('.form-actualizar-cantidad').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            fetch(this.action, { method: 'POST', body: new FormData(this) })
                .then(r => r.json())
                .then(data => { if (data.success) location.reload(); else alert(data.error); });
        });
    });

    // AJAX eliminar item
    document.querySelectorAll('.form-eliminar-item').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!confirm('¿Eliminar este item del carrito?')) return;
            fetch(this.action, { method: 'POST', body: new FormData(this) })
                .then(r => r.json())
                .then(data => { if (data.success) location.reload(); else alert(data.error); });
        });
    });
});
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
