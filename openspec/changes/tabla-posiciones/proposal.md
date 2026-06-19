## Why

El comportamiento del `TablaManager` —cálculo de posiciones, criterios de desempate y transición automática de estado de grupo— no está documentado formalmente. Sin specs, cualquier cambio en la lógica de puntuación o desempate puede romper invariantes silenciosamente y solo detectarse en producción.

## What Changes

- Documentar la capacidad `calculo-posiciones`: reglas de puntuación, desempate en cascada (puntos → sets → puntos de cancha), soporte de hasta 5 sets.
- Documentar la capacidad `estado-grupo`: transición automática a FINALIZADO cuando todos los partidos están FINALIZADO o CANCELADO.
- Documentar la capacidad `cache-posiciones`: TTL de 1 hora con invalidación explícita al cargar resultado.

## Capabilities

### New Capabilities

- `calculo-posiciones`: Cálculo de puntos por partido, acumulación de estadísticas de sets/puntos, ordenamiento con criterios de desempate en cascada.
- `estado-grupo`: Determinación automática del estado FINALIZADO del grupo al completarse todos sus partidos.
- `cache-posiciones`: Estrategia de cache de posiciones por grupo con TTL y mecanismo de invalidación.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`TablaManager`**: clase principal afectada; sin cambios de código, solo documentación de comportamiento.
- **`GrupoRepository`**: implicado en la persistencia del cambio de estado a FINALIZADO.
- **Tests**: se crearán specs en formato Given/When/Then; los tests unitarios existentes de `TablaManager` deben alinearse con estos escenarios.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
