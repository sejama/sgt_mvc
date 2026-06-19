## 1. Tests de registro de usuario (specs/registro-usuario)

- [ ] 1.1 Test: username con espacios lanza AppException
- [ ] 1.2 Test: username menor a 4 caracteres lanza AppException
- [ ] 1.3 Test: contraseña sin mayúscula lanza AppException
- [ ] 1.4 Test: contraseña sin número lanza AppException
- [ ] 1.5 Test: contraseña igual al username lanza AppException
- [ ] 1.6 Test: username ya registrado lanza AppException
- [ ] 1.7 Test: email ya registrado lanza AppException
- [ ] 1.8 Test: contraseña en texto plano nunca almacenada (hash verificado)

## 2. Tests de edición de usuario (specs/edicion-usuario)

- [ ] 2.1 Test: conservar mismo username permite la edición sin error
- [ ] 2.2 Test: cambiar username a valor disponible lanza AppException (bug documentado)
- [ ] 2.3 Test: cambiar username al de otro usuario lanza AppException
- [ ] 2.4 Test: editarUsuario no altera la contraseña
- [ ] 2.5 Test: editarUsuario persiste cambios de nombre, apellido y roles

## 3. Tests de control de acceso (specs/control-acceso-partido)

- [ ] 3.1 Test: ROLE_ADMIN puede cargar resultado en Partido FINALIZADO
- [ ] 3.2 Test: ROLE_ADMIN puede cargar resultado en Partido BORRADOR
- [ ] 3.3 Test: ROLE_PLANILLERO puede cargar resultado en Partido PROGRAMADO
- [ ] 3.4 Test: ROLE_PLANILLERO puede cargar resultado en Partido BORRADOR
- [ ] 3.5 Test: ROLE_PLANILLERO no puede cargar resultado en Partido FINALIZADO
- [ ] 3.6 Test: usuario no autenticado es denegado
- [ ] 3.7 Test: usuario sin ROLE_ADMIN ni ROLE_PLANILLERO es denegado
- [ ] 3.8 Test: atributo distinto a CARGAR_RESULTADO no es soportado por el Voter

## 4. Tests de gestión de usuarios (specs/gestion-usuarios)

- [ ] 4.1 Test: cambiarPassword hashea la nueva contraseña y persiste
- [ ] 4.2 Test: cambiarPassword no valida complejidad de la nueva contraseña
- [ ] 4.3 Test: eliminarUsuario borra el usuario permanentemente
- [ ] 4.4 Test: obtenerUsuarios retorna todos sin filtro de rol ni estado
- [ ] 4.5 Test: buscarUsuario retorna null si el id no existe
