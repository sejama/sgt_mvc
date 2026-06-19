## Context

`TablaManager::calcularPosiciones(Grupo $grupo)` usa Symfony Cache con TTL de 1 hora. Si el resultado de un partido se carga después de que la tabla fue calculada, el cache sigue sirviendo datos viejos. `TablaManager::clearCache(Grupo $grupo)` ya existe y borra la entrada del cache para ese grupo.

`cargarResultado()` recibe un `Partido`. El partido tiene una relación `grupo` (nullable). Solo los partidos de zona/grupo tienen tabla de posiciones; los de playoff no tienen grupo asignado.

## Goals / Non-Goals

**Goals:**
- Garantizar que la tabla de posiciones refleja el estado real tras cada carga de resultado.
- Solo invalidar cache cuando el partido tiene grupo asignado.

**Non-Goals:**
- No modificar el TTL ni la estrategia de cache.
- No cachear resultados de playoff.

## Decisions

### D1 — Llamada a clearCache justo antes del flush

`clearCache()` se llama antes del `flush()` final en `cargarResultado()`. Si el flush falla (excepción de BD), el cache ya fue invalidado pero el resultado no se guardó — la próxima consulta de tabla recalculará correctamente. El orden inverso (flush → clearCache) podría servir datos viejos si clearCache falla.

### D2 — Guard: solo cuando partido tiene grupo

```php
if ($partido->getGrupo() !== null) {
    $this->tablaManager->clearCache($partido->getGrupo());
}
```

## Risks / Trade-offs

- **[Mínimo] Cache invalidado sin commit exitoso**: Si el flush falla después de clearCache, la próxima lectura de tabla recalcula desde la BD (comportamiento correcto). No hay pérdida de datos.
