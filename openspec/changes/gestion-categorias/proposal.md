## Why

El `CategoriaManager` gestiona la estructura competitiva dentro de un `Torneo`. Su operación más crítica —`armarPlayOff()`— orquesta la transición de la fase de grupos al bracket eliminatorio resolviendo los equipos participantes desde la tabla de posiciones. Sin specs, esta lógica de resolución (posición N-1 del array de posiciones) puede romperse silenciosamente si cambia el `TablaManager` o la estructura de grupos.

## What Changes

- Documentar la capacidad `creacion-categoria`: unicidad por torneo, validaciones de campos, estado inicial.
- Documentar la capacidad `edicion-categoria`: unicidad excluyendo la propia categoría, comportamiento diferenciado respecto a `TorneoManager` (sin reset de estado, sin re-validación de campos).
- Documentar la capacidad `disputa-categoria`: edición aislada del campo disputa con sanitización HTML.
- Documentar la capacidad `armar-playoff`: prerequisito de grupos finalizados, resolución de equipos desde tabla de posiciones, transición de estado a ZONAS_CERRADAS.
- Documentar la capacidad `ciclo-vida-categoria`: estados válidos, transiciones gestionadas por el manager y consultas disponibles.

## Capabilities

### New Capabilities

- `creacion-categoria`: Creación de una `Categoria` dentro de un `Torneo` con validación de unicidad y campos.
- `edicion-categoria`: Edición de genero, nombre y nombreCorto con revalidación de unicidad.
- `disputa-categoria`: Edición aislada del texto de disputa con sanitización HTML.
- `armar-playoff`: Resolución del bracket eliminatorio a partir de la tabla de posiciones de los grupos.
- `ciclo-vida-categoria`: Estados del ciclo de vida, transiciones gestionadas y eliminación.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`CategoriaManager`**: clase principal documentada.
- **`TablaManager`**: consumido por `armarPlayOff()` para resolver posiciones; cambios en el formato de retorno de `calcularPosiciones()` romperían el armado del playoff.
- **`ValidadorManager`**: usado solo en creación, no en edición.
- **`PartidoRepository`**: usado en `armarPlayOff()` para persistir los equipos resueltos en los partidos eliminatorios.
- **Tests**: se crearán specs en formato Given/When/Then; los tests existentes deben alinearse.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
