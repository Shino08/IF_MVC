<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Restablecer Contraseña') ?> — InstalFuego</title>
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/output.css">
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/styles.css">
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50 text-gray-800" style="background-image: url('<?= $base_url ?? '' ?>/img/background.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="text-center mb-8">
                    <a href="<?= $base_url ?? '' ?>/">
                        <img src="<?= $base_url ?? '' ?>/img/4 - Copy.png" alt="InstalFuego Logo" class="h-20 mx-auto mb-6 object-contain">
                    </a>
                </div>

                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Nueva Contraseña</h1>
                    <p class="text-gray-500 text-sm">Crea una nueva contraseña segura para tu cuenta.</p>
                </div>

                <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <form action="<?= $base_url ?? '' ?>/reset-password" method="POST" class="space-y-5">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Nueva Contraseña</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                   placeholder="Mínimo 8 caracteres"
                                   class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl outline-none transition duration-200 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   required>
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-700" aria-label="Mostrar contraseña">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirm" class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                        <div class="relative">
                            <input type="password" id="password_confirm" name="password_confirm"
                                   placeholder="Repite la contraseña"
                                   class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl outline-none transition duration-200 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   required>
                            <button type="button" id="togglePasswordConfirm" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-700" aria-label="Mostrar contraseña">
                                <svg id="eyeIconConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeOffIconConfirm" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-red-700 text-white py-3 px-4 rounded-xl font-bold shadow-sm hover:bg-red-800 transition-colors mt-6">
                        Restablecer Contraseña
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        <a href="<?= $base_url ?? '' ?>/login" class="font-semibold text-red-600 hover:text-red-700 transition ml-1">Volver a Iniciar Sesión</a>
                    </p>
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

        setupPasswordToggle('password', 'togglePassword', 'eyeIcon', 'eyeOffIcon');
        setupPasswordToggle('password_confirm', 'togglePasswordConfirm', 'eyeIconConfirm', 'eyeOffIconConfirm');
    </script>
</body>
</html>
