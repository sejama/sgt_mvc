## Problem

`PartidoManager::cargarResultado()` no invalida el cache de `TablaManager` al persistir un resultado. `TablaManager` tiene un cache con TTL de 1 hora. Si la tabla se calculó antes de cargar el resultado, los datos servidos quedan desactualizados durante hasta 1 hora sin que el sistema lo detecte.

## Proposal

Agregar una llamada a `TablaManager::clearCache($grupo)` dentro de `cargarResultado()`, inmediatamente después de persistir el resultado, cuando el partido pertenece a un grupo. Partidos sin grupo (playoff) no tienen tabla de posiciones, por lo que no requieren invalidación.

## Out of Scope

- No se modifica la lógica de TTL del cache.
- No se agrega cache para partidos de playoff.
