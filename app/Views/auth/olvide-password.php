<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Recuperar Contraseña') ?> — InstalFuego</title>
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
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Recuperar Contraseña</h1>
                    <p class="text-gray-500 text-sm">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                </div>

                <?php if (!empty($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-6 flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span><?= htmlspecialchars($success) ?></span>
                    </div>
                    <?php if (!empty($_SESSION['resetlinkdemo'])): ?>
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm break-all">
                            <p class="font-bold mb-2 text-yellow-800">Modo Dev Local: Haz clic aquí para restablecer tu contraseña</p>
                            <a href="<?= $_SESSION['resetlinkdemo'] ?>" class="text-blue-600 hover:text-blue-800 font-semibold underline"><?= $_SESSION['resetlinkdemo'] ?></a>
                            <?php unset($_SESSION['resetlinkdemo']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>

                <form action="<?= $base_url ?? '' ?>/olvide-password" method="POST" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" id="email" name="email"
                               placeholder="Ej: juan@empresa.com"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none transition duration-200 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               required>
                    </div>

                    <button type="submit" class="w-full bg-red-700 text-white py-3 px-4 rounded-xl font-bold shadow-sm hover:bg-red-800 transition-colors mt-6">
                        Enviar Enlace de Recuperación
                    </button>
                </form>
                <?php endif; ?>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        ¿Recordaste tu contraseña?
                        <a href="<?= $base_url ?? '' ?>/login" class="font-semibold text-red-600 hover:text-red-700 transition ml-1">Inicia Sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
