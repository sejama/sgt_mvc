## Context

El sistema de seguridad tiene dos capas: autenticación (quién puede entrar) y autorización (qué puede hacer). La autenticación usa el mecanismo estándar de Symfony con `UserInterface`. La autorización se implementa con un único `Voter`: `PartidoVoter`, que controla quién puede cargar resultados.

El `Usuario` implementa `UserInterface` y `PasswordAuthenticatedUserInterface`. Sus roles (`ROLE_ADMIN`, `ROLE_PLANILLERO`) determinan el nivel de acceso. Symfony agrega `ROLE_USER` automáticamente a todos los usuarios autenticados.

## Goals / Non-Goals

**Goals:**
- Documentar todas las reglas de validación del registro de usuarios.
- Documentar el comportamiento observable de `editarUsuario()` incluyendo su divergencia respecto a cambios de username/email.
- Documentar las reglas de autorización del `PartidoVoter` con sus tres casos exactos.
- Documentar cambio de contraseña y eliminación.

**Non-Goals:**
- No agregar nuevos Voters ni nuevos atributos de seguridad.
- No corregir la divergencia en `editarUsuario()`.
- No implementar recuperación de contraseña.

## Decisions

### D1 — Roles: dos niveles funcionales

El sistema tiene dos roles con permisos distintos:
- `ROLE_ADMIN`: acceso total. No está limitado por ningún Voter.
- `ROLE_PLANILLERO`: acceso parcial. Solo puede cargar resultados en partidos no finalizados.

No hay roles intermedios. Cualquier usuario sin uno de estos dos roles (o no autenticado) es rechazado por el `PartidoVoter`.

### D2 — PartidoVoter: único punto de control de autorización explícita

El `PartidoVoter` solo actúa sobre el atributo `CARGAR_RESULTADO` sobre instancias de `Partido`. El orden de evaluación es: primero autenticación → luego ROLE_ADMIN → luego ROLE_PLANILLERO + estado → denegado. La verificación de estado del partido (`!= FINALIZADO`) solo se aplica al `ROLE_PLANILLERO`, nunca al `ROLE_ADMIN`.

### D3 — editarUsuario: comparación por identidad para unicidad (divergencia documentada)

`editarUsuario()` usa `$usuario !== $this->usuarioRepository->findOneBy(['username' => $username])` para verificar unicidad. Esta comparación falla cuando `findOneBy` retorna `null` (username disponible, nadie lo tiene): `null !== $usuario` es `true`, lanzando `AppException`. Esto hace imposible cambiar username o email a valores disponibles. Comportamiento actual del sistema, no intención de diseño.

### D4 — Contraseña solo editable via cambiarPassword()

`editarUsuario()` tiene el cambio de contraseña comentado. El único mecanismo para cambiar la contraseña es `cambiarPassword()`, que hashea con `UserPasswordHasherInterface` sin validaciones adicionales (sin verificar complejidad, sin verificar contraseña actual).

### D5 — Validación doble en registrarUsuario (bug menor)

`registrarUsuario()` llama `validadorManager->validarUsuario()` dos veces: una antes de los checks de unicidad y otra después. El segundo llamado es redundante pero inofensivo.

## Risks / Trade-offs

- **[Riesgo] ROLE_PLANILLERO puede cargar resultados en partidos BORRADOR** → El Voter solo bloquea FINALIZADO, no BORRADOR. Un planillero puede cargar resultado en un partido sin programar. Comportamiento documentado.
- **[Riesgo] cambiarPassword() sin validación** → Un admin puede establecer cualquier contraseña (incluyendo triviales) sin restricciones. Sin verificación de contraseña actual.
- **[Trade-off] editarUsuario no puede cambiar username a valor disponible** → Efectivamente, la edición de username/email está rota para cambios a valores nuevos. Un admin que necesite cambiar username debe eliminar y recrear el usuario.
