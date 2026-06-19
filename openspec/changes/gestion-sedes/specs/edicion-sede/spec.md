## ADDED Requirements

### Requirement: Edición permite conservar el mismo nombre sin conflicto
El sistema SHALL permitir editar una sede conservando su nombre actual sin lanzar error de duplicado.

#### Scenario: Editar sede sin cambiar el nombre procede sin error de unicidad
- **GIVEN** una `Sede` con nombre "Polideportivo Norte" en un torneo
- **WHEN** se invoca `editarSede(torneo, sede, "Polideportivo Norte", "Nueva dirección válida 456")`
- **THEN** la edición persiste sin error

### Requirement: Nombre nuevo ya usado por otra sede del torneo es rechazado
El sistema SHALL lanzar excepción si el nuevo nombre pertenece a otra sede del mismo torneo.

#### Scenario: Cambiar nombre a uno existente en el mismo torneo lanza excepción
- **GIVEN** un torneo con sedes "Sede A" y "Sede B", editando "Sede A"
- **WHEN** se invoca `editarSede(torneo, sedeA, "Sede B", "Dirección cualquiera")`
- **THEN** se lanza `AppException` con mensaje "Ya existe una sede con ese nombre"

### Requirement: Edición valida longitud de nombre y dirección
Las mismas reglas de validación que en la creación aplican en la edición (nombre 3-128, dirección 8-128).

#### Scenario: Editar sede con dirección inválida lanza excepción
- **GIVEN** una sede existente
- **WHEN** se invoca `editarSede()` con dirección de 5 caracteres
- **THEN** se lanza `AppException` indicando el rango válido de la dirección

### Requirement: editarSede flushea automáticamente
A diferencia de `crearSede`, la edición persiste con flush automático.

#### Scenario: editarSede persiste inmediatamente sin flush externo
- **GIVEN** una sede existente con datos válidos
- **WHEN** se invoca `editarSede(torneo, sede, "Nuevo nombre válido", "Dirección nueva válida")`
- **THEN** los cambios se persisten con flush automático

### Requirement: Eliminación de sede es permanente (hard delete)
El sistema SHALL eliminar la sede de forma permanente sin verificar canchas o partidos asociados.

#### Scenario: eliminarSede borra la sede sin canchas asociadas
- **GIVEN** una `Sede` existente sin canchas
- **WHEN** se invoca `eliminarSede(sede)`
- **THEN** la sede ya no existe en el sistema

### Requirement: eliminarSede falla a nivel de BD si la Sede tiene canchas asociadas (comportamiento actual)
La entidad `Sede` no tiene `cascade: ['remove']` en su relación con `Cancha`. `SedeManager::eliminarSede()` no verifica dependencias. Si la sede tiene canchas, la eliminación falla con error de FK a nivel de base de datos.

#### Scenario: Eliminar Sede con canchas produce error de FK
- **GIVEN** una `Sede` que tiene al menos una `Cancha` asociada
- **WHEN** se invoca `eliminarSede(sede)`
- **THEN** la operación falla con un error de integridad referencial antes de completarse
