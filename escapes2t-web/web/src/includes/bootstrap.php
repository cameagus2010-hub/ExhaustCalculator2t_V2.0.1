<?php
declare(strict_types=1);

ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
session_start();

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        getenv('DB_HOST') ?: 'db',
        getenv('DB_NAME') ?: 'escapes2t'
    );

    $pdo = new PDO($dsn, getenv('DB_USER') ?: '', getenv('DB_PASSWORD') ?: '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valido(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}

function usuario_actual(): ?string
{
    return $_SESSION['usuario'] ?? null;
}

function url_descarga(): string
{
    return getenv('DOWNLOAD_URL') ?: '#';
}

function url_repo(): string
{
    return getenv('REPO_URL') ?: '#';
}

function url_blog(): string
{
    return getenv('BLOG_URL') ?: 'https://motorscoot.net';
}

function url_libro(): string
{
    return getenv('LIBRO_URL') ?: '#';
}
