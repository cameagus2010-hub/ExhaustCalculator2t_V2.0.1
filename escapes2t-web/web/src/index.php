<?php
require __DIR__ . '/includes/bootstrap.php';
$titulo = 'ExhaustCalculator2T · Diseño de escapes de expansión 2T';

$imagenApp = file_exists(__DIR__ . '/img/app.png') ? '/img/app.png' : '/img/app-placeholder.png';

require __DIR__ . '/includes/header.php';
?>

<!-- ============================ 1. INICIO ============================ -->
<section id="inicio" data-seccion="inicio" class="seccion hero">
    <p class="etiqueta">01 // INICIO</p>
    <h1 class="titulo-grande">Exhaust<span>Calculator</span>2T</h1>
    <p class="subtitulo">Diseñá el escape de expansión de tu motor de 2 tiempos con datos, no con suposiciones.</p>

    <figure class="captura reveal">
        <div class="marco-ventana">
            <span></span><span></span><span></span>
            <em>ExhaustCalculator2T</em>
        </div>
        <img src="<?= e($imagenApp) ?>" alt="Captura de la aplicación ExhaustCalculator2T">
    </figure>

    <div class="texto-centrado reveal">
        <h2>¿Para qué sirve?</h2>
        <p>
            ExhaustCalculator2T calcula las dimensiones de una cámara de expansión para motores de dos tiempos
            a partir de los datos de tu motor: cilindrada, régimen objetivo y duración de escape.
            Te entrega la longitud afinada, el header, un difusor de una a tres etapas, la panza, el contracono (baffle)
            y el stinger, y dibuja el perfil completo en 2D para que veas cómo queda antes de cortar una sola chapa.
        </p>
        <p>
            Los métodos y los valores recomendados siguen el libro <em>Two Stroke Performance Tuning</em>
            de A. Graham Bell, y todos los números se pueden modificar.
        </p>
    </div>

    <div class="acciones reveal">
        <a class="boton" href="#descarga">Descargar</a>
        <a class="boton secundario" href="#funcionamiento">Cómo funciona</a>
    </div>
</section>

