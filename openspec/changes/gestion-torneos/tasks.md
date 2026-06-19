## 1. Tests de creacion-torneo

- [ ] 1.1 Escribir test unitario: torneo creado con estado BORRADOR y creador asignado
- [ ] 1.2 Escribir test unitario: nombre duplicado lanza AppException
- [ ] 1.3 Escribir test unitario: ruta duplicada lanza AppException
- [ ] 1.4 Escribir test unitario: nombre menor a 3 caracteres lanza AppException
- [ ] 1.5 Escribir test unitario: ruta con espacios lanza AppException
- [ ] 1.6 Escribir test unitario: ruta con caracteres inválidos (mayúsculas, guion bajo) lanza AppException
- [ ] 1.7 Escribir test unitario: fecha con formato inválido lanza AppException
- [ ] 1.8 Escribir test unitario: fechaFinInscripcion >= fechaInicioTorneo lanza AppException
- [ ] 1.9 Escribir test unitario: fechaInicioTorneo >= fechaFinTorneo lanza AppException
- [ ] 1.10 Escribir test unitario: cadena temporal coherente permite la creación exitosa

## 2. Tests de edicion-torneo

- [ ] 2.1 Escribir test unitario: edición exitosa actualiza campos del torneo
- [ ] 2.2 Escribir test unitario: torneo sin creador lanza AppException al editar
- [ ] 2.3 Escribir test unitario: cambiar nombre a uno ya usado por otro torneo lanza AppException
- [ ] 2.4 Escribir test unitario: conservar el mismo nombre no lanza excepción
- [ ] 2.5 Escribir test unitario: conservar la misma ruta no lanza excepción
- [ ] 2.6 Escribir test unitario: torneo ACTIVO queda en BORRADOR tras edición
- [ ] 2.7 Escribir test unitario: torneo EN_CURSO queda en BORRADOR tras edición

## 3. Tests de reglamento-torneo

- [ ] 3.1 Escribir test unitario: edición de reglamento no cambia el estado del torneo
- [ ] 3.2 Escribir test unitario: reglamento es sanitizado por RichTextSanitizer antes de persistir
- [ ] 3.3 Escribir test unitario: reglamento vacío puede editarse sin error

## 4. Tests de ciclo-vida-torneo

- [ ] 4.1 Escribir test unitario: estado inicial al crear es siempre BORRADOR
- [ ] 4.2 Escribir test unitario: eliminarTorneo realiza hard delete (torneo no recuperable)
- [ ] 4.3 Escribir test unitario: eliminarTorneo loguea torneo_id, nombre y ruta

## 5. Tests de consulta-torneos

- [ ] 5.1 Escribir test unitario: obtenerTorneos retorna todos sin filtro de estado
- [ ] 5.2 Escribir test unitario: obtenerTorneosXCreador retorna solo torneos del userId dado
- [ ] 5.3 Escribir test unitario: obtenerTorneosXCreador retorna lista vacía si el creador no tiene torneos
- [ ] 5.4 Escribir test unitario: obtenerTorneo por ruta existente retorna el Torneo correcto
- [ ] 5.5 Escribir test unitario: obtenerTorneo con ruta inexistente lanza AppException "Torneo no encontrado"

## 6. Verificación con herramientas de calidad

- [ ] 6.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 6.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `TorneoManager` y `ValidadorManager`
