## ADDED Requirements

### Requirement: Validación de username y contraseña en el registro
El sistema SHALL validar que el username tenga entre 4 y 128 caracteres, sin espacios. La contraseña SHALL tener entre 5 y 255 caracteres, contener al menos una mayúscula, una minúscula y un número, y no puede ser igual al username.

#### Scenario: Username con espacios lanza excepción
- **GIVEN** un username que contiene espacios
- **WHEN** se invoca `registrarUsuario()`
- **THEN** se lanza `AppException` indicando que el usuario no puede contener espacios

#### Scenario: Username menor a 4 caracteres lanza excepción
- **GIVEN** un username con menos de 4 caracteres
- **WHEN** se invoca `registrarUsuario()`
- **THEN** se lanza `AppException` indicando el rango válido

#### Scenario: Contraseña sin mayúscula lanza excepción
- **GIVEN** una contraseña que no contiene ninguna letra mayúscula
- **WHEN** se invoca `registrarUsuario()`
- **THEN** se lanza `AppException` indicando que la contraseña debe contener al menos una letra mayúscula

#### Scenario: Contraseña sin número lanza excepción
- **GIVEN** una contraseña que no contiene ningún dígito
- **WHEN** se invoca `registrarUsuario()`
- **THEN** se lanza `AppException` indicando que la contraseña debe contener al menos un número

#### Scenario: Contraseña igual al username lanza excepción
- **GIVEN** username "Admin1" y contraseña "Admin1"
- **WHEN** se invoca `registrarUsuario()`
- **THEN** se lanza `AppException` indicando que el nombre de usuario y la contraseña no pueden ser iguales

### Requirement: Unicidad de username y email en el registro
El sistema SHALL rechazar el registro si ya existe un `Usuario` con el mismo username o el mismo email.

#### Scenario: Username ya registrado lanza excepción
- **GIVEN** ya existe un `Usuario` con username "admin"
- **WHEN** se intenta registrar otro usuario con username "admin"
- **THEN** se lanza `AppException` indicando que el nombre de usuario ya está registrado

#### Scenario: Email ya registrado lanza excepción
- **GIVEN** ya existe un `Usuario` con email "admin@example.com"
- **WHEN** se intenta registrar otro usuario con ese email
- **THEN** se lanza `AppException` indicando que el email ya está registrado

### Requirement: Contraseña hasheada antes de persistir
El sistema SHALL hashear la contraseña usando `UserPasswordHasherInterface` antes de persistir el `Usuario`. La contraseña en texto plano nunca SHALL almacenarse.

#### Scenario: Contraseña almacenada como hash
- **GIVEN** una contraseña en texto plano "Admin1pass"
- **WHEN** se invoca `registrarUsuario()`
- **THEN** el `Usuario` persiste con `password` distinto a "Admin1pass" (hasheado)
