# Documentación de ExhaustCalculator2T

Este documento describe el proyecto completo: el **software de escritorio** (Java) y el **sitio web** (PHP + MariaDB + Docker).

## Índice

1. [Visión general](#1-visión-general)
2. [Software de escritorio](#2-software-de-escritorio)
3. [Sitio web](#3-sitio-web)
4. [Despliegue con Docker](#4-despliegue-con-docker)
5. [Publicar en Git](#5-publicar-en-git)
6. [Solución de problemas](#6-solución-de-problemas)
7. [Notas y limitaciones](#7-notas-y-limitaciones)

---

## 1. Visión general

| Parte | Tecnología | Función |
|---|---|---|
| Software | Java, Swing | Calcula y dibuja el escape de expansión 2T |
| Web | PHP 8.3, Apache | Presentación, guía de construcción, registro/login, descargas |
| Base de datos | MariaDB 11 | Guarda usuarios (nombre y hash de contraseña) |
| Despliegue | Docker Compose | Levanta web y base de datos con un comando |

---

## 2. Software de escritorio

Paquete: `com.agustin.calculadoraescape2t`.

### 2.1 Clases

| Clase | Responsabilidad |
|---|---|
| `Main` | Abre `VentanaPrincipal` en el hilo de Swing (`SwingUtilities.invokeLater`). |
| `VentanaPrincipal` | Construye la interfaz, lee los campos, llama al motor y muestra los resultados. |
| `CalculadoraEscape` | Motor de cálculo. Guarda entradas y resultados, valida, calcula y ofrece recomendaciones. No depende de la interfaz. |
| `CalculadoraEscape.ResultadoDifusor` | Contenedor de diámetros, longitudes y ángulos de cada etapa del difusor, más la longitud total. |
| `VistaEscape2D` | Panel que dibuja el perfil a escala a partir de los resultados. |

Flujo: `VentanaPrincipal.calcular()` → lee campos → setters de `CalculadoraEscape` → `calcular()` → `mostrarResultados()` → `VistaEscape2D.actualizar()`.

### 2.2 Datos de entrada

**Motor**

| Campo | Unidad | Descripción |
|---|---|---|
| Cilindrada | cc | Determina las recomendaciones. |
| RPM objetivo | rpm | Régimen del pico de potencia. |
| Duración escape | ° | Apertura del puerto de escape en grados de cigüeñal. |
| Diámetro puerto | mm | Base de las proporciones del escape. |
| Largo puerto | mm | Se descuenta del header total. |
| Largo flange | mm | Se descuenta del header total. |
| Uso | – | `Competición` o `Enduro / Motocross`. |
| Difusor | etapas | 1 a 3. |

**Header:** multiplicador (largo total = diámetro del puerto × multiplicador) y ángulo (°).

**Difusor:** multiplicador de diámetro (diámetro final = diámetro del puerto × multiplicador), ángulo de cada etapa (°) y multiplicador de cada etapa intermedia (entre 0 y 1).

**Baffle:** ángulo (°).

**Stinger:** diámetro interior (mm) y largo (mm).

### 2.3 Cálculo

Constante: `CONSTANTE_BELL = 42545`.

1. **Longitud afinada**
   `L = duración × 42545 / rpm`

2. **Header**
   - `headerTotal = Dp × multiplicadorHeader`
   - `headerFísico = headerTotal − largoPuerto − largoFlange`
   - `diámetroHeader = Dp + 2 · headerFísico · tan(ánguloHeader)`

3. **Diámetro final del difusor**
   `Df = Dp × multiplicadorDifusor`

4. **Difusor** (desde `diámetroHeader` hasta `Df`)
   Para cada etapa `i`, con diámetro actual `D` y ángulo `θ`:
   - Longitud teórica: `Lteo = (Df − D)/2 · cot(θ)`
   - Etapa intermedia: `L = Lteo × multiplicador[i]` y `D' = D + 2 · L · tan(θ)`
   - Última etapa: `L = Lteo`, y termina en `Df`.

5. **Baffle**
   - `OLB = (Df/2) · cot(β)`
   - `TLB = ((Df − Dstinger)/2) · cot(β)`

6. **Panza**
   `panza = L − (headerTotal + longitudDifusor + OLB/2)`

Se usa `OLB/2` porque la onda se refleja, en promedio, en la mitad del contracono completo (punto de reflexión medio).

### 2.4 Valores recomendados

| Concepto | Regla |
|---|---|
| Multiplicador del header | 8,0 si cilindrada ≤ 80 cc; 8,5 en otro caso |
| Multiplicador del difusor | 2,5 |
| Ángulos del difusor | 1 etapa: 6,5° · 2 etapas: 4,5° y 7° · 3 etapas: 4°, 6° y 8° |
| Ángulo del baffle | ≤ 80 cc: 11,25° (competición) o 9° (enduro/MX) · > 80 cc: 11° |
| Diámetro del stinger | ≤ 80 cc: 18 · ≤ 125 cc: 23 · mayor: 26 mm |
| Largo del stinger | ≤ 80 cc: 217,5 · ≤ 125 cc: 277,5 · mayor: 290 mm |
| Multiplicadores de etapa | 2 etapas: 0,50 · 3 etapas: 0,75 y 0,50 |

### 2.5 Validaciones

El motor lanza `IllegalArgumentException` (mostrada en un cuadro de diálogo) cuando:

- RPM, duración, diámetro del puerto, diámetro o largo del stinger son ≤ 0.
- Largo de puerto o de flange son negativos.
- Multiplicadores del header o del difusor son ≤ 0.
- Ángulo del header o del baffle no está entre 0 y 90 (exclusivo).
- La cantidad de etapas no es válida.
- El multiplicador de una etapa no está entre 0 y 1.
- El header físico resulta menor o igual a 0 (revisar multiplicador, puerto y flange).

Si la panza da negativa, la interfaz muestra una advertencia, porque las medidas elegidas no caben en la longitud afinada.

### 2.6 Vista 2D

`VistaEscape2D` arma una lista de segmentos (puerto, flange, header, cada etapa del difusor, panza, baffle y stinger), calcula una escala común, dibuja el contorno simétrico respecto al eje y rotula cada tramo. Los segmentos de largo cero o negativo no se dibujan.

### 2.7 Ejecución

```bash
java -jar CalculadoraEscape2T.jar
```

Requiere Java 17 o superior (JRE).

---

## 3. Sitio web

### 3.1 Estructura

```
escapes2t-web/
├── docker-compose.yml        servicios web y db
├── .env.example              variables de configuración (copiar como .env)
├── README.md
├── db/
│   └── init.sql              crea la tabla usuarios (primera vez)
└── web/
    ├── Dockerfile            php:8.3-apache + pdo_mysql
    └── src/
        ├── index.php         página principal (6 secciones)
        ├── register.php      registro de usuarios
        ├── login.php         inicio de sesión
        ├── logout.php        cierre de sesión
        ├── style.css         estilos
        ├── app.js            animaciones y menú activo
        ├── img/              imágenes (app.png, ejemplo-escape.svg, placeholder)
        └── includes/
            ├── bootstrap.php sesión, conexión PDO, CSRF y helpers
            ├── header.php    cabecera y menú
            └── footer.php    pie
```

### 3.2 Secciones de la página principal

1. **Inicio:** título, captura de la aplicación y para qué sirve.
2. **Cómo funciona:** datos de entrada y función de cada bloque.
3. **El escape de expansión:** teoría de ondas, partes del escape y enlace al blog.
4. **Ejemplo:** cálculo completo de un 125 cc de enduro/motocross y enlace al libro.
5. **Construcción real:** materiales, espesores, plantillas de conos, curvas, brida, silenciador, soportes y mantenimiento (según Bell).
6. **Descarga:** pasos para bajar, instalar Java y ejecutar, y enlace al repositorio.

### 3.3 Variables de entorno (`.env`)

| Variable | Descripción |
|---|---|
| `WEB_PORT` | Puerto local de la web (por defecto 8080). |
| `DB_NAME` | Nombre de la base de datos. |
| `DB_USER` | Usuario de la base de datos. |
| `DB_PASSWORD` | Contraseña de ese usuario. |
| `DB_ROOT_PASSWORD` | Contraseña de root de MariaDB. |
| `DOWNLOAD_URL` | Enlace de descarga del `.zip` del software. |
| `REPO_URL` | Enlace al repositorio de Git. |
| `BLOG_URL` | Enlace al artículo del blog (por defecto `https://motorscoot.net`). |
| `LIBRO_URL` | Enlace de descarga del libro de Graham Bell. |

### 3.4 Base de datos

Tabla `usuarios`:

| Columna | Tipo | Notas |
|---|---|---|
| `id` | INT UNSIGNED | Clave primaria, autoincremental |
| `nombre_usuario` | VARCHAR(50) | Único |
| `password_hash` | VARCHAR(255) | Hash generado con `password_hash()` |
| `creado_en` | TIMESTAMP | Fecha de alta |

`db/init.sql` solo se ejecuta cuando el volumen de la base está vacío.

### 3.5 Páginas de cuenta

- **Registro:** usuario de 3 a 50 caracteres (letras, números, `_`, `.`, `-`), contraseña de al menos 8 caracteres, confirmación y comprobación de usuario duplicado.
- **Login:** verifica con `password_verify`; mensaje genérico si falla.
- **Logout:** destruye la sesión.

### 3.6 Seguridad

- Consultas preparadas (PDO) contra inyección SQL.
- Contraseñas con hash (`password_hash`/`password_verify`).
- Token CSRF en los formularios.
- `session_regenerate_id(true)` al registrarse o iniciar sesión; cookie de sesión `httponly`.
- Salida escapada con `htmlspecialchars`.
- Para internet: usar HTTPS (proxy inverso como Caddy o Nginx), no exponer la base de datos y usar contraseñas fuertes en `.env`.
- No subir `.env` a Git.

---

## 4. Despliegue con Docker

Requisitos: Docker y Docker Compose.

```bash
cp .env.example .env        # editar valores
docker compose up -d --build
```

Abrir `http://localhost:8080` (o el puerto de `WEB_PORT`).

### Comandos útiles

```bash
docker compose ps                 # estado de los servicios
docker compose logs -f web        # logs de la web
docker compose logs -f db         # logs de la base
docker compose down               # detener
docker compose down -v            # detener y BORRAR la base de datos
docker compose up -d --build      # reconstruir tras cambiar archivos de web/src
```

### Cambios en la web

Los archivos de `web/src/` se copian dentro de la imagen al construirla, por eso después de editarlos hay que reconstruir con `docker compose up -d --build`.

### Respaldo de la base de datos

```bash
docker compose exec db sh -c 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' > respaldo.sql
```

Restaurar:

```bash
docker compose exec -T db sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < respaldo.sql
```

### Linux: permiso de Docker

Si aparece `permission denied ... docker.sock`:

```bash
sudo usermod -aG docker $USER
```

y cerrar sesión y volver a entrar.

---

## 5. Publicar en Git

Se recomiendan dos repositorios (o dos carpetas): uno para el software Java y otro para la web.

`.gitignore` sugerido para la web:

```
.env
```

`.gitignore` sugerido para el software Java:

```
out/
target/
build/
*.class
.idea/
*.iml
.vscode/
```

Para ofrecer el `.zip` del programa, creá un **Release** en el repositorio, adjuntá el `.zip` y copiá su enlace de descarga a `DOWNLOAD_URL`.

---

## 6. Solución de problemas

| Síntoma | Causa probable | Solución |
|---|---|---|
| "El header físico resultó menor o igual a 0" | Header total ≤ largo de puerto + flange | Subir el multiplicador del header o reducir puerto/flange |
| Panza negativa | Las medidas del header, difusor y baffle superan la longitud afinada | Revisar RPM, duración de escape, ángulos y multiplicadores |
| La web no carga | Contenedor caído o puerto ocupado | `docker compose ps`, `docker compose logs web`; cambiar `WEB_PORT` |
| "No se pudo conectar con la base de datos" | La base todavía arranca o las credenciales cambiaron | Esperar a que `db` esté `healthy`; si cambiaste las contraseñas, `docker compose down -v` y volver a levantar |
| Los links de la web apuntan a `TU_USUARIO/TU_REPO` | Falta editar `.env` | Poner los enlaces reales y reiniciar con `docker compose up -d` |
| Imagen de la app es una ilustración | No existe `web/src/img/app.png` | Guardar una captura con ese nombre y reconstruir |

---

## 7. Notas y limitaciones

- Los valores recomendados son puntos de partida. No reemplazan las pruebas en banco o en pista.
- El **multiplicador por etapa** del difusor es una convención del programa (fracción de la longitud teórica de la etapa). Bell no la define: solo indica el largo de la primera sección, por ejemplo 200 a 240 mm para motocross y enduro de dos etapas, y 110 a 160 mm en carretera según el número de etapas.
- Bell indica que en motocross y enduro se usan como máximo dos etapas de difusor.
- Bell indica que el largo del header solo es una estimación inicial y se determina probando; su regla de multiplicadores vale si el puerto tiene un diámetro estándar para esa cilindrada.
- La constante 42545 equivale a una velocidad del sonido de unos 510 m/s; Bell señala que depende de la temperatura de los gases.
- Las equivalencias de calibre de chapa a milímetros que muestra la web son aproximadas.

## Referencias

- A. Graham Bell, *Two Stroke Performance Tuning*.
