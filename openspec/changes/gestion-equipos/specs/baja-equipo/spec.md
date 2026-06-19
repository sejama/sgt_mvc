## ADDED Requirements

### Requirement: bajarEquipo transiciona el estado del Equipo a NO_PARTICIPA
El sistema SHALL cambiar el estado del `Equipo` a `EstadoEquipo::NO_PARTICIPA` al invocar `bajarEquipo()`, independientemente del estado actual del equipo.

#### Scenario: Equipo ACTIVO pasa a NO_PARTICIPA al darlo de baja
- **GIVEN** un `Equipo` en estado `EstadoEquipo::ACTIVO`
- **WHEN** se invoca `bajarEquipo()`
- **THEN** el estado del `Equipo` cambia a `EstadoEquipo::NO_PARTICIPA`

### Requirement: bajarEquipo cancela todos los partidos del Equipo sin distinción de estado
El sistema SHALL cancelar (pasar a `EstadoPartido::CANCELADO`) todos los `Partido` donde el equipo participa como local o visitante, independientemente del estado actual de esos partidos (incluyendo BORRADOR, PROGRAMADO y FINALIZADO).

#### Scenario: Partidos como local son cancelados
- **GIVEN** un `Equipo` con 3 partidos donde es equipo local (en estados BORRADOR, PROGRAMADO y FINALIZADO)
- **WHEN** se invoca `bajarEquipo()`
- **THEN** los 3 partidos quedan en estado `EstadoPartido::CANCELADO`

#### Scenario: Partidos como visitante son cancelados
- **GIVEN** un `Equipo` con 2 partidos donde es equipo visitante
- **WHEN** se invoca `bajarEquipo()`
- **THEN** los 2 partidos quedan en estado `EstadoPartido::CANCELADO`

#### Scenario: Partidos locales y visitantes son cancelados en la misma operación
- **GIVEN** un `Equipo` con 2 partidos como local y 3 partidos como visitante
- **WHEN** se invoca `bajarEquipo()`
- **THEN** los 5 partidos quedan en estado `EstadoPartido::CANCELADO`

#### Scenario: Equipo sin partidos no genera error
- **GIVEN** un `Equipo` sin partidos asociados
- **WHEN** se invoca `bajarEquipo()`
- **THEN** el estado del equipo cambia a NO_PARTICIPA y no se lanza ninguna excepción

### Requirement: bajarEquipo registra un log de nivel WARNING con la cantidad de partidos cancelados
El sistema SHALL registrar un log de nivel `warning` al dar de baja un equipo, incluyendo el id, nombre del equipo y la cantidad total de partidos cancelados.

#### Scenario: Log WARNING registrado con cantidad de partidos cancelados
- **GIVEN** un `Equipo` con 4 partidos en total (local y visitante)
- **WHEN** se invoca `bajarEquipo()`
- **THEN** se registra un log de nivel WARNING con `partidos_cancelados = 4`
