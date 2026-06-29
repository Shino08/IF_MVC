<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-16 flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 w-full">
        <div class="bg-white rounded-3xl p-10 md:p-16 text-center shadow-xl border border-gray-100">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-8">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">¡Solicitud Enviada con Éxito!</h1>
            
            <p class="text-lg text-gray-600 mb-10 max-w-lg mx-auto leading-relaxed">
                Hemos recibido tu solicitud de presupuesto correctamente. Uno de nuestros asesores especializados revisará tus requerimientos y se pondrá en contacto contigo muy pronto.
            </p>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="<?= $base_url ?? '' ?>/mis-pedidos" class="w-full sm:w-auto px-8 py-3 bg-red-700 text-white font-bold rounded-full hover:bg-red-800 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200">
                    Ver mis solicitudes
                </a>
                <a href="<?= $base_url ?? '' ?>/catalogo" class="w-full sm:w-auto px-8 py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-full hover:border-red-600 hover:text-red-600 transition-colors shadow-sm hover:shadow-md transform hover:-translate-y-0.5 duration-200">
                    Seguir explorando
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
