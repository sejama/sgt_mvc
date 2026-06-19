## CHANGED Requirements

### Requirement: Cambiar solo el género de una categoría sin duplicado real no lanza excepción
El sistema SHALL permitir editar el género de una categoría cuando no existe otra categoría con el mismo par (nombre, género) en el torneo. El bug previo rechazaba este caso por error de precedencia de operadores.

#### Scenario: Cambiar solo el género sin duplicado es permitido
- **GIVEN** una categoría "Libre A" de género "Masculino" y ninguna otra categoría "Libre A" de género "Femenino" en el torneo
- **WHEN** se invoca `editarCategoria()` cambiando el género a "Femenino" (mismo nombre)
- **THEN** la categoría queda con género "Femenino" sin lanzar excepción

#### Scenario: Cambiar solo el nombre sin duplicado es permitido
- **GIVEN** una categoría "Libre A" de género "Masculino" y ninguna categoría "Libre B" de género "Masculino" en el torneo
- **WHEN** se invoca `editarCategoria()` cambiando el nombre a "Libre B" (mismo género)
- **THEN** la categoría queda con nombre "Libre B" sin lanzar excepción

#### Scenario: Cambiar nombre y género a combinación existente lanza excepción
- **GIVEN** una categoría "Libre A / Masculino" y otra categoría "Libre B / Femenino" en el torneo
- **WHEN** se invoca `editarCategoria()` sobre "Libre A / Masculino" con nombre "Libre B" y género "Femenino"
- **THEN** se lanza `AppException('Ya existe una categoría con ese nombre y genero')`

#### Scenario: Conservar nombre y género sin cambio es permitido
- **GIVEN** una categoría "Libre A / Masculino"
- **WHEN** se invoca `editarCategoria()` con el mismo nombre y género (editando solo otro campo)
- **THEN** la edición procede sin error de duplicado
