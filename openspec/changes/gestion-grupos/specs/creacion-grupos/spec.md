## ADDED Requirements

### Requirement: Creación en lote de Grupos con distribución secuencial de equipos
El sistema SHALL crear todos los `Grupo` del lote en una sola invocación de `crearGrupos()`. Los equipos de la `Categoria` SHALL asignarse secuencialmente: los primeros N al primer grupo, los siguientes M al segundo, y así sucesivamente, según el tamaño indicado en cada `CreateGrupoDTO`. Cada `Grupo` creado SHALL tener estado `EstadoGrupo::BORRADOR`.

#### Scenario: Grupos creados con equipos distribuidos secuencialmente
- **GIVEN** una `Categoria` con 6 equipos (E1..E6) y dos `CreateGrupoDTO`: grupoA(cantidad=3) y grupoB(cantidad=3)
- **WHEN** se invoca `crearGrupos([grupoA, grupoB])`
- **THEN** el grupoA queda con E1, E2, E3 y el grupoB queda con E4, E5, E6

#### Scenario: Grupo creado con estado BORRADOR
- **GIVEN** un `CreateGrupoDTO` válido
- **WHEN** se invoca `crearGrupos()`
- **THEN** cada `Grupo` creado tiene estado `EstadoGrupo::BORRADOR`

### Requirement: Validación de balance entre equipos en zonas y equipos en la Categoria
El sistema SHALL verificar que la suma de `cantidad` de todos los `CreateGrupoDTO` sea exactamente igual al total de equipos de la `Categoria`. Si no coincide, SHALL lanzar `AppException` sin crear ningún grupo.

#### Scenario: Suma de equipos en zonas menor al total de la Categoria lanza excepción
- **GIVEN** una `Categoria` con 6 equipos y DTOs que suman 5 equipos en total
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando que la cantidad de equipos en las zonas no coincide con la categoría

#### Scenario: Suma de equipos en zonas mayor al total de la Categoria lanza excepción
- **GIVEN** una `Categoria` con 6 equipos y DTOs que suman 7 equipos en total
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando que la cantidad de equipos en las zonas no coincide con la categoría

#### Scenario: Suma exacta permite la creación
- **GIVEN** una `Categoria` con 6 equipos y DTOs que suman exactamente 6 equipos
- **WHEN** se invoca `crearGrupos()`
- **THEN** los grupos se crean sin error de balance

### Requirement: Validación de nombre de Grupo
El sistema SHALL validar que el nombre de cada `Grupo` tenga entre 1 y 16 caracteres mediante `ValidadorManager::validarGrupo()`.

#### Scenario: Nombre de grupo vacío lanza excepción
- **GIVEN** un `CreateGrupoDTO` con nombre de 0 caracteres
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

#### Scenario: Nombre de grupo mayor a 16 caracteres lanza excepción
- **GIVEN** un `CreateGrupoDTO` con nombre de 17 caracteres
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando el rango válido para el nombre

### Requirement: Categoria pasa a estado ZONAS_CREADAS al crear grupos
El sistema SHALL cambiar el estado de la `Categoria` a `EstadoCategoria::ZONAS_CREADAS` durante la creación de los grupos.

#### Scenario: Categoria cambia a ZONAS_CREADAS tras crear grupos
- **GIVEN** una `Categoria` en estado BORRADOR con equipos suficientes
- **WHEN** `crearGrupos()` procesa al menos un grupo exitosamente
- **THEN** el estado de la `Categoria` cambia a `EstadoCategoria::ZONAS_CREADAS`

### Requirement: Categoria inexistente lanza excepción
El sistema SHALL lanzar `AppException` si la `Categoria` referenciada en el primer `CreateGrupoDTO` no existe.

#### Scenario: Categoria no encontrada lanza excepción
- **GIVEN** un `CreateGrupoDTO` con un `categoriaId` que no corresponde a ninguna `Categoria`
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando que la categoría no fue encontrada

### Requirement: Estado de Categoria puede quedar inconsistente si falla un grupo intermedio (comportamiento actual)
El estado `ZONAS_CREADAS` se asigna a la `Categoria` dentro del loop de creación de grupos, no al finalizar todos. Si el primer grupo se crea exitosamente (y la categoría pasa a ZONAS_CREADAS) pero un grupo posterior falla, la categoría queda en ZONAS_CREADAS con solo una parte de los grupos creados.

#### Scenario: Categoria queda en ZONAS_CREADAS si el primer grupo se creó y el segundo falla
- **GIVEN** una `Categoria` en estado BORRADOR con 6 equipos y dos DTOs: grupoA(válido) y grupoB(nombre > 16 chars)
- **WHEN** se invoca `crearGrupos([grupoA, grupoB])`
- **THEN** se lanza `AppException` por el grupoB, pero la `Categoria` ya quedó en estado ZONAS_CREADAS y el grupoA fue persistido
