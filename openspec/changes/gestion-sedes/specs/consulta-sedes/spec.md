## ADDED Requirements

### Requirement: Consulta de sedes
El sistema SHALL proveer listado global de sedes y búsqueda por id. La búsqueda por id retorna `null` si no existe (sin excepción).

#### Scenario: obtenerSedes retorna todas las sedes del sistema
- **GIVEN** existen múltiples sedes en diferentes torneos
- **WHEN** se invoca `obtenerSedes()`
- **THEN** se retornan todas sin filtro por torneo

#### Scenario: obtenerSede retorna null si el id no existe
- **GIVEN** ninguna `Sede` tiene el id indicado
- **WHEN** se invoca `obtenerSede(id)`
- **THEN** retorna `null` sin lanzar excepción

### Requirement: Consulta de canchas por sede
El sistema SHALL retornar las canchas filtradas por sede.

#### Scenario: obtenerCanchas retorna solo las canchas de la sede indicada
- **GIVEN** una sede con dos canchas y otra sede con tres canchas
- **WHEN** se invoca `obtenerCanchas(sede1)`
- **THEN** retorna exactamente las dos canchas de sede1

### Requirement: Consulta cruzada de sedes y canchas por torneo
El sistema SHALL proveer una consulta que retorne las sedes con sus canchas filtrando por el slug del torneo.

#### Scenario: obtenerSedesYCanchasByTorneo retorna estructura sedes-canchas del torneo
- **GIVEN** un torneo con slug "torneo-verano-2025" y dos sedes cada una con sus canchas
- **WHEN** se invoca `obtenerSedesYCanchasByTorneo("torneo-verano-2025")`
- **THEN** retorna las sedes del torneo con sus canchas anidadas

#### Scenario: obtenerSedesYCanchasByTorneo retorna vacío para torneo sin sedes
- **GIVEN** un torneo sin sedes registradas
- **WHEN** se invoca `obtenerSedesYCanchasByTorneo(ruta)`
- **THEN** retorna array vacío
