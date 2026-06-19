## ADDED Requirements

### Requirement: Eliminación permanente del Jugador con logging
El sistema SHALL eliminar el `Jugador` de forma permanente (hard delete) al invocar `eliminarJugador()`, registrando un log con el id, nombre completo y el id del equipo al que pertenecía.

#### Scenario: Jugador eliminado no existe más en el sistema
- **GIVEN** un `Jugador` existente
- **WHEN** se invoca `eliminarJugador(jugador)`
- **THEN** el `Jugador` ya no puede ser recuperado por ninguna consulta

#### Scenario: Log registra jugador_id, nombre completo y equipo_id
- **GIVEN** un `Jugador` con id, nombre "Carlos", apellido "López" y equipo con id 5
- **WHEN** se invoca `eliminarJugador(jugador)`
- **THEN** el log incluye `jugador_id`, `nombre = "Carlos López"` y `equipo_id = 5`

### Requirement: Búsqueda de Jugador por id lanza excepción si no existe
El sistema SHALL retornar el `Jugador` si existe y SHALL lanzar `AppException` si no se encuentra ningún jugador con ese id.

#### Scenario: Jugador encontrado por id
- **GIVEN** existe un `Jugador` con el id indicado
- **WHEN** se invoca `obtenerJugador(id)`
- **THEN** se retorna ese `Jugador`

#### Scenario: Id inexistente lanza AppException
- **GIVEN** ningún `Jugador` tiene el id indicado
- **WHEN** se invoca `obtenerJugador(id)`
- **THEN** se lanza `AppException` con el mensaje "No se encontró el jugador"

### Requirement: Consulta de Jugadores por distintos criterios
El sistema SHALL proveer listado global sin filtro y listado por equipo.

#### Scenario: obtenerJugadores retorna todos sin filtro
- **GIVEN** existen jugadores en distintos equipos
- **WHEN** se invoca `obtenerJugadores()`
- **THEN** se retornan todos los jugadores sin filtro

#### Scenario: obtenerJugadoresPorEquipo retorna solo los del Equipo indicado
- **GIVEN** el equipo A tiene 3 jugadores y el equipo B tiene 2 jugadores
- **WHEN** se invoca `obtenerJugadoresPorEquipo(equipoA)`
- **THEN** se retornan los 3 jugadores del equipo A sin los del equipo B
