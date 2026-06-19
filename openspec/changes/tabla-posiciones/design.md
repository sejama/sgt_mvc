## Context

El `TablaManager` es el componente central para calcular posiciones dentro de un `Grupo`. Acumula estadísticas de todos los `Partido` finalizados (sets ganados/perdidos, puntos anotados) y produce un ranking ordenado. El resultado se cachea por grupo con TTL de 1 hora y se invalida explícitamente cuando se carga un resultado.

La lógica de desempate refleja las reglas oficiales de volleyball: primero puntos por partido, luego diferencia de sets y finalmente diferencia de puntos individuales.

## Goals / Non-Goals

**Goals:**
- Documentar con precisión las reglas de puntuación y desempate implementadas.
- Establecer el contrato observable de `calcularPosiciones()` como base para tests.
- Documentar el efecto colateral de transición de estado del `Grupo` a FINALIZADO.
- Documentar la estrategia de cache y su invalidación.

**Non-Goals:**
- No cambiar el algoritmo de cálculo actual.
- No agregar soporte para formatos de puntuación distintos (e.g., 3pts por victoria).
- No introducir persistencia de posiciones en base de datos.

## Decisions

### D1 — Sistema de puntuación: 2pts ganado / 1pt perdido

El sistema asigna 2 puntos al equipo ganador y 1 punto al perdedor (en lugar del sistema 3/0 de otras disciplinas). Esto asegura que todo partido jugado suma al marcador global, reduciendo el incentivo de abandonar partidos ya perdidos.

_Alternativa considerada_: 3pts victoria / 0pts derrota — descartado porque el dominio volleyball ya implementa el sistema 2/1 y cambiarlo sería un breaking change.

### D2 — Hasta 5 sets por partido

El `Partido` soporta `localSet1..5` y `visitanteSet1..5`. Los sets 3, 4 y 5 son opcionales (`nullable`). El conteo de sets ganados acumula todos los sets no nulos. Esto cubre formatos de 2 sets ganadores (sets 1-3) y formatos extendidos (sets 1-5).

### D3 — Transición de estado de Grupo como efecto colateral de `calcularPosiciones()`

La transición a `EstadoGrupo::FINALIZADO` ocurre dentro del cálculo de posiciones cuando todos los partidos del grupo están en estado FINALIZADO o CANCELADO. Esta decisión acopla la transición de estado a la lectura de posiciones; la alternativa (un evento de dominio separado) requeriría refactoring mayor.

_Riesgo_: si `calcularPosiciones()` no se llama después del último resultado, el grupo nunca transiciona. Mitigación: la vista del grupo siempre renderiza posiciones, disparando el cálculo.

### D4 — Cache por grupo con TTL 1 hora

Se usa `CacheInterface` de Symfony con clave `posiciones_grupo_{id}`. TTL de 1 hora como fallback; la invalidación principal ocurre explícitamente desde el controlador de resultados vía `clearCache()`.

## Risks / Trade-offs

- **[Riesgo] Grupo nunca transiciona a FINALIZADO si `calcularPosiciones()` no se invoca** → Mitigación: asegurar que la vista siempre llame al manager.
- **[Riesgo] Cache stale si el resultado se carga desde un proceso que no invoca `clearCache()`** → Mitigación: documentar que toda operación de carga de resultado DEBE llamar `TablaManager::clearCache()`.
- **[Trade-off] Sets nulos tratados como 0 en sumas de puntos** → El código actual suma `getLocalSet3()` etc. sin null-check en la acumulación de puntos; esto no afecta sets ganados (que sí verifica null). Comportamiento documentado, no corregido en este change.
