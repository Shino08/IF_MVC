<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrate — InstalFuego</title>
    <meta name="description" content="Crea tu cuenta empresarial en InstalFuego para solicitar cotizaciones.">
    <link rel="stylesheet" href="<?= $base_url ?>/css/output.css">
    <link rel="stylesheet" href="<?= $base_url ?>/css/styles.css">
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50 text-gray-800" style="background-image: url('<?= $base_url ?>/img/background.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <div class="w-1/2 max-w-lg">

            <div class="bg-white rounded-2xl shadow-xl p-8">

                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="<?= $base_url ?>/">
                        <img src="<?= $base_url ?>/img/4 - Copy.png"
                             alt="InstalFuego Logo"
                             class="h-24 mx-auto mb-6 object-contain">
                    </a>
                </div>

                <!-- Título -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Crear Cuenta</h1>
                    <p class="text-gray-500 text-sm">Ingresa tus datos corporativos para acceder al catálogo técnico y solicitar cotizaciones.</p>
                </div>

                <!-- Mensaje de error / éxito -->
                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
                <?php endif; ?>

                <!-- Formulario -->
                <form action="<?= $base_url ?>/register" method="POST" class="space-y-4">

                    <!-- Fila 1: Nombre Completo y Teléfono -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre"
                                   value="<?= htmlspecialchars($nombre ?? '') ?>"
                                   placeholder="Ej: Juan Pérez"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="telefono" name="telefono"
                                   value="<?= htmlspecialchars($telefono ?? '') ?>"
                                   placeholder="Ej: 0414-1234567"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>
                    </div>

                    <!-- Fila 2: Empresa y Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="empresa" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Empresa (opcional)
                            </label>
                            <input type="text" id="empresa" name="empresa"
                                   value="<?= htmlspecialchars($empresa ?? '') ?>"
                                   placeholder="Nombre de la industria"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Correo <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($email ?? '') ?>"
                                   placeholder="usuario@empresa.com"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                                   required>
                        </div>
                    </div>

                    <!-- Fila 3: Contraseñas -->
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

                    <!-- Botón Registro -->
                    <button type="submit" id="btn-register"
                            class="w-full bg-[#0D0D0D] text-white py-3.5 px-4 rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition duration-200 mt-6 btn">
                        Crear Cuenta
                    </button>

                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes una cuenta?
                        <a href="<?= $base_url ?>/login" class="font-semibold text-primary hover:text-primary-dark transition ml-1">
                            Inicia Sesión
                        </a>
                    </p>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <p class="text-sm font-extrabold text-footer">
                        © 2026 InstalFuego.
                    </p>
                </div>

            </div>

            <!-- Volver al sitio -->
            <div class="text-center mt-6">
                <a href="<?= $base_url ?>/" class="text-sm text-white transition-colors flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Volver al sitio</span>
                </a>
            </div>

        </div>
    </div>
</body>
</html>
