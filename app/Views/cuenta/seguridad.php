<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4">
                <?php require_once dirname(__DIR__) . '/layouts/cuenta-sidebar.php'; ?>
            </div>
            
            <!-- Content -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-8 border-b border-gray-100 pb-4">Seguridad</h2>
                    
                    <?php if (!empty($_SESSION['success_msg'])): ?>
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center space-x-2" role="alert">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="block sm:inline font-medium"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['error_msg'])): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center space-x-2" role="alert">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="block sm:inline font-medium"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $base_url ?>/cuenta/seguridad" method="POST" class="space-y-6 max-w-md relative z-10">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña Actual</label>
                            <div class="relative">
                                <input type="password" id="password_actual" name="password_actual" class="w-full px-4 py-3.5 pr-12 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" required>
                                <button type="button" id="togglePasswordActual" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-red-600 transition-colors" aria-label="Mostrar contraseña">
                                    <svg id="eyeIconActual" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eyeOffIconActual" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" id="password_nueva" name="password_nueva" class="w-full px-4 py-3.5 pr-12 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" required minlength="8">
                                <button type="button" id="togglePasswordNueva" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-red-600 transition-colors" aria-label="Mostrar contraseña">
                                    <svg id="eyeIconNueva" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eyeOffIconNueva" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" id="password_confirmacion" name="password_confirmacion" class="w-full px-4 py-3.5 pr-12 bg-gray-50 border border-gray-200 rounded-2xl outline-none transition duration-300 hover:bg-white hover:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100 focus:border-red-500 shadow-sm" required minlength="8">
                                <button type="button" id="togglePasswordConfirmacion" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-red-600 transition-colors" aria-label="Mostrar contraseña">
                                    <svg id="eyeIconConfirmacion" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eyeOffIconConfirmacion" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="pt-6 mt-8 border-t border-gray-100 flex justify-start">
                            <button type="submit" class="bg-gradient-to-r from-red-600 to-red-800 text-white font-bold py-3.5 px-8 rounded-2xl hover:from-red-700 hover:to-red-900 transition-all duration-300 shadow-lg shadow-red-200 focus:ring-4 focus:ring-red-100 w-full sm:w-auto">Actualizar Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setupPasswordToggle(inputId, toggleId, eyeIconId, eyeOffIconId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        const eyeIcon = document.getElementById(eyeIconId);
        const eyeOffIcon = document.getElementById(eyeOffIconId);

        if (input && toggle) {
            toggle.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                toggle.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
                
                if (isHidden) {
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        }
    }

    setupPasswordToggle('password_actual', 'togglePasswordActual', 'eyeIconActual', 'eyeOffIconActual');
    setupPasswordToggle('password_nueva', 'togglePasswordNueva', 'eyeIconNueva', 'eyeOffIconNueva');
    setupPasswordToggle('password_confirmacion', 'togglePasswordConfirmacion', 'eyeIconConfirmacion', 'eyeOffIconConfirmacion');
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
