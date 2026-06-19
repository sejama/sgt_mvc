## ADDED Requirements

### Requirement: Precondición de estado ZONAS_CREADAS para el intercambio
El sistema SHALL permitir el intercambio de equipos entre grupos únicamente si la `Categoria` está en estado `EstadoCategoria::ZONAS_CREADAS`. Cualquier otro estado SHALL ser rechazado.

#### Scenario: Categoria en estado distinto a ZONAS_CREADAS lanza excepción
- **GIVEN** una `Categoria` en estado `EstadoCategoria::BORRADOR`
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()`
- **THEN** se lanza `AppException` indicando que solo se puede intercambiar cuando la categoría está en estado Zonas_creadas

### Requirement: Precondición de ausencia de partidos generados
El sistema SHALL rechazar el intercambio si ya existen `Partido` asociados a la `Categoria`, independientemente del estado de esos partidos.

#### Scenario: Partidos ya generados impiden el intercambio
- **GIVEN** una `Categoria` en estado ZONAS_CREADAS con al menos un `Partido` generado
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()`
- **THEN** se lanza `AppException` indicando que no se pueden intercambiar equipos porque ya existen partidos generados

### Requirement: Validación de los IDs de los equipos a intercambiar
El sistema SHALL requerir que ambos `equipoId` sean mayores a cero y distintos entre sí.

#### Scenario: equipoOrigenId igual a cero lanza excepción
- **GIVEN** `equipoOrigenId = 0`
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()`
- **THEN** se lanza `AppException` indicando que se deben seleccionar ambos equipos

#### Scenario: Ambos IDs iguales lanza excepción
- **GIVEN** `equipoOrigenId === equipoDestinoId`
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()`
- **THEN** se lanza `AppException` indicando que se deben seleccionar dos equipos distintos

### Requirement: Los equipos deben pertenecer a la Categoria
El sistema SHALL verificar que tanto el equipo origen como el equipo destino pertenezcan a la `Categoria` en la que se realiza el intercambio. Un equipo de otra categoría SHALL ser rechazado.

#### Scenario: Equipo que no pertenece a la Categoria lanza excepción
- **GIVEN** un `equipoOrigenId` que no pertenece a la `Categoria`
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()`
- **THEN** se lanza `AppException` indicando que alguno de los equipos no pertenece a la categoría

### Requirement: Los equipos deben tener grupo asignado
El sistema SHALL rechazar el intercambio si alguno de los equipos no tiene un `Grupo` asignado.

#### Scenario: Equipo sin grupo asignado lanza excepción
- **GIVEN** un equipo de la `Categoria` con `grupo = null`
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()` incluyendo ese equipo
- **THEN** se lanza `AppException` indicando que solo se pueden intercambiar equipos que ya tengan grupo asignado

### Requirement: Los equipos no pueden estar en el mismo Grupo
El sistema SHALL rechazar el intercambio si ambos equipos pertenecen al mismo `Grupo`.

#### Scenario: Equipos del mismo grupo lanza excepción
- **GIVEN** dos equipos que pertenecen al mismo `Grupo`
- **WHEN** se invoca `intercambiarEquiposEntreGrupos()`
- **THEN** se lanza `AppException` indicando que los equipos ya pertenecen al mismo grupo

### Requirement: Intercambio atómico de grupos entre los dos equipos
El sistema SHALL intercambiar los grupos de los dos equipos: el equipo origen recibe el grupo del equipo destino y viceversa. Ambas asignaciones SHALL persistirse en la misma operación.

#### Scenario: Intercambio exitoso asigna grupos cruzados
- **GIVEN** equipoA en GrupoX y equipoB en GrupoY (grupos distintos, misma categoría, sin partidos)
- **WHEN** se invoca `intercambiarEquiposEntreGrupos(categoria, equipoA.id, equipoB.id)`
- **THEN** equipoA queda asignado a GrupoY y equipoB queda asignado a GrupoX
