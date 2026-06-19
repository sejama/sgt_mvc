## Problem

El sistema actualmente solo persiste 3 sets por partido, aunque la entidad `Partido` ya tiene campos para sets 1-5 y `TablaManager` ya los suma todos. No hay forma de configurar si los partidos se juegan al mejor de 3 o al mejor de 5 sets. En volleyball es habitual que la fase de grupos se dispute a 3 sets y el playoff eliminatorio a 5 sets dentro del mismo torneo.

## Proposal

Agregar un campo `maxSets` a `Categoria` (valores: 3 o 5) que determine el formato de juego de todos los partidos de esa categoría. `cargarResultado()` usará este valor para:
1. Persistir sets 4 y 5 cuando corresponde (maxSets = 5).
2. Validar que el ganador alcanzó la cantidad de sets necesaria (2 de 3, o 3 de 5).

El campo tendrá valor por defecto 3 para no romper categorías existentes. Al tener la configuración en `Categoria`, un torneo puede tener fases con distintos formatos: zona a 3 sets y playoff a 5 sets.

## Out of Scope

- No se soportan otros formatos (al mejor de 7, etc.).
- No se modifica la UI de creación de partidos ni la lógica de generación automática de partidos.
- No se valida el formato de sets al generar partidos automáticamente.
