## ADDED Requirements

### Requirement: Edición de datos del Torneo con revalidación completa
El sistema SHALL revalidar todos los campos al editar un `Torneo` con las mismas reglas que en la creación. Los cambios se persisten solo si todas las validaciones pasan.

#### Scenario: Edición exitosa con datos válidos
- **GIVEN** un `Torneo` existente y los nuevos datos: nombre="Copa Invierno 2026", ruta="copa-invierno-2026", fechaInicioInscripcion="2026-09-01 09:00", fechaFinInscripcion="2026-09-15 23:59", fechaInicioTorneo="2026-10-01 09:00", fechaFinTorneo="2026-10-31 23:59"
- **WHEN** se invoca `editarTorneo()`
- **THEN** el `Torneo` queda actualizado con los nuevos datos

#### Scenario: Torneo sin creador asignado lanza excepción
- **GIVEN** un `Torneo` cuyo campo `creador` es nulo
- **WHEN** se invoca `editarTorneo()`
- **THEN** se lanza `AppException` indicando que el torneo no tiene creador asignado

### Requirement: Unicidad de nombre y ruta excluyendo el propio Torneo
El sistema SHALL rechazar el cambio de nombre o ruta si ya existe otro `Torneo` distinto con ese valor, pero SHALL permitir conservar el mismo nombre o ruta del torneo que se edita.

#### Scenario: Nombre cambiado a uno ya existente en otro torneo lanza excepción
- **GIVEN** un torneo A con nombre "Copa Verano" y un torneo B que se edita intentando usar el mismo nombre
- **WHEN** se invoca `editarTorneo()` sobre el torneo B
- **THEN** se lanza `AppException` indicando que el nombre ya está registrado

#### Scenario: Conservar el mismo nombre no lanza excepción
- **GIVEN** un `Torneo` que se edita sin cambiar su nombre
- **WHEN** se invoca `editarTorneo()`
- **THEN** la edición procede sin error de nombre duplicado

#### Scenario: Conservar la misma ruta no lanza excepción
- **GIVEN** un `Torneo` que se edita sin cambiar su ruta
- **WHEN** se invoca `editarTorneo()`
- **THEN** la edición procede sin error de ruta duplicada

### Requirement: Edición resetea el estado del Torneo a BORRADOR
El sistema SHALL cambiar el estado del `Torneo` a `EstadoTorneo::BORRADOR` al finalizar cualquier edición exitosa, independientemente del estado previo del torneo.

#### Scenario: Torneo ACTIVO vuelve a BORRADOR al editar
- **GIVEN** un `Torneo` en estado `EstadoTorneo::ACTIVO`
- **WHEN** se edita cualquier campo del torneo
- **THEN** el estado del `Torneo` cambia a `EstadoTorneo::BORRADOR`

#### Scenario: Torneo EN_CURSO vuelve a BORRADOR al editar
- **GIVEN** un `Torneo` en estado `EstadoTorneo::EN_CURSO`
- **WHEN** se edita cualquier campo del torneo
- **THEN** el estado del `Torneo` cambia a `EstadoTorneo::BORRADOR`
