<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — InstalFuego</title>
    <meta name="description" content="Accede al panel de administración de InstalFuego.">
    <link rel="stylesheet" href="<?= $base_url ?>/css/output.css">
    <link rel="stylesheet" href="<?= $base_url ?>/css/styles.css">
</head>

<body>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50 text-gray-800" style="background-image: url('<?= $base_url ?>/img/background.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <div class="w-full max-w-md">

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
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Iniciar Sesión</h1>
                    <p class="text-gray-500 text-lg">Por favor, ingrese sus datos para continuar</p>
                </div>

                <!-- Mensaje de error -->
                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <!-- Formulario -->
                <form action="<?= $base_url ?>/login" method="POST" class="space-y-5">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico
                        </label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($email ?? '') ?>"
                               placeholder="Ingrese su correo electrónico"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                               required>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Contraseña
                            </label>
                            <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark transition">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                        <input type="password" id="password" name="password"
                               placeholder="Ingrese su contraseña"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition duration-200 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white"
                               required>
                    </div>

                    <button type="submit" id="btn-login"
                            class="w-full bg-[#0D0D0D] text-white py-3.5 px-4 rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition duration-200 mt-6 btn">
                        Iniciar Sesión
                    </button>

                </form>

                <!-- Registro -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes cuenta?
                        <a href="<?= $base_url ?>/register" class="font-semibold text-primary hover:text-primary-dark transition ml-1">
                            Regístrate
                        </a>
                    </p>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <p class="text-sm font-extrabold text-footer">
                        © 2025 InstalFuego.
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
