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
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="<?= $base_url ?>/">
            <img src="<?= $base_url ?>/img/Photoroom-20251106_165742.png"
                 alt="InstalFuego Logo" class="h-16 object-contain mx-auto mb-4">
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Iniciar Sesión</h1>
        <p class="text-gray-500 text-sm mt-1">Ingresa tus datos para acceder al panel</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">

        <?php if (isset($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form action="<?= $base_url ?>/login" method="POST" class="space-y-5">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Correo Electrónico</label>
                <input type="email" name="email" id="email" required
                       value="<?= htmlspecialchars($email ?? '') ?>"
                       placeholder="admin@instalfuego.com"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                              placeholder-gray-400 transition-all">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="text-sm font-medium text-gray-700">Contraseña</label>
                    <a href="#" class="text-xs text-red-600 hover:text-red-800 font-medium transition-colors">¿Olvidaste tu contraseña?</a>
                </div>
                <input type="password" name="password" id="password" required
                       placeholder="••••••••"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                              placeholder-gray-400 transition-all">
            </div>

            <button type="submit" id="btn-login"
                    class="w-full py-3 bg-red-700 text-white font-semibold rounded-xl
                           hover:bg-red-800 active:bg-red-900 transition-colors text-sm">
                Entrar al Panel
            </button>
        </form>

        <!-- Demo hint -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs text-gray-500">
            <p class="font-semibold text-gray-700 mb-1">🔑 Datos de demostración</p>
            <p>Email: <code class="bg-white px-1.5 py-0.5 rounded border border-gray-200">admin@test.com</code></p>
            <p class="mt-1">Password: <code class="bg-white px-1.5 py-0.5 rounded border border-gray-200">123456</code></p>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            ¿No tienes cuenta?
            <a href="<?= $base_url ?>/register" class="text-red-700 font-semibold hover:text-red-800 transition-colors">Regístrate aquí</a>
        </p>
    </div>

    <!-- Back to site -->
    <div class="text-center mt-6">
        <a href="<?= $base_url ?>/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors flex items-center justify-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Volver al sitio</span>
        </a>
    </div>
</div>

</body>
</html>
