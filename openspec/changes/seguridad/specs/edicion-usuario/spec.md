## ADDED Requirements

### Requirement: Edición de usuario no puede cambiar username ni email a valores disponibles (comportamiento actual)
El sistema actual usa comparación por identidad de objeto para verificar unicidad en `editarUsuario()`. Si el username o email proporcionado no pertenece exactamente al mismo `Usuario` que se edita, lanza `AppException`. Esto incluye valores disponibles (que nadie tiene), ya que `findOneBy()` retorna `null` y `null !== $usuario` es `true`.

#### Scenario: Conservar el mismo username permite la edición
- **GIVEN** un `Usuario` con username "planillero1" que se edita sin cambiar el username
- **WHEN** se invoca `editarUsuario()` con username "planillero1"
- **THEN** la edición procede sin error de username

#### Scenario: Cambiar username a valor disponible lanza excepción (comportamiento actual)
- **GIVEN** un `Usuario` con username "planillero1" y ningún otro usuario tiene username "planillero2"
- **WHEN** se invoca `editarUsuario()` con username "planillero2"
- **THEN** se lanza `AppException` indicando que el username ya está registrado

#### Scenario: Cambiar username a uno de otro usuario lanza excepción
- **GIVEN** un `Usuario` A con username "admin" y un `Usuario` B que intenta cambiar su username a "admin"
- **WHEN** se invoca `editarUsuario()` sobre el usuario B
- **THEN** se lanza `AppException` indicando que el username ya está registrado

### Requirement: Contraseña no modificable mediante editarUsuario
El sistema NO SHALL modificar la contraseña del `Usuario` al invocar `editarUsuario()`. El cambio de contraseña solo está disponible a través de `cambiarPassword()`.

#### Scenario: editarUsuario no altera la contraseña
- **GIVEN** un `Usuario` con contraseña hasheada existente
- **WHEN** se invoca `editarUsuario()` con cualquier argumento
- **THEN** la contraseña del `Usuario` permanece sin cambios

### Requirement: Edición no revalida username ni contraseña via ValidadorManager
El sistema NO SHALL invocar `ValidadorManager::validarUsuario()` en `editarUsuario()`. Los campos nombre, apellido, roles y username se actualizan sin revalidación de formato.

#### Scenario: editarUsuario persiste cambios de nombre, apellido y roles
- **GIVEN** un `Usuario` con nombre "Juan" y rol ROLE_PLANILLERO
- **WHEN** se invoca `editarUsuario()` con nombre "Carlos" y rol ROLE_ADMIN (mismo username y email)
- **THEN** el `Usuario` queda con nombre "Carlos" y rol ROLE_ADMIN
