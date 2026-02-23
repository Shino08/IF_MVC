<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrate — InstalFuego</title>
    <meta name="description" content="Crea tu cuenta empresarial en InstalFuego para solicitar cotizaciones.">
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/output.css">
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/styles.css">
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50 text-gray-800" style="background-image: url('<?= $base_url ?? '' ?>/img/background.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <div class="w-full md:w-3/4 max-w-2xl">

            <div class="bg-white rounded-2xl shadow-xl p-8">

                <div class="text-center mb-8">
                    <a href="<?= $base_url ?? '' ?>/">
                        <img src="<?= $base_url ?? '' ?>/img/4 - Copy.png"
                             alt="InstalFuego Logo"
                             class="h-24 mx-auto mb-6 object-contain">
                    </a>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Crear Cuenta</h1>
                    <p class="text-gray-500 text-sm">Ingresa tus datos corporativos para acceder al catálogo técnico y solicitar cotizaciones.</p>
                </div>

                <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <?php endif; ?>

                <div id="ajax-alert" class="hidden px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span id="ajax-alert-text"></span>
                </div>

                <form id="registerForm" action="<?= $base_url ?? '' ?>/register" method="POST" class="space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre"
                                   value="<?= htmlspecialchars($nombre ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="Ej: Juan"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>

                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="apellido" name="apellido"
                                   value="<?= htmlspecialchars($apellido ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="Ej: Pérez"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="cedula" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Cédula / RIF <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="cedula" name="cedula"
                                   value="<?= htmlspecialchars($cedula ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="Ej: V-12345678 / J-12345678-9"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="telefono" name="telefono"
                                   value="<?= htmlspecialchars($telefono ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="Ej: 0414-1234567"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="empresa" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Empresa <span class="text-gray-400 text-xs font-normal">(Opcional)</span>
                            </label>
                            <input type="text" id="empresa" name="empresa"
                                   value="<?= htmlspecialchars($empresa ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="Nombre de la industria"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   placeholder="usuario@empresa.com"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password"
                                   placeholder="Mínimo 8 caracteres"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>

                        <div>
                            <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirm" name="password_confirm"
                                   placeholder="Repita la contraseña"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>
                    </div>

                    <button type="submit" id="btn-register"
                            class="w-full bg-[#0D0D0D] text-white py-3.5 px-4 rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition duration-200 mt-6 btn flex justify-center items-center">
                        <span id="btn-text">Crear Cuenta</span>
                        <svg id="btn-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>

                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes una cuenta?
                        <a href="<?= $base_url ?? '' ?>/login" class="font-semibold text-primary hover:text-primary-dark transition ml-1">
                            Inicia Sesión
                        </a>
                    </p>
                </div>

                <div class="text-center mt-6">
                    <p class="text-sm font-extrabold text-footer">
                        © 2026 InstalFuego.
                    </p>
                </div>

            </div>

            <div class="text-center mt-6">
                <a href="<?= $base_url ?? '' ?>/" class="text-sm text-white transition-colors flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Volver al sitio</span>
                </a>
            </div>

        </div>
    </div>

    <script>
        const BASE_URL = "<?= $base_url ?? '' ?>";
    </script>
    <script src="<?= $base_url ?? '' ?>/js/registro.js"></script>
</body>
</html>