<!-- ======================== 2. CÓMO FUNCIONA ======================== -->
<section id="funcionamiento" data-seccion="funcionamiento" class="seccion">
    <p class="etiqueta">02 // CÓMO FUNCIONA LA APLICACIÓN</p>
    <h2 class="titulo">Cargás los datos, el programa hace la geometría</h2>
    <p class="intro reveal">
        La ventana tiene cinco bloques de datos. Al elegir la cilindrada y el uso, el programa propone valores
        iniciales para casi todo; vos los ajustás y presionás <strong>Calcular escape</strong>.
    </p>

    <div class="rejilla reveal">
        <article class="modulo">
            <h3><i>A</i> Motor</h3>
            <ul>
                <li><b>Cilindrada (cc):</b> define las recomendaciones de header, baffle y stinger.</li>
                <li><b>RPM objetivo:</b> régimen en el que querés el pico de potencia.</li>
                <li><b>Duración de escape (°):</b> apertura del puerto de escape medida en grados de cigüeñal.</li>
                <li><b>Diámetro del puerto (mm):</b> base de todas las proporciones del escape.</li>
                <li><b>Largo de puerto y de flange (mm):</b> se descuentan del largo total del header.</li>
                <li><b>Uso:</b> Competición o Enduro / Motocross.</li>
                <li><b>Difusor:</b> cantidad de etapas (hasta 3 en competición, hasta 2 en enduro y motocross).</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>B</i> Header</h3>
            <ul>
                <li><b>Multiplicador:</b> largo total del header = diámetro del puerto × multiplicador. Ese largo se mide desde la cara del pistón.</li>
                <li><b>Ángulo (°):</b> conicidad del header. Con él se calcula el diámetro con el que sale hacia el difusor.</li>
            </ul>
            <p class="nota">Largo físico = largo total − largo de puerto − largo de flange.</p>
        </article>

        <article class="modulo">
            <h3><i>C</i> Difusor</h3>
            <ul>
                <li><b>Multiplicador de diámetro:</b> diámetro final del difusor = diámetro del puerto × multiplicador (la referencia es 2,5).</li>
                <li><b>Ángulo por etapa (°):</b> cuanto más cerrado, mayor es el rango útil de potencia; cuanto más abierto, mayor es el pico pero más angosta la banda.</li>
                <li><b>Multiplicador por etapa (0 a 1):</b> qué fracción de la longitud teórica de esa etapa se usa antes de pasar a la siguiente. La última etapa siempre llega al diámetro final.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>D</i> Baffle / contracono</h3>
            <ul>
                <li><b>Ángulo (°):</b> inclinación del cono de salida.</li>
            </ul>
            <p class="nota">Con él se obtienen <b>OLB</b> (largo del cono completo) y <b>TLB</b> (largo real del cono, ya recortado por el stinger).</p>
        </article>

        <article class="modulo">
            <h3><i>E</i> Stinger</h3>
            <ul>
                <li><b>Diámetro interior (mm):</b> controla la contrapresión del sistema.</li>
                <li><b>Largo (mm):</b> tramo recto de salida.</li>
            </ul>
            <p class="nota">Un stinger demasiado chico o largo puede recalentar y griparse el motor.</p>
        </article>

        <article class="modulo resultado">
            <h3><i>▶</i> Resultados</h3>
            <ul>
                <li>Longitud afinada total.</li>
                <li>Header total, header físico y diámetro de salida.</li>
                <li>Cada etapa del difusor: ángulo, diámetros y longitud.</li>
                <li>Largo de la panza (parte cilíndrica central).</li>
                <li>OLB, TLB y medidas del stinger.</li>
                <li>Vista 2D a escala con todos los tramos.</li>
            </ul>
        </article>
    </div>
</section>

