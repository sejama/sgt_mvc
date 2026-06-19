## 1. Tests de creacion-equipo

- [ ] 1.1 Escribir test unitario: equipo creado con estado BORRADOR
- [ ] 1.2 Escribir test unitario: número secuencial global — nuevo equipo recibe count(torneo)+1
- [ ] 1.3 Escribir test unitario: Categoria sin Torneo asignado lanza AppException
- [ ] 1.4 Escribir test unitario: nombre duplicado en la misma Categoria lanza AppException
- [ ] 1.5 Escribir test unitario: mismo nombre en distinta Categoria se permite
- [ ] 1.6 Escribir test unitario: nombreCorto duplicado en la misma Categoria lanza AppException
- [ ] 1.7 Escribir test unitario: nombre menor a 3 caracteres lanza AppException
- [ ] 1.8 Escribir test unitario: nombreCorto menor a 2 caracteres lanza AppException
- [ ] 1.9 Escribir test unitario: logoPath nulo se acepta sin error

## 2. Tests de edicion-equipo

- [ ] 2.1 Escribir test unitario: cambiar nombre a uno ya usado por otro equipo lanza AppException
- [ ] 2.2 Escribir test unitario: conservar el mismo nombre no lanza excepción
- [ ] 2.3 Escribir test unitario: cambiar nombreCorto a uno ya usado por otro equipo lanza AppException
- [ ] 2.4 Escribir test unitario: conservar el mismo nombreCorto no lanza excepción
- [ ] 2.5 Escribir test unitario: nombre demasiado corto en edición lanza AppException (ValidadorManager invocado)
- [ ] 2.6 Escribir test unitario: logoPath nulo preserva el logo existente (no sobreescribe)
- [ ] 2.7 Escribir test unitario: logoPath no nulo reemplaza el logo existente

## 3. Tests de baja-equipo

- [ ] 3.1 Escribir test unitario: estado del Equipo cambia a NO_PARTICIPA
- [ ] 3.2 Escribir test unitario: partidos como local quedan en estado CANCELADO
- [ ] 3.3 Escribir test unitario: partidos como visitante quedan en estado CANCELADO
- [ ] 3.4 Escribir test unitario: partidos locales y visitantes cancelados en la misma operación (conteo total)
- [ ] 3.5 Escribir test unitario: equipo sin partidos no genera error
- [ ] 3.6 Escribir test unitario: log de nivel WARNING registrado con partidos_cancelados correcto

## 4. Tests de ciclo-vida-equipo

- [ ] 4.1 Escribir test unitario: estado inicial al crear es BORRADOR
- [ ] 4.2 Escribir test unitario: eliminarEquipo realiza hard delete (equipo no recuperable)
- [ ] 4.3 Escribir test unitario: eliminarEquipo loguea equipo_id y nombre con nivel info
- [ ] 4.4 Escribir test unitario: obtenerEquipo con id existente retorna el Equipo
- [ ] 4.5 Escribir test unitario: obtenerEquipo con id inexistente lanza AppException "No se encontró el equipo"
- [ ] 4.6 Escribir test unitario: obtenerEquipos retorna todos sin filtro
- [ ] 4.7 Escribir test unitario: obtenerEquiposPorCategoria retorna solo los de la Categoria indicada

## 5. Verificación con herramientas de calidad

- [ ] 5.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 5.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `EquipoManager`
