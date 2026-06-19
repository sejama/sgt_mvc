## ADDED Requirements

### Requirement: Estados válidos de la Categoria y transiciones gestionadas por el manager
El sistema SHALL gestionar la `Categoria` a través de seis estados. El `CategoriaManager` gestiona directamente tres transiciones: asignación a BORRADOR (creación), a ZONAS_CERRADAS (armarPlayOff) y a CERRADA (cerrarCategoria).

```
BORRADOR → ACTIVA → ZONAS_CREADAS → ZONAS_CERRADAS → CERRADA → FINALIZADO
   ↑                                      ↑                ↑
  (crearCategoria)               (armarPlayOff)   (cerrarCategoria)
```

#### Scenario: Estado inicial al crear es BORRADOR
- **GIVEN** datos válidos para una nueva categoría
- **WHEN** se invoca `crearCategoria()`
- **THEN** la `Categoria` queda con estado `EstadoCategoria::BORRADOR`

#### Scenario: cerrarCategoria transiciona a CERRADA sin validaciones
- **GIVEN** una `Categoria` en cualquier estado
- **WHEN** se invoca `cerrarCategoria()`
- **THEN** el estado de la `Categoria` cambia a `EstadoCategoria::CERRADA`

### Requirement: Eliminación permanente por id con verificación de existencia
El sistema SHALL buscar la `Categoria` por id y lanzar `AppException` si no existe. Si existe, SHALL eliminarla de forma permanente (hard delete) y registrar el evento en el log.

#### Scenario: Eliminación por id exitosa
- **GIVEN** una `Categoria` con id conocido
- **WHEN** se invoca `eliminarCategoria(id)`
- **THEN** la `Categoria` es eliminada permanentemente y se registra un log con su id y nombre

#### Scenario: Id inexistente lanza AppException
- **GIVEN** un id que no corresponde a ninguna `Categoria`
- **WHEN** se invoca `eliminarCategoria(id)`
- **THEN** se lanza `AppException` indicando que no se encontró la categoría

### Requirement: Eliminación permanente por entidad sin verificación adicional
El sistema SHALL eliminar la `Categoria` recibida como entidad directamente, sin verificar su existencia, y registrar el evento en el log.

#### Scenario: Eliminación por entidad no requiere búsqueda previa
- **GIVEN** una instancia de `Categoria` válida
- **WHEN** se invoca `eliminarCategoriaEntidad(Categoria)`
- **THEN** la `Categoria` es eliminada permanentemente y se registra un log con su id y nombre

### Requirement: Consulta de Categorias por distintos criterios
El sistema SHALL proveer tres métodos de consulta: listado global, listado por torneo y búsqueda por id. La búsqueda por id SHALL retornar `null` si no existe (no lanza excepción).

#### Scenario: obtenerCategorias retorna todas sin filtro
- **GIVEN** existen categorías de distintos torneos y estados
- **WHEN** se invoca `obtenerCategorias()`
- **THEN** se retornan todas las categorías sin filtro

#### Scenario: obtenerCategoriasPorTorneo retorna solo las del torneo indicado
- **GIVEN** un `Torneo` con 3 categorías y otro torneo con 2 categorías
- **WHEN** se invoca `obtenerCategoriasPorTorneo(torneo)`
- **THEN** se retornan solo las 3 categorías del torneo indicado

#### Scenario: obtenerCategoria retorna null si el id no existe
- **GIVEN** un id que no corresponde a ninguna `Categoria`
- **WHEN** se invoca `obtenerCategoria(id)`
- **THEN** retorna `null` sin lanzar excepción
