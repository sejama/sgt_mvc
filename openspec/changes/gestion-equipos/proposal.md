## Why

El `EquipoManager` gestiona la inscripción y participación de equipos en un torneo. Su operación más crítica —`bajarEquipo()`— tiene un efecto colateral de gran alcance: cancela todos los partidos del equipo (incluyendo finalizados) sin distinción de estado. Sin specs formales, este comportamiento puede sorprender a quien modifique la lógica de cancelación o agregue filtros por estado.

## What Changes

- Documentar la capacidad `creacion-equipo`: unicidad por categoría, numeración global por torneo, validaciones de campos, dependencia de Torneo asignado.
- Documentar la capacidad `edicion-equipo`: revalidación de unicidad y campos, comportamiento especial de logoPath.
- Documentar la capacidad `baja-equipo`: transición a NO_PARTICIPA y cancelación masiva de partidos.
- Documentar la capacidad `ciclo-vida-equipo`: estados válidos, transiciones gestionadas por el manager y consultas disponibles.

## Capabilities

### New Capabilities

- `creacion-equipo`: Creación de un `Equipo` con numeración global por torneo, validación de unicidad y campos.
- `edicion-equipo`: Edición de datos del equipo con revalidación completa y comportamiento de logo inmutable si no se envía.
- `baja-equipo`: Dar de baja un equipo: transición de estado y cancelación masiva de sus partidos.
- `ciclo-vida-equipo`: Estados válidos del `Equipo`, transiciones gestionadas y eliminación.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`EquipoManager`**: clase principal documentada.
- **`PartidoRepository`**: consumido por `bajarEquipo()` para cancelar los partidos del equipo.
- **`ValidadorManager`**: usado tanto en creación como en edición (a diferencia de `CategoriaManager`).
- **Tests**: se crearán specs en formato Given/When/Then; los tests existentes deben alinearse.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
