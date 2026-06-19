## ADDED Requirements

### Requirement: Edición revalida unicidad de nombre excluyendo el propio Equipo
El sistema SHALL rechazar el cambio de nombre si ya existe otro `Equipo` en la misma `Categoria` con ese nombre. Si el nombre no cambia, no SHALL verificarse unicidad.

#### Scenario: Cambiar nombre a uno ya usado por otro equipo lanza excepción
- **GIVEN** un equipo A con nombre "Los Tigres" y un equipo B que intenta cambiar su nombre a "Los Tigres"
- **WHEN** se invoca `editarEquipo()` sobre el equipo B
- **THEN** se lanza `AppException` indicando que ya existe un equipo con ese nombre en esta categoría

#### Scenario: Conservar el mismo nombre no lanza excepción
- **GIVEN** un `Equipo` que se edita sin cambiar su nombre
- **WHEN** se invoca `editarEquipo()`
- **THEN** la edición procede sin error de nombre duplicado

### Requirement: Edición revalida unicidad de nombreCorto excluyendo el propio Equipo
El sistema SHALL rechazar el cambio de nombreCorto si ya existe otro `Equipo` en la misma `Categoria` con ese nombreCorto. Si el nombreCorto no cambia, no SHALL verificarse unicidad.

#### Scenario: Cambiar nombreCorto a uno ya usado por otro equipo lanza excepción
- **GIVEN** un equipo A con nombreCorto "TIG" y un equipo B que intenta cambiar su nombreCorto a "TIG"
- **WHEN** se invoca `editarEquipo()` sobre el equipo B
- **THEN** se lanza `AppException` indicando que ya existe un equipo con ese nombre corto en esta categoría

#### Scenario: Conservar el mismo nombreCorto no lanza excepción
- **GIVEN** un `Equipo` que se edita sin cambiar su nombreCorto
- **WHEN** se invoca `editarEquipo()`
- **THEN** la edición procede sin error de nombre corto duplicado

### Requirement: Edición revalida longitud de campos via ValidadorManager
El sistema SHALL invocar `ValidadorManager::validarEquipo()` en la edición, garantizando que nombre (3-128 chars) y nombreCorto (2-16 chars) cumplan el rango.

#### Scenario: Nombre demasiado corto en edición lanza excepción
- **GIVEN** un nombre con menos de 3 caracteres
- **WHEN** se invoca `editarEquipo()`
- **THEN** se lanza `AppException` indicando el rango válido

### Requirement: logoPath no se actualiza si se pasa null en la edición
El sistema SHALL actualizar el `logoPath` solo si el argumento es no nulo. Pasar `null` SHALL preservar el `logoPath` existente del equipo.

#### Scenario: logoPath nulo preserva el logo existente
- **GIVEN** un `Equipo` con `logoPath = "equipos/tigres.png"` existente
- **WHEN** se invoca `editarEquipo()` con `logoPath = null`
- **THEN** el `Equipo` conserva `logoPath = "equipos/tigres.png"` sin cambios

#### Scenario: logoPath no nulo reemplaza el logo existente
- **GIVEN** un `Equipo` con `logoPath = "equipos/tigres.png"`
- **WHEN** se invoca `editarEquipo()` con `logoPath = "equipos/tigres-nuevo.png"`
- **THEN** el `Equipo` queda con `logoPath = "equipos/tigres-nuevo.png"`
