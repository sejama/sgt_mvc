## Why

El `PartidoManager` concentra la lógica más compleja del sistema: generación automática de fixtures, creación de brackets eliminatorios, programación en canchas, carga de resultados y propagación del ganador al bracket. Sin specs formales, cualquier cambio en estas reglas puede romper silenciosamente torneos en curso.

## What Changes

- Documentar la capacidad `generacion-partidos`: round-robin por grupo y bracket eliminatorio con `PartidoConfig`.
- Documentar la capacidad `creacion-manual-partido`: creación individual de partidos clasificatorios o eliminatorios con configuración opcional.
- Documentar la capacidad `programacion-partido`: asignación de `Cancha` + horario con validaciones de pertenencia, conflicto y fecha de inicio.
- Documentar la capacidad `carga-resultado`: determinación del ganador por sets, propagación al bracket y ciclo de vida del `Partido`.
- Documentar la capacidad `ciclo-vida-partido`: transiciones de estado BORRADOR → PROGRAMADO → FINALIZADO / CANCELADO y la activación automática de `Equipo`.

## Capabilities

### New Capabilities

- `generacion-partidos`: Generación automática de partidos clasificatorios (round-robin) y eliminatorios (bracket) dentro de una `Categoria`.
- `creacion-manual-partido`: Creación de un único `Partido` con tipo, grupo, equipos y configuración de bracket opcionales.
- `programacion-partido`: Asignación de cancha y horario a un `Partido`, con validaciones de integridad.
- `carga-resultado`: Registro del resultado por sets, determinación del ganador y propagación al bracket vía `PartidoConfig`.
- `ciclo-vida-partido`: Ciclo de vida del `Partido` (estados) y activación automática del `Equipo`.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`PartidoManager`**: clase principal documentada.
- **`PartidoConfigRepository`**: implicado en la propagación de ganadores al bracket.
- **`EquipoRepository`**: afectado por la activación automática de equipos al generar partidos.
- **`ValidadorPartidoManager`**: valida la estructura del playoff antes de la generación.
- **Tests**: se crearán specs en formato Given/When/Then; los tests existentes deben alinearse con estos escenarios.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
