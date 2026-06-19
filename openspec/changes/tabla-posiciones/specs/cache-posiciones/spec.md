## ADDED Requirements

### Requirement: Cache de posiciones por Grupo con TTL
El sistema SHALL cachear el resultado de `calcularPosiciones()` usando la clave `posiciones_grupo_{id}` donde `{id}` es el identificador del `Grupo`. El cache SHALL expirar automáticamente después de 3600 segundos (1 hora).

#### Scenario: Segunda llamada retorna resultado cacheado
- **GIVEN** que `calcularPosiciones()` fue invocado para un `Grupo` y el resultado está en cache
- **WHEN** se invoca nuevamente `calcularPosiciones()` para el mismo `Grupo` dentro de la hora
- **THEN** el resultado se obtiene del cache sin recalcular

#### Scenario: Cache expira después de 1 hora
- **GIVEN** que el resultado de posiciones de un `Grupo` está cacheado
- **WHEN** transcurre más de 1 hora desde la última carga al cache
- **THEN** el cache expira y la próxima llamada recalcula las posiciones

### Requirement: Invalidación explícita del cache al cargar resultado
El sistema SHALL proveer el método `clearCache(int $grupoId)` para invalidar el cache de un `Grupo` específico. Este método SHALL ser invocado en toda operación que modifique el resultado de un `Partido` del `Grupo`.

#### Scenario: Cache invalidado al cargar un resultado
- **GIVEN** que el resultado cacheado de un `Grupo` existe
- **WHEN** se carga el resultado de un `Partido` del `Grupo`
- **THEN** el cache del `Grupo` es invalidado y la próxima llamada a `calcularPosiciones()` recalcula desde la base de datos

#### Scenario: Invocación de clearCache con grupo inexistente no lanza excepción
- **GIVEN** un `grupoId` cuya clave no existe en el cache
- **WHEN** se invoca `clearCache($grupoId)`
- **THEN** el sistema completa sin error
