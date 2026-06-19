## ADDED Requirements

### Requirement: Edición de genero, nombre y nombreCorto sin reset de estado
El sistema SHALL permitir editar el genero, nombre y nombreCorto de una `Categoria`. A diferencia de `editarTorneo()`, la edición de categoría NO SHALL cambiar el estado de la `Categoria`.

#### Scenario: Edición exitosa no altera el estado de la Categoria
- **GIVEN** una `Categoria` en estado `EstadoCategoria::ACTIVA`
- **WHEN** se editan nombre, genero o nombreCorto con valores válidos y únicos
- **THEN** la `Categoria` queda actualizada y su estado permanece `EstadoCategoria::ACTIVA`

### Requirement: Unicidad de torneo+genero+nombre excluyendo la propia Categoria en edición
El sistema SHALL rechazar la edición si la nueva combinación de torneo+genero+nombre ya existe en otra `Categoria` distinta del mismo torneo.

#### Scenario: Cambiar nombre a combinación ya existente en otra Categoria lanza excepción
- **GIVEN** una `Categoria` A con genero "Masculino" y nombre "Sub20", y una `Categoria` B con genero "Masculino" y nombre "Mayor"
- **WHEN** se edita la categoría B intentando cambiar su nombre a "Sub20" (misma combinación que A)
- **THEN** se lanza `AppException` indicando que ya existe una categoría con ese nombre y género

#### Scenario: Conservar el mismo nombre y genero no lanza excepción
- **GIVEN** una `Categoria` con genero "Masculino" y nombre "Mayor"
- **WHEN** se edita la categoría sin cambiar genero ni nombre (solo se cambia nombreCorto)
- **THEN** la edición procede sin error de duplicado

### Requirement: Unicidad de nombreCorto en edición excluyendo la propia Categoria
El sistema SHALL rechazar el cambio de nombreCorto si ya existe otra `Categoria` en el mismo torneo con ese nombreCorto.

#### Scenario: Cambiar nombreCorto a uno ya usado por otra Categoria lanza excepción
- **GIVEN** una `Categoria` A con nombreCorto "MAS" y una `Categoria` B con nombreCorto "FEM"
- **WHEN** se edita la categoría B intentando cambiar su nombreCorto a "MAS"
- **THEN** se lanza `AppException` indicando que ya existe una categoría con ese nombre corto

#### Scenario: Conservar el mismo nombreCorto no lanza excepción
- **GIVEN** una `Categoria` con nombreCorto "MAS"
- **WHEN** se edita la categoría sin cambiar el nombreCorto
- **THEN** la edición procede sin error de duplicado de nombre corto

### Requirement: Edición no revalida longitud ni formato de campos via ValidadorManager
El sistema SHALL persistir los nuevos valores de nombre, genero y nombreCorto sin invocar `ValidadorManager::validarCategoria()`. La única validación de integridad aplicada es la de unicidad.

#### Scenario: Género inválido en edición lanza ValueError (no AppException)
- **GIVEN** un valor de genero que no existe en el enum `Genero`
- **WHEN** se invoca `editarCategoria()`
- **THEN** se lanza `ValueError` (no `AppException`) al intentar `Genero::from($genero)`
