## ADDED Requirements

### Requirement: Cambio de contraseña como operación independiente
El sistema SHALL permitir cambiar la contraseña de un `Usuario` mediante `cambiarPassword()`. La nueva contraseña SHALL ser hasheada con `UserPasswordHasherInterface` antes de persistirse, sin validaciones adicionales de complejidad ni verificación de la contraseña actual.

#### Scenario: Nueva contraseña hasheada y persistida
- **GIVEN** un `Usuario` existente y una nueva contraseña en texto plano
- **WHEN** se invoca `cambiarPassword(usuario, password)`
- **THEN** el `Usuario` queda con la contraseña hasheada actualizada

#### Scenario: cambiarPassword no valida complejidad de la nueva contraseña
- **GIVEN** una nueva contraseña que no cumple los requisitos de registro (ej. sin mayúscula)
- **WHEN** se invoca `cambiarPassword(usuario, password)`
- **THEN** la contraseña se hashea y persiste sin lanzar excepción

### Requirement: Eliminación permanente de Usuario
El sistema SHALL eliminar el `Usuario` de forma permanente (hard delete) al invocar `eliminarUsuario()`, sin verificar dependencias (torneos creados, torneos colaborados).

#### Scenario: Usuario eliminado no existe más en el sistema
- **GIVEN** un `Usuario` existente
- **WHEN** se invoca `eliminarUsuario(usuario)`
- **THEN** el `Usuario` ya no puede ser recuperado

### Requirement: Consulta de Usuarios
El sistema SHALL proveer listado global y búsqueda por id. La búsqueda por id retorna `null` si no existe (no lanza excepción).

#### Scenario: obtenerUsuarios retorna todos sin filtro
- **GIVEN** existen múltiples usuarios con distintos roles
- **WHEN** se invoca `obtenerUsuarios()`
- **THEN** se retornan todos sin filtro de rol ni estado

#### Scenario: buscarUsuario retorna null si el id no existe
- **GIVEN** ningún `Usuario` tiene el id indicado
- **WHEN** se invoca `buscarUsuario(id)`
- **THEN** retorna `null` sin lanzar excepción
