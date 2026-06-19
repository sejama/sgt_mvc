## ADDED Requirements

### Requirement: Estados válidos del Equipo
El sistema SHALL gestionar el `Equipo` a través de seis estados: BORRADOR, ACTIVO, NO_PARTICIPA, ELIMINADO, DESCALIFICADO y CALIFICADO. El `EquipoManager` gestiona directamente dos transiciones: BORRADOR (creación) y NO_PARTICIPA (bajarEquipo). Las demás transiciones son responsabilidad de otros componentes del sistema.

```
BORRADOR ──► ACTIVO ──► NO_PARTICIPA
   ↑            │
(crear)    (generar/programar partido)
                │
                ├──► ELIMINADO
                ├──► DESCALIFICADO
                └──► CALIFICADO
```

#### Scenario: Estado inicial al crear es siempre BORRADOR
- **GIVEN** datos válidos para un nuevo equipo
- **WHEN** se invoca `crearEquipo()`
- **THEN** el `Equipo` queda con estado `EstadoEquipo::BORRADOR`

#### Scenario: bajarEquipo es la única transición a NO_PARTICIPA gestionada por EquipoManager
- **GIVEN** un `Equipo` en cualquier estado
- **WHEN** se invoca `bajarEquipo()`
- **THEN** el estado cambia a `EstadoEquipo::NO_PARTICIPA`

### Requirement: Eliminación permanente del Equipo
El sistema SHALL eliminar el `Equipo` de forma permanente (hard delete) con logging al invocar `eliminarEquipo()`. La operación recibe la entidad directamente sin buscarla por id.

#### Scenario: Equipo eliminado no existe más en el sistema
- **GIVEN** un `Equipo` existente
- **WHEN** se invoca `eliminarEquipo(equipo)`
- **THEN** el `Equipo` ya no puede ser recuperado por ninguna consulta

#### Scenario: Eliminación loguea id y nombre del equipo
- **GIVEN** un `Equipo` con id y nombre conocidos
- **WHEN** se invoca `eliminarEquipo(equipo)`
- **THEN** se registra un log de nivel info con `equipo_id` y `nombre`

### Requirement: Búsqueda de Equipo por id lanza excepción si no existe
El sistema SHALL retornar el `Equipo` si existe, y SHALL lanzar `AppException` si no se encuentra ningún equipo con ese id.

#### Scenario: Equipo encontrado por id
- **GIVEN** existe un `Equipo` con el id indicado
- **WHEN** se invoca `obtenerEquipo(id)`
- **THEN** se retorna ese `Equipo`

#### Scenario: Id inexistente lanza AppException
- **GIVEN** ningún `Equipo` tiene el id indicado
- **WHEN** se invoca `obtenerEquipo(id)`
- **THEN** se lanza `AppException` con el mensaje "No se encontró el equipo"

### Requirement: Consulta de Equipos por distintos criterios
El sistema SHALL proveer listado global sin filtro y listado por categoría.

#### Scenario: obtenerEquipos retorna todos sin filtro de estado ni categoría
- **GIVEN** existen equipos en distintas categorías y estados
- **WHEN** se invoca `obtenerEquipos()`
- **THEN** se retornan todos los equipos sin filtro

#### Scenario: obtenerEquiposPorCategoria retorna solo los de la Categoria indicada
- **GIVEN** la categoría A tiene 3 equipos y la categoría B tiene 2 equipos
- **WHEN** se invoca `obtenerEquiposPorCategoria(categoriaA)`
- **THEN** se retornan los 3 equipos de la categoría A, sin los de categoría B