<!-- ======================== 3. EL ESCAPE EXPANSIVO ======================== -->
<section id="teoria" data-seccion="teoria" class="seccion">
    <p class="etiqueta">03 // EL ESCAPE DE EXPANSIÓN</p>
    <h2 class="titulo">Un escape que trabaja con ondas de presión</h2>

    <div class="dos-col reveal">
        <div>
            <p>
                Cuando se abre el puerto de escape, los gases salen empujando una onda de presión por el caño a la velocidad
                del sonido. Una onda reflejada vuelve con signo opuesto cuando encuentra un ensanchamiento, y con el mismo signo
                cuando encuentra un estrechamiento. La cámara de expansión está diseñada para aprovechar esas reflexiones:
            </p>
            <ol class="pasos">
                <li><b>Escape abierto.</b> La onda positiva viaja por el header hacia el difusor.</li>
                <li><b>El difusor la invierte.</b> Vuelve como onda negativa, ayuda a vaciar el cilindro y tira de la mezcla fresca desde el cárter hacia arriba por los transfers.</li>
                <li><b>El contracono la devuelve.</b> Refleja una onda positiva que llega justo antes de que se cierre el puerto y empuja de vuelta al cilindro la mezcla que se escapó.</li>
            </ol>
            <p>
                Para que esa última onda llegue a tiempo, la cámara tiene que tener una longitud afinada:
                <code>L = duración de escape × 42545 / RPM</code>, medida desde la cara del pistón hasta
                el punto medio del contracono. Ejemplo del libro: 196° a 11.000 rpm da unos 758 mm.
            </p>
        </div>

        <figure class="esquema">
            <svg viewBox="0 0 1000 260" role="img" aria-label="Esquema de una cámara de expansión con sus partes">
                <path d="M40,106 L220,100 L340,86 L480,52 L640,52 L860,112 L860,128 L640,188 L480,188 L340,154 L220,140 L40,134 Z"
                      fill="rgba(0,229,255,.10)" stroke="#00e5ff" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M860,112 L970,112 L970,128 L860,128 Z" fill="rgba(0,229,255,.10)" stroke="#00e5ff" stroke-width="2.5"/>
                <line x1="20" y1="120" x2="985" y2="120" stroke="#4b5b73" stroke-dasharray="6 6"/>
                <g stroke="#6f819c" stroke-dasharray="3 5">
                    <line x1="220" y1="30" x2="220" y2="230"/><line x1="480" y1="30" x2="480" y2="230"/>
                    <line x1="640" y1="30" x2="640" y2="230"/><line x1="860" y1="30" x2="860" y2="230"/>
                </g>
                <g fill="#d7e4f5" font-family="monospace" font-size="15" font-weight="700" text-anchor="middle">
                    <text x="130" y="24">HEADER</text>
                    <text x="350" y="24">DIFUSOR</text>
                    <text x="560" y="24">PANZA</text>
                    <text x="750" y="24">BAFFLE</text>
                    <text x="925" y="24">STINGER</text>
                </g>
                <text x="45" y="250" fill="#9fb3cc" font-family="monospace" font-size="13">← cilindro</text>
            </svg>
            <figcaption>Esquema de las partes (no está a escala).</figcaption>
        </figure>
    </div>

    <div class="rejilla reveal">
        <article class="modulo">
            <h3>Header</h3>
            <p>Primer tramo, que sale del cilindro. Conviene que sea cónico (entre 1,15° y 1,5° normalmente): aumenta el volumen, reduce la resistencia al flujo y deja que el gas se expanda y enfríe de forma gradual. Un header más largo mejora la zona media pero baja la potencia máxima.</p>
        </article>
        <article class="modulo">
            <h3>Difusor</h3>
            <p>Convierte la onda positiva en negativa. Su ángulo manda: poco ángulo da un rango útil ancho y pico más bajo; mucho ángulo, lo contrario. Se llega normalmente a 2,5 veces el diámetro del puerto. Los difusores de varias etapas pierden menos energía de pulso.</p>
        </article>
        <article class="modulo">
            <h3>Panza</h3>
            <p>Tramo cilíndrico central, del mismo diámetro que la salida del difusor. Su largo es lo que falta para completar la longitud afinada una vez definidos los demás tramos.</p>
        </article>
        <article class="modulo">
            <h3>Baffle (contracono)</h3>
            <p>Refleja la onda con el mismo signo para devolver la mezcla al cilindro. Un cono corto y cerrado sube el pico pero el motor se corta rápido pasado el régimen máximo; uno más suave da una banda más ancha.</p>
        </article>
        <article class="modulo">
            <h3>Stinger</h3>
            <p>Es un tubo de escape de sangrado: deja salir los gases de forma controlada y crea la contrapresión que ayuda a empujar la mezcla de vuelta. Sus medidas son críticas para la temperatura del motor.</p>
        </article>
    </div>

    <div class="enlace-bloque reveal">
        <p>¿Querés profundizar en la teoría?</p>
        <a class="boton secundario" href="<?= e(url_blog()) ?>" target="_blank" rel="noopener">Leer en el blog de motorscoot.net →</a>
    </div>
</section>

