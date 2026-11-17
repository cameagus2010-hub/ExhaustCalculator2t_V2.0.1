<?php
/** @var string $titulo */
$usuario = usuario_actual();
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'ExhaustCalculator2T') ?></title>
    <link rel="stylesheet" href="/style.css">
    <script src="/app.js" defer></script>
</head>
<body>
<header class="barra">
    <a class="marca" href="/"><span class="marca-punto"></span>ExhaustCalculator<b>2T</b></a>
    <nav class="menu" aria-label="Secciones">
        <a href="/#inicio" data-sec="inicio">Inicio</a>
        <a href="/#funcionamiento" data-sec="funcionamiento">Cómo funciona</a>
        <a href="/#teoria" data-sec="teoria">El escape</a>
        <a href="/#ejemplo" data-sec="ejemplo">Ejemplo</a>
        <a href="/#construccion" data-sec="construccion">Construcción</a>
        <a href="/#descarga" data-sec="descarga">Descarga</a>
    </nav>
    <div class="cuenta">
        <?php if ($usuario): ?>
            <span class="usuario">▸ <?= e($usuario) ?></span>
            <a href="/logout.php">Salir</a>
        <?php else: ?>
            <a href="/login.php">Entrar</a>
            <a class="boton chico" href="/register.php">Registrarse</a>
        <?php endif; ?>
    </div>
</header>
<main>
