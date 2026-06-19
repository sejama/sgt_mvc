## Context

El `Equipo` vive dentro de una `Categoria` y participa en un `Torneo`. Tiene dos identificadores: su `nombre` (legible, único por categoría) y su `numero` (entero secuencial, único por torneo). El número se asigna al momento de la creación contando todos los equipos ya existentes en el torneo.

El ciclo de vida tiene seis estados, pero solo dos son gestionados directamente por este manager: BORRADOR al crear y NO_PARTICIPA al dar de baja. Las demás transiciones (ACTIVO, ELIMINADO, DESCALIFICADO, CALIFICADO) son responsabilidad de otros managers.

`bajarEquipo()` es la operación de mayor impacto: modifica el estado del equipo y cancela en cascada todos sus partidos, tanto como local como visitante, independientemente del estado en que se encuentren.

## Goals / Non-Goals

**Goals:**
- Documentar el contrato exacto de `crearEquipo()`: unicidad, número global, validaciones.
- Documentar el comportamiento de `editarEquipo()` respecto al logoPath.
- Documentar el alcance completo de `bajarEquipo()`: estados afectados y nivel de log.
- Documentar el ciclo de vida con sus seis estados y cuáles gestiona este manager.

**Non-Goals:**
- No implementar transiciones a ELIMINADO, DESCALIFICADO ni CALIFICADO.
- No agregar filtros por estado en la cancelación de partidos de `bajarEquipo()`.
- No cambiar la lógica de numeración secuencial.

## Decisions

### D1 — Número de equipo: secuencial global por torneo, no por categoría

El número se calcula como `count(equipoRepository.buscarEquiposXTorneo(ruta)) + 1`. Esto significa que equipos de distintas categorías del mismo torneo comparten la secuencia. Un torneo con 2 categorías de 6 equipos cada una tendrá equipos numerados del 1 al 12 (mezclados entre categorías). El número identifica al equipo dentro del contexto del torneo, no de la categoría.

### D2 — logoPath: inmutable si no se envía en edición

`editarEquipo()` solo actualiza `logoPath` si el argumento es no-nulo. Pasar `null` preserva el logo existente. No existe mecanismo para borrar el logo a través de este método.

### D3 — bajarEquipo() cancela partidos sin filtro de estado

La cancelación incluye todos los `Partido` donde el equipo es local o visitante: BORRADOR, PROGRAMADO, FINALIZADO y CANCELADO (ya cancelados no cambian de estado en el negocio, pero el código los procesa igual). El contador `$cancelados` refleja todos los partidos procesados. El log usa nivel `warning` para indicar la gravedad del evento.

### D4 — obtenerEquipo() siempre lanza, nunca retorna null

Aunque el tipo de retorno declara `?Equipo`, la implementación lanza `AppException` si el equipo no se encuentra. El `null` del tipo de retorno es una inconsistencia de implementación, no un comportamiento observable.

### D5 — Edición sí llama ValidadorManager (a diferencia de editarCategoria)

`editarEquipo()` llama `validadorManager->validarEquipo()` después del chequeo de unicidad. Esto garantiza que nombre (3-128 chars) y nombreCorto (2-16 chars) se revalidan también en la edición, manteniendo consistencia con la creación.

## Risks / Trade-offs

- **[Riesgo] bajarEquipo() cancela partidos FINALIZADOS** → Si un torneo ya registró resultados y un equipo se da de baja, los resultados quedan en partidos CANCELADO, afectando la tabla de posiciones retroactivamente. Comportamiento documentado.
- **[Riesgo] Número de equipo puede colisionar bajo concurrencia** → El cálculo `count() + 1` no tiene bloqueo. Dos equipos creados simultáneamente pueden obtener el mismo número. Comportamiento documentado.
- **[Trade-off] No hay distinción de estado en la cancelación** → `bajarEquipo()` no discrimina entre partidos futuros y pasados. Una implementación futura podría solo cancelar los no finalizados.