<!-- ============================ 4. EJEMPLO ============================ -->
<section id="ejemplo" data-seccion="ejemplo" class="seccion">
    <p class="etiqueta">04 // EJEMPLO PASO A PASO</p>
    <h2 class="titulo">Un escape para un 125 cc de enduro / motocross</h2>

    <p class="intro reveal">
        Ejemplo de uso con los valores que propone el programa para esta cilindrada. Son datos de demostración:
        antes de construir nada, usá los de tu motor.
    </p>

    <div class="dos-col reveal">
        <div class="panel">
            <h3>Datos de entrada</h3>
            <table class="tabla">
                <tr><th>Cilindrada</th><td>125 cc</td></tr>
                <tr><th>Uso</th><td>Enduro / Motocross, 2 etapas</td></tr>
                <tr><th>RPM objetivo</th><td>11.000</td></tr>
                <tr><th>Duración de escape</th><td>197°</td></tr>
                <tr><th>Puerto / flange</th><td>Ø 38 mm · 20 mm + 20 mm</td></tr>
                <tr><th>Header</th><td>×8,5 · 1,25°</td></tr>
                <tr><th>Difusor</th><td>×2,5 · 4,5° y 7° · etapa 1 al 75 %</td></tr>
                <tr><th>Baffle</th><td>11°</td></tr>
                <tr><th>Stinger</th><td>Ø 23 mm × 277,5 mm</td></tr>
            </table>
        </div>

        <div class="panel">
            <h3>Resultados</h3>
            <table class="tabla">
                <tr><th>Longitud afinada</th><td>761,9 mm</td></tr>
                <tr><th>Header total / físico</th><td>323,0 mm / 283,0 mm</td></tr>
                <tr><th>Salida del header</th><td>Ø 50,4 mm</td></tr>
                <tr><th>Difusor, etapa 1</th><td>Ø 50,4 → 83,8 mm · 212,7 mm</td></tr>
                <tr><th>Difusor, etapa 2</th><td>Ø 83,8 → 95,0 mm · 45,5 mm</td></tr>
                <tr><th>Panza</th><td>Ø 95,0 mm · 58,6 mm</td></tr>
                <tr><th>Baffle</th><td>OLB 244,4 mm · TLB 185,2 mm</td></tr>
                <tr><th>Stinger</th><td>Ø 23 mm · 277,5 mm</td></tr>
            </table>
        </div>
    </div>

    <figure class="perfil reveal">
        <img src="/img/ejemplo-escape.svg" alt="Perfil 2D del escape de ejemplo, con la longitud de cada tramo">
        <figcaption>Vista 2D del ejemplo, a escala horizontal y vertical común.</figcaption>
    </figure>

    <p class="intro reveal">
        Control rápido: header total (323) + difusor (258,2) + mitad del contracono (122,2) + panza (58,6) = 762 mm,
        que coincide con la longitud afinada. La longitud de la primera etapa del difusor (213 mm) cae dentro del rango
        que Bell da para motocross y enduro de dos etapas (200 a 240 mm).
    </p>

    <div class="enlace-bloque reveal">
        <p>Todo el método de cálculo sale del libro de Graham Bell.</p>
        <a class="boton secundario" href="<?= e(url_libro()) ?>" target="_blank" rel="noopener">Descargar el libro (PDF) →</a>
    </div>
</section>

