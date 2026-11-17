# Web de la Calculadora de escapes 2T

Sitio en PHP 8.3 + Apache con base de datos MariaDB, listo para levantar con Docker.
Incluye registro e inicio de sesión (usuario y contraseña), link de descarga del `.zip` y link al repositorio de Git.

## Cómo levantarlo

1. Levantá todo:

   ```bash
   docker compose up -d --build
   ```

2. Abrí http://localhost:8080 (o el puerto que pusiste en `WEB_PORT`).

## Comandos útiles

```bash
docker compose logs -f        # ver logs
docker compose down           # detener
docker compose down -v        # detener y BORRAR la base de datos
```

## Estructura

```
docker-compose.yml     servicios web y db
.env.example           variables de configuración
db/init.sql            crea la tabla `usuarios` la primera vez
web/Dockerfile         imagen PHP + Apache
web/src/               páginas del sitio
```

## Base de datos

Tabla `usuarios`: `id`, `nombre_usuario` (único), `password_hash`, `creado_en`.
Las contraseñas se guardan con `password_hash()` (nunca en texto plano).

`db/init.sql` solo se ejecuta cuando el volumen de la base está vacío. Si cambiás ese archivo,
corré `docker compose down -v` para recrearla.

## Seguridad

- Consultas preparadas (PDO) contra inyección SQL.
- Token CSRF en los formularios.
- `session_regenerate_id` al iniciar sesión.
- Salida escapada con `htmlspecialchars`.
- Para publicarlo en internet, ponelo detrás de HTTPS (por ejemplo con un proxy inverso como Caddy o Nginx).
