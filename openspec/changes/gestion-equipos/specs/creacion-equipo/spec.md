## ADDED Requirements

### Requirement: Equipo creado con estado BORRADOR y número secuencial global por torneo
El sistema SHALL crear el `Equipo` con estado `EstadoEquipo::BORRADOR`. El número del equipo SHALL ser `count(equipos del torneo) + 1` al momento de la creación, contando todos los equipos de todas las categorías del mismo torneo.

#### Scenario: Equipo creado con estado BORRADOR
- **GIVEN** una `Categoria` con `Torneo` asignado y datos válidos
- **WHEN** se invoca `crearEquipo()`
- **THEN** el `Equipo` queda con estado `EstadoEquipo::BORRADOR`

#### Scenario: Número secuencial global por torneo
- **GIVEN** un torneo con 5 equipos existentes (en cualquier categoría)
- **WHEN** se crea un nuevo equipo en cualquier categoría del mismo torneo
- **THEN** el nuevo equipo recibe el número 6

#### Scenario: Categoria sin Torneo asignado lanza excepción
- **GIVEN** una `Categoria` cuyo `Torneo` es nulo
- **WHEN** se invoca `crearEquipo()`
- **THEN** se lanza `AppException` indicando que la categoría no tiene torneo asignado

### Requirement: Unicidad de nombre por Categoria
El sistema SHALL rechazar la creación si ya existe un `Equipo` en la misma `Categoria` con el mismo `nombre`.

#### Scenario: Nombre duplicado en la misma Categoria lanza excepción
- **GIVEN** ya existe un `Equipo` con nombre "Los Tigres" en la categoría
- **WHEN** se intenta crear otro equipo con nombre "Los Tigres" en la misma categoría
- **THEN** se lanza `AppException` indicando que ya existe un equipo con ese nombre en esta categoría

#### Scenario: Mismo nombre en distinta Categoria se permite
- **GIVEN** existe un `Equipo` "Los Tigres" en Categoría A
- **WHEN** se crea un `Equipo` "Los Tigres" en Categoría B del mismo torneo
- **THEN** el equipo se crea sin error de unicidad de nombre

### Requirement: Unicidad de nombreCorto por Categoria
El sistema SHALL rechazar la creación si ya existe un `Equipo` en la misma `Categoria` con el mismo `nombreCorto`.

#### Scenario: nombreCorto duplicado en la misma Categoria lanza excepción
- **GIVEN** ya existe un `Equipo` con nombreCorto "TIG" en la categoría
- **WHEN** se intenta crear otro equipo con nombreCorto "TIG" en la misma categoría
- **THEN** se lanza `AppException` indicando que ya existe un equipo con ese nombre corto en esta categoría

### Requirement: Validación de campos en la creación
El sistema SHALL validar que nombre tenga entre 3 y 128 caracteres y nombreCorto entre 2 y 16 caracteres mediante `ValidadorManager::validarEquipo()`. El logoPath es opcional y puede ser nulo.

#### Scenario: Nombre menor a 3 caracteres lanza excepción
- **GIVEN** un nombre con menos de 3 caracteres
- **WHEN** se invoca `crearEquipo()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

#### Scenario: nombreCorto menor a 2 caracteres lanza excepción
- **GIVEN** un nombreCorto de 1 carácter
- **WHEN** se invoca `crearEquipo()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre corto

#### Scenario: logoPath nulo se acepta sin error
- **GIVEN** datos válidos con `logoPath = null`
- **WHEN** se invoca `crearEquipo()`
- **THEN** el `Equipo` se crea con `logoPath` nulo sin error

### Requirement: Campos pais, provincia y localidad son requeridos pero sin validación de longitud
El sistema SHALL aceptar y persistir `pais`, `provincia` y `localidad` como parámetros obligatorios de `crearEquipo()`. `ValidadorManager::validarEquipo()` tiene la validación de longitud de estos campos comentada — cualquier string es aceptado sin restricción de longitud.

#### Scenario: pais, provincia y localidad se persisten sin validación de longitud
- **GIVEN** `pais = "A"` (1 carácter), `provincia = ""` (vacío), `localidad = "x".repeat(300)` junto con el resto de datos válidos
- **WHEN** se invoca `crearEquipo()`
- **THEN** el `Equipo` se crea sin error de validación con los valores tal como fueron provistos