<!-- ======================== 5. CONSTRUCCIÓN ======================== -->
<section id="construccion" data-seccion="construccion" class="seccion">
    <p class="etiqueta">05 // CÓMO ARMARLO EN LA VIDA REAL</p>
    <h2 class="titulo">Del cálculo a la chapa, según Graham Bell</h2>

    <p class="intro reveal">
        Bell no da un procedimiento paso a paso de taller, pero sí varias pautas muy concretas. Esta sección las reúne.
        Lo que no figura en el libro está marcado como <span class="marca-propia">consejo práctico</span>.
    </p>

    <div class="rejilla reveal">
        <article class="modulo">
            <h3><i>1</i> Material y espesor de chapa</h3>
            <ul>
                <li>Chapa de acero calibre <b>16 o 18</b> (≈ 1,5 y 1,2 mm): más fácil de limpiar y más rígida.</li>
                <li>Calibre <b>20 o 22</b> (≈ 0,9 y 0,75 mm): más liviana, pero se deforma al calentarla para limpiarla.</li>
                <li>En una de sus modificaciones (YZ80F) usa conos de <b>1 mm</b> de acero.</li>
                <li>En cámaras de competencia con silenciador integrado, el cuerpo del silenciador es de aluminio calibre 16 (≈ 1,3 mm).</li>
            </ul>
            <p class="nota">Las equivalencias calibre → mm son aproximadas y de chapa de acero estándar; Bell solo da el calibre.</p>
        </article>

        <article class="modulo">
            <h3><i>2</i> Dimensiones a tomar del programa</h3>
            <ul>
                <li>Largo del header = diámetro del puerto × multiplicador, <b>menos el largo del puerto</b> (y de la brida).</li>
                <li>Header cónico de 1,15° a 1,5° normalmente.</li>
                <li>Difusor hasta unas 2,5 veces el diámetro del puerto; en motos grandes puede quedar más chico (≈ 2,2) por falta de espacio, ya que pocas aceptan más de 110 mm.</li>
                <li>Stinger por cilindrada: 50–80 cc: Ø 17–19 × 205–230 mm · 100 cc: Ø 19–21 × 230–250 · 125 cc: Ø 22–24 × 265–290 · 175 cc: Ø 25–27 × 270–295 · 250 cc: Ø 26–28 × 280–305 · 350–500 cc: Ø 27–29 × 285–310. Empezá por valores intermedios, no por el diámetro más chico y el largo más grande.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>3</i> Plantilla de cada cono</h3>
            <p>Cada cono (difusor, contracono o header cónico) se desarrolla sobre una chapa plana como un sector de corona circular. Hacé primero la plantilla en <b>cartulina rígida</b> y recién después pasala a la chapa.</p>
            <ul>
                <li>Largo del lado inclinado: <code>s = √(L² + (R − r)²)</code></li>
                <li>Radio mayor del arco: <code>A = s · R / (R − r)</code></li>
                <li>Radio menor del arco: <code>A₁ = s · r / (R − r)</code></li>
                <li>Ángulo del sector: <code>θ ≈ conicidad × 2π</code> (en grados)</li>
            </ul>
            <p class="nota">L: largo del cono por el eje · R y r: radios mayor y menor. Bell usa la última fórmula como control de las anteriores.</p>
            <p class="nota">Ejemplo, contracono del ejemplo anterior (R = 47,5, r = 11,5, L = 185,2): s ≈ 188,7 · A ≈ 248,9 · A₁ ≈ 60,3 · θ ≈ 68,7°.</p>
        </article>

        <article class="modulo">
            <h3><i>4</i> Corte, rolado y soldadura</h3>
            <ul>
                <li>Si no tenés experiencia con chapa, Bell recomienda llevar las plantillas a un taller de chapistería para que corten y rolen los conos.</li>
                <li>Con los conos formados se sueldan todas las piezas entre sí.</li>
                <li>Si hay que rellenar el puerto de aluminio del cilindro, se suelda con arco de argón y de a poco, dejando enfriar entre pasadas para que no se deforme.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>5</i> Cuidados de diseño</h3>
            <ul>
                <li>La unión del header con el difusor debe ser <b>lisa</b>, sin escalones; un cambio brusco genera turbulencia y debilita el pulso.</li>
                <li><b>No aplastes</b> la cámara en ningún punto: cualquier cambio de sección transversal quita energía a la onda.</li>
                <li>Las curvas y codos <b>no</b> molestan a las ondas, así que podés cortar y reubicar los conos para que entre en la moto.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>6</i> Cómo hacer una curva sin acortar el cono</h3>
            <ul>
                <li>Primer corte: <b>de lado a lado</b> del cono.</li>
                <li>Segundo corte: solo <b>hasta el centro</b>.</li>
                <li>La cuña que se quita se vuelve a soldar en la parte <b>exterior</b> de la curva.</li>
            </ul>
            <p class="nota">Así el largo medido por el eje queda igual y no se altera la longitud afinada. Cortar y volver a soldar "a lo fácil" la acorta y cambia el ángulo.</p>
        </article>

        <article class="modulo">
            <h3><i>7</i> Brida y junta del header</h3>
            <ul>
                <li>El header y la brida deben <b>solaparse al menos 25 mm</b>.</li>
                <li>Se sujeta con resortes (2 o 3) o con tuerca de brida asegurada con resortes.</li>
                <li>Cualquier fuga cambia la presión interna y desafina el sistema; sellá las juntas con silicona RTV tipo Silastic.</li>
                <li>La brida debe quedar alineada con el puerto, sin cambios bruscos de dirección.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>8</i> Silenciador</h3>
            <ul>
                <li>Tubo perforado de unos <b>8 mm más de diámetro</b> que el stinger, envuelto en lana de vidrio.</li>
                <li>Para uso en pista, el silenciador puede ir integrado a la cámara; el nivel permitido suele ser de unos 96 dBA.</li>
                <li>Debe poder desarmarse para cambiar la lana de vidrio cuando se llene de aceite y carbón.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>9</i> Soportes</h3>
            <ul>
                <li>Soportes soldados con <b>área de apoyo grande</b>, para repartir la carga y evitar fisuras.</li>
                <li>Si empieza a rajarse, soldar una varilla de 6 mm en las esquinas donde el soporte se separa bruscamente.</li>
                <li>En karts se usa una "silla" de acero de 4 × 13 mm con dos resortes.</li>
            </ul>
        </article>

        <article class="modulo">
            <h3><i>10</i> Mantenimiento</h3>
            <ul>
                <li>Después de unas carreras se forma una capa de carbón de 1 a 2 mm en el header; hay que limpiarla.</li>
                <li>Bell la quema con soplete mientras golpea suavemente la cámara. Hacelo al aire libre: los humos son muy irritantes.</li>
                <li>Revisá si hay fisuras, sobre todo en los soportes y en las costuras de la panza.</li>
            </ul>
        </article>
    </div>

    <p class="aviso reveal">
        <b>Antes de cortar chapa:</b> el libro aclara que la primera cámara que fabriques rara vez será perfecta y que la longitud del header
        se afina probando. Hacé pruebas con el motor y cambiá una sola cosa por vez.
    </p>
