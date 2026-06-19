## ADDED Requirements

### Requirement: Generación round-robin por Grupo
El sistema SHALL generar automáticamente todos los cruces posibles entre equipos de un `Grupo` usando la fórmula n*(n-1)/2, donde n es el número de equipos. Cada cruce produce un `Partido` de tipo CLASIFICATORIO con estado BORRADOR. Si el grupo tiene menos de 2 equipos, no SHALL generarse ningún partido.

#### Scenario: Generación correcta con 4 equipos
- **GIVEN** un `Grupo` con 4 equipos (A, B, C, D)
- **WHEN** se invoca `crearPartidosXGrupo()` para ese grupo
- **THEN** se crean exactamente 6 partidos (4*3/2), cubriendo todos los cruces únicos entre equipos

#### Scenario: Grupo con un solo equipo no genera partidos
- **GIVEN** un `Grupo` con 1 equipo
- **WHEN** se invoca `crearPartidosXGrupo()`
- **THEN** no se crea ningún `Partido`

#### Scenario: Partido generado tiene tipo CLASIFICATORIO y estado BORRADOR
- **GIVEN** un `Grupo` con 2 equipos
- **WHEN** se genera el partido entre ellos
- **THEN** el `Partido` tiene `tipo = TipoPartido::CLASIFICATORIO` y `estado = EstadoPartido::BORRADOR`

#### Scenario: Numeración global única por torneo
- **GIVEN** un torneo con partidos existentes (último número = 5) y un `Grupo` que genera 3 partidos nuevos
- **WHEN** se invoca `crearPartidosXGrupo()`
- **THEN** los nuevos partidos reciben números 6, 7 y 8 en orden

### Requirement: Generación de partidos eliminatorios (bracket) por Categoría
El sistema SHALL crear los partidos eliminatorios definidos en la estructura de playoff junto con los partidos clasificatorios de todos los grupos de la `Categoria`, todo dentro de una única transacción. Cada partido eliminatorio tiene estado BORRADOR, tipo ELIMINATORIO y un `PartidoConfig` asociado.

#### Scenario: Generación completa de categoría con playoff
- **GIVEN** una `Categoria` con 2 grupos y una estructura de playoff con 3 partidos eliminatorios
- **WHEN** se invoca `crearPartidoXCategoria()`
- **THEN** se crean los partidos clasificatorios de ambos grupos más los 3 partidos eliminatorios, todos dentro de la misma transacción

#### Scenario: Partido eliminatorio origen por posición de grupo
- **GIVEN** un partido eliminatorio configurado con `grupoEquipo1`, `posicionEquipo1`, `grupoEquipo2`, `posicionEquipo2`
- **WHEN** se crea el `PartidoConfig`
- **THEN** el config tiene los cuatro campos asignados y `ganadorPartido1/2` nulos

#### Scenario: Partido eliminatorio origen por ganador de partido previo
- **GIVEN** un partido eliminatorio configurado con `equipoGanador1` y `equipoGanador2` referenciando partidos ya creados
- **WHEN** se crea el `PartidoConfig`
- **THEN** el config tiene `ganadorPartido1` y `ganadorPartido2` asignados y los campos de grupo nulos

#### Scenario: Categoría sin playoff genera solo clasificatorios
- **GIVEN** una `Categoria` con grupos pero con estructura de playoff vacía (0 partidos)
- **WHEN** se invoca `crearPartidoXCategoria()`
- **THEN** solo se crean los partidos clasificatorios, sin ningún `PartidoConfig`

### Requirement: Activación automática de Equipo al generar partidos
El sistema SHALL cambiar el estado de un `Equipo` de BORRADOR a ACTIVO cuando se genera su primer partido (clasificatorio). Si el `Equipo` ya está en estado ACTIVO, no SHALL modificar su estado.

#### Scenario: Equipo en BORRADOR se activa al generar su primer partido
- **GIVEN** un `Equipo` en estado `EstadoEquipo::BORRADOR`
- **WHEN** se genera un partido clasificatorio que incluye a ese equipo
- **THEN** el estado del `Equipo` cambia a `EstadoEquipo::ACTIVO`

#### Scenario: Equipo ya ACTIVO no cambia de estado
- **GIVEN** un `Equipo` en estado `EstadoEquipo::ACTIVO`
- **WHEN** se genera un nuevo partido que incluye a ese equipo
- **THEN** el estado del `Equipo` permanece ACTIVO sin modificación
