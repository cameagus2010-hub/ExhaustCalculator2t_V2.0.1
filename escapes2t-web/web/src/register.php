<?php
require __DIR__ . '/includes/bootstrap.php';

if (usuario_actual()) {
    header('Location: /');
    exit;
}

$error = '';
$nombre = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim((string) ($_POST['nombre_usuario'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $password2 = (string) ($_POST['password2'] ?? '');

    if (!csrf_valido()) {
        $error = 'Sesión inválida, recargá la página e intentá de nuevo.';
    } elseif (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $nombre)) {
        $error = 'El usuario debe tener entre 3 y 50 caracteres (letras, números, _ . -).';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $password2) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            $stmt = db()->prepare('SELECT id FROM usuarios WHERE nombre_usuario = ?');
            $stmt->execute([$nombre]);
            if ($stmt->fetch()) {
                $error = 'Ese nombre de usuario ya está en uso.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = db()->prepare('INSERT INTO usuarios (nombre_usuario, password_hash) VALUES (?, ?)');
                $ins->execute([$nombre, $hash]);

                session_regenerate_id(true);
                $_SESSION['usuario'] = $nombre;
                header('Location: /');
                exit;
            }
        } catch (PDOException $ex) {
            $error = 'No se pudo conectar con la base de datos.';
        }
    }
}

$titulo = 'Registrarse';
require __DIR__ . '/includes/header.php';
?>
<section class="formulario">
    <h1>Crear cuenta</h1>
    <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <label>Nombre de usuario
            <input type="text" name="nombre_usuario" value="<?= e($nombre) ?>" required maxlength="50">
        </label>
        <label>Contraseña
            <input type="password" name="password" required minlength="8">
        </label>
        <label>Repetir contraseña
            <input type="password" name="password2" required minlength="8">
        </label>
        <button class="boton" type="submit">Registrarse</button>
    </form>
    <p>¿Ya tenés cuenta? <a href="/login.php">Iniciá sesión</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
