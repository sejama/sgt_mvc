## Context

El `PartidoManager` es el orquestador central del flujo competitivo. Sus responsabilidades se dividen en dos ejes:

1. **Generación**: crea masivamente los partidos de una `Categoria` en una transacción —primero los clasificatorios round-robin de cada `Grupo`, luego los eliminatorios del bracket— usando numeración global reservada por rango para evitar colisiones.

2. **Operación**: programa, edita manualmente, carga resultados y propaga ganadores. Cada operación tiene validaciones de integridad (pertenencia al torneo, unicidad de horario/cancha, coherencia de equipo/grupo).

El `PartidoConfig` es el nodo del bracket: conecta un `Partido` eliminatorio con sus dos predecesores (por posición de grupo o por ganador/perdedor de otro partido). La propagación ocurre automáticamente al cargar un resultado.

## Goals / Non-Goals

**Goals:**
- Documentar con precisión todos los flujos del `PartidoManager` como specs testables.
- Establecer el ciclo de vida del `Partido` y sus transiciones válidas.
- Documentar las validaciones de integridad en programación y creación manual.
- Documentar la propagación de ganadores/perdedores en el bracket.

**Non-Goals:**
- No rediseñar el modelo de bracket ni el algoritmo round-robin.
- No agregar nuevos tipos de partido (solo clasificatorio y eliminatorio).
- No implementar lógica de desempate para playoffs (bracket asume un ganador claro por sets).

## Decisions

### D1 — Numeración global de partidos por torneo

Los partidos se numeran secuencialmente dentro del torneo (no por grupo/categoría). La reserva de rango (`reservarRangoNumerosXTorneo`) garantiza que dos transacciones concurrentes no asignen el mismo número. Esto permite identificar cualquier partido públicamente por `ruta + número`.

### D2 — Generación en transacción única por Categoría

La creación de todos los partidos de una `Categoria` (clasificatorios + playoff) ocurre en una sola transacción. Si falla cualquier paso, se revierten todos los partidos. Esto preserva la consistencia del fixture completo.

### D3 — PartidoConfig como nodo de bracket

El `PartidoConfig` desacopla la estructura del bracket del `Partido` en sí. Un partido eliminatorio puede depender de:
- **Posición en grupo**: `grupoEquipo1 + posicionEquipo1` / `grupoEquipo2 + posicionEquipo2`
- **Ganador de partido previo**: `ganadorPartido1` / `ganadorPartido2`
- **Perdedor de partido previo**: `perdedorPartido1` / `perdedorPartido2`

La propagación ocurre en `cargarResultado()`, que resuelve qué equipo avanza al siguiente `Partido` del bracket.

### D4 — Activación de Equipo al generar primer partido

Un `Equipo` en estado BORRADOR pasa automáticamente a ACTIVO cuando se genera o programa su primer partido. Esta transición está distribuida entre `crearPartidosXGrupo()` y `editarPartido()`. El estado BORRADOR indica "inscripto pero sin fixture asignado".

### D5 — Validaciones de programación

Al programar un partido:
1. La `Cancha` debe existir y pertenecer al torneo (vía `Sede`).
2. No puede haber otro partido en la misma cancha y horario.
3. El primer partido del torneo no puede programarse antes de `Torneo::fechaInicioTorneo`.

La condición 3 solo aplica si no hay otros partidos programados en el torneo; partidos subsiguientes no tienen restricción de fecha mínima.

## Risks / Trade-offs

- **[Riesgo] Propagación de ganador incompleta si `PartidoConfig` está mal configurado** → el sistema asigna `null` como equipo si no hay config, resultando en un partido sin equipos. Mitigación: `ValidadorPartidoManager` valida la estructura antes de crear el bracket.
- **[Riesgo] Número de partido duplicado bajo concurrencia extrema** → mitigado por `reservarRangoNumerosXTorneo` con bloqueo a nivel de base de datos.
- **[Trade-off] La activación de `Equipo` está distribuida en dos métodos** → `crearPartidosXGrupo()` y `editarPartido()` activan equipos por separado. Si se agrega un tercer punto de generación, debe incluir la misma lógica.
- **[Trade-off] `cargarResultado()` no valida que el partido sea PROGRAMADO antes de aceptar sets** → permite cargar resultados en partidos BORRADOR. Comportamiento documentado, no corregido en este change.
