## ADDED Requirements

### Requirement: Categoría creada con estado BORRADOR y asociada al Torneo
El sistema SHALL crear la `Categoria` con estado `EstadoCategoria::BORRADOR` y asociarla al `Torneo` indicado. El estado BORRADOR SHALL ser el único estado posible al momento de la creación.

#### Scenario: Categoría creada exitosamente
- **GIVEN** un `Torneo` existente y datos válidos (genero, nombre, nombreCorto únicos en el torneo)
- **WHEN** se invoca `crearCategoria()`
- **THEN** se crea una `Categoria` con estado `EstadoCategoria::BORRADOR` asociada al `Torneo`

### Requirement: Unicidad de combinación torneo+genero+nombre
El sistema SHALL rechazar la creación si ya existe una `Categoria` en el mismo `Torneo` con la misma combinación de `genero` y `nombre`.

#### Scenario: Combinación torneo+genero+nombre duplicada lanza excepción
- **GIVEN** ya existe una `Categoria` en el torneo con genero "Masculino" y nombre "Mayor"
- **WHEN** se intenta crear otra `Categoria` en el mismo torneo con genero "Masculino" y nombre "Mayor"
- **THEN** se lanza `AppException` indicando que ya existe una categoría con ese nombre y género

#### Scenario: Mismo nombre con distinto genero se permite
- **GIVEN** ya existe una `Categoria` con nombre "Mayor" y genero "Masculino"
- **WHEN** se crea una `Categoria` con nombre "Mayor" y genero "Femenino" en el mismo torneo
- **THEN** la categoría se crea sin error de duplicado

### Requirement: Unicidad de nombreCorto dentro del Torneo
El sistema SHALL rechazar la creación si ya existe una `Categoria` en el mismo `Torneo` con el mismo `nombreCorto`, independientemente del género o nombre.

#### Scenario: nombreCorto duplicado en el mismo torneo lanza excepción
- **GIVEN** ya existe una `Categoria` en el torneo con nombreCorto "MAS"
- **WHEN** se intenta crear otra `Categoria` en el mismo torneo con nombreCorto "MAS"
- **THEN** se lanza `AppException` indicando que ya existe una categoría con ese nombre corto

### Requirement: Validación de campos en la creación
El sistema SHALL validar que nombre tenga entre 3 y 128 caracteres, nombreCorto entre 3 y 32 caracteres, y genero sea un valor válido del enum `Genero`.

#### Scenario: Nombre menor a 3 caracteres lanza excepción
- **GIVEN** un nombre con menos de 3 caracteres
- **WHEN** se invoca `crearCategoria()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

#### Scenario: Género inválido lanza excepción
- **GIVEN** un valor de genero que no existe en el enum `Genero`
- **WHEN** se invoca `crearCategoria()`
- **THEN** se lanza `AppException` indicando que el género no es válido
