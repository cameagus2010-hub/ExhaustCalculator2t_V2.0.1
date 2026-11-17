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

    if (!csrf_valido()) {
        $error = 'Sesión inválida, recargá la página e intentá de nuevo.';
    } else {
        try {
            $stmt = db()->prepare('SELECT password_hash FROM usuarios WHERE nombre_usuario = ?');
            $stmt->execute([$nombre]);
            $fila = $stmt->fetch();

            if ($fila && password_verify($password, $fila['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = $nombre;
                header('Location: /');
                exit;
            }
            $error = 'Usuario o contraseña incorrectos.';
        } catch (PDOException $ex) {
            $error = 'No se pudo conectar con la base de datos.';
        }
    }
}

$titulo = 'Iniciar sesión';
require __DIR__ . '/includes/header.php';
?>
<section class="formulario">
    <h1>Iniciar sesión</h1>
    <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <label>Nombre de usuario
            <input type="text" name="nombre_usuario" value="<?= e($nombre) ?>" required maxlength="50">
        </label>
        <label>Contraseña
            <input type="password" name="password" required>
        </label>
        <button class="boton" type="submit">Entrar</button>
    </form>
    <p>¿No tenés cuenta? <a href="/register.php">Registrate</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
