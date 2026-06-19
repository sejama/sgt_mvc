## ADDED Requirements

### Requirement: Nombre de cancha único dentro de la sede
El sistema SHALL rechazar la creación o edición de una cancha si ya existe otra con el mismo nombre en la misma sede.

#### Scenario: Crear cancha con nombre duplicado en la misma sede lanza excepción
- **GIVEN** una `Sede` con una `Cancha` de nombre "Cancha 1"
- **WHEN** se invoca `crearCancha(sede, "Cancha 1", "descripción")`
- **THEN** se lanza `AppException` con mensaje "Ya existe una cancha con ese nombre"

#### Scenario: Mismo nombre de cancha en sedes distintas es permitido
- **GIVEN** dos sedes distintas, la primera con una cancha "Cancha 1"
- **WHEN** se invoca `crearCancha(sede2, "Cancha 1", "descripción")`
- **THEN** la cancha se crea sin error

### Requirement: Validación de longitud de nombre y descripción de cancha
El nombre SHALL tener entre 1 y 128 caracteres. La descripción SHALL tener entre 0 y 255 caracteres (puede estar vacía).

#### Scenario: Nombre vacío lanza excepción
- **GIVEN** un nombre de cancha vacío ("")
- **WHEN** se invoca `crearCancha(sede, "", "descripción")`
- **THEN** se lanza `AppException` indicando el rango válido del nombre

#### Scenario: Descripción vacía es permitida
- **GIVEN** una descripción de cancha vacía ("")
- **WHEN** se invoca `crearCancha(sede, "Cancha central", "")`
- **THEN** la cancha se crea sin error

### Requirement: crearCancha flushea automáticamente
A diferencia de `crearSede`, `crearCancha` persiste con flush automático sin necesidad de flush externo.

#### Scenario: crearCancha persiste inmediatamente
- **GIVEN** datos válidos de nombre y descripción
- **WHEN** se invoca `crearCancha(sede, "Cancha 2", "descripción")`
- **THEN** la cancha queda persistida sin flush externo

### Requirement: editarCancha permite conservar el nombre sin conflicto
Si el nombre no cambia, no se valida unicidad contra la misma cancha.

#### Scenario: Editar cancha conservando nombre no lanza error
- **GIVEN** una cancha con nombre "Cancha 1"
- **WHEN** se invoca `editarCancha(cancha, "Cancha 1", "nueva descripción")`
- **THEN** la edición persiste sin error

### Requirement: obtenerCancha lanza excepción si no existe (divergencia con obtenerSede)
A diferencia de `SedeManager::obtenerSede()` que retorna `null`, `CanchaManager::obtenerCancha()` lanza `AppException` cuando el id no corresponde a ninguna cancha.

#### Scenario: obtenerCancha con id inexistente lanza AppException
- **GIVEN** ninguna cancha con el id indicado
- **WHEN** se invoca `obtenerCancha(id)`
- **THEN** se lanza `AppException` con mensaje "No se encontró la cancha"

### Requirement: Eliminación de cancha es permanente
El sistema SHALL eliminar la cancha de forma permanente.

#### Scenario: eliminarCancha borra la cancha del sistema
- **GIVEN** una `Cancha` existente
- **WHEN** se invoca `eliminarCancha(cancha)`
- **THEN** la cancha ya no existe en el sistema
