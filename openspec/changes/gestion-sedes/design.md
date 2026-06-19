## Context

Las sedes y canchas forman una jerarquía de dos niveles dentro de un torneo: `Torneo → Sede → Cancha → Partido`. Una sede pertenece a un único torneo y tiene un nombre único dentro de ese torneo. Una cancha pertenece a una única sede y tiene un nombre único dentro de esa sede.

El acceso a toda la gestión de sedes y canchas está restringido a `ROLE_ADMIN` (vía `#[IsGranted('ROLE_ADMIN')]` en el controlador). No existe Voter para sedes ni canchas.

## Goals / Non-Goals

**Goals:**
- Documentar todas las reglas de validación de SedeManager y CanchaManager.
- Documentar la unicidad de nombres dentro del torneo (sede) y dentro de la sede (cancha).
- Documentar las inconsistencias observables entre SedeManager y CanchaManager.
- Documentar la consulta cruzada `obtenerSedesYCanchasByTorneo`.

**Non-Goals:**
- No corregir inconsistencias.
- No agregar roles de acceso a sedes/canchas.
- No implementar soft delete.

## Decisions

### D1 — Unicidad: nombre de sede único por torneo, nombre de cancha único por sede

La unicidad se valida en el manager antes de persistir. En ambos casos la excepción es `AppException`. La entidad también tiene `#[UniqueEntity]` en Sede, pero la validación de negocio ocurre en el manager.

### D2 — Inconsistencia de flush entre crearSede y crearCancha

`SedeManager::crearSede()` persiste con `flush: false`; el controller llama `EntityManager::flush()` explícitamente después. `CanchaManager::crearCancha()` persiste con `flush: true` directamente. Comportamiento asimétrico pero funcional.

### D3 — Inconsistencia en consulta por id: obtenerSede vs obtenerCancha

`SedeManager::obtenerSede(int $id)` retorna `?Sede` (null si no existe, sin excepción).  
`CanchaManager::obtenerCancha(int $id)` lanza `AppException('No se encontró la cancha')` si no existe.  
Esta asimetría es comportamiento actual del sistema, no intención de diseño.

### D4 — validarCancha permite descripción vacía

`ValidadorManager::validarCancha()` exige nombre 1-128 chars pero la descripción puede ser vacía (0-255 chars). En cambio, `validarSede()` exige dirección 8-128 chars (no puede estar vacía).

### D5 — obtenerSedesYCanchasByTorneo está en CanchaManager, no en SedeManager

El método `obtenerSedesYCanchasByTorneo(string $ruta)` retorna sedes con sus canchas filtrando por el slug del torneo. Está en `CanchaManager` porque delega a `CanchaRepository::buscarSedesYCanchasByTorneo()`.

## Risks / Trade-offs

- **[Riesgo] eliminarSede sin verificar canchas asociadas**: Si una sede tiene canchas con partidos asignados, eliminarla puede generar cascada o error de FK dependiendo de la configuración de Doctrine. Comportamiento no explicitado en el manager.
- **[Riesgo] obtenerCancha lanza excepción en lugar de retornar null**: Cualquier caller que no maneje la excepción romperá silenciosamente en producción. Asimetría con obtenerSede.
- **[Trade-off] flush asimétrico entre Sede y Cancha**: crearSede requiere flush externo; crearCancha no. Aumenta el riesgo de inconsistencias si se mezclan operaciones en una misma transacción.
