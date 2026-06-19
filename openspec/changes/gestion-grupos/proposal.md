## Why

El `GrupoManager` gestiona la fase de grupos dentro de una `Categoria`. Sus reglas de negocio más complejas —la distribución secuencial de equipos, las validaciones de clasificación en cascada y las precondiciones del intercambio de equipos— no están documentadas formalmente. Sin specs, un cambio en la lógica de distribución o en las restricciones de intercambio puede invalidar fixtures ya configurados sin detección temprana.

## What Changes

- Documentar la capacidad `creacion-grupos`: creación en lote via `CreateGrupoDTO[]`, distribución secuencial de equipos, validación de totales y transición de estado de la `Categoria`.
- Documentar la capacidad `clasificacion-grupos`: reglas de clasificaOro/Plata/Bronce por grupo y límite acumulado entre todos los grupos.
- Documentar la capacidad `intercambio-equipos`: precondiciones de estado, ausencia de partidos, pertenencia y unicidad de grupo, operación de swap atómico.
- Documentar la capacidad `consulta-grupos`: comportamiento de cada método de consulta y su ordenamiento.

## Capabilities

### New Capabilities

- `creacion-grupos`: Creación en lote de `Grupo` dentro de una `Categoria` con distribución secuencial de equipos.
- `clasificacion-grupos`: Validación de clasificados por instancia de premio (oro, plata, bronce) con límite acumulado global.
- `intercambio-equipos`: Intercambio atómico de grupos entre dos equipos de la misma categoría.
- `consulta-grupos`: Recuperación de grupos y equipos por distintos criterios con ordenamiento definido.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`GrupoManager`**: clase principal documentada.
- **`CategoriaManager`**: consumido para obtener la `Categoria` y persistir su cambio de estado a ZONAS_CREADAS.
- **`ValidadorManager`**: usado para validar el nombre del grupo (1-16 chars).
- **`EquipoRepository`**: usado en el intercambio para persistir los cambios de grupo de los equipos.
- **Tests**: se crearán specs en formato Given/When/Then; los tests existentes deben alinearse.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
