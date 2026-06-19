## ADDED Requirements

### Requirement: Unicidad de numeroDocumento en edición es global (sin scope de equipo)
El sistema SHALL rechazar el cambio de `numeroDocumento` si ya existe cualquier otro `Jugador` en el sistema —en cualquier equipo— con ese número de documento. Esta validación NO está acotada al equipo del jugador que se edita.

#### Scenario: Cambiar documento a uno usado por jugador de otro equipo lanza excepción
- **GIVEN** existe un jugador en el equipo B con numeroDocumento "99999999"
- **WHEN** se edita un jugador del equipo A intentando cambiar su numeroDocumento a "99999999"
- **THEN** se lanza `AppException` indicando que ya existe un jugador con ese DNI

#### Scenario: Conservar el mismo numeroDocumento no lanza excepción
- **GIVEN** un `Jugador` que se edita sin cambiar su numeroDocumento
- **WHEN** se invoca `editarJugador()`
- **THEN** la edición procede sin error de documento duplicado

### Requirement: El apellido no puede modificarse mediante editarJugador (comportamiento actual)
El sistema actual NO actualiza el campo `apellido` del `Jugador` al invocar `editarJugador()`. El apellido permanece con el valor original independientemente del argumento `$apellido` recibido.

#### Scenario: Apellido no cambia tras la edición
- **GIVEN** un `Jugador` con apellido "González"
- **WHEN** se invoca `editarJugador()` con apellido "Rodríguez"
- **THEN** el `Jugador` conserva apellido "González" (el argumento es ignorado)

### Requirement: fechaNacimiento nula en edición no limpia el campo nacimiento (comportamiento actual)
El sistema actual siempre invoca `setNacimiento()` en `editarJugador()`. Pasar `fechaNacimiento = null` no preserva ni limpia el campo; el resultado es indeterminado según el entorno PHP (potencialmente la fecha actual o un error de tipo).

#### Scenario: fechaNacimiento no nula actualiza el campo nacimiento
- **GIVEN** un `Jugador` con nacimiento "1990-05-15"
- **WHEN** se invoca `editarJugador()` con `fechaNacimiento = "2000-01-01"`
- **THEN** el `Jugador` queda con `nacimiento = 2000-01-01`

### Requirement: Edición revalida campos via ValidadorManager
El sistema SHALL invocar `ValidadorManager::validarJugador()` en la edición, garantizando que nombre, apellido (longitud), tipoDocumento y numeroDocumento cumplan los rangos establecidos.

#### Scenario: Nombre demasiado corto en edición lanza excepción
- **GIVEN** un nombre con menos de 3 caracteres
- **WHEN** se invoca `editarJugador()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

### Requirement: editarJugador registra un log con el id y nombre del jugador
El sistema SHALL emitir un log de nivel INFO en el canal `sgt` al finalizar `editarJugador()` exitosamente, incluyendo `jugador_id` y nombre completo.

#### Scenario: Log INFO registrado tras editar un jugador
- **GIVEN** un `Jugador` con nombre "Carlos" y apellido "López" (apellido nunca actualizable por editarJugador)
- **WHEN** se invoca `editarJugador()` con nombre "Pablo" y apellido "González"
- **THEN** se emite un log INFO con mensaje "Jugador editado" que incluye `jugador_id` y `nombre = "Pablo López"` — el apellido logueado es el original porque `setApellido()` no es llamado

### Requirement: Email y celular se actualizan en la edición sin validación de formato
El sistema SHALL actualizar `email` y `celular` al invocar `editarJugador()`. No se valida formato ni longitud, igual que en la creación.

#### Scenario: Email y celular se actualizan con cualquier string
- **GIVEN** un `Jugador` con email "original@mail.com"
- **WHEN** se invoca `editarJugador()` con `email = "nuevo-sin-formato"` y `celular = "000"`
- **THEN** el `Jugador` queda con `email = "nuevo-sin-formato"` y `celular = "000"`
