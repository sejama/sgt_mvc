## ADDED Requirements

### Requirement: Nombre de sede único dentro del torneo
El sistema SHALL rechazar la creación de una sede si ya existe otra sede con el mismo nombre en el mismo torneo.

#### Scenario: Crear sede con nombre duplicado en el mismo torneo lanza excepción
- **GIVEN** un `Torneo` que ya tiene una `Sede` con nombre "Polideportivo Norte"
- **WHEN** se invoca `crearSede(torneo, "Polideportivo Norte", "Dirección cualquiera")`
- **THEN** se lanza `AppException` con mensaje "Ya existe una sede con ese nombre"

#### Scenario: Mismo nombre de sede en torneos distintos es permitido
- **GIVEN** dos torneos distintos, el primero con una `Sede` llamada "Polideportivo Norte"
- **WHEN** se invoca `crearSede(torneo2, "Polideportivo Norte", "Dirección cualquiera")`
- **THEN** la sede se crea sin error

### Requirement: Validación de longitud de nombre y dirección
El sistema SHALL rechazar valores fuera de rango: nombre 3-128 caracteres, dirección 8-128 caracteres.

#### Scenario: Nombre con menos de 3 caracteres lanza excepción
- **GIVEN** un nombre de 2 caracteres
- **WHEN** se invoca `crearSede(torneo, nombre, "Dirección válida")`
- **THEN** se lanza `AppException` indicando el rango válido del nombre

#### Scenario: Dirección con menos de 8 caracteres lanza excepción
- **GIVEN** una dirección de 7 caracteres
- **WHEN** se invoca `crearSede(torneo, "Nombre válido", dirección)`
- **THEN** se lanza `AppException` indicando el rango válido de la dirección

#### Scenario: Crear sede con datos válidos persiste sin flush automático
- **GIVEN** un torneo sin sede con ese nombre y datos válidos
- **WHEN** se invoca `crearSede(torneo, "Polideportivo", "Bv. Galvez 123")`
- **THEN** la `Sede` queda en el unit of work pero NO se hace flush automático (el controller debe llamar flush)
