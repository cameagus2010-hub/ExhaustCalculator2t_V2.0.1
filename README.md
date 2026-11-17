# ExhaustCalculator2T

Calculadora de escapes de expansión para motores de **2 tiempos**, con interfaz gráfica en Java (Swing) y vista 2D a escala del perfil del escape.

A partir de los datos del motor (cilindrada, RPM objetivo, duración de escape y medidas del puerto) calcula la longitud afinada, el header, un difusor de 1 a 3 etapas, la panza, el contracono (baffle) y el stinger. Los métodos y valores recomendados siguen el libro *Two Stroke Performance Tuning*, de A. Graham Bell.

> **Aviso:** los resultados son una base de diseño. Bell mismo aclara que el largo del header y el comportamiento final se afinan probando en el motor. Verificá siempre las medidas antes de cortar chapa.

## Características

- Longitud afinada a partir de la duración de escape y las RPM.
- Header cónico: largo total y físico (descontando puerto y flange) y diámetro de salida.
- Difusor de 1, 2 o 3 etapas con ángulo y multiplicador configurables por etapa.
- Panza, OLB y TLB del contracono, y medidas del stinger.
- Valores recomendados según cilindrada, uso (Competición o Enduro / Motocross) y cantidad de etapas.
- Vista 2D del escape a escala, con cada tramo rotulado.

## Requisitos

- Java 17 o superior (JDK para compilar, JRE para ejecutar).

## Ejecutar

Desde el `.zip` de la última versión:

```bash
java -jar CalculadoraEscape2T.jar
```

Desde el código fuente:

```bash
git clone https://github.com/TU_USUARIO/TU_REPO.git
cd TU_REPO
# compilá con tu IDE o con javac, por ejemplo:
javac -d out src/com/agustin/calculadoraescape2t/*.java
java -cp out com.agustin.calculadoraescape2t.Main
```

## Cómo se usa

1. Elegí la **cilindrada** y el **uso**; el programa propone valores para header, difusor, baffle y stinger.
2. Cargá **RPM objetivo**, **duración de escape** (°) y las medidas del puerto.
3. Ajustá los valores que quieras.
4. Presioná **CALCULAR ESCAPE**: los resultados aparecen en el panel de texto y el perfil se dibuja en la vista 2D.

Detalle de cada campo y de las fórmulas: [DOCUMENTACION.md](DOCUMENTACION.md).

## Estructura

```
src/com/agustin/calculadoraescape2t/
├── Main.java               punto de entrada
├── VentanaPrincipal.java   interfaz (campos, botones, resultados)
├── CalculadoraEscape.java  motor de cálculo (sin dependencias de interfaz)
└── VistaEscape2D.java      dibujo 2D del perfil
```

## Fórmulas principales

| Magnitud | Fórmula |
|---|---|
| Longitud afinada | `L = duración (°) × 42545 / RPM` |
| Header total | `diámetro puerto × multiplicador` |
| Header físico | `total − largo puerto − largo flange` |
| Diámetro de salida del header | `Dp + 2 · Lfísico · tan(ángulo)` |
| Largo de una etapa de cono | `(Dfinal − Dinicial) / 2 · cot(ángulo)` |
| OLB / TLB | `Df/2 · cot(β)` / `(Df − Dstinger)/2 · cot(β)` |
| Panza | `L − (header total + difusor + OLB/2)` |

## Sitio web

El proyecto cuenta con una página web (PHP + MariaDB + Docker) con la presentación, la guía de construcción y el enlace de descarga. Ver la carpeta `web/` o el repositorio del sitio.

## Referencias

- A. Graham Bell, *Two Stroke Performance Tuning*.

## Licencia

Definí acá la licencia del proyecto (por ejemplo MIT) y agregá el archivo `LICENSE`.
