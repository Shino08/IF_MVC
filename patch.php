<?php
$content = file_get_contents('/home/deibervasquez/php-mysql/IF_MVC/app/Views/cotizacion/actual.php');

$step1_start = <<<EOT
            <div class="flex flex-col gap-8 items-start w-full">

                <!-- ===== COLUMNA IZQUIERDA: Items ===== -->
                <div class="w-full" id="step-1">
EOT;
$step1_end = <<<EOT
                            <span class="text-sm text-gray-500 font-medium">Subtotal (<?= count(\$detalles) ?> item<?= count(\$detalles) != 1 ? 's' : '' ?>)</span>
                            <span class="text-lg font-extrabold text-gray-900" id="subtotal-display">\$<?= number_format(\$subtotalCart, 2) ?></span>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="btn-continuar" class="px-8 py-3 bg-red-600 text-white font-bold rounded-xl shadow hover:bg-red-700 transition-colors">
                            Continuar al Pago &rarr;
                        </button>
                    </div>
                </div>

                <!-- ===== COLUMNA DERECHA: Checkout ===== -->
                <div class="w-full flex-shrink-0 hidden" id="step-2">
                    <div class="mb-4">
                        <button type="button" id="btn-volver" class="text-sm text-gray-500 hover:text-red-600 font-medium flex items-center gap-1 transition-colors">
                            &larr; Volver al Carrito
                        </button>
                    </div>
EOT;

$content = str_replace('                <div class="w-full">', '                <div class="w-full" id="step-1">', $content);

$content = str_replace(
    '                    </div>
                </div>

                <!-- ===== COLUMNA DERECHA: Checkout ===== -->
                <div class="w-full flex-shrink-0">',
    '                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="btn-continuar" class="px-8 py-3 bg-red-600 text-white font-bold rounded-xl shadow hover:bg-red-700 transition-colors">
                            Continuar &rarr;
                        </button>
                    </div>
                </div>

                <!-- ===== COLUMNA DERECHA: Checkout ===== -->
                <div class="w-full flex-shrink-0 hidden" id="step-2">
                    <div class="mb-4">
                        <button type="button" id="btn-volver" class="text-sm text-gray-500 hover:text-red-600 font-medium flex items-center gap-1 transition-colors">
                            &larr; Volver a los ítems
                        </button>
                    </div>',
    $content
);

$js = <<<EOT
    const btnContinuar = document.getElementById('btn-continuar');
    const btnVolver = document.getElementById('btn-volver');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');

    if (btnContinuar) {
        btnContinuar.addEventListener('click', () => {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
        });
    }

    if (btnVolver) {
        btnVolver.addEventListener('click', () => {
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
        });
    }
EOT;

$content = str_replace("document.addEventListener('DOMContentLoaded', function () {", "document.addEventListener('DOMContentLoaded', function () {\n" . $js, $content);

file_put_contents('/home/deibervasquez/php-mysql/IF_MVC/app/Views/cotizacion/actual.php', $content);
?>
