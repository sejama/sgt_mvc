## Context

El `GrupoManager` opera exclusivamente dentro del contexto de una `Categoria`. Su operación principal, `crearGrupos()`, recibe un array de `CreateGrupoDTO` y crea todos los grupos en una sola llamada. La distribución de equipos es determinística y secuencial: el orden de los equipos en `$categoria->getEquipos()` determina qué equipo queda en qué grupo.

El intercambio de equipos (`intercambiarEquiposEntreGrupos`) es la única operación que permite reconfigurar la distribución post-creación, pero solo mientras no se hayan generado partidos.

## Goals / Non-Goals

**Goals:**
- Documentar el contrato exacto de `crearGrupos()`: validaciones previas, distribución secuencial, efecto en la `Categoria`.
- Documentar las reglas de clasificación en cascada con su contador acumulado global.
- Documentar todas las precondiciones del intercambio de equipos.
- Documentar el comportamiento y ordenamiento de cada método de consulta.

**Non-Goals:**
- No implementar creación de grupo individual (solo existe creación en lote).
- No agregar edición ni eliminación de grupos.
- No cambiar el orden de distribución de equipos.

## Decisions

### D1 — Creación siempre en lote, nunca individual

`crearGrupos()` recibe y procesa un array completo de DTOs. No existe un método `crearGrupo()` singular. Esto garantiza que la validación de totales (`totalEquiposZonas === totalEquipos`) se pueda hacer antes de persistir cualquier grupo.

### D2 — Distribución secuencial de equipos por índice de `getEquipos()`

Los equipos se toman en el orden que retorna `$categoria->getEquipos()` (colección Doctrine, sin orden garantizado a nivel de spec). Se usa `array_slice` con un cursor acumulativo (`$inicio`) para asignar los primeros N al primer grupo, los N siguientes al segundo, etc. El orden de los equipos dentro de la colección no es controlado por el `GrupoManager`.

### D3 — Contador de clasificados es acumulativo entre todos los grupos

El contador `$totalClasificados` se incrementa con cada `clasificaOro`, `clasificaPlata` y `clasificaBronce` de cada grupo del lote. Si la suma global supera el total de equipos en la categoría, lanza `AppException`. Esto previene configurar más clasificados que equipos existentes.

### D4 — clasificaBronce requiere clasificaPlata

La jerarquía de clasificación es obligatoria: no puede haber clasificados a bronce sin clasificados a plata. `clasificaOro` siempre es requerido; `clasificaPlata` y `clasificaBronce` son opcionales pero dependientes en cascada.

### D5 — La Categoria cambia a ZONAS_CREADAS dentro del loop

El estado de la `Categoria` se actualiza a `EstadoCategoria::ZONAS_CREADAS` en cada iteración del loop. Si el procesamiento de un grupo falla con `AppException`, los grupos anteriores ya creados y el flush ya ejecutado no se revierten (no hay transacción explícita en `crearGrupos()`).

### D6 — Intercambio requiere ausencia de partidos en la Categoria

La precondición más restrictiva del intercambio es que `count($categoria->getPartidos()) === 0`. Una vez que se generaron partidos, la distribución de grupos es inmutable. Esto protege la integridad de los fixtures ya creados.

## Risks / Trade-offs

- **[Riesgo] `crearGrupos()` no usa transacción explícita** → Si falla al crear el grupo N (de un lote de M), los grupos 1..N-1 ya están persistidos y la `Categoria` ya está en ZONAS_CREADAS. Mitigación: documentado como comportamiento actual.
- **[Riesgo] Orden de equipos en `getEquipos()` no garantizado** → La distribución secuencial depende del orden de la colección Doctrine. Si ese orden cambia (por reindexación, por ejemplo), los equipos caen en grupos distintos. Comportamiento documentado.
- **[Trade-off] No hay reversión de la creación de grupos** → No existe método para "deshacer" la creación de grupos y volver a BORRADOR. El intercambio de equipos es el único ajuste permitido post-creación.
