## CHANGED Requirements

### Requirement: cargarResultado invalida el cache de tabla-posiciones del grupo
El sistema SHALL llamar a `TablaManager::clearCache(grupo)` al finalizar `cargarResultado()` cuando el partido tiene un grupo asignado. Esto garantiza que la próxima consulta de tabla-posiciones refleje el resultado recién cargado.

#### Scenario: Cargar resultado invalida cache del grupo
- **GIVEN** un `Partido` en estado PROGRAMADO perteneciente a un `Grupo`, y la tabla de posiciones del grupo ya está en cache
- **WHEN** se invoca `cargarResultado()` con un resultado válido
- **THEN** el cache de tabla-posiciones del grupo queda invalidado y la próxima consulta recalcula desde la BD

#### Scenario: Cargar resultado de partido sin grupo no invalida cache
- **GIVEN** un `Partido` de playoff sin grupo asignado (`partido.grupo === null`)
- **WHEN** se invoca `cargarResultado()` con un resultado válido
- **THEN** no se llama a `clearCache()` (los partidos de playoff no tienen tabla de posiciones)

#### Scenario: Si el flush falla, el cache ya fue invalidado pero el resultado no persiste
- **GIVEN** un `Partido` con grupo y un resultado válido, pero el flush lanza excepción de BD
- **WHEN** se invoca `cargarResultado()`
- **THEN** el cache queda invalidado y la próxima consulta de tabla recalcula desde la BD (sin el resultado fallido)
