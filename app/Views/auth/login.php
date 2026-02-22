<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="Inicia sesión en FrameworkMVC.">
    <link rel="stylesheet" href="<?= $base_url ?>/css/main.css">
    <style>
        .form-card { max-width: 400px; }
        .form-group { margin-bottom: 1rem; text-align: left; }
        label { display: block; margin-bottom: 0.5rem; color: var(--dark-color); font-weight: 500; }
        input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 1rem; }
        input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(52,152,219,.2); }
        .error-msg { color: #e74c3c; background: #fde8e8; padding: 10px; border-radius: 4px; margin-bottom: 1rem; }
        .demo-hint { margin-top: 1rem; font-size: 0.85rem; color: #888; background: #f8f9fa; padding: .75rem; border-radius: 6px; }
    </style>
</head>

<body>
    <div class="card form-card">
        <h1>Iniciar Sesión</h1>
        <p>Ingresa tus datos para acceder</p>

        <?php if (isset($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= $base_url ?>/login" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                       value="<?= htmlspecialchars($email ?? '') ?>"
                       placeholder="admin@test.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••" required>
            </div>

            <button type="submit" class="btn" style="width:100%; cursor:pointer;">Entrar</button>
        </form>

        <div class="demo-hint">
            <strong>Datos demo:</strong><br>
            User: <code>admin@test.com</code> · Pass: <code>123456</code>
        </div>

        <p style="margin-top:1rem; font-size:.9rem;">
            ¿No tienes cuenta? <a href="<?= $base_url ?>/register">Regístrate</a>
        </p>
    </div>
</body>

</html>
