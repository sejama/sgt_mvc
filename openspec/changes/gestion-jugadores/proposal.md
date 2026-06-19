## Why

El `JugadorManager` gestiona la nómina de jugadores de cada equipo. Tiene tres comportamientos del estado actual que difieren de lo esperado y que deben quedar documentados explícitamente antes de cualquier refactor: la unicidad global del documento en edición, la imposibilidad de editar el apellido, y el manejo incorrecto de `fechaNacimiento` nula en edición. Documentarlos como specs testables permite detectar cualquier cambio involuntario en el futuro.

## What Changes

- Documentar la capacidad `creacion-jugador`: unicidad por equipo+documento, validaciones de campos, campos opcionales.
- Documentar la capacidad `edicion-jugador`: comportamientos observables actuales incluyendo los tres divergentes del diseño esperado.
- Documentar la capacidad `ciclo-vida-jugador`: eliminación y consultas disponibles.

## Capabilities

### New Capabilities

- `creacion-jugador`: Creación de un `Jugador` asociado a un `Equipo` con validación de unicidad de documento y campos.
- `edicion-jugador`: Edición de datos del jugador con comportamientos observables del sistema actual documentados.
- `ciclo-vida-jugador`: Eliminación permanente y consultas de jugadores.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`JugadorManager`**: clase principal documentada.
- **`ValidadorManager`**: usado tanto en creación como en edición.
- **Tests**: los specs documentan el comportamiento actual, incluyendo divergencias. Los tests deben reflejar lo que el código hace hoy, no lo que debería hacer.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
