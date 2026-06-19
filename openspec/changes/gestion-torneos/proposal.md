## Why

El `TorneoManager` es el punto de entrada de toda la jerarquía del sistema. Sus reglas de validación, unicidad y ciclo de vida no están documentadas formalmente. El efecto colateral más riesgoso —que editar un torneo resetea su estado a BORRADOR independientemente del estado actual— puede causar regresiones silenciosas si se modifica sin conocer este comportamiento.

## What Changes

- Documentar la capacidad `creacion-torneo`: validaciones de campos, unicidad de nombre/ruta, coherencia temporal entre los cuatro rangos de fechas, estado inicial BORRADOR.
- Documentar la capacidad `edicion-torneo`: mismas validaciones excluyendo el propio torneo, efecto colateral de reset a BORRADOR.
- Documentar la capacidad `reglamento-torneo`: edición separada del reglamento con sanitización HTML.
- Documentar la capacidad `ciclo-vida-torneo`: estados y transiciones válidas del `Torneo`.
- Documentar la capacidad `consulta-torneos`: listado global, por creador y búsqueda por ruta.

## Capabilities

### New Capabilities

- `creacion-torneo`: Creación de un `Torneo` con validación completa de campos, unicidad y coherencia temporal.
- `edicion-torneo`: Edición de campos del `Torneo` con revalidación y reset de estado a BORRADOR.
- `reglamento-torneo`: Edición aislada del reglamento HTML con sanitización.
- `ciclo-vida-torneo`: Estados válidos del `Torneo` y transiciones definidas.
- `consulta-torneos`: Recuperación de torneos por distintos criterios.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`TorneoManager`**: clase principal documentada.
- **`ValidadorManager`**: lógica de validación compartida con otras entidades; documentada en la parte que aplica a `Torneo`.
- **`TorneoRepository`**: implicado en las verificaciones de unicidad y las consultas.
- **`RichTextSanitizer`**: implicado en la edición de reglamento.
- **Tests**: se crearán specs en formato Given/When/Then; los tests existentes deben alinearse con estos escenarios.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
