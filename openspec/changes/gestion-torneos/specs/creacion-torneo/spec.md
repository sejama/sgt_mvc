## ADDED Requirements

### Requirement: Torneo creado con estado BORRADOR y creador asignado
El sistema SHALL crear el `Torneo` con estado `EstadoTorneo::BORRADOR` y asignar el `Usuario` que lo crea como creador. El estado BORRADOR SHALL ser el único estado válido al momento de la creación.

#### Scenario: Torneo creado exitosamente
- **GIVEN** un `Usuario` autenticado y los datos: nombre="Copa Verano 2026", ruta="copa-verano-2026", descripción="Torneo anual", fechaInicioInscripcion="2026-07-01 09:00", fechaFinInscripcion="2026-07-15 23:59", fechaInicioTorneo="2026-08-01 09:00", fechaFinTorneo="2026-08-31 23:59"
- **WHEN** se invoca `crearTorneo()`
- **THEN** se crea un `Torneo` con estado `EstadoTorneo::BORRADOR`, el creador asignado y todas las fechas persistidas en timezone `America/Argentina/Buenos_Aires`

### Requirement: Unicidad global de nombre y ruta
El sistema SHALL rechazar la creación si ya existe un `Torneo` con el mismo `nombre` o la misma `ruta`, independientemente del creador.

#### Scenario: Nombre duplicado lanza excepción
- **GIVEN** ya existe un `Torneo` con nombre "Torneo Nacional 2026"
- **WHEN** se intenta crear otro torneo con el mismo nombre
- **THEN** se lanza `AppException` indicando que el nombre ya está registrado

#### Scenario: Ruta duplicada lanza excepción
- **GIVEN** ya existe un `Torneo` con ruta "torneo-nacional-2026"
- **WHEN** se intenta crear otro torneo con la misma ruta
- **THEN** se lanza `AppException` indicando que la ruta ya está registrada

### Requirement: Validación de formato y longitud de campos
El sistema SHALL validar nombre (3–128 chars), ruta (3–32 chars, solo minúsculas/dígitos/guiones, sin espacios) y descripción (0–255 chars). El formato de cada fecha SHALL ser `Y-m-d H:i`.

#### Scenario: Nombre demasiado corto lanza excepción
- **GIVEN** un nombre con menos de 3 caracteres
- **WHEN** se invoca `crearTorneo()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

#### Scenario: Ruta con espacios lanza excepción
- **GIVEN** una ruta que contiene espacios (ej. "torneo nacional")
- **WHEN** se invoca `crearTorneo()`
- **THEN** se lanza `AppException` indicando que la ruta no puede contener espacios

#### Scenario: Ruta con caracteres inválidos lanza excepción
- **GIVEN** una ruta que contiene caracteres no permitidos (ej. "Torneo_2026" con mayúscula y guion bajo)
- **WHEN** se invoca `crearTorneo()`
- **THEN** se lanza `AppException` indicando que la ruta solo admite minúsculas, dígitos y guiones

#### Scenario: Fecha con formato inválido lanza excepción
- **GIVEN** una fecha en formato `d/m/Y` en lugar de `Y-m-d H:i`
- **WHEN** se invoca `crearTorneo()`
- **THEN** se lanza `AppException` indicando que la fecha no es válida

### Requirement: Coherencia temporal de los cuatro rangos de fechas
El sistema SHALL verificar que se cumpla la cadena: `fechaInicioInscripcion < fechaFinInscripcion`, `fechaInicioTorneo < fechaFinTorneo`, y `fechaFinInscripcion < fechaInicioTorneo`.

#### Scenario: Fin de inscripción posterior al inicio del torneo lanza excepción
- **GIVEN** una `fechaFinInscripcion` igual o posterior a `fechaInicioTorneo`
- **WHEN** se invoca `crearTorneo()`
- **THEN** se lanza `AppException` indicando la incoherencia entre inscripción y torneo

#### Scenario: Inicio de torneo igual a fin de torneo lanza excepción
- **GIVEN** `fechaInicioTorneo` igual a `fechaFinTorneo`
- **WHEN** se invoca `crearTorneo()`
- **THEN** se lanza `AppException` indicando que la fecha de inicio no puede ser mayor o igual a la de fin

#### Scenario: Fechas coherentes permiten la creación
- **GIVEN** fechaInicioInscripcion="2026-07-01 09:00" < fechaFinInscripcion="2026-07-15 23:59" < fechaInicioTorneo="2026-08-01 09:00" < fechaFinTorneo="2026-08-31 23:59"
- **WHEN** se invoca `crearTorneo()`
- **THEN** el `Torneo` se crea sin error de coherencia temporal
