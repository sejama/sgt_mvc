## Context

El `Jugador` representa a una persona (jugador o cuerpo técnico) inscripta en un `Equipo`. Su identificador de negocio es la combinación `tipoDocumento + numeroDocumento`. El `JugadorManager` delega validaciones de formato al `ValidadorManager` y maneja la persistencia.

Este documento registra tres divergencias entre el diseño esperado y el comportamiento observable del código actual en `editarJugador()`. Se documentan como estado del sistema, no como intención de diseño, para que los specs sirvan como línea base ante futuros refactors.

## Goals / Non-Goals

**Goals:**
- Documentar el contrato de `crearJugador()`: unicidad, validaciones, campos opcionales.
- Documentar el comportamiento observable de `editarJugador()` incluyendo las tres divergencias.
- Documentar eliminación y consultas.

**Non-Goals:**
- No corregir las divergencias en este change.
- No agregar validación de dominio para el campo `tipo` (libre en el manager actual).

## Decisions

### D1 — Unicidad de documento en creación: scoped por equipo

`crearJugador()` verifica la combinación `(equipo, tipoDocumento, numeroDocumento)`. Dos jugadores en equipos distintos pueden tener el mismo documento. Esto refleja el caso de uso de torneos donde el mismo jugador puede estar en equipos de distintas categorías.

### D2 — Unicidad de documento en edición: GLOBAL (divergencia documentada)

`editarJugador()` verifica `findOneBy(['numeroDocumento' => $numeroDocumento])` sin filtro de equipo. Si el número de documento cambia a uno que ya existe en **cualquier** jugador del sistema (independientemente del equipo), lanza `AppException`. Esta asimetría con la creación es un comportamiento del código actual.

### D3 — Apellido no se actualiza en edición (divergencia documentada)

`editarJugador()` llama `setNombre()` pero nunca `setApellido()`. El apellido de un jugador no puede modificarse a través de este método. El campo `apellido` del `Jugador` permanece con el valor original independientemente del argumento pasado.

### D4 — fechaNacimiento nula en edición produce fecha actual (divergencia documentada)

La expresión `new \DateTimeImmutable($fechaNacimiento) ?? null` evalúa el null-coalesce sobre el resultado del constructor, no sobre el argumento. Si `$fechaNacimiento` es `null`, `new \DateTimeImmutable(null)` lanza `TypeError` en PHP estricto. En la práctica, si llega como cadena vacía `""`, construye la fecha actual. El campo nacimiento no puede limpiarse a nulo mediante edición.

### D5 — fechaNacimiento opcional solo en creación

En `crearJugador()`, si `$fechaNacimiento === null`, no se llama `setNacimiento()` y el campo queda nulo. En `editarJugador()`, siempre se llama `setNacimiento()` (ver D4), por lo que el comportamiento difiere entre creación y edición.

## Risks / Trade-offs

- **[Riesgo] Test de edición de documento puede fallar si hay datos de otro equipo** → La unicidad global puede causar fallos inesperados en tests de integración si la base de datos tiene jugadores previos con el mismo documento.
- **[Trade-off] Los specs documentan los bugs como comportamiento actual** → Un futuro fix de D2, D3 o D4 invalidará los scenarios correspondientes. Eso es intencional: los specs deben actualizarse junto con el fix.
