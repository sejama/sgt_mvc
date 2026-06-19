## Why

El sistema de seguridad del SGT no tiene specs formales. El `PartidoVoter` implementa una regla de acceso crítica (ROLE_PLANILLERO no puede modificar resultados finalizados) que si se rompe permite adulteración de resultados. Además, el `UsuarioManager` tiene una divergencia en `editarUsuario()` que hace imposible cambiar username o email a valores disponibles, lo que debe quedar documentado para no confundirse con comportamiento intencional.

## What Changes

- Documentar la capacidad `registro-usuario`: validaciones de username/password, unicidad de username y email, hashing de contraseña.
- Documentar la capacidad `edicion-usuario`: comportamiento observable actual incluyendo la imposibilidad de cambiar username/email a valores nuevos.
- Documentar la capacidad `control-acceso-partido`: reglas del `PartidoVoter` para el atributo `CARGAR_RESULTADO`.
- Documentar la capacidad `gestion-usuarios`: cambio de contraseña, eliminación y consultas.

## Capabilities

### New Capabilities

- `registro-usuario`: Registro de un `Usuario` con validación completa de credenciales y unicidad.
- `edicion-usuario`: Edición de datos del usuario con comportamiento observable actual documentado.
- `control-acceso-partido`: Autorización para cargar resultados de partidos según rol del usuario.
- `gestion-usuarios`: Cambio de contraseña, eliminación y consultas de usuarios.

### Modified Capabilities

_(ninguna — specs nuevas sobre comportamiento existente)_

## Impact

- **`UsuarioManager`**: documentado incluyendo divergencias en `editarUsuario()`.
- **`PartidoVoter`**: único Voter del sistema, documenta las reglas de autorización para `CARGAR_RESULTADO`.
- **`ValidadorManager`**: usado en registro pero no en edición.
- **Tests**: specs reflejan el comportamiento actual del sistema, incluyendo divergencias en edición.
- **Sin migración de base de datos**: no hay cambios en entidades Doctrine.
