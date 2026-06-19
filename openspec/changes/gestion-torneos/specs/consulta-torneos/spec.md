## ADDED Requirements

### Requirement: Listado de todos los Torneos
El sistema SHALL retornar todos los `Torneo` existentes sin filtro de estado ni creador cuando se invoca `obtenerTorneos()`.

#### Scenario: Listado retorna todos los torneos sin filtro
- **GIVEN** existen 3 torneos en distintos estados (BORRADOR, ACTIVO, FINALIZADO)
- **WHEN** se invoca `obtenerTorneos()`
- **THEN** se retornan los 3 torneos independientemente de su estado

### Requirement: Listado de Torneos por creador
El sistema SHALL retornar únicamente los `Torneo` cuyo creador coincide con el `userId` proporcionado cuando se invoca `obtenerTorneosXCreador()`.

#### Scenario: Listado filtrado por creador retorna solo sus torneos
- **GIVEN** el usuario A creó 2 torneos y el usuario B creó 1 torneo
- **WHEN** se invoca `obtenerTorneosXCreador()` con el userId del usuario A
- **THEN** se retornan los 2 torneos del usuario A, sin el torneo del usuario B

#### Scenario: Creador sin torneos retorna lista vacía
- **GIVEN** un `Usuario` que no creó ningún torneo
- **WHEN** se invoca `obtenerTorneosXCreador()` con su userId
- **THEN** se retorna una lista vacía

### Requirement: Búsqueda de Torneo por ruta
El sistema SHALL retornar el `Torneo` cuya ruta coincide exactamente con la proporcionada. Si no existe ningún torneo con esa ruta, SHALL lanzar `AppException`.

#### Scenario: Torneo encontrado por ruta
- **GIVEN** existe un `Torneo` con ruta "copa-verano-2026"
- **WHEN** se invoca `obtenerTorneo("copa-verano-2026")`
- **THEN** se retorna ese `Torneo`

#### Scenario: Ruta inexistente lanza AppException
- **GIVEN** no existe ningún `Torneo` con ruta "torneo-fantasma"
- **WHEN** se invoca `obtenerTorneo("torneo-fantasma")`
- **THEN** se lanza `AppException` con el mensaje "Torneo no encontrado"
