## Context

La `Categoria` es el contenedor intermedio entre `Torneo` y `Grupo`. Cada torneo puede tener múltiples categorías (ej. Masculino Mayor, Femenino Sub-18), cada una con su propia fase de grupos y bracket eliminatorio. El `CategoriaManager` gestiona el ciclo de vida de la categoría desde la creación hasta el cierre.

La operación más compleja es `armarPlayOff()`, que actúa como el puente entre la fase clasificatoria y la eliminatoria: toma las posiciones finales de cada grupo (calculadas por `TablaManager`) y las inyecta en los partidos del bracket que tienen un `PartidoConfig` de tipo "origen por posición de grupo".

## Goals / Non-Goals

**Goals:**
- Documentar todas las reglas de unicidad de `Categoria` dentro de un torneo.
- Documentar el comportamiento diferenciado entre creación (con ValidadorManager) y edición (sin ValidadorManager).
- Documentar el contrato de `armarPlayOff()`: prerequisitos, resolución de equipos y efecto en el estado.
- Documentar el ciclo de vida completo de estados con las transiciones que gestiona el manager.

**Non-Goals:**
- No implementar transiciones de estado faltantes (ACTIVA, ZONAS_CREADAS, FINALIZADO).
- No corregir la precedencia de operadores en `editarCategoria()` — se documenta el comportamiento observable.
- No cambiar la lógica de resolución de posiciones en `armarPlayOff()`.

## Decisions

### D1 — Unicidad de Categoría es doble e independiente

La `Categoria` tiene dos restricciones de unicidad dentro del mismo torneo:
1. La combinación `(torneo, genero, nombre)` debe ser única.
2. El `nombreCorto` debe ser único dentro del torneo (sin importar género ni nombre).

Ambas se verifican independientemente y producen mensajes de error distintos.

### D2 — Edición no revalida campos via ValidadorManager

`editarCategoria()` no llama a `ValidadorManager::validarCategoria()`. Solo verifica unicidad y llama directamente a los setters. El `Genero::from($genero)` lanzará `ValueError` (no `AppException`) si el género es inválido. Este comportamiento asimétrico respecto a `crearCategoria()` está documentado como estado actual, no como intención de diseño.

### D3 — armarPlayOff() resuelve equipos por índice de posición (N-1)

El array retornado por `TablaManager::calcularPosiciones()` está ordenado (1º, 2º, 3º...). `armarPlayOff()` usa `$posicionEquipo - 1` como índice en ese array para obtener el `Equipo`. Esto crea un acoplamiento implícito: si el array de posiciones cambia su estructura o su orden, el playoff se rompe sin error inmediato (asigna el equipo equivocado).

### D4 — armarPlayOff() solo resuelve partidos sin equipos y con config de origen "por grupo"

La función itera los partidos de la categoría y resuelve únicamente los que:
- No tienen `equipoLocal` ni `equipoVisitante` (ambos nulos)
- Tienen `PartidoConfig` con `grupoEquipo1` y `grupoEquipo2` no nulos

Los partidos con origen "por ganador de partido previo" no se tocan en esta etapa (se resuelven en `cargarResultado()` de `PartidoManager`).

### D5 — Dos variantes de eliminación

`eliminarCategoria(int $id)` busca la entidad por id y lanza `AppException` si no existe.  
`eliminarCategoriaEntidad(Categoria)` recibe la entidad directamente sin verificación adicional.  
Ambas hacen hard delete con logging idéntico.

## Risks / Trade-offs

- **[Riesgo] Acoplamiento implícito `armarPlayOff()` ↔ `TablaManager`** → Si `calcularPosiciones()` cambia el formato del array retornado (estructura, orden, clave `'equipo'`), `armarPlayOff()` fallará silenciosamente asignando equipos incorrectos. Mitigación: los specs de `calculo-posiciones` y `armar-playoff` deben mantenerse sincronizados.
- **[Riesgo] `editarCategoria()` puede dejar valores inválidos** → Sin llamar a `ValidadorManager`, un nombre de 1 carácter puede persistir. Comportamiento documentado.
- **[Trade-off] Eliminación por entidad no verifica existencia** → `eliminarCategoriaEntidad()` asume que quien la llama validó que la entidad existe. No lanza `AppException` si el objeto está detached o inválido.
