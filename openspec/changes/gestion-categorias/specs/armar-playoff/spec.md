## ADDED Requirements

### Requirement: Prerequisito de grupos finalizados para armar el playoff
El sistema SHALL verificar que todos los `Grupo` de la `Categoria` estén en estado `EstadoGrupo::FINALIZADO` antes de armar el playoff. Si algún grupo no está finalizado, SHALL lanzar `AppException` y no modificar ningún partido ni el estado de la categoría.

#### Scenario: Todos los grupos finalizados permite armar el playoff
- **GIVEN** una `Categoria` cuyos todos los `Grupo` tienen estado `EstadoGrupo::FINALIZADO`
- **WHEN** se invoca `armarPlayOff()`
- **THEN** el proceso de resolución de equipos inicia sin error de prerequisito

#### Scenario: Un grupo no finalizado lanza excepción
- **GIVEN** una `Categoria` con 3 grupos donde 2 están FINALIZADOS y 1 no
- **WHEN** se invoca `armarPlayOff()`
- **THEN** se lanza `AppException` indicando que no se puede armar el playoff si no se finalizaron todos los grupos

### Requirement: Resolución de equipos del bracket desde la tabla de posiciones
El sistema SHALL asignar equipos a los `Partido` eliminatorios que no tienen equipos asignados y tienen un `PartidoConfig` con origen "por posición de grupo". El equipo en la posición N del ranking del grupo se obtiene como el elemento en el índice N-1 del array retornado por `TablaManager::calcularPosiciones()`.

#### Scenario: Equipo local resuelto desde posición 1 del grupo
- **GIVEN** un `Partido` eliminatorio sin equipos, con `PartidoConfig` que indica grupoEquipo1=GrupoA y posicionEquipo1=1
- **WHEN** se invoca `armarPlayOff()`
- **THEN** el `equipoLocal` del partido queda asignado al primer equipo del ranking del GrupoA (índice 0)

#### Scenario: Equipo visitante resuelto desde posición 2 del grupo
- **GIVEN** un `Partido` eliminatorio sin equipos, con `PartidoConfig` que indica grupoEquipo2=GrupoB y posicionEquipo2=2
- **WHEN** se invoca `armarPlayOff()`
- **THEN** el `equipoVisitante` del partido queda asignado al segundo equipo del ranking del GrupoB (índice 1)

#### Scenario: Partidos con equipos ya asignados no son modificados
- **GIVEN** un `Partido` eliminatorio que ya tiene `equipoLocal` y `equipoVisitante` asignados
- **WHEN** se invoca `armarPlayOff()`
- **THEN** ese partido no es modificado (la condición requiere que ambos sean nulos)

#### Scenario: Partidos con config de origen "por ganador" no son resueltos en esta etapa
- **GIVEN** un `Partido` eliminatorio sin equipos, cuyo `PartidoConfig` tiene `ganadorPartido1` y `ganadorPartido2` (no grupos)
- **WHEN** se invoca `armarPlayOff()`
- **THEN** ese partido no recibe equipos (se resolverá al cargar el resultado del partido origen)

### Requirement: Transición de estado a ZONAS_CERRADAS al completar el playoff
El sistema SHALL cambiar el estado de la `Categoria` a `EstadoCategoria::ZONAS_CERRADAS` al finalizar exitosamente el armado del playoff, independientemente de si se resolvieron o no partidos con equipos.

#### Scenario: Estado cambia a ZONAS_CERRADAS tras armar playoff exitosamente
- **GIVEN** una `Categoria` en cualquier estado con todos sus grupos FINALIZADOS
- **WHEN** `armarPlayOff()` completa sin error
- **THEN** el estado de la `Categoria` cambia a `EstadoCategoria::ZONAS_CERRADAS`
