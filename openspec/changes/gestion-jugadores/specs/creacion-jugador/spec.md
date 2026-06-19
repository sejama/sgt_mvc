## ADDED Requirements

### Requirement: Unicidad de documento por Equipo en la creación
El sistema SHALL rechazar la creación si ya existe un `Jugador` en el mismo `Equipo` con la misma combinación de `tipoDocumento` y `numeroDocumento`. Jugadores en equipos distintos pueden compartir el mismo documento.

#### Scenario: Documento duplicado en el mismo Equipo lanza excepción
- **GIVEN** ya existe un `Jugador` en el equipo con tipoDocumento "DNI" y numeroDocumento "12345678"
- **WHEN** se intenta crear otro jugador en el mismo equipo con tipoDocumento "DNI" y numeroDocumento "12345678"
- **THEN** se lanza `AppException` indicando que ya existe un jugador con ese DNI

#### Scenario: Mismo documento en distinto Equipo se permite
- **GIVEN** existe un `Jugador` con documento "DNI/12345678" en el equipo A
- **WHEN** se crea un `Jugador` con documento "DNI/12345678" en el equipo B
- **THEN** el jugador se crea sin error de unicidad

### Requirement: Validación de campos via ValidadorManager en la creación
El sistema SHALL validar que nombre tenga entre 3 y 128 caracteres, apellido entre 3 y 128, tipoDocumento entre 1 y 8, y numeroDocumento entre 5 y 8 caracteres. La `fechaNacimiento` es opcional: si se provee, SHALL tener formato `Y-m-d`.

#### Scenario: Nombre menor a 3 caracteres lanza excepción
- **GIVEN** un nombre con menos de 3 caracteres
- **WHEN** se invoca `crearJugador()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

#### Scenario: numeroDocumento menor a 5 caracteres lanza excepción
- **GIVEN** un numeroDocumento con menos de 5 caracteres
- **WHEN** se invoca `crearJugador()`
- **THEN** se lanza `AppException` indicando el rango válido para el número de documento

#### Scenario: fechaNacimiento nula se acepta sin error
- **GIVEN** `fechaNacimiento = null`
- **WHEN** se invoca `crearJugador()`
- **THEN** el `Jugador` se crea con `nacimiento = null`

#### Scenario: fechaNacimiento con formato inválido lanza excepción
- **GIVEN** `fechaNacimiento = "31/12/2000"` (formato incorrecto)
- **WHEN** se invoca `crearJugador()`
- **THEN** se lanza `AppException` indicando que la fecha no es válida

### Requirement: Campos tipo y responsable sin validación de dominio en el manager
El sistema SHALL persistir los campos `tipo` (string libre) y `responsable` (bool) sin validación de dominio en el `JugadorManager`.

#### Scenario: Jugador creado con responsable=true
- **GIVEN** datos válidos y `responsable = true`
- **WHEN** se invoca `crearJugador()`
- **THEN** el `Jugador` queda con `responsable = true`

### Requirement: crearJugador registra un log con los datos del jugador creado
El sistema SHALL emitir un log de nivel INFO en el canal `sgt` al finalizar `crearJugador()` exitosamente, incluyendo `jugador_id`, nombre completo, `equipo_id` y nombre del equipo.

#### Scenario: Log INFO registrado tras crear un jugador
- **GIVEN** datos válidos y la creación se completa sin error
- **WHEN** se invoca `crearJugador()`
- **THEN** se emite un log INFO con mensaje "Jugador creado" que incluye `jugador_id`, `nombre` (nombre + apellido concatenados), `equipo_id` y `equipo` (nombre del equipo)

### Requirement: Email y celular son campos requeridos pero sin validación de formato
El sistema SHALL aceptar y persistir `email` y `celular` como parámetros obligatorios de `crearJugador()`. `ValidadorManager::validarJugador()` no valida formato ni longitud de estos campos — cualquier string es aceptado.

#### Scenario: Email y celular se persisten sin validación de formato
- **GIVEN** `email = "no-es-un-email"` y `celular = "abc"` junto con el resto de datos válidos
- **WHEN** se invoca `crearJugador()`
- **THEN** el `Jugador` queda con `email = "no-es-un-email"` y `celular = "abc"` sin lanzar excepción

#### Scenario: crearJugador sin email lanza error de tipo (campo requerido)
- **GIVEN** se invoca `crearJugador()` sin el parámetro `email`
- **WHEN** PHP resuelve la firma del método
- **THEN** se produce un error de tipo (argument count mismatch), el jugador no se crea
