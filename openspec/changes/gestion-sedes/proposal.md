## Problem

El sistema SGT no tiene especificaciones documentadas para la gestión de sedes y canchas. Las sedes representan los lugares físicos donde se disputan los partidos de un torneo; cada sede contiene una o más canchas, y cada cancha puede estar asignada a múltiples partidos. La documentación del comportamiento existente es necesaria para guiar los tests y detectar inconsistencias en el sistema actual.

## Proposal

Documentar el comportamiento observable de `SedeManager` y `CanchaManager` mediante specs retrospectivos en formato Given/When/Then. El alcance incluye creación, edición, eliminación y consulta tanto de sedes como de canchas, junto con sus reglas de validación y la relación jerárquica Sede → Cancha → Partido.

## Out of Scope

- No se modifica el comportamiento actual del sistema.
- No se agrega soporte para horarios de disponibilidad de canchas.
- No se agrega control de acceso granular (actualmente todo requiere ROLE_ADMIN).
