## Context

La entidad `Partido` ya tiene `localSet1`…`localSet5` y `visitanteSet1`…`visitanteSet5` (todos `?int`, nullable). `TablaManager::calcularPosiciones()` ya suma los 5 sets para puntos a favor/en contra. El único gap es que `PartidoManager::cargarResultado()` solo llama `setLocalSet1/2/3` y `setVisitanteSet1/2/3`, dejando sets 4-5 siempre en null.

La configuración de formato vive en `Categoria` porque una categoría ya encapsula una fase de competición completa (zona o playoff). Esto permite que un torneo tenga:
- Categoría "Zona A" → maxSets = 3
- Categoría "Playoff" → maxSets = 5

`cargarResultado()` accede al formato vía `partido → grupo → categoria → maxSets`. Para partidos de playoff sin grupo, la cadena es `partido → categoria → maxSets`.

## Goals / Non-Goals

**Goals:**
- Agregar `maxSets` (3 o 5) a `Categoria` con default 3.
- Actualizar `crearCategoria()` y `editarCategoria()` para aceptar `maxSets`.
- Actualizar `cargarResultado()` para persistir sets 4-5 y validar el ganador según el formato.
- Validar puntajes de cada set según las reglas FIVB: sets regulares = 25 + dif 2, set decisivo = 15 + dif 2.
- No romper categorías existentes (default 3).

**Non-Goals:**
- Formato por grupo o por partido individual.
- Otros valores de maxSets (1, 7, etc.).
- Cambiar la lógica de `TablaManager` (ya está correcta para 5 sets).

## Decisions

### D1 — Campo `maxSets: int` en Categoria (no enum)

Se usa `int` en lugar de un enum PHP. Los valores válidos (3 y 5) son simples y el mapeo directo a la BD es más limpio. La validación de que solo se aceptan 3 o 5 ocurre en `ValidadorManager::validarCategoria()`.

### D2 — Default 3 en migración

Las categorías existentes quedan con `maxSets = 3`, comportamiento idéntico al actual. No se requiere intervención manual.

### D3 — Validación de ganador en cargarResultado

Con formato 3 sets: el ganador necesita 2 sets. Con formato 5 sets: el ganador necesita 3 sets. La fórmula es `ceil(maxSets / 2)`. `cargarResultado()` rechazará resultados donde ningún equipo alcance ese umbral.

### D4 — Sets 4-5 se ignoran si maxSets = 3

Si `maxSets = 3`, los valores de sets 4 y 5 recibidos se ignoran silenciosamente. No se lanza excepción para simplificar el formulario (que puede enviar campos vacíos para todos los sets).

### D5 — maxSets solo editable en estado BORRADOR o ACTIVA de la Categoria

Cambiar el formato cuando ya hay partidos con resultados cargados invalidaría los resultados existentes. El cambio de `maxSets` se restringe a los estados BORRADOR y ACTIVA.

### D6 — Dos reglas de cierre de set, configurables e independientes por tipo

Cada `Categoria` almacena dos booleanos independientes:

| Campo | `true` (default) | `false` |
|-------|-----------------|---------|
| `setRegularDif2` | Sets regulares: 25 puntos + dif ≥ 2. 25-24 inválido. | Primero en llegar a 25 gana. 25-24 válido. |
| `setDecisivoDif2` | Set decisivo: 15 puntos + dif ≥ 2. 15-14 inválido. | Primero en llegar a 15 gana. 15-14 válido. |

Las 4 combinaciones son válidas. Por defecto ambos son `true` (regla FIVB estándar).

La función de validación recibe `(localScore, visitanteScore, esDif2, tope)` y verifica:
```
winnerScore = max(local, visitante)
diff        = winnerScore - min(local, visitante)
winnerScore >= tope && (esDif2 ? diff >= 2 : diff >= 1)
```

Donde `tope = 15` para el set decisivo, `25` para el resto.

### D7 — Acceso al formato desde cargarResultado

El método recupera la categoría vía: `partido → grupo → categoria` (partidos de zona) o `partido → categoria` (partidos de playoff). Ambas rutas exponen `getMaxSets()`. Si la categoría no puede resolverse (caso raro), se usa 3 como fallback seguro.

## Risks / Trade-offs

- **[Riesgo] Partidos ya cargados con 3 sets en una categoría cambiada a 5**: Si se cambia `maxSets` de 3 a 5 mientras hay partidos finalizados, la tabla sumará correctamente los sets existentes pero la validación del ganador no aplica retroactivamente. Fuera de scope para esta iteración.
- **[Trade-off] Acceso encadenado partido → grupo → categoria**: Agrega una consulta de relación lazy. Aceptable dado el volumen bajo de partidos por torneo.
