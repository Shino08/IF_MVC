<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="Bienvenido al miniframework PHP MVC.">
    <link rel="stylesheet" href="<?= $base_url ?>/css/main.css">
</head>

<body>
    <div class="card">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>

        <?php if (isset($logged_in) && $logged_in): ?>
            <a href="<?= $base_url ?>/logout" class="btn btn-danger">Cerrar Sesión</a>
        <?php else: ?>
            <a href="<?= $base_url ?>/login"    class="btn">Iniciar Sesión</a>
            <a href="<?= $base_url ?>/register" class="btn btn-secondary">Registrarse</a>
        <?php endif; ?>

        <div class="footer-note">
            <p><small>Vista renderizada por <code>HomeController</code> · Framework MVC PHP</small></p>
            <a href="#" class="btn btn-ghost">Probar JavaScript</a>
        </div>
    </div>

    <script src="<?= $base_url ?>/js/main.js"></script>
</body>

</html>
