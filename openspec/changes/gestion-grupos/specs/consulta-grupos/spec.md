## ADDED Requirements

### Requirement: Búsqueda de Grupo por id lanza excepción si no existe
El sistema SHALL retornar el `Grupo` si existe, y SHALL lanzar `AppException` si no se encuentra ningún grupo con ese id.

#### Scenario: Grupo encontrado por id
- **GIVEN** existe un `Grupo` con el id indicado
- **WHEN** se invoca `obtenerGrupo(id)`
- **THEN** se retorna ese `Grupo`

#### Scenario: Id inexistente lanza AppException
- **GIVEN** ningún `Grupo` tiene el id indicado
- **WHEN** se invoca `obtenerGrupo(id)`
- **THEN** se lanza `AppException` con el mensaje "No se encontró el grupo"

### Requirement: Listado de Grupos de una Categoria ordenado por nombre ASC
El sistema SHALL retornar todos los `Grupo` de la `Categoria` indicada, ordenados alfabéticamente por nombre de forma ascendente.

#### Scenario: Grupos retornados en orden alfabético
- **GIVEN** una `Categoria` con tres grupos de nombres "C", "A", "B"
- **WHEN** se invoca `obtenerGrupos(categoria)`
- **THEN** se retorna [grupoA, grupoB, grupoC] en ese orden

#### Scenario: Categoria sin grupos retorna lista vacía
- **GIVEN** una `Categoria` sin grupos asociados
- **WHEN** se invoca `obtenerGrupos(categoria)`
- **THEN** se retorna una lista vacía

### Requirement: Listado de Equipos con grupo asignado, ordenado por grupo y nombre
El sistema SHALL retornar únicamente los `Equipo` de la `Categoria` que tienen un `Grupo` asignado (no nulo), ordenados primero por nombre de grupo (ASC) y luego por nombre de equipo (ASC) dentro del mismo grupo.

#### Scenario: Solo equipos con grupo son retornados
- **GIVEN** una `Categoria` con 3 equipos con grupo y 2 equipos sin grupo
- **WHEN** se invoca `obtenerEquiposDeCategoriaConGrupo(categoria)`
- **THEN** se retornan los 3 equipos que tienen grupo, sin los 2 sin grupo

#### Scenario: Equipos ordenados por nombre de grupo y luego por nombre de equipo
- **GIVEN** equipos: [E3 en GrupoB, E1 en GrupoA, E2 en GrupoA]
- **WHEN** se invoca `obtenerEquiposDeCategoriaConGrupo(categoria)`
- **THEN** el orden retornado es [E1(GrupoA), E2(GrupoA), E3(GrupoB)]

#### Scenario: Categoria sin equipos con grupo retorna lista vacía
- **GIVEN** una `Categoria` cuyos equipos no tienen grupo asignado
- **WHEN** se invoca `obtenerEquiposDeCategoriaConGrupo(categoria)`
- **THEN** se retorna una lista vacía
