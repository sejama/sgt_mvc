## 1. Tests de creación de sede (specs/creacion-sede)

- [ ] 1.1 Test: crear sede con nombre duplicado en el mismo torneo lanza AppException
- [ ] 1.2 Test: crear sede con mismo nombre en torneo distinto es permitido
- [ ] 1.3 Test: nombre con menos de 3 caracteres lanza AppException
- [ ] 1.4 Test: dirección con menos de 8 caracteres lanza AppException
- [ ] 1.5 Test: crearSede no hace flush automático (verificar que el controller flushea)

## 2. Tests de edición y eliminación de sede (specs/edicion-sede)

- [ ] 2.1 Test: editar sede sin cambiar nombre no lanza error de unicidad
- [ ] 2.2 Test: cambiar nombre a uno existente en el mismo torneo lanza AppException
- [ ] 2.3 Test: editar sede con dirección inválida lanza AppException
- [ ] 2.4 Test: editarSede persiste con flush automático
- [ ] 2.5 Test: eliminarSede borra la sede permanentemente

## 3. Tests de gestión de canchas (specs/gestion-canchas)

- [ ] 3.1 Test: crear cancha con nombre duplicado en la misma sede lanza AppException
- [ ] 3.2 Test: mismo nombre de cancha en sedes distintas es permitido
- [ ] 3.3 Test: nombre de cancha vacío lanza AppException
- [ ] 3.4 Test: descripción vacía es permitida
- [ ] 3.5 Test: crearCancha persiste con flush automático
- [ ] 3.6 Test: editar cancha conservando nombre no lanza error de unicidad
- [ ] 3.7 Test: obtenerCancha con id inexistente lanza AppException (divergencia con obtenerSede)
- [ ] 3.8 Test: eliminarCancha borra la cancha permanentemente

## 4. Tests de consulta (specs/consulta-sedes)

- [ ] 4.1 Test: obtenerSedes retorna todas las sedes sin filtro por torneo
- [ ] 4.2 Test: obtenerSede retorna null si el id no existe
- [ ] 4.3 Test: obtenerCanchas retorna solo las canchas de la sede indicada
- [ ] 4.4 Test: obtenerSedesYCanchasByTorneo retorna sedes con canchas del torneo
- [ ] 4.5 Test: obtenerSedesYCanchasByTorneo retorna vacío para torneo sin sedes