</section>

<!-- ============================ 6. DESCARGA ============================ -->
<section id="descarga" data-seccion="descarga" class="seccion">
    <p class="etiqueta">06 // DESCARGA Y PUESTA EN MARCHA</p>
    <h2 class="titulo">Bajalo y corrélo en tres pasos</h2>

    <div class="rejilla tres reveal">
        <article class="modulo paso">
            <span class="num">1</span>
            <h3>Descargá</h3>
            <p>Bajá el archivo <code>.zip</code> y descomprimilo en una carpeta.</p>
            <a class="boton" href="<?= e(url_descarga()) ?>">Descargar .zip</a>
        </article>
        <article class="modulo paso">
            <span class="num">2</span>
            <h3>Instalá Java</h3>
            <p>El programa está hecho en Java (interfaz Swing), así que necesitás un entorno de ejecución de Java instalado.</p>
            <p class="nota">Linux Mint / Ubuntu:<br><code>sudo apt install default-jre</code></p>
            <p class="nota">Windows y Mac: descargá un JDK/JRE actual, por ejemplo Eclipse Temurin.</p>
        </article>
        <article class="modulo paso">
            <span class="num">3</span>
            <h3>Ejecutalo</h3>
            <p>Entrá a la carpeta descomprimida y ejecutá:</p>
            <p><code>java -jar "Exaust calculator V2.0.1.jar"</code></p>
            <p class="nota">En muchos sistemas alcanza con hacer doble clic en el archivo <code>.jar</code>.</p>
        </article>
    </div>

    <div class="enlace-bloque reveal">
        <p>¿Preferís el código fuente?</p>
        <a class="boton secundario" href="<?= e(url_repo()) ?>" target="_blank" rel="noopener">Ver el repositorio en Git →</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
