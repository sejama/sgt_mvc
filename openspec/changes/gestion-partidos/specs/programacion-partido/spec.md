## ADDED Requirements

### Requirement: Asignación de Cancha y horario a un Partido
El sistema SHALL permitir asignar una `Cancha` y un horario a un `Partido`, cambiando su estado a PROGRAMADO. La `Cancha` SHALL existir y pertenecer al torneo actual (vía su `Sede`).

#### Scenario: Programación exitosa de partido
- **GIVEN** un `Partido` en estado BORRADOR, una `Cancha` válida del torneo y un horario disponible
- **WHEN** se invoca `editarPartido()` con la cancha y el horario
- **THEN** el `Partido` queda con la cancha asignada, el horario asignado y estado PROGRAMADO

#### Scenario: Cancha inexistente lanza excepción
- **GIVEN** un canchaId que no existe en la base de datos
- **WHEN** se invoca `editarPartido()`
- **THEN** se lanza `AppException` indicando que la cancha no existe

#### Scenario: Cancha de otro torneo lanza excepción
- **GIVEN** una `Cancha` cuya `Sede` pertenece a un torneo diferente al de la ruta del partido
- **WHEN** se invoca `editarPartido()`
- **THEN** se lanza `AppException` indicando que la cancha no pertenece al torneo

### Requirement: Validación de conflicto de cancha y horario
El sistema SHALL rechazar la programación de un partido si ya existe otro partido programado en la misma `Cancha` y el mismo horario dentro del mismo torneo.

#### Scenario: Conflicto de cancha y horario lanza excepción
- **GIVEN** un partido ya programado en Cancha X a las 10:00
- **WHEN** se intenta programar otro partido en Cancha X a las 10:00 del mismo torneo
- **THEN** se lanza `AppException` indicando que ya existe un partido en esa cancha y horario

#### Scenario: Misma cancha, horario diferente es válido
- **GIVEN** un partido programado en Cancha X a las 10:00
- **WHEN** se programa otro partido en Cancha X a las 12:00
- **THEN** el segundo partido queda programado sin error

### Requirement: El primer partido del torneo no puede ser anterior a la fecha de inicio
El sistema SHALL verificar que, si no hay ningún otro partido programado en el torneo, el horario asignado no sea anterior a `Torneo::fechaInicioTorneo`. Esta restricción NO aplica a partidos subsiguientes.

#### Scenario: Primer partido antes del inicio del torneo lanza excepción
- **GIVEN** un torneo con `fechaInicioTorneo = 2026-07-01 09:00` y ningún partido programado aún
- **WHEN** se intenta programar el primer partido a las 2026-06-30 18:00
- **THEN** se lanza `AppException` indicando que el primer partido no puede ser anterior al inicio del torneo

#### Scenario: Primer partido exactamente en la fecha de inicio es válido
- **GIVEN** un torneo con `fechaInicioTorneo = 2026-07-01 09:00` y ningún partido programado
- **WHEN** se programa el primer partido a las 2026-07-01 09:00
- **THEN** el partido queda programado sin error

#### Scenario: Segundo partido puede programarse antes del primero sin restricción de fecha de torneo
- **GIVEN** un torneo con al menos un partido ya programado
- **WHEN** se programa un nuevo partido con horario anterior a `fechaInicioTorneo`
- **THEN** el partido queda programado (la restricción solo aplica al primer partido)

### Requirement: Activación de Equipo al programar partido
El sistema SHALL cambiar el estado del equipo local y visitante de BORRADOR a ACTIVO cuando se programa exitosamente un partido que los incluye, si aún no están activos.

#### Scenario: Equipos BORRADOR se activan al programar
- **GIVEN** un `Partido` con equipos en estado BORRADOR
- **WHEN** el partido es programado exitosamente
- **THEN** ambos equipos cambian a estado `EstadoEquipo::ACTIVO`
