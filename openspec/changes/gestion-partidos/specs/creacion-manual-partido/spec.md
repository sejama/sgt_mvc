## ADDED Requirements

### Requirement: Creación manual de Partido clasificatorio con Grupo
El sistema SHALL permitir crear un `Partido` individual de tipo CLASIFICATORIO, requiriendo que se especifique un `Grupo` válido que pertenezca a la `Categoria` elegida. La `Categoria` SHALL pertenecer al torneo en curso.

#### Scenario: Partido clasificatorio creado correctamente
- **GIVEN** una `Categoria` del torneo actual con un `Grupo` válido y dos equipos disponibles
- **WHEN** se invoca `crearPartidoManual()` con tipo CLASIFICATORIO, grupo, equipo local y visitante
- **THEN** se crea un `Partido` con tipo CLASIFICATORIO, estado BORRADOR y el grupo asignado

#### Scenario: Partido clasificatorio sin Grupo lanza excepción
- **GIVEN** una solicitud de creación manual de tipo CLASIFICATORIO sin grupoId
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** se lanza `AppException` indicando que se debe seleccionar un grupo

#### Scenario: Equipo local y visitante iguales lanza excepción
- **GIVEN** una solicitud donde `equipoLocalId` es igual a `equipoVisitanteId`
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** se lanza `AppException` indicando que los equipos no pueden ser el mismo

### Requirement: Creación manual de Partido eliminatorio sin Grupo
El sistema SHALL permitir crear un `Partido` de tipo ELIMINATORIO sin requerir un `Grupo`. Los equipos son opcionales en la creación (pueden asignarse luego vía bracket).

#### Scenario: Partido eliminatorio creado sin grupo ni equipos
- **GIVEN** una solicitud de creación manual de tipo ELIMINATORIO sin grupoId ni equipos
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** se crea un `Partido` con tipo ELIMINATORIO, estado BORRADOR, grupo nulo y equipos nulos

### Requirement: Validación de pertenencia de Equipo a Categoría en creación manual
El sistema SHALL verificar que el equipo local y el equipo visitante (si se especifican) pertenezcan a la `Categoria` seleccionada. Un equipo de otra categoría SHALL ser rechazado.

#### Scenario: Equipo de otra categoría es rechazado
- **GIVEN** un equipoLocalId que pertenece a una categoría diferente a la seleccionada
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** se lanza `AppException` indicando que el equipo local no pertenece a la categoría

### Requirement: Configuración de bracket opcional en creación manual
El sistema SHALL permitir asociar un `PartidoConfig` al crear manualmente un partido, cuando el campo `crear_usarConfig` está activo. La configuración define el origen de los equipos del bracket (por posición de grupo o por ganador de partido previo).

#### Scenario: Partido creado con config por posición de grupo
- **GIVEN** una solicitud con `usarConfig=true`, origen `grupos`, grupoEquipo1Id, grupoEquipo2Id, posicion1 y posicion2 válidos
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** el `PartidoConfig` asociado tiene los grupos y posiciones asignados

#### Scenario: Config incompleta lanza excepción
- **GIVEN** una solicitud con `usarConfig=true`, origen `grupos` pero sin posicionEquipo1
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** se lanza `AppException` indicando que faltan datos de configuración

#### Scenario: Partido no puede depender de sí mismo en config por ganadores
- **GIVEN** una solicitud con `usarConfig=true`, origen `ganadores` donde `ganadorPartido1Id` es el mismo partido que se está creando
- **WHEN** se invoca `crearPartidoManual()`
- **THEN** se lanza `AppException` indicando que un partido no puede depender de sí mismo

### Requirement: PartidoConfig soporta origen por perdedor de partido previo, pero no hay forma de configurarlo (comportamiento actual)
La entidad `PartidoConfig` tiene campos `perdedorPartido1` y `perdedorPartido2` que son evaluados en `cargarResultado()` para propagar el perdedor al siguiente partido del bracket. Sin embargo, `crearPartidoManual()` no expone ningún `origen = 'perdedores'` — no hay mecanismo actual para asignar estos campos. Son funcionalidad parcialmente implementada.

#### Scenario: perdedorPartido1/2 nunca se poblan vía crearPartidoManual
- **GIVEN** cualquier solicitud de creación manual con `usarConfig=true`
- **WHEN** se invoca `crearPartidoManual()` con origen `grupos` o `ganadores`
- **THEN** `PartidoConfig.perdedorPartido1` y `perdedorPartido2` quedan siempre en null
