## ADDED Requirements

### Requirement: Categoria tiene campo maxSets con valores 3 o 5
El sistema SHALL almacenar en cada `Categoria` el formato de sets (3 o 5). El valor por defecto SHALL ser 3. Solo se aceptan los valores 3 y 5.

#### Scenario: Crear categoría sin especificar maxSets usa formato 3 por defecto
- **GIVEN** una nueva categoría sin valor de maxSets explícito
- **WHEN** se invoca `crearCategoria()`
- **THEN** la categoría queda con `maxSets = 3`

#### Scenario: Crear categoría con maxSets = 5 para playoff
- **GIVEN** datos válidos de categoría y maxSets = 5
- **WHEN** se invoca `crearCategoria()` con maxSets = 5
- **THEN** la categoría queda con `maxSets = 5`

#### Scenario: Valor de maxSets distinto de 3 o 5 lanza excepción
- **GIVEN** maxSets = 4
- **WHEN** se invoca `crearCategoria()` o `editarCategoria()`
- **THEN** se lanza `AppException` indicando que el formato de sets debe ser 3 o 5

### Requirement: maxSets editable solo en estados BORRADOR y ACTIVA
El sistema SHALL rechazar el cambio de `maxSets` si la categoría está en un estado posterior a ACTIVA (CERRADA, ZONAS_CREADAS, ZONAS_CERRADAS, FINALIZADO).

#### Scenario: Cambiar maxSets en categoría BORRADOR es permitido
- **GIVEN** una categoría en estado BORRADOR con `maxSets = 3`
- **WHEN** se invoca `editarCategoria()` con `maxSets = 5`
- **THEN** la categoría queda con `maxSets = 5`

#### Scenario: Cambiar maxSets en categoría CERRADA lanza excepción
- **GIVEN** una categoría en estado CERRADA con `maxSets = 3`
- **WHEN** se invoca `editarCategoria()` con `maxSets = 5`
- **THEN** se lanza `AppException` indicando que el formato no puede cambiarse en este estado

### Requirement: Un torneo puede tener categorías con distintos maxSets
El sistema SHALL permitir que distintas categorías del mismo torneo tengan valores de maxSets diferentes.

#### Scenario: Torneo con categoría zona a 3 sets y categoría playoff a 5 sets
- **GIVEN** un torneo con una categoría "Zona A" (maxSets=3) y una categoría "Playoff" (maxSets=5)
- **WHEN** se consultan ambas categorías
- **THEN** "Zona A" tiene maxSets=3 y "Playoff" tiene maxSets=5

### Requirement: Categoria configura la regla de cierre para sets regulares (setRegularDif2)
El sistema SHALL almacenar en cada `Categoria` si los sets regulares requieren diferencia mínima de 2 puntos para cerrarse (`setRegularDif2 = true`) o si alcanza con llegar al puntaje tope aunque la diferencia sea 1 (`setRegularDif2 = false`). El valor por defecto SHALL ser `true` (regla FIVB estándar).

#### Scenario: setRegularDif2 = true por defecto (requiere diferencia de 2)
- **GIVEN** una nueva categoría sin especificar `setRegularDif2`
- **WHEN** se invoca `crearCategoria()`
- **THEN** la categoría queda con `setRegularDif2 = true`

#### Scenario: setRegularDif2 = false (primero en llegar a 25 gana aunque sea 25-24)
- **GIVEN** una categoría creada con `setRegularDif2 = false`
- **WHEN** se consulta la configuración
- **THEN** la categoría tiene `setRegularDif2 = false`

### Requirement: Categoria configura la regla de cierre para el set decisivo (setDecisivoDif2)
El sistema SHALL almacenar si el set decisivo requiere diferencia mínima de 2 para cerrarse (`setDecisivoDif2 = true`) o si alcanza con llegar a 15 aunque la diferencia sea 1 (`setDecisivoDif2 = false`). El valor por defecto SHALL ser `true`.

#### Scenario: setDecisivoDif2 = true por defecto
- **GIVEN** una nueva categoría sin especificar `setDecisivoDif2`
- **WHEN** se invoca `crearCategoria()`
- **THEN** la categoría queda con `setDecisivoDif2 = true`

### Requirement: Las cuatro combinaciones de reglas son válidas
El sistema SHALL permitir cualquier combinación de `setRegularDif2` y `setDecisivoDif2` dentro de una misma categoría.

#### Scenario: Combinación FIVB estándar (ambos con dif 2)
- **GIVEN** una categoría con `setRegularDif2 = true` y `setDecisivoDif2 = true`
- **WHEN** se validan los sets
- **THEN** sets regulares requieren 25+dif2 y el set decisivo requiere 15+dif2

#### Scenario: Combinación simple (ambos sin requisito de diferencia)
- **GIVEN** una categoría con `setRegularDif2 = false` y `setDecisivoDif2 = false`
- **WHEN** se validan los sets
- **THEN** basta con llegar a 25 en regulares (25-24 válido) y a 15 en el decisivo (15-14 válido)

#### Scenario: Combinación mixta (regular con dif2, decisivo sin dif2)
- **GIVEN** una categoría con `setRegularDif2 = true` y `setDecisivoDif2 = false`
- **WHEN** se validan los sets
- **THEN** sets regulares exigen 25+dif2 pero el set decisivo se cierra al llegar a 15 (15-14 válido)
