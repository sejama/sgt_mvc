## CHANGED Requirements

### Requirement: cargarResultado persiste sets 4 y 5 cuando la categoría tiene maxSets = 5
El sistema SHALL persistir los valores de sets 4 y 5 si la categoría del partido tiene `maxSets = 5`. Si `maxSets = 3`, los valores de sets 4 y 5 SHALL ser ignorados.

#### Scenario: Partido de categoría maxSets=3 solo persiste sets 1-3
- **GIVEN** un `Partido` perteneciente a una categoría con `maxSets = 3`
- **WHEN** se invoca `cargarResultado()` con valores para los 5 sets
- **THEN** `localSet4`, `localSet5`, `visitanteSet4`, `visitanteSet5` quedan en null

#### Scenario: Partido de categoría maxSets=5 persiste los 5 sets
- **GIVEN** un `Partido` perteneciente a una categoría con `maxSets = 5`
- **WHEN** se invoca `cargarResultado()` con local ganando 3-1 (sets: 25/18, 18/25, 25/20, 25/22)
- **THEN** los 4 sets se persisten y `localSet5` queda en null

### Requirement: cargarResultado valida que el ganador alcanzó los sets necesarios
El sistema SHALL rechazar resultados donde ningún equipo haya alcanzado el umbral de sets ganados (`ceil(maxSets / 2)`): 2 sets para formato 3, 3 sets para formato 5.

#### Scenario: Resultado inválido en formato 3 sets (ninguno llega a 2)
- **GIVEN** un `Partido` con `maxSets = 3` y sets jugados: local 1 set, visitante 1 set (set 3 sin jugar)
- **WHEN** se invoca `cargarResultado()`
- **THEN** se lanza `AppException` indicando que el resultado es inválido

#### Scenario: Resultado válido formato 3 sets — local gana 2-0
- **GIVEN** un `Partido` con `maxSets = 3`, local gana sets 1 y 2 (25/18, 25/20), set 3 no jugado
- **WHEN** se invoca `cargarResultado()`
- **THEN** el resultado se persiste con ganador = equipoLocal

#### Scenario: Resultado válido formato 3 sets — local gana 2-1
- **GIVEN** un `Partido` con `maxSets = 3`, local gana sets 1 y 3, visitante gana set 2
- **WHEN** se invoca `cargarResultado()`
- **THEN** el resultado se persiste con ganador = equipoLocal

#### Scenario: Resultado válido formato 5 sets — visitante gana 3-2
- **GIVEN** un `Partido` con `maxSets = 5`, visitante gana sets 1, 3 y 5 (local gana 2 y 4)
- **WHEN** se invoca `cargarResultado()`
- **THEN** el resultado se persiste con ganador = equipoVisitante y los 5 sets guardados

#### Scenario: Resultado inválido en formato 5 sets (nadie llega a 3)
- **GIVEN** un `Partido` con `maxSets = 5` y sets jugados: local 2, visitante 2 (set 5 sin jugar)
- **WHEN** se invoca `cargarResultado()`
- **THEN** se lanza `AppException` indicando que el resultado es inválido

### Requirement: cargarResultado resuelve maxSets desde la categoría del partido
El sistema SHALL obtener el `maxSets` vía `partido → grupo → categoria` (zona) o `partido → categoria` (playoff). Si la categoría no puede resolverse, SHALL usar 3 como fallback.

#### Scenario: Partido de grupo obtiene maxSets desde su categoría vía el grupo
- **GIVEN** un `Partido` de grupo cuya categoría tiene `maxSets = 5`
- **WHEN** se invoca `cargarResultado()`
- **THEN** se aplica la validación de 3 sets necesarios para ganar
