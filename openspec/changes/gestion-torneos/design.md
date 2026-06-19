## Context

El `TorneoManager` gestiona el ciclo de vida completo de un `Torneo`: creación, edición de datos, edición de reglamento y eliminación. La validación de campos es delegada al `ValidadorManager`, que actúa como guardia de negocio compartido entre entidades.

El `Torneo` tiene cuatro fechas con horario que deben cumplir una cadena de coherencia temporal:

```
fechaInicioInscripcion < fechaFinInscripcion < fechaInicioTorneo < fechaFinTorneo
```

El ciclo de vida de estados tiene cuatro valores pero solo dos transiciones están gestionadas por el manager: la asignación a BORRADOR al crear y el reset a BORRADOR al editar. Las demás transiciones (a ACTIVO, EN_CURSO, FINALIZADO) ocurren fuera del `TorneoManager`.

## Goals / Non-Goals

**Goals:**
- Documentar todas las validaciones de creación y edición con sus mensajes de error exactos.
- Documentar el efecto colateral de reset de estado al editar.
- Documentar el ciclo de vida completo de estados aunque no todas las transiciones estén en el manager.
- Documentar las reglas de unicidad de nombre y ruta.

**Non-Goals:**
- No implementar métodos de transición de estado (activar, iniciar, finalizar) — eso es un change separado.
- No cambiar la lógica de validación del `ValidadorManager`.
- No agregar soft-delete; la eliminación es hard delete.

## Decisions

### D1 — Validación delegada a ValidadorManager

El `TorneoManager` delega toda la validación de campos al `ValidadorManager::validarTorneo()`. La verificación de unicidad de nombre y ruta se hace en el `TorneoManager` antes de llamar al validador (para creación) o después de verificar si el valor cambió (para edición). El orden importa: unicidad antes que persistencia.

### D2 — Ruta como identificador público

La `ruta` del torneo funciona como slug público (URL-safe): solo minúsculas, dígitos y guiones, entre 3 y 32 caracteres, sin espacios. Es el identificador usado en todas las URLs del sistema. Un cambio de ruta en edición rompe URLs existentes — comportamiento documentado, no prevenido por el sistema.

### D3 — Reset de estado a BORRADOR al editar

`editarTorneo()` siempre llama `setEstado(EstadoTorneo::BORRADOR->value)` al final, independientemente del estado previo. Esto es intencional: cualquier cambio en datos del torneo requiere que el administrador lo reactive explícitamente. Es el mecanismo de control de calidad del flujo editorial.

### D4 — Reglamento como campo separado con sanitización HTML

El reglamento es texto enriquecido (HTML) editado independientemente del resto de los datos. Se pasa por `RichTextSanitizer::sanitize()` antes de persistir para prevenir XSS. No tiene validación de longitud mínima.

### D5 — Timezone fija Argentina/Buenos_Aires

Todas las fechas se almacenan como `DateTimeImmutable` con timezone `America/Argentina/Buenos_Aires`. Las fechas de entrada llegan como strings en formato `Y-m-d H:i` y se convierten en el manager.

## Risks / Trade-offs

- **[Riesgo] Cambio de ruta rompe URLs en curso** → No hay redirección automática. Si se edita la ruta de un torneo activo, todos los links existentes (partidos, categorías, fixtures) dejan de funcionar. Mitigación: documentar que la ruta no debería editarse una vez que el torneo está en curso.
- **[Riesgo] Reset a BORRADOR al editar puede sorprender** → Si el administrador edita un dato menor (descripción) en un torneo ACTIVO, vuelve a BORRADOR sin advertencia. Comportamiento documentado; la UI debería advertir.
- **[Trade-off] Eliminación hard delete sin verificación de dependencias** → Eliminar un torneo con categorías, grupos y partidos existentes puede dejar huérfanos o disparar cascada. El comportamiento de cascada depende de la configuración Doctrine de cada relación.